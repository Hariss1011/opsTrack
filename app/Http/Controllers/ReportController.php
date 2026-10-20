<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use App\Models\TaskCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Query dasar berdasarkan hak akses dan filter laporan.
     */
    private function filteredTasks(Request $request): Builder
    {
        $user = $request->user();

        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'category_id' => ['nullable', 'integer', 'exists:task_categories,id'],
            'status' => [
                'nullable',
                'in:open,in_progress,blocked,pending_review,completed,cancelled',
            ],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
        ]);

        $query = Task::query();

        // Batasi data berdasarkan role pengguna.
        if ($user->role === 'manager') {
            $query->where('tasks.department_id', $user->department_id);
        } elseif ($user->role === 'staff') {
            $query->where('tasks.assigned_to', $user->id);
        }

        // Filter departemen hanya berlaku untuk admin.
        if (
            $user->role === 'admin' &&
            !empty($filters['department_id'])
        ) {
            $query->where(
                'tasks.department_id',
                $filters['department_id']
            );
        }

        // Periode laporan berdasarkan tanggal pembuatan tugas.
        if (!empty($filters['date_from'])) {
            $query->whereDate('tasks.created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('tasks.created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('tasks.category_id', $filters['category_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('tasks.status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('tasks.priority', $filters['priority']);
        }

        return $query;
    }

    /**
     * Halaman laporan operasional.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $this->filteredTasks($request);

        $totalTasks = (clone $query)->count();

        $completedTasks = (clone $query)
            ->where('status', 'completed')
            ->count();

        $inProgressTasks = (clone $query)
            ->where('status', 'in_progress')
            ->count();

        $pendingReviewTasks = (clone $query)
            ->where('status', 'pending_review')
            ->count();

        $overdueTasks = (clone $query)
            ->whereDate('due_date', '<', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $completionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 1)
            : 0;

        // Statistik berdasarkan departemen.
        $departmentPerformance = (clone $query)
            ->join(
                'departments',
                'tasks.department_id',
                '=',
                'departments.id'
            )
            ->selectRaw('
                departments.id,
                departments.name,
                COUNT(tasks.id) as total_tasks,
                SUM(CASE WHEN tasks.status = ? THEN 1 ELSE 0 END) as completed_tasks
            ', ['completed'])
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('total_tasks')
            ->get();

        // Statistik berdasarkan staf yang ditugaskan.
        $staffPerformance = (clone $query)
            ->join('users', 'tasks.assigned_to', '=', 'users.id')
            ->selectRaw('
                users.id,
                users.name,
                COUNT(tasks.id) as total_tasks,
                SUM(CASE WHEN tasks.status = ? THEN 1 ELSE 0 END) as completed_tasks
            ', ['completed'])
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_tasks')
            ->get();

        // Daftar tugas dengan pagination.
        $tasks = (clone $query)
            ->with(['department', 'category', 'assignee'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Pilihan filter mengikuti cakupan hak akses.
        if ($user->role === 'admin') {
            $departments = Department::orderBy('name')->get();
        } elseif ($user->department_id) {
            $departments = Department::where(
                'id',
                $user->department_id
            )->get();
        } else {
            $departments = collect();
        }

        $categories = TaskCategory::orderBy('name')->get();

        return view('reports.index', compact(
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'pendingReviewTasks',
            'overdueTasks',
            'completionRate',
            'departmentPerformance',
            'staffPerformance',
            'tasks',
            'departments',
            'categories'
        ));
    }

    /**
     * Ekspor laporan ke CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $tasks = $this->filteredTasks($request)
            ->with(['department', 'category', 'assignee'])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'laporan-opstrack-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($tasks) {
            $handle = fopen('php://output', 'w');

            $priorityLabels = [
                'low' => 'Rendah',
                'medium' => 'Sedang',
                'high' => 'Tinggi',
                'urgent' => 'Mendesak',
            ];

            $statusLabels = [
                'open' => 'Belum Dimulai',
                'in_progress' => 'Sedang Dikerjakan',
                'blocked' => 'Terhambat',
                'pending_review' => 'Menunggu Verifikasi',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];

            // Header CSV
            fputcsv($handle, [
                'ID',
                'Judul Tugas',
                'Departemen',
                'Kategori',
                'Penanggung Jawab',
                'Prioritas',
                'Status',
                'Tanggal Dibuat',
                'Tanggal Mulai',
                'Tenggat',
                'Tanggal Selesai',
            ], ',', '"', '');

            foreach ($tasks as $task) {
                fputcsv($handle, [
                    $task->id,
                    $task->title,
                    $task->department?->name ?? '-',
                    $task->category?->name ?? '-',
                    $task->assignee?->name ?? '-',
                    $priorityLabels[$task->priority] ?? $task->priority,
                    $statusLabels[$task->status] ?? $task->status,
                    $task->created_at?->format('Y-m-d'),
                    $task->start_date?->format('Y-m-d'),
                    $task->due_date?->format('Y-m-d'),
                    $task->completed_at?->format('Y-m-d'),
                ], ',', '"', '');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
