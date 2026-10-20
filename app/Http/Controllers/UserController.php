<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        $role = $request->input('role');
        $departmentId = $request->input('department_id');
        $status = $request->input('status');

        $users = User::with('department')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array($role, ['admin', 'manager', 'staff'], true),
                fn($query) => $query->where('role', $role)
            )
            ->when(
                is_numeric($departmentId),
                fn($query) => $query->where('department_id', $departmentId)
            )
            ->when(
                in_array($status, ['active', 'inactive'], true),
                fn($query) => $query->where(
                    'is_active',
                    $status === 'active'
                )
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();

        return view('users.index', compact(
            'users',
            'departments',
            'search',
            'role',
            'departmentId',
            'status'
        ));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);

        $departments = Department::orderBy('name')->get();

        return view('users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'manager', 'staff'])],
            'department_id' => [
                'nullable',
                'required_if:role,manager,staff',
                'exists:departments,id',
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['required', 'boolean'],
        ], [
            'department_id.required_if' => 'Manager dan Staff wajib memiliki departemen.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($data['role'] === 'admin') {
            $data['department_id'] = null;
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        $departments = Department::orderBy('name')->get();

        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'manager', 'staff'])],
            'department_id' => [
                'nullable',
                'required_if:role,manager,staff',
                'exists:departments,id',
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['required', 'boolean'],
        ], [
            'department_id.required_if' => 'Manager dan Staff wajib memiliki departemen.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Admin tidak boleh menghilangkan akses admin dari akunnya sendiri.
        if ((int) $user->id === (int) $request->user()->id) {
            $data['role'] = 'admin';
            $data['is_active'] = true;
        }

        if ($data['role'] === 'admin') {
            $data['department_id'] = null;
        }

        // Jangan sampai tidak ada admin aktif yang tersisa.
        $removingAdminAccess = $user->role === 'admin'
            && $user->is_active
            && ($data['role'] !== 'admin' || !$data['is_active']);

        if (
            $removingAdminAccess &&
            User::where('role', 'admin')->where('is_active', true)->count() <= 1
        ) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors([
                    'role' => 'Tidak dapat menonaktifkan atau mengubah role admin aktif terakhir.',
                ]);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        $this->authorizeAdmin($request);

        if ((int) $user->id === (int) $request->user()->id) {
            return back()->with(
                'error',
                'Anda tidak dapat menonaktifkan akun sendiri.'
            );
        }

        if ($user->is_active && $user->role === 'admin') {
            $activeAdmins = User::where('role', 'admin')
                ->where('is_active', true)
                ->count();

            if ($activeAdmins <= 1) {
                return back()->with(
                    'error',
                    'Admin aktif terakhir tidak dapat dinonaktifkan.'
                );
            }
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $message = $user->is_active
            ? 'Akun pengguna berhasil diaktifkan.'
            : 'Akun pengguna berhasil dinonaktifkan.';

        return back()->with('success', $message);
    }
}
