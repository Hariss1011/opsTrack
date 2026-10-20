<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
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

        $departments = Department::query()
            ->withCount(['users', 'tasks'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact('departments', 'search'));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);

        return view('departments.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
        ], [
            'name.required' => 'Nama departemen wajib diisi.',
            'name.unique' => 'Nama departemen sudah digunakan.',
        ]);

        Department::create($data);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Request $request, Department $department)
    {
        $this->authorizeAdmin($request);

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->ignore($department->id),
            ],
        ], [
            'name.required' => 'Nama departemen wajib diisi.',
            'name.unique' => 'Nama departemen sudah digunakan.',
        ]);

        $department->update($data);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Request $request, Department $department)
    {
        $this->authorizeAdmin($request);

        $hasUsers = User::where('department_id', $department->id)->exists();
        $hasTasks = Task::where('department_id', $department->id)->exists();

        if ($hasUsers || $hasTasks) {
            return back()->with(
                'error',
                'Departemen tidak dapat dihapus karena masih digunakan oleh pengguna atau tugas.'
            );
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}
