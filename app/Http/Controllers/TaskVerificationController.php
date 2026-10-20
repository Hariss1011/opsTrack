<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Notifications\TaskEventNotification;

class TaskVerificationController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $user = $request->user();

        // Hanya Admin dan Manager yang boleh memverifikasi.
        abort_unless(
            in_array($user->role, ['admin', 'manager'], true),
            403,
            'Hanya Admin atau Manager yang dapat memverifikasi tugas.'
        );

        // Manager hanya boleh memverifikasi tugas departemennya.
        abort_unless(
            $user->role === 'admin'
                || (int) $task->department_id === (int) $user->department_id,
            403,
            'Anda hanya dapat memverifikasi tugas dari departemen sendiri.'
        );

        $data = $request->validate([
            'decision' => [
                'required',
                Rule::in(['approve', 'reject']),
            ],
            'note' => [
                Rule::requiredIf($request->input('decision') === 'reject'),
                'nullable',
                'string',
                'min:5',
                'max:5000',
            ],
        ], [
            'note.required' => 'Alasan pengembalian tugas wajib diisi.',
            'note.min' => 'Alasan harus minimal 5 karakter.',
        ]);

        DB::transaction(function () use ($task, $user, $data) {
            // Ambil ulang data dengan lock untuk mencegah verifikasi ganda.
            $lockedTask = Task::whereKey($task->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedTask->status !== 'pending_review') {
                throw ValidationException::withMessages([
                    'decision' => 'Tugas ini tidak sedang menunggu verifikasi.',
                ]);
            }

            $oldStatus = $lockedTask->status;

            if ($data['decision'] === 'approve') {
                $newStatus = 'completed';

                $lockedTask->update([
                    'status' => $newStatus,
                    'completed_at' => now(),
                ]);

                $description = 'Penyelesaian tugas disetujui oleh '
                    . $user->name . '.';
            } else {
                $newStatus = 'in_progress';

                $lockedTask->update([
                    'status' => $newStatus,
                    'completed_at' => null,
                ]);

                $description = 'Tugas dikembalikan untuk dikerjakan kembali. '
                    . 'Alasan: ' . trim($data['note']);
            }

            // Simpan catatan verifikasi pada aktivitas tugas.
            $lockedTask->updates()->create([
                'user_id' => $user->id,
                'update_type' => 'general',
                'description' => $description,
            ]);


            // Catat perubahan status dan staff yang menyelesaikan tugas.
            $lockedTask->statusHistories()->create([
                'changed_by' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_at' => now(),

                // Simpan snapshot hanya saat tugas disetujui selesai.
                'assigned_to_at_completion' => $newStatus === 'completed'
                    ? $lockedTask->assigned_to
                    : null,
            ]);
        });

        // Beri tahu staff mengenai hasil verifikasi.
        $task->refresh();

        $assignee = $task->assignee;

        if ($assignee) {
            if ($data['decision'] === 'approve') {
                $assignee->notify(new TaskEventNotification(
                    title: 'Tugas Disetujui',
                    message: 'Tugas "' . $task->title
                        . '" telah disetujui dan ditandai selesai.',
                    url: route('tasks.show', $task),
                    type: 'task_approved'
                ));
            } else {
                $assignee->notify(new TaskEventNotification(
                    title: 'Tugas Perlu Direvisi',
                    message: 'Tugas "' . $task->title
                        . '" dikembalikan untuk revisi. Alasan: '
                        . trim($data['note']),
                    url: route('tasks.show', $task),
                    type: 'task_rejected'
                ));
            }
        }

        $message = $data['decision'] === 'approve'
            ? 'Tugas berhasil diverifikasi dan ditandai selesai.'
            : 'Tugas dikembalikan kepada staff untuk dikerjakan kembali.';

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', $message);
    }
}
