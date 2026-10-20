<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskEventNotification;

class TaskController extends Controller
{
    private function accessibleTasks()
    {
        $user = Auth::user();

        $query = Task::with(['department', 'category', 'assignee', 'creator']);

        if ($user->role === 'manager') {
            $query->where('department_id', $user->department_id);
        } elseif ($user->role === 'staff') {
            $query->where('assigned_to', $user->id);
        }

        return $query;
    }


    public function index(Request $request)
    {
        // Query dasar sesuai hak akses pengguna.
        $baseQuery = $this->accessibleTasks();

        // Ringkasan dihitung dari seluruh tugas yang dapat diakses,
        // tanpa dipengaruhi filter dan pagination.
        $totalTasks = (clone $baseQuery)->count();

        $activeTasks = (clone $baseQuery)
            ->whereIn('status', [
                'open',
                'in_progress',
                'blocked',
                'pending_review',
            ])
            ->count();

        $completedTasks = (clone $baseQuery)
            ->where('status', 'completed')
            ->count();

        // Query terpisah untuk daftar tugas dan filter.
        $query = clone $baseQuery;

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('assignee', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->input('overdue') === '1') {
            $query->whereDate('due_date', '<', today())
                ->whereNotIn('status', ['completed', 'cancelled']);
        }

        $tasks = $query
            ->latest()
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('tasks.index', compact(
            'tasks',
            'totalTasks',
            'activeTasks',
            'completedTasks'
        ));
    }


    public function create()
    {
        abort_unless(
            in_array(Auth::user()->role, ['admin', 'manager'], true),
            403
        );

        $user = Auth::user();

        $departments = Department::where('is_active', true)
            ->when(
                $user->role === 'manager',
                fn($q) => $q->whereKey($user->department_id)
            )
            ->orderBy('name')
            ->get();

        $categories = TaskCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        $users = User::where('is_active', true)
            ->where('role', 'staff')
            ->when(
                $user->role === 'manager',
                fn($q) => $q->where('department_id', $user->department_id)
            )
            ->orderBy('name')
            ->get();

        return view('tasks.create', compact(
            'departments',
            'categories',
            'users'
        ));
    }

    public function store(Request $request)
    {
        abort_unless(
            in_array(Auth::user()->role, ['admin', 'manager'], true),
            403
        );

        $user = Auth::user();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'category_id' => ['required', 'exists:task_categories,id'],
            'assigned_to' => ['required', 'exists:users,id'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'start_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        if (
            $user->role === 'manager' &&
            (int) $data['department_id'] !== (int) $user->department_id
        ) {
            abort(403, 'Anda hanya dapat membuat tugas untuk departemen sendiri.');
        }

        $assignee = User::whereKey($data['assigned_to'])
            ->where('is_active', true)
            ->where('role', 'staff')
            ->first();

        if (! $assignee || (int) $assignee->department_id !== (int) $data['department_id']) {
            return back()
                ->withErrors(['assigned_to' => 'Staff harus aktif dan berasal dari departemen yang dipilih.'])
                ->withInput();
        }

        $categoryExists = TaskCategory::whereKey($data['category_id'])
            ->where('is_active', true)
            ->exists();

        if (! $categoryExists) {
            return back()
                ->withErrors(['category_id' => 'Kategori tidak aktif atau tidak tersedia.'])
                ->withInput();
        }

        $task = DB::transaction(function () use ($data, $user) {
            $task = Task::create([
                ...$data,
                'created_by' => $user->id,
                'status' => 'open',
            ]);

            $task->statusHistories()->create([
                'changed_by' => $user->id,
                'old_status' => null,
                'new_status' => 'open',
                'changed_at' => now(),
            ]);

            $task->updates()->create([
                'user_id' => $user->id,
                'update_type' => 'general',
                'description' => 'Tugas dibuat oleh ' . $user->name . '.',
            ]);

            return $task;
        });

        // Beri tahu staff yang ditugaskan.
        $assignee->notify(new TaskEventNotification(
            title: 'Tugas Baru',
            message: 'Anda mendapatkan tugas baru: ' . $task->title,
            url: route('tasks.show', $task),
            type: 'task_assigned'
        ));

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Task $task)
    {
        abort_unless(
            $this->accessibleTasks()->whereKey($task->id)->exists(),
            403
        );

        $task->load([
            'department',
            'category',
            'assignee',
            'creator',
            'updates.user',
            'statusHistories.changedBy',
        ]);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['admin', 'manager'], true),
            403
        );

        abort_unless(
            $user->role === 'admin' ||
                (int) $task->department_id === (int) $user->department_id,
            403
        );

        abort_if(
            in_array($task->status, ['completed', 'cancelled'], true),
            403,
            'Tugas yang sudah selesai atau dibatalkan tidak dapat diedit.'
        );

        $departments = Department::where('is_active', true)
            ->when(
                $user->role === 'manager',
                fn($q) => $q->whereKey($user->department_id)
            )
            ->orderBy('name')
            ->get();

        $categories = TaskCategory::query()
            ->where(function ($query) use ($task) {
                $query->where('is_active', true)
                    ->orWhere('id', $task->category_id);
            })
            ->orderBy('name')
            ->get();

        $users = User::where('is_active', true)
            ->where('role', 'staff')
            ->when(
                $user->role === 'manager',
                fn($q) => $q->where('department_id', $user->department_id)
            )
            ->where('department_id', $task->department_id)
            ->orderBy('name')
            ->get();

        return view('tasks.edit', compact(
            'task',
            'departments',
            'categories',
            'users'
        ));
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['admin', 'manager'], true),
            403
        );

        abort_unless(
            $user->role === 'admin' ||
                (int) $task->department_id === (int) $user->department_id,
            403
        );

        abort_if(
            in_array($task->status, ['completed', 'cancelled'], true),
            403,
            'Tugas yang sudah selesai atau dibatalkan tidak dapat diedit.'
        );

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'category_id' => ['required', 'exists:task_categories,id'],
            'assigned_to' => ['required', 'exists:users,id'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'start_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        if (
            $user->role === 'manager' &&
            (int) $data['department_id'] !== (int) $user->department_id
        ) {
            abort(403);
        }

        $assignee = User::whereKey($data['assigned_to'])
            ->where('is_active', true)
            ->where('role', 'staff')
            ->where('department_id', $data['department_id'])
            ->first();

        if (! $assignee) {
            return back()
                ->withErrors(['assigned_to' => 'Pilih staff aktif dari departemen tugas.'])
                ->withInput();
        }

        $categoryExists = TaskCategory::whereKey($data['category_id'])
            ->where('is_active', true)
            ->exists();

        if (! $categoryExists) {
            return back()
                ->withErrors(['category_id' => 'Kategori tidak aktif atau tidak tersedia.'])
                ->withInput();
        }

        $changes = [];

        $changeLabels = [
            'title' => 'judul tugas',
            'description' => 'deskripsi',
            'department_id' => 'departemen',
            'category_id' => 'kategori',
            'assigned_to' => 'penanggung jawab',
            'priority' => 'prioritas',
            'start_date' => 'tanggal mulai',
            'due_date' => 'tenggat waktu',
        ];

        foreach ($changeLabels as $field => $label) {
            $oldValue = $task->getAttribute($field);
            $newValue = $data[$field] ?? null;

            if (in_array($field, ['start_date', 'due_date'], true)) {
                $oldValue = $oldValue?->toDateString();
            }

            if ((string) ($oldValue ?? '') !== (string) ($newValue ?? '')) {
                $changes[] = $label;
            }
        }

        DB::transaction(function () use ($task, $user, $data, $changes) {
            $task->update($data);

            if (! empty($changes)) {
                $task->updates()->create([
                    'user_id' => $user->id,
                    'update_type' => 'general',
                    'description' => 'Detail tugas diperbarui oleh '
                        . $user->name . '. Perubahan: '
                        . implode(', ', $changes) . '.',
                ]);
            }
        });

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function start(Request $request, Task $task)
    {
        $user = $request->user();

        // Hanya staff yang ditugaskan boleh memulai tugas.
        abort_unless(
            $user->role === 'staff' &&
                (int) $task->assigned_to === (int) $user->id,
            403,
            'Anda tidak memiliki akses untuk memulai tugas ini.'
        );

        DB::transaction(function () use ($task, $user) {
            $lockedTask = Task::whereKey($task->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedTask->status !== 'open') {
                abort(422, 'Tugas ini sudah tidak berstatus Belum Dimulai.');
            }

            $lockedTask->update([
                'status' => 'in_progress',
            ]);

            $lockedTask->statusHistories()->create([
                'changed_by' => $user->id,
                'old_status' => 'open',
                'new_status' => 'in_progress',
                'changed_at' => now(),
            ]);

            $lockedTask->updates()->create([
                'user_id' => $user->id,
                'update_type' => 'general',
                'description' => $user->name . ' mulai mengerjakan tugas.',
            ]);
        });

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tugas berhasil dimulai. Silakan catat progres pekerjaan Anda.');
    }

    public function cancel(Task $task)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['admin', 'manager'], true),
            403
        );

        abort_unless(
            $user->role === 'admin' ||
                (int) $task->department_id === (int) $user->department_id,
            403
        );

        abort_if(
            in_array($task->status, ['completed', 'cancelled'], true),
            403,
            'Tugas ini sudah selesai atau dibatalkan.'
        );

        DB::transaction(function () use ($task, $user) {
            $oldStatus = $task->status;

            $task->update(['status' => 'cancelled']);

            $task->statusHistories()->create([
                'changed_by' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'changed_at' => now(),
            ]);

            $task->updates()->create([
                'user_id' => $user->id,
                'update_type' => 'general',
                'description' => 'Tugas dibatalkan oleh ' . $user->name . '.',
            ]);
        });

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil dibatalkan.');
    }
}
