<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskUpdate;
use Illuminate\Database\Seeder;

class TaskUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Task::with('assignee', 'creator')->get();

        foreach ($tasks as $task) {
            $activity = match ($task->status) {
                'open' => [
                    'update_type' => 'general',
                    'description' => 'Tugas dibuat dan siap dikerjakan.',
                ],
                'in_progress' => [
                    'update_type' => 'progress',
                    'description' => 'Pekerjaan sedang dikerjakan sesuai rencana.',
                ],
                'blocked' => [
                    'update_type' => 'blocker',
                    'description' => 'Pekerjaan mengalami kendala dan membutuhkan tindak lanjut.',
                ],
                'pending_review' => [
                    'update_type' => 'progress',
                    'description' => 'Pekerjaan telah diajukan untuk diverifikasi.',
                ],
                'completed' => [
                    'update_type' => 'general',
                    'description' => 'Pekerjaan telah selesai dan diverifikasi.',
                ],
                'cancelled' => [
                    'update_type' => 'general',
                    'description' => 'Tugas dibatalkan.',
                ],
                default => [
                    'update_type' => 'general',
                    'description' => 'Aktivitas tugas dicatat.',
                ],
            };

            $userId = in_array(
                $task->status,
                ['completed', 'cancelled']
            )
                ? $task->created_by
                : ($task->assigned_to ?? $task->created_by);

            if (!$userId) {
                continue;
            }

            TaskUpdate::firstOrCreate(
                [
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'description' => $activity['description'],
                ],
                [
                    'update_type' => $activity['update_type'],
                ]
            );
        }
    }
}
