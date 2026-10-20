<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatusHistory;
use App\Models\TaskUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Cakupan Data Berdasarkan Role
        |--------------------------------------------------------------------------
        | Admin   : seluruh tugas.
        | Manager : tugas dalam departemennya.
        | Staff   : tugas yang ditugaskan kepadanya.
        */

        $tasksQuery = Task::query();

        if ($user->role === 'manager') {
            $tasksQuery->where('department_id', $user->department_id);
        } elseif ($user->role === 'staff') {
            $tasksQuery->where('assigned_to', $user->id);
        }

        /*
        |--------------------------------------------------------------------------
        | KPI Utama
        |--------------------------------------------------------------------------
        */

        $totalTasks = (clone $tasksQuery)->count();

        $inProgressTasks = (clone $tasksQuery)
            ->where('status', 'in_progress')
            ->count();

        $pendingReviewTasks = (clone $tasksQuery)
            ->where('status', 'pending_review')
            ->count();

        $completedTasks = (clone $tasksQuery)
            ->where('status', 'completed')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Indikator Perhatian
        |--------------------------------------------------------------------------
        */

        $openTasks = (clone $tasksQuery)
            ->where('status', 'open')
            ->count();

        $blockedTasks = (clone $tasksQuery)
            ->where('status', 'blocked')
            ->count();

        $overdueTasks = (clone $tasksQuery)
            ->whereDate('due_date', '<', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $upcomingTasks = (clone $tasksQuery)
            ->whereDate('due_date', '>=', today())
            ->whereDate('due_date', '<=', today()->addDays(3))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Grafik 1: Tren Penyelesaian 7 Hari Terakhir
        |--------------------------------------------------------------------------
        | Menghitung setiap transisi menuju status completed berdasarkan
        | waktu perubahan status, bukan waktu terakhir task diperbarui.
        */

        $startDate = today()->subDays(6);
        $endDate = today();

        $historyQuery = TaskStatusHistory::query()
            ->where('new_status', 'completed')
            ->whereBetween('changed_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ]);

        if ($user->role === 'manager') {
            $historyQuery->whereHas('task', function (Builder $query) use ($user) {
                $query->where('department_id', $user->department_id);
            });
        } elseif ($user->role === 'staff') {
            $historyQuery->whereHas('task', function (Builder $query) use ($user) {
                $query->where('assigned_to', $user->id);
            });
        }

        $completionCounts = $historyQuery
            ->selectRaw('DATE(changed_at) as completion_date, COUNT(*) as total')
            ->groupBy('completion_date')
            ->orderBy('completion_date')
            ->pluck('total', 'completion_date');

        $completionTrendLabels = [];
        $completionTrendData = [];

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $dateKey = $date->format('Y-m-d');

            $completionTrendLabels[] = $date->translatedFormat('d M');
            $completionTrendData[] = (int) ($completionCounts[$dateKey] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | Grafik 2: Distribusi Status Tugas
        |--------------------------------------------------------------------------
        */

        $statusDefinitions = [
            'open' => [
                'label' => 'Belum Dimulai',
                'color' => '#94A3B8',
            ],
            'in_progress' => [
                'label' => 'Sedang Dikerjakan',
                'color' => '#3B82F6',
            ],
            'blocked' => [
                'label' => 'Terhambat',
                'color' => '#F59E0B',
            ],
            'pending_review' => [
                'label' => 'Menunggu Verifikasi',
                'color' => '#A78BFA',
            ],
            'completed' => [
                'label' => 'Selesai',
                'color' => '#10B981',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'color' => '#F87171',
            ],
        ];

        $statusCounts = (clone $tasksQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusDistributionLabels = [];
        $statusDistributionData = [];
        $statusDistributionColors = [];

        foreach ($statusDefinitions as $status => $definition) {
            $statusDistributionLabels[] = $definition['label'];
            $statusDistributionData[] = (int) ($statusCounts[$status] ?? 0);
            $statusDistributionColors[] = $definition['color'];
        }

        /*
        |--------------------------------------------------------------------------
        | Grafik 3: Kinerja Penyelesaian Staff
        |--------------------------------------------------------------------------
        | Hanya ditampilkan untuk admin dan manager.
        | Penghitungan memakai riwayat transisi completed dalam 7 hari terakhir,
        | dikelompokkan berdasarkan penanggung jawab tugas saat ini.
        */

        $staffPerformanceLabels = [];
        $staffPerformanceData = [];

        if (in_array($user->role, ['admin', 'manager'], true)) {
            $staffQuery = User::query()
                ->where('role', 'staff')
                ->where('is_active', true);

            if ($user->role === 'manager') {
                $staffQuery->where('department_id', $user->department_id);
            }

            $staffMembers = $staffQuery
                ->orderBy('name')
                ->get(['id', 'name']);


            $completedByStaff = TaskStatusHistory::query()
                ->where('new_status', 'completed')
                ->whereNotNull('assigned_to_at_completion')
                ->whereBetween('changed_at', [
                    $startDate->copy()->startOfDay(),
                    $endDate->copy()->endOfDay(),
                ])
                ->when(
                    $user->role === 'manager',
                    fn($query) => $query->whereHas(
                        'task',
                        fn($taskQuery) => $taskQuery->where(
                            'department_id',
                            $user->department_id
                        )
                    )
                )
                ->selectRaw(
                    'assigned_to_at_completion, COUNT(*) as total'
                )
                ->groupBy('assigned_to_at_completion')
                ->pluck('total', 'assigned_to_at_completion');

            foreach ($staffMembers as $staff) {
                $staffPerformanceLabels[] = $staff->name;

                $staffPerformanceData[] = (int) (
                    $completedByStaff[$staff->id] ?? 0
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Daftar Tugas yang Perlu Dipantau
        |--------------------------------------------------------------------------
        | Prioritas:
        | 1. Terlambat.
        | 2. Terhambat.
        | 3. Tenggat terdekat.
        */

        $priorityOrder = [
            'blocked' => 0,
            'open' => 1,
            'in_progress' => 2,
            'pending_review' => 3,
        ];

        $attentionTasks = (clone $tasksQuery)
            ->with(['assignee', 'department', 'category'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->where(function (Builder $query) {
                $query->whereDate('due_date', '<', today())
                    ->orWhere('status', 'blocked')
                    ->orWhere(function (Builder $subQuery) {
                        $subQuery->whereNotNull('due_date')
                            ->whereDate('due_date', '<=', today()->addDays(3));
                    });
            })
            ->orderByRaw(
                "CASE
                    WHEN due_date < ? AND status NOT IN ('completed', 'cancelled') THEN 0
                    WHEN status = 'blocked' THEN 1
                    ELSE 2
                END",
                [today()->toDateString()]
            )
            ->orderBy('due_date')
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Lima Tugas Terbaru
        |--------------------------------------------------------------------------
        */

        $recentTasks = (clone $tasksQuery)
            ->with(['assignee', 'department', 'category'])
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Aktivitas Terbaru
        |--------------------------------------------------------------------------
        */

        $recentActivities = TaskUpdate::query()
            ->with(['task', 'user'])
            ->whereHas('task', function (Builder $query) use ($user) {
                if ($user->role === 'manager') {
                    $query->where('department_id', $user->department_id);
                } elseif ($user->role === 'staff') {
                    $query->where('assigned_to', $user->id);
                }
            })
            ->latest()
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Data Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'totalTasks',
            'inProgressTasks',
            'pendingReviewTasks',
            'completedTasks',
            'openTasks',
            'blockedTasks',
            'overdueTasks',
            'upcomingTasks',
            'completionTrendLabels',
            'completionTrendData',
            'statusDistributionLabels',
            'statusDistributionData',
            'statusDistributionColors',
            'staffPerformanceLabels',
            'staffPerformanceData',
            'attentionTasks',
            'recentTasks',
            'recentActivities'
        ));
    }
}
