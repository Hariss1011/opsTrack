<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskEventNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskUpdateController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $user = $request->user();

        // Hanya Staff yang ditugaskan boleh memperbarui progres.
        abort_unless(
            $user->role === 'staff'
                && (int) $task->assigned_to === (int) $user->id,
            403,
            'Hanya Staff yang ditugaskan dapat memperbarui progres tugas ini.'
        );

        // Tugas yang menunggu verifikasi atau sudah final tidak dapat diperbarui.
        abort_if(
            in_array($task->status, [
                'completed',
                'cancelled',
                'pending_review',
            ], true),
            403,
            'Tugas tidak dapat diperbarui dalam status saat ini.'
        );

        $data = $request->validate([
            'action' => [
                'required',
                Rule::in(['progress', 'blocker', 'submit_review']),
            ],
            'description' => [
                'required',
                'string',
                'min:5',
                'max:5000',
            ],
        ]);

        $updateType = match ($data['action']) {
            'progress' => 'progress',
            'blocker' => 'blocker',
            'submit_review' => 'progress',
        };

        $newStatus = match ($data['action']) {
            'progress' => 'in_progress',
            'blocker' => 'blocked',
            'submit_review' => 'pending_review',
        };

        $description = trim($data['description']);

        // Simpan aktivitas dan perubahan status secara atomik.
        DB::transaction(function () use (
            $task,
            $user,
            $data,
            $updateType,
            $newStatus,
            $description
        ) {
            $lockedTask = Task::whereKey($task->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Periksa kembali status setelah mendapatkan lock.
            if (in_array($lockedTask->status, [
                'completed',
                'cancelled',
                'pending_review',
            ], true)) {
                abort(
                    403,
                    'Tugas tidak dapat diperbarui dalam status saat ini.'
                );
            }

            // Catat aktivitas Staff.
            $activityDescription = match ($data['action']) {
                'progress' => $description,
                'blocker' => 'Kendala dilaporkan: ' . $description,
                'submit_review' => 'Tugas diajukan untuk verifikasi: ' . $description,
            };

            $lockedTask->updates()->create([
                'user_id' => $user->id,
                'update_type' => $updateType,
                'description' => $activityDescription,
            ]);

            // Perbarui status dan catat riwayat jika status berubah.
            if ($lockedTask->status !== $newStatus) {
                $oldStatus = $lockedTask->status;

                $lockedTask->update([
                    'status' => $newStatus,
                ]);

                $lockedTask->statusHistories()->create([
                    'changed_by' => $user->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_at' => now(),
                ]);
            }
        });

        // Beri tahu Admin dan Manager terkait saat tugas diajukan.
        if ($data['action'] === 'submit_review') {
            $reviewers = User::where('is_active', true)
                ->where(function ($query) use ($task) {
                    $query->where('role', 'admin')
                        ->orWhere(function ($managerQuery) use ($task) {
                            $managerQuery
                                ->where('role', 'manager')
                                ->where(
                                    'department_id',
                                    $task->department_id
                                );
                        });
                })
                ->get();

            foreach ($reviewers as $reviewer) {
                $reviewer->notify(new TaskEventNotification(
                    title: 'Tugas Menunggu Verifikasi',
                    message: $user->name
                        . ' mengajukan tugas "'
                        . $task->title
                        . '" untuk diverifikasi.',
                    url: route('tasks.show', $task),
                    type: 'task_review'
                ));
            }
        }

        $message = match ($data['action']) {
            'progress' => 'Progres tugas berhasil diperbarui.',
            'blocker' => 'Kendala tugas berhasil dicatat.',
            'submit_review' => 'Tugas berhasil diajukan untuk verifikasi.',
        };

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', $message);
    }
}
