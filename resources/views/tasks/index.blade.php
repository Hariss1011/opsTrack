@extends('layouts.app')

@section('title', 'Manajemen Tugas')

@section('content')
    @php
        $statusLabels = [
            'open' => 'Belum Dimulai',
            'in_progress' => 'Sedang Dikerjakan',
            'blocked' => 'Terhambat',
            'pending_review' => 'Menunggu Verifikasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $priorityLabels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak',
        ];

        $statusColors = [
            'open' => 'border-slate-500/20 bg-slate-500/10 text-slate-300',
            'in_progress' => 'border-blue-400/20 bg-blue-400/10 text-blue-300',
            'blocked' => 'border-rose-400/20 bg-rose-400/10 text-rose-300',
            'pending_review' => 'border-amber-400/20 bg-amber-400/10 text-amber-300',
            'completed' => 'border-emerald-400/20 bg-emerald-400/10 text-emerald-300',
            'cancelled' => 'border-slate-600/30 bg-slate-700/30 text-slate-400',
        ];

        $priorityColors = [
            'low' => 'text-slate-400',
            'medium' => 'text-sky-300',
            'high' => 'text-orange-300',
            'urgent' => 'text-rose-300',
        ];

        $priorityDots = [
            'low' => 'bg-slate-400',
            'medium' => 'bg-sky-400',
            'high' => 'bg-orange-400',
            'urgent' => 'bg-rose-400',
        ];

        $inputClass =
            'w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-4 py-3 text-sm text-[#E2E8F0] placeholder:text-slate-500 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20';
        $labelClass = 'mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-400';
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <section class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
            <div>
                <div class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-blue-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                    Operasional
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-[#E2E8F0] sm:text-3xl">
                    Manajemen Tugas
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-[#94A3B8]">
                    Kelola pekerjaan, pantau progres, dan pastikan setiap tugas selesai sesuai tenggat.
                </p>
            </div>

            @if (in_array(auth()->user()->role, ['admin', 'manager'], true))
                <a href="{{ route('tasks.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/30 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#0B1120]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>
                    Buat Tugas
                </a>
            @endif
        </section>

        {{-- Ringkasan --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- Total Tugas --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-blue-400/20 bg-gradient-to-br from-[#142440] to-[#111B2E] p-5 transition duration-200 hover:-translate-y-0.5 hover:border-blue-400/40 hover:shadow-lg hover:shadow-blue-950/20">
                <div
                    class="absolute -right-5 -top-5 h-24 w-24 rounded-full bg-blue-500/10 blur-2xl transition group-hover:bg-blue-500/20">
                </div>

                <div class="relative flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-300">Total Tugas</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-white">
                            {{ number_format($totalTasks) }}
                        </p>
                        <p class="mt-2 text-xs text-slate-400">
                            Seluruh tugas sesuai hak akses
                        </p>
                    </div>

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-400/10 text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Tugas Aktif --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-sky-400/20 bg-[#111B2E] p-5 transition duration-200 hover:-translate-y-0.5 hover:border-sky-400/40 hover:shadow-lg hover:shadow-sky-950/10">
                <div
                    class="absolute -right-5 -top-5 h-24 w-24 rounded-full bg-sky-500/10 blur-2xl transition group-hover:bg-sky-500/15">
                </div>

                <div class="relative flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-300">Tugas Aktif</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-white">
                            {{ number_format($activeTasks) }}
                        </p>
                        <p class="mt-2 text-xs text-slate-400">
                            Belum dimulai, berjalan, atau terhambat
                        </p>
                    </div>

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-sky-400/20 bg-sky-400/10 text-sky-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Tugas Selesai --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-emerald-400/20 bg-[#111B2E] p-5 transition duration-200 hover:-translate-y-0.5 hover:border-emerald-400/40 hover:shadow-lg hover:shadow-emerald-950/10">
                <div
                    class="absolute -right-5 -top-5 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl transition group-hover:bg-emerald-500/15">
                </div>

                <div class="relative flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-300">Tugas Selesai</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-white">
                            {{ number_format($completedTasks) }}
                        </p>
                        <p class="mt-2 text-xs text-slate-400">
                            Tugas yang telah berstatus selesai
                        </p>
                    </div>

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-emerald-400/20 bg-emerald-400/10 text-emerald-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" />
                        </svg>
                    </div>
                </div>
            </div>

        </section>

        {{-- Filter --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-sm shadow-black/10">
            <div class="flex items-center gap-3 border-b border-[#29364A] px-5 py-4 sm:px-6">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-700/40 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16M7 12h10m-7 7h4" />
                    </svg>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-[#E2E8F0]">Pencarian dan Filter</h2>
                    <p class="mt-1 text-xs text-[#94A3B8]">Persempit daftar berdasarkan informasi tugas.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('tasks.index') }}" class="p-5 sm:p-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">

                    <div class="md:col-span-2 xl:col-span-2">
                        <label for="search" class="{{ $labelClass }}">Cari Tugas</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4" />
                            </svg>

                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Judul tugas atau nama staff..." class="{{ $inputClass }} pl-10">
                        </div>
                    </div>

                    <div>
                        <label for="status" class="{{ $labelClass }}">Status</label>
                        <select name="status" id="status" class="{{ $inputClass }}">
                            <option value="">Semua Status</option>
                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="{{ $labelClass }}">Prioritas</label>
                        <select name="priority" id="priority" class="{{ $inputClass }}">
                            <option value="">Semua Prioritas</option>
                            @foreach ($priorityLabels as $value => $label)
                                <option value="{{ $value }}" @selected(request('priority') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="overdue" class="{{ $labelClass }}">Tenggat</label>
                        <select name="overdue" id="overdue" class="{{ $inputClass }}">
                            <option value="">Semua Tenggat</option>
                            <option value="1" @selected(request('overdue') === '1')>
                                Tugas Terlambat
                            </option>
                        </select>
                    </div>

                </div>

                <div class="mt-5 flex flex-col gap-3 border-t border-[#29364A] pt-5 sm:flex-row sm:items-center">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#111B2E]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4" />
                        </svg>
                        Terapkan Filter
                    </button>

                    <a href="{{ route('tasks.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-[#35445B] px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-white/5 hover:text-white">
                        Reset Filter
                    </a>

                    @if (request()->hasAny(['search', 'status', 'priority', 'overdue']))
                        <span class="text-xs text-slate-500 sm:ml-auto">
                            Filter aktif diterapkan pada daftar tugas.
                        </span>
                    @endif
                </div>
            </form>
        </section>

        {{-- Tabel tugas --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">

            <div
                class="flex flex-col justify-between gap-3 border-b border-[#29364A] px-5 py-5 sm:flex-row sm:items-center sm:px-6">
                <div>
                    <h2 class="text-base font-semibold text-[#E2E8F0]">Daftar Tugas</h2>
                    <p class="mt-1.5 text-sm text-[#94A3B8]">
                        Menampilkan {{ $tasks->count() }} dari {{ $tasks->total() }} tugas yang tersedia.
                    </p>
                </div>

                <div
                    class="inline-flex w-fit items-center gap-2 rounded-lg border border-[#29364A] bg-[#0B1120] px-3 py-2 text-xs font-medium text-slate-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Data sesuai hak akses
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-262.5 text-left text-sm">
                    <thead
                        class="border-b border-[#29364A] bg-[#0B1120]/70 text-[11px] uppercase tracking-[0.12em] text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Tugas</th>
                            <th class="px-5 py-4 font-semibold">Departemen</th>
                            <th class="px-5 py-4 font-semibold">Penanggung Jawab</th>
                            <th class="px-5 py-4 font-semibold">Prioritas</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold">Tenggat</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#29364A]/80">
                        @forelse ($tasks as $task)
                            @php
                                $isOverdue =
                                    $task->due_date &&
                                    $task->due_date->toDateString() < today()->toDateString() &&
                                    !in_array($task->status, ['completed', 'cancelled'], true);
                            @endphp

                            <tr class="transition-colors duration-150 hover:bg-[#18253B]/70">

                                {{-- Informasi tugas --}}
                                <td class="px-6 py-5">
                                    <div class="flex min-w-55 items-start gap-3">
                                        <div
                                            class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-[#35445B] bg-[#18253B] text-blue-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.7">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 4h8l4 4v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3Zm7 0v5h5M8 13h8M8 17h5" />
                                            </svg>
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('tasks.show', $task) }}"
                                                class="font-semibold leading-5 text-[#E2E8F0] transition hover:text-blue-300">
                                                {{ $task->title }}
                                            </a>

                                            <p class="mt-1.5 text-xs text-slate-500">
                                                {{ $task->category->name ?? 'Tanpa kategori' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Departemen --}}
                                <td class="px-5 py-5">
                                    <span class="whitespace-nowrap text-slate-300">
                                        {{ $task->department->name ?? '-' }}
                                    </span>
                                </td>

                                {{-- Penanggung jawab --}}
                                <td class="px-5 py-5">
                                    <div class="flex min-w-38.75 items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-blue-400/20 bg-blue-400/10 text-xs font-bold text-blue-300">
                                            {{ $task->assignee ? mb_strtoupper(mb_substr($task->assignee->name, 0, 1)) : '?' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-slate-200">
                                                {{ $task->assignee->name ?? 'Belum ditugaskan' }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                Pembuat: {{ $task->creator->name ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Prioritas --}}
                                <td class="px-5 py-5">
                                    <span
                                        class="inline-flex items-center gap-2 whitespace-nowrap font-medium {{ $priorityColors[$task->priority] ?? 'text-slate-400' }}">
                                        <span
                                            class="h-2 w-2 rounded-full {{ $priorityDots[$task->priority] ?? 'bg-slate-400' }}"></span>
                                        {{ $priorityLabels[$task->priority] ?? ucfirst($task->priority) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-5">
                                    <span
                                        class="inline-flex items-center whitespace-nowrap rounded-lg border px-2.5 py-1.5 text-xs font-semibold {{ $statusColors[$task->status] ?? 'border-slate-500/20 bg-slate-500/10 text-slate-300' }}">
                                        {{ $statusLabels[$task->status] ?? ucfirst($task->status) }}
                                    </span>
                                </td>

                                {{-- Tenggat --}}
                                <td class="px-5 py-5">
                                    <div class="whitespace-nowrap">
                                        <p class="font-medium {{ $isOverdue ? 'text-rose-300' : 'text-slate-300' }}">
                                            {{ $task->due_date?->format('d M Y') ?? '-' }}
                                        </p>

                                        @if ($isOverdue)
                                            <p
                                                class="mt-1.5 inline-flex items-center gap-1.5 text-xs font-medium text-rose-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                                                Terlambat
                                            </p>
                                        @elseif ($task->status === 'completed')
                                            <p class="mt-1.5 text-xs text-emerald-400">Tugas selesai</p>
                                        @elseif ($task->status === 'cancelled')
                                            <p class="mt-1.5 text-xs text-slate-500">Tidak berlaku</p>
                                        @else
                                            <p class="mt-1.5 text-xs text-slate-500">Batas penyelesaian</p>
                                        @endif
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('tasks.show', $task) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-[#35445B] px-3 py-2 text-xs font-semibold text-slate-300 transition hover:border-blue-400/40 hover:bg-blue-400/10 hover:text-blue-300">
                                            Detail
                                        </a>

                                        @if (in_array(auth()->user()->role, ['admin', 'manager'], true) &&
                                                !in_array($task->status, ['completed', 'cancelled'], true))
                                            <a href="{{ route('tasks.edit', $task) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-blue-400/20 bg-blue-400/6 px-3 py-2 text-xs font-semibold text-blue-300 transition hover:border-blue-400/40 hover:bg-blue-400/15">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('tasks.cancel', $task) }}"
                                                onsubmit="return confirm('Yakin ingin membatalkan tugas ini?')">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="rounded-lg border border-rose-400/20 px-3 py-2 text-xs font-semibold text-rose-300 transition hover:border-rose-400/40 hover:bg-rose-400/10">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#35445B] bg-[#18253B] text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.6">
                                                <circle cx="11" cy="11" r="7" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16 16 4 4M8 11h6" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 text-base font-semibold text-[#E2E8F0]">
                                            Tidak ada tugas ditemukan
                                        </h3>

                                        <p class="mt-2 text-sm leading-6 text-[#94A3B8]">
                                            Tidak ada tugas yang sesuai dengan pencarian atau filter saat ini.
                                            Coba ubah kriteria pencarian atau reset filter.
                                        </p>

                                        <a href="{{ route('tasks.index') }}"
                                            class="mt-5 inline-flex items-center gap-2 rounded-xl border border-[#35445B] px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/5 hover:text-white">
                                            Reset Filter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($tasks->hasPages())
                <div
                    class="flex flex-col gap-3 border-t border-[#29364A] px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <p class="text-xs text-slate-500">
                        Menampilkan
                        <span class="font-semibold text-slate-300">{{ $tasks->firstItem() ?? 0 }}</span>
                        –
                        <span class="font-semibold text-slate-300">{{ $tasks->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-slate-300">{{ $tasks->total() }}</span>
                        tugas
                    </p>

                    <div class="opstrack-pagination">
                        {{ $tasks->withQueryString()->links() }}
                    </div>
                </div>
            @endif

        </section>

    </div>
@endsection
