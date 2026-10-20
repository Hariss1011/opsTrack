<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'task_id',
        'changed_by',
        'old_status',
        'new_status',
        'changed_at',
        'assigned_to_at_completion',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function assignedToAtCompletion(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to_at_completion'
        );
    }
}
