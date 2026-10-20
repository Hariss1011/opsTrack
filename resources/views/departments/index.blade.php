@extends('layouts.app')

@section('title', 'Manajemen Departemen')

@section('content')
    <div class="space-y-6">

        {{-- Page Heading --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                    Pengaturan Sistem
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-[#E2E8F0] sm:text-3xl">
                    Manajemen Departemen
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-[#94A3B8]">
                    Kelola struktur departemen dan pembagian organisasi yang digunakan dalam operasional OpsTrack.
                </p>
            </div>

            <a href="{{ route('departments.create') }}"
                class="inline-flex items-center justify-center gap-2 self-start rounded-xl bg-[#3B82F6] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#0B1120] sm:self-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                </svg>
                Tambah Departemen
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm text-emerald-300"
                role="status">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                </svg>
                <div>
                    <p class="font-semibold">Berhasil</p>
                    <p class="mt-1 text-emerald-300/80">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 rounded-xl border border-rose-400/20 bg-rose-400/10 p-4 text-sm text-rose-300"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.3 3.8 2.9 17a2 2 0 0 0 1.8 3h14.2a2 2 0 0 0 1.8-3L13.7 3.8a2 2 0 0 0-3.4 0Z" />
                </svg>
                <div>
                    <p class="font-semibold">Terjadi Kesalahan</p>
                    <p class="mt-1 text-rose-300/80">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Search --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div class="flex items-center gap-3 border-b border-[#29364A] px-5 py-4 sm:px-6">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-400/10 text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <circle cx="10.8" cy="10.8" r="6.8" />
                        <path stroke-linecap="round" d="m16 16 4 4" />
                    </svg>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-[#E2E8F0]">Cari Departemen</h2>
                    <p class="mt-0.5 text-xs text-[#94A3B8]">Temukan departemen berdasarkan nama.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('departments.index') }}"
                class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:p-6">
                <div class="relative min-w-0 flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="10.8" cy="10.8" r="6.8" />
                        <path stroke-linecap="round" d="m16 16 4 4" />
                    </svg>

                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama departemen..."
                        class="block w-full rounded-xl border border-[#29364A] bg-[#0B1120] py-3 pl-10 pr-4 text-sm text-[#E2E8F0] outline-none transition placeholder:text-slate-600 focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#3B82F6] px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 sm:flex-none">
                        Cari
                    </button>

                    <a href="{{ route('departments.index') }}"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-[#35445B] bg-[#151F32] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-[#1B2940] hover:text-white sm:flex-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M5.6 15a7 7 0 1 0 .1-6" />
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- Department Table --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">

            {{-- Table Header --}}
            <div
                class="flex flex-col gap-3 border-b border-[#29364A] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-400/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 21h18M5 21V7l8-4v18M13 10h6v11M8 9h1m-1 4h1m-1 4h1m7-4h1m-1 4h1" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold text-[#E2E8F0]">Daftar Departemen</h2>
                        <p class="mt-1 text-xs text-[#94A3B8]">Informasi departemen dan aktivitas yang terkait.</p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-2 self-start rounded-lg border border-[#29364A] bg-[#0B1120] px-3 py-2 sm:self-center">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                    <span
                        class="text-sm font-semibold tabular-nums text-[#E2E8F0]">{{ number_format($departments->total()) }}</span>
                    <span class="text-xs text-[#94A3B8]">departemen</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-170 text-left text-sm">
                    <thead class="bg-[#0B1120]/70">
                        <tr>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-6">
                                Nama Departemen
                            </th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Jumlah Pengguna
                            </th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Jumlah Tugas
                            </th>
                            <th
                                class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-6">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#29364A]">
                        @forelse ($departments as $department)
                            <tr class="transition duration-150 hover:bg-[#151F32]">

                                {{-- Department Name --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-400/10 text-sm font-bold text-blue-300">
                                            {{ strtoupper(substr($department->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-[#E2E8F0]">
                                                {{ $department->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500">
                                                Dibuat {{ $department->created_at?->format('d M Y') ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Users Count --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-lg border border-blue-400/20 bg-blue-400/10 px-3 py-1.5 text-xs font-semibold text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                        {{ number_format($department->users_count) }} pengguna
                                    </span>
                                </td>

                                {{-- Tasks Count --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-lg border border-slate-500/20 bg-slate-500/10 px-3 py-1.5 text-xs font-semibold text-slate-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                        </svg>
                                        {{ number_format($department->tasks_count) }} tugas
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('departments.edit', $department) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-blue-400/20 bg-blue-400/5 px-3 py-2 text-xs font-semibold text-blue-300 transition hover:border-blue-400/40 hover:bg-blue-400/10 hover:text-blue-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.5 3.5 4 4M4 20l4.5-1 11-11a2.12 2.12 0 0 0-3-3l-11 11L4 20Z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('departments.destroy', $department) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus departemen {{ addslashes($department->name) }}? Departemen yang masih digunakan tidak dapat dihapus.')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-rose-400/20 bg-rose-400/5 px-3 py-2 text-xs font-semibold text-rose-300 transition hover:border-rose-400/40 hover:bg-rose-400/10 hover:text-rose-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 6h18m-2 0-.9 14H5.9L5 6m4 0V4h6v2m-5 4v6m4-6v6" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-14 text-center">
                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-[#29364A] bg-[#0B1120] text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 21h18M5 21V7l8-4v18" />
                                        </svg>
                                    </div>

                                    <p class="mt-4 text-sm font-semibold text-[#E2E8F0]">
                                        Departemen tidak ditemukan
                                    </p>

                                    <p class="mx-auto mt-1 max-w-sm text-sm leading-6 text-[#94A3B8]">
                                        Tidak ada departemen yang cocok dengan kata kunci tersebut. Coba kata kunci lain
                                        atau tambahkan departemen baru.
                                    </p>

                                    <a href="{{ route('departments.create') }}"
                                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#3B82F6] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                        </svg>
                                        Tambah Departemen
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($departments->hasPages())
                <div
                    class="flex flex-col gap-3 border-t border-[#29364A] px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <p class="text-xs text-slate-500">
                        Menampilkan
                        <span class="font-semibold text-slate-300">{{ $departments->firstItem() ?? 0 }}</span>
                        –
                        <span class="font-semibold text-slate-300">{{ $departments->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-slate-300">{{ $departments->total() }}</span>
                        departemen
                    </p>

                    <div class="opstrack-pagination">
                        {{ $departments->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </section>

        {{-- Footer --}}
        <div
            class="flex flex-col gap-2 border-t border-[#29364A] pt-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>OpsTrack · Operational Performance Monitoring System</p>
            <p>Manajemen struktur organisasi</p>
        </div>

    </div>
@endsection
