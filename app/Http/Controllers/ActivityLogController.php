<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => [
                'nullable',
                Rule::in([
                    'progress',
                    'blocker',
                    'general',
                    'status_change',
                ]),
            ],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from',
            ],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        /*
         * Sumber 1: aktivitas umum tugas.
         */
        $updates = DB::table('task_updates as a')
            ->join('tasks as t', 't.id', '=', 'a.task_id')
            ->join('users as u', 'u.id', '=', 'a.user_id')
            ->select([
                'a.id as activity_id',
                'a.task_id',
                't.title as task_title',
                'u.id as user_id',
                'u.name as user_name',
                'a.update_type as activity_type',
                'a.description',
            ])
            ->selectRaw('a.created_at as occurred_at')
            ->selectRaw("'update' as source");

        /*
         * Sumber 2: perubahan status tugas.
         */
        $histories = DB::table('task_status_histories as h')
            ->join('tasks as t', 't.id', '=', 'h.task_id')
            ->join('users as u', 'u.id', '=', 'h.changed_by')
            ->select([
                'h.id as activity_id',
                'h.task_id',
                't.title as task_title',
                'u.id as user_id',
                'u.name as user_name',
            ])
            ->selectRaw("'status_change' as activity_type")
            ->selectRaw("
                CONCAT(
                    'Status berubah: ',
                    CASE
                        WHEN h.old_status IS NULL THEN 'Tugas Baru'
                        WHEN h.old_status = 'open' THEN 'Belum Dikerjakan'
                        WHEN h.old_status = 'in_progress' THEN 'Sedang Dikerjakan'
                        WHEN h.old_status = 'blocked' THEN 'Terkendala'
                        WHEN h.old_status = 'pending_review' THEN 'Menunggu Verifikasi'
                        WHEN h.old_status = 'completed' THEN 'Selesai'
                        WHEN h.old_status = 'cancelled' THEN 'Dibatalkan'
                        ELSE h.old_status
                    END,
                    ' → ',
                    CASE
                        WHEN h.new_status = 'open' THEN 'Belum Dikerjakan'
                        WHEN h.new_status = 'in_progress' THEN 'Sedang Dikerjakan'
                        WHEN h.new_status = 'blocked' THEN 'Terkendala'
                        WHEN h.new_status = 'pending_review' THEN 'Menunggu Verifikasi'
                        WHEN h.new_status = 'completed' THEN 'Selesai'
                        WHEN h.new_status = 'cancelled' THEN 'Dibatalkan'
                        ELSE h.new_status
                    END
                ) as description
            ")
            ->selectRaw('h.changed_at as occurred_at')
            ->selectRaw("'status' as source");

        /*
         * Batasi aktivitas berdasarkan role.
         */
        foreach ([$updates, $histories] as $query) {
            if ($user->role === 'manager') {
                $query->where('t.department_id', $user->department_id);
            } elseif ($user->role === 'staff') {
                $query->where('t.assigned_to', $user->id);
            }
        }

        /*
         * Filter jenis aktivitas.
         */
        if (($filters['type'] ?? null) === 'status_change') {
            $updates->whereRaw('1 = 0');
        } elseif (! empty($filters['type'])) {
            $histories->whereRaw('1 = 0');
            $updates->where('a.update_type', $filters['type']);
        }

        /*
         * Filter pengguna, tanggal, dan pencarian.
         */
        foreach ([$updates, $histories] as $query) {
            if (! empty($filters['user_id'])) {
                $query->where('u.id', $filters['user_id']);
            }

            if (! empty($filters['from'])) {
                $query->whereDate(
                    $query === $updates ? 'a.created_at' : 'h.changed_at',
                    '>=',
                    $filters['from']
                );
            }

            if (! empty($filters['to'])) {
                $query->whereDate(
                    $query === $updates ? 'a.created_at' : 'h.changed_at',
                    '<=',
                    $filters['to']
                );
            }

            if (! empty($filters['search'])) {
                $keyword = '%' . $filters['search'] . '%';

                $query->where(function ($subquery) use ($keyword) {
                    $subquery
                        ->where('t.title', 'like', $keyword)
                        ->orWhere('u.name', 'like', $keyword);

                    if ($subquery->getQuery()->from === 'task_updates as a') {
                        $subquery->orWhere('a.description', 'like', $keyword);
                    } else {
                        $subquery->orWhereRaw(
                            "CONCAT(
                                COALESCE(h.old_status, 'belum ada'),
                                ' ',
                                h.new_status
                            ) LIKE ?",
                            [$keyword]
                        );
                    }
                });
            }
        }

        /*
         * Gabungkan kedua sumber dan urutkan secara kronologis.
         */
        $activities = DB::query()
            ->fromSub($updates->unionAll($histories), 'activity_logs')
            ->orderByDesc('occurred_at')
            ->orderByDesc('activity_id')
            ->paginate(15)
            ->withQueryString();

        return view('activity-logs.index', [
            'activities' => $activities,
            'filters' => $filters,
        ]);
    }
}
