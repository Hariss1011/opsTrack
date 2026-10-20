
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_status_histories', function (Blueprint $table) {
            $table->foreignId('assigned_to_at_completion')
                ->nullable()
                ->after('new_status')
                ->constrained('users')
                ->nullOnDelete();

            $table->index([
                'new_status',
                'changed_at',
                'assigned_to_at_completion',
            ], 'task_history_completion_staff_index');
        });
    }

    public function down(): void
    {
        Schema::table('task_status_histories', function (Blueprint $table) {
            $table->dropIndex('task_history_completion_staff_index');
            $table->dropConstrainedForeignId('assigned_to_at_completion');
        });
    }
};
