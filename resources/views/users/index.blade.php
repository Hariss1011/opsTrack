@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
            <div>
                <div class="mb-2 flex items-center gap-2">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-blue-400/20 bg-blue-500/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-13a4 4 0 010 8m4 5v-2a4 4 0 00-3-3.87M9 11a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </span>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-400">
                        Pengaturan Sistem
                    </p>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-100 sm:text-3xl">
                    Manajemen Pengguna
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-400">
                    Kelola akun, hak akses, departemen, dan status pengguna dalam sistem OpsTrack.
                </p>
            </div>

            <a href="{{ route('users.create') }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/30 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#0B1120]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                </svg>
                Tambah Pengguna
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/8 p-4 text-sm text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div
                class="flex items-start gap-3 rounded-xl border border-rose-500/20 bg-rose-500/8 p-4 text-sm text-rose-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.3 3.9L1.8 18.6A1.6 1.6 0 003.2 21h17.6a1.6 1.6 0 001.4-2.4L13.7 3.9a1.97 1.97 0 00-3.4 0z" />
                </svg>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Summary --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Total Pengguna</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-100">
                            {{ number_format($users->total()) }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Sesuai hasil pencarian</p>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-500/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-13a4 4 0 010 8m4 5v-2a4 4 0 00-3-3.87M9 11a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Admin</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-violet-300">
                            {{ $users->getCollection()->where('role', 'admin')->count() }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Pada halaman ini</p>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-violet-400/20 bg-violet-500/10 text-violet-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15l-2 5 2-1 2 1-2-5zm0 0a6 6 0 100-12 6 6 0 000 12z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Akun Aktif</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-emerald-400">
                            {{ $users->getCollection()->where('is_active', true)->count() }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Pada halaman ini</p>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-emerald-400/20 bg-emerald-500/10 text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filters --}}
        <section class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5 sm:p-6">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0B1120] text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-slate-100">Pencarian dan Filter</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Temukan pengguna berdasarkan identitas, role, departemen, atau status akun.
                    </p>
                </div>
            </div>

            <form action="{{ route('users.index') }}" method="GET"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">

                <div class="xl:col-span-2">
                    <label for="search" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Cari Pengguna
                    </label>
                    <input id="search" name="search" type="text" value="{{ $search }}"
                        placeholder="Nama atau email..."
                        class="w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-500 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>

                <div>
                    <label for="role" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Role
                    </label>
                    <select id="role" name="role"
                        class="w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-3 py-3 text-sm text-slate-200 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua Role</option>
                        <option value="admin" @selected($role === 'admin')>Admin</option>
                        <option value="manager" @selected($role === 'manager')>Manager</option>
                        <option value="staff" @selected($role === 'staff')>Staff</option>
                    </select>
                </div>

                <div>
                    <label for="department_id"
                        class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Departemen
                    </label>
                    <select id="department_id" name="department_id"
                        class="w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-3 py-3 text-sm text-slate-200 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua Departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) $departmentId === (string) $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Status Akun
                    </label>
                    <select id="status" name="status"
                        class="w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-3 py-3 text-sm text-slate-200 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-3 md:col-span-2 xl:col-span-5">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Terapkan Filter
                    </button>

                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#29364A] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-[#151F32]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h5M20 20v-5h-5M5.5 9a7 7 0 0112-2L20 9M4 15l2.5 2a7 7 0 0012-2" />
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- Users Table --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div
                class="flex flex-col justify-between gap-3 border-b border-[#29364A] px-5 py-5 sm:flex-row sm:items-center">
                <div>
                    <h2 class="font-semibold text-slate-100">Daftar Pengguna</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Informasi akun dan pengaturan akses pengguna OpsTrack.
                    </p>
                </div>

                <span
                    class="inline-flex w-fit items-center gap-2 rounded-lg border border-[#29364A] bg-[#0B1120] px-3 py-2 text-xs font-medium text-slate-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                    {{ number_format($users->total()) }} pengguna
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-250 text-left text-sm">
                    <thead
                        class="border-b border-[#29364A] bg-[#0B1120]/70 text-[11px] uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Pengguna</th>
                            <th class="px-5 py-4 font-semibold">Role</th>
                            <th class="px-5 py-4 font-semibold">Departemen</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold">Tanggal Dibuat</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#29364A]/70">
                        @forelse ($users as $user)
                            <tr class="transition hover:bg-[#151F32]/70">

                                {{-- User Identity --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-400/15 bg-blue-500/10 text-sm font-bold text-blue-300">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-100">
                                                {{ $user->name }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $user->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Role --}}
                                <td class="px-5 py-4">
                                    @if ($user->role === 'admin')
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-violet-400/20 bg-violet-500/10 px-3 py-1.5 text-xs font-semibold text-violet-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-violet-400"></span>
                                            Admin
                                        </span>
                                    @elseif ($user->role === 'manager')
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-blue-400/20 bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                                            Manager
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-slate-500/20 bg-slate-500/10 px-3 py-1.5 text-xs font-semibold text-slate-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Department --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 text-slate-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 21h18M5 21V7l8-4v18m6 0V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01m8-3v.01m0 3v.01" />
                                        </svg>
                                        <span>{{ $user->department->name ?? '—' }}</span>
                                    </div>
                                </td>

                                {{-- Account Status --}}
                                <td class="px-5 py-4">
                                    @if ($user->is_active)
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1.5 text-xs font-semibold text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-rose-400/20 bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Created At --}}
                                <td class="px-5 py-4 text-slate-400">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z" />
                                        </svg>
                                        {{ $user->created_at?->format('d M Y') ?? '—' }}
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-blue-400/20 bg-blue-500/5 px-3 py-2 text-xs font-semibold text-blue-300 transition hover:border-blue-400/40 hover:bg-blue-500/15">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l2.651 2.651M4 20l4.5-1 11-11a2.121 2.121 0 00-3-3l-11 11L4 20z" />
                                            </svg>
                                            Edit
                                        </a>

                                        @if ((int) $user->id !== (int) auth()->id())
                                            <form action="{{ route('users.toggle-status', $user) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun ini?')">
                                                @csrf
                                                @method('PATCH')

                                                @if ($user->is_active)
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-lg border border-rose-400/20 px-3 py-2 text-xs font-semibold text-rose-300 transition hover:border-rose-400/40 hover:bg-rose-500/10">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="1.8">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M18.364 5.636A9 9 0 115.636 5.636m12.728 0L12 12m6.364-6.364V11m0-5.364H13" />
                                                        </svg>
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-400/20 px-3 py-2 text-xs font-semibold text-emerald-300 transition hover:border-emerald-400/40 hover:bg-emerald-500/10">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="1.8">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Aktifkan
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-[#29364A] bg-[#0B1120] text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-13a4 4 0 010 8m4 5v-2a4 4 0 00-3-3.87M9 11a4 4 0 100-8 4 4 0 000 8z" />
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 font-semibold text-slate-200">
                                        Pengguna tidak ditemukan
                                    </h3>
                                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                                        Tidak ada pengguna yang sesuai dengan kata kunci atau filter yang dipilih.
                                        Coba ubah kriteria pencarian.
                                    </p>

                                    <a href="{{ route('users.index') }}"
                                        class="mt-5 inline-flex items-center gap-2 rounded-xl border border-[#29364A] px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-[#151F32]">
                                        Reset Pencarian
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div
                    class="flex flex-col justify-between gap-3 border-t border-[#29364A] px-5 py-4 sm:flex-row sm:items-center">
                    <p class="text-xs text-slate-500">
                        Menampilkan
                        <span class="font-semibold text-slate-300">{{ $users->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-semibold text-slate-300">{{ $users->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-slate-300">{{ $users->total() }}</span>
                        pengguna
                    </p>

                    <div class="opstrack-pagination">
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </section>

    </div>
@endsection
