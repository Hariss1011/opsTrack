<?php

namespace App\Http\Controllers;

use App\Models\TaskCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskCategoryController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        abort_unless(
            $request->user()?->role === 'admin',
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', '');

        $categories = TaskCategory::query()
            ->withCount('tasks')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['active', 'inactive'], true), function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('task-categories.index', compact(
            'categories',
            'search',
            'status'
        ));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);

        return view('task-categories.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:task_categories,name'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['required', 'boolean'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'is_active.required' => 'Status kategori wajib dipilih.',
        ]);

        TaskCategory::create($data);

        return redirect()
            ->route('task-categories.index')
            ->with('success', 'Kategori tugas berhasil ditambahkan.');
    }

    public function edit(Request $request, TaskCategory $taskCategory)
    {
        $this->authorizeAdmin($request);

        return view('task-categories.edit', [
            'category' => $taskCategory,
        ]);
    }

    public function update(Request $request, TaskCategory $taskCategory)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('task_categories', 'name')->ignore($taskCategory->id),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['required', 'boolean'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'is_active.required' => 'Status kategori wajib dipilih.',
        ]);

        $taskCategory->update($data);

        return redirect()
            ->route('task-categories.index')
            ->with('success', 'Kategori tugas berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, TaskCategory $taskCategory)
    {
        $this->authorizeAdmin($request);

        $taskCategory->update([
            'is_active' => ! $taskCategory->is_active,
        ]);

        $message = $taskCategory->is_active
            ? 'Kategori berhasil diaktifkan.'
            : 'Kategori berhasil dinonaktifkan.';

        return redirect()
            ->route('task-categories.index')
            ->with('success', $message);
    }

    public function destroy(Request $request, TaskCategory $taskCategory)
    {
        $this->authorizeAdmin($request);

        if ($taskCategory->tasks()->exists()) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena sudah digunakan oleh tugas. Nonaktifkan kategori jika tidak ingin digunakan lagi.'
            );
        }

        $taskCategory->delete();

        return redirect()
            ->route('task-categories.index')
            ->with('success', 'Kategori tugas berhasil dihapus.');
    }
}
