<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskStatusHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskStatusHistorySeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Task::with('assignee', 'creator')->get();

        foreach ($tasks as $task) {
            $actorId = $task->created_by
                ?? User::where('role', 'admin')->value('id');

            if (!$actorId) {
                continue;
            }

            TaskStatusHistory::firstOrCreate(
                [
                    'task_id' => $task->id,
                    'new_status' => 'open',
                ],
                [
                    'changed_by' => $actorId,
                    'old_status' => null,
                    'changed_at' => $task->created_at ?? now(),
                ]
            );

            if ($task->status === 'open') {
                continue;
            }

            $oldStatus = match ($task->status) {
                'in_progress', 'blocked', 'cancelled' => 'open',
                'pending_review' => 'in_progress',
                'completed' => 'pending_review',
                default => 'open',
            };

            $changedBy = in_array(
                $task->status,
                ['completed', 'cancelled']
            )
                ? $actorId
                : ($task->assigned_to ?? $actorId);

            TaskStatusHistory::firstOrCreate(
                [
                    'task_id' => $task->id,
                    'new_status' => $task->status,
                ],
                [
                    'changed_by' => $changedBy,
                    'old_status' => $oldStatus,
                    'changed_at' => $task->completed_at ?? now(),
                ]
            );
        }
    }
}
