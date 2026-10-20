@extends('layouts.app')

@section('title', 'Activity Log')
@section('page-heading', 'Activity Log')

@section('content')
    @php
        $typeLabels = [
            'progress' => 'Progress',
            'blocker' => 'Kendala',
            'general' => 'Aktivitas Umum',
            'status_change' => 'Perubahan Status',
        ];

        $statusLabels = [
            'open' => 'Belum Dikerjakan',
            'in_progress' => 'Sedang Dikerjakan',
            'blocked' => 'Terkendala',
            'pending_review' => 'Menunggu Verifikasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Page Introduction --}}
        <section
            class="relative overflow-hidden rounded-2xl border border-[#29364A] bg-linear-to-br from-[#111D33] via-[#101A2D] to-[#0D1525] p-5 sm:p-7">
            <div class="pointer-events-none absolute -right-12 -top-16 h-48 w-48 rounded-full bg-blue-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-500/10 text-blue-300">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-blue-300">
                            Monitoring & Audit
                        </p>
                        <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            Activity Log
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-400">
                            Pantau riwayat aktivitas tugas, perubahan status, pembaruan progress, dan proses verifikasi
                            dalam satu halaman.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-[#29364A] bg-[#0B1120]/70 px-4 py-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-500/10 text-blue-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 5h16M4 12h16M4 19h16M8 3v4m8 3v4m-8 3v4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Aktivitas ditemukan</p>
                        <p class="mt-0.5 text-lg font-bold text-white">
                            {{ number_format($activities->total()) }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Filters --}}
        <section class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5 sm:p-6">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1B2940] text-blue-300">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16M7 12h10m-7 7h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-white">Filter Aktivitas</h2>
                    <p class="mt-1 text-xs text-slate-400">Persempit riwayat berdasarkan kata kunci, jenis, atau tanggal.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('activity-logs.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    {{-- Search --}}
                    <div class="xl:col-span-2">
                        <label for="search" class="mb-2 block text-xs font-semibold text-slate-300">
                            Pencarian
                        </label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" d="m16 16 4 4" />
                            </svg>
                            <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                                placeholder="Cari judul tugas, pengguna, atau aktivitas..."
                                class="w-full rounded-xl border border-[#334155] bg-[#0B1120] py-3 pl-10 pr-4 text-sm text-slate-200 outline-none transition placeholder:text-slate-500 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10">
                        </div>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="mb-2 block text-xs font-semibold text-slate-300">
                            Jenis Aktivitas
                        </label>
                        <select id="type" name="type"
                            class="w-full rounded-xl border border-[#334155] bg-[#0B1120] px-3 py-3 text-sm text-slate-200 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10">
                            <option value="">Semua aktivitas</option>
                            @foreach ($typeLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label for="from" class="mb-2 block text-xs font-semibold text-slate-300">
                            Dari Tanggal
                        </label>
                        <input type="date" id="from" name="from" value="{{ $filters['from'] ?? '' }}"
                            class="w-full rounded-xl border border-[#334155] bg-[#0B1120] px-3 py-3 text-sm text-slate-200 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label for="to" class="mb-2 block text-xs font-semibold text-slate-300">
                            Sampai Tanggal
                        </label>
                        <input type="date" id="to" name="to" value="{{ $filters['to'] ?? '' }}"
                            min="{{ $filters['from'] ?? '' }}"
                            class="w-full rounded-xl border border-[#334155] bg-[#0B1120] px-3 py-3 text-sm text-slate-200 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10">
                    </div>
                </div>

                <div
                    class="flex flex-col gap-3 border-t border-[#29364A] pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs leading-5 text-slate-500">
                        Hasil pencarian mengikuti batas akses akun yang sedang digunakan.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('activity-logs.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-[#334155] px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-[#1B2940] hover:text-white">
                            Reset
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#3B82F6] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#111B2E]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" d="m16 16 4 4" />
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </section>

        {{-- Activity Table --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div
                class="flex flex-col gap-3 border-b border-[#29364A] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-base font-bold text-white">Riwayat Aktivitas</h2>
                    <p class="mt-1 text-xs text-slate-400">
                        Daftar aktivitas tugas terbaru berdasarkan waktu kejadian.
                    </p>
                </div>

                <div
                    class="inline-flex w-fit items-center gap-2 rounded-lg border border-[#29364A] bg-[#0B1120] px-3 py-2 text-xs text-slate-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    {{ $activities->firstItem() ?? 0 }}–{{ $activities->lastItem() ?? 0 }}
                    dari {{ number_format($activities->total()) }} aktivitas
                </div>
            </div>

            @if ($activities->count())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-212.5 text-left">
                        <thead class="border-b border-[#29364A] bg-[#0D1627]">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                                    Aktivitas
                                </th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                                    Tugas
                                </th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                                    Pengguna
                                </th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                                    Waktu
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">
                                    Detail
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#29364A]">
                            @foreach ($activities as $activity)
                                @php
                                    $type = $activity->activity_type;

                                    $badgeClasses = match ($type) {
                                        'progress' => 'border-blue-400/20 bg-blue-500/10 text-blue-300',
                                        'blocker' => 'border-rose-400/20 bg-rose-500/10 text-rose-300',
                                        'status_change' => 'border-violet-400/20 bg-violet-500/10 text-violet-300',
                                        default => 'border-slate-500/20 bg-slate-500/10 text-slate-300',
                                    };

                                    $iconClasses = match ($type) {
                                        'progress' => 'bg-blue-500/10 text-blue-300',
                                        'blocker' => 'bg-rose-500/10 text-rose-300',
                                        'status_change' => 'bg-violet-500/10 text-violet-300',
                                        default => 'bg-slate-500/10 text-slate-300',
                                    };

                                    $occurredAt = \Illuminate\Support\Carbon::parse($activity->occurred_at);
                                @endphp

                                <tr class="group transition hover:bg-[#172338]/70">
                                    {{-- Activity --}}
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $iconClasses }}">
                                                @if ($type === 'progress')
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.7">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 12h14m-6-6 6 6-6 6" />
                                                    </svg>
                                                @elseif ($type === 'blocker')
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.7">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                                                    </svg>
                                                @elseif ($type === 'status_change')
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.7">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7 7h13l-3-3m3 3-3 3M17 17H4l3 3m-3-3 3-3" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.7">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                                    </svg>
                                                @endif
                                            </div>

                                            <div class="min-w-0 max-w-md">
                                                <span
                                                    class="inline-flex rounded-md border px-2 py-1 text-[10px] font-semibold {{ $badgeClasses }}">
                                                    {{ $typeLabels[$type] ?? 'Aktivitas' }}
                                                </span>

                                                <p
                                                    class="mt-2 wrap-break-word text-sm font-medium leading-6 text-slate-200">
                                                    {{ $activity->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Task --}}
                                    <td class="px-6 py-5 align-top">
                                        <a href="{{ route('tasks.show', $activity->task_id) }}"
                                            class="inline-flex max-w-xs items-start gap-2 text-sm font-semibold leading-6 text-blue-300 transition hover:text-blue-200">
                                            <svg class="mt-1 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.7">
                                                <rect x="4" y="3" width="16" height="18" rx="2" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 8h8M8 12h8M8 16h5" />
                                            </svg>
                                            <span class="wrap-break-word">{{ $activity->task_title }}</span>
                                        </a>
                                    </td>

                                    {{-- User --}}
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-blue-400/15 bg-blue-500/10 text-xs font-bold text-blue-300">
                                                {{ strtoupper(substr($activity->user_name, 0, 1)) }}
                                            </div>
                                            <span class="max-w-37.5 wrap-break-word text-sm font-medium text-slate-300">
                                                {{ $activity->user_name }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Time --}}
                                    <td class="px-6 py-5 align-top">
                                        <p class="whitespace-nowrap text-sm font-medium text-slate-300">
                                            {{ $occurredAt->format('d M Y') }}
                                        </p>
                                        <p class="mt-1 whitespace-nowrap text-xs text-slate-500">
                                            {{ $occurredAt->format('H:i') }} WIB
                                        </p>
                                        <p class="mt-1 whitespace-nowrap text-[11px] text-slate-500">
                                            {{ $occurredAt->diffForHumans() }}
                                        </p>
                                    </td>

                                    {{-- Detail --}}
                                    <td class="px-6 py-5 text-right align-top">
                                        <a href="{{ route('tasks.show', $activity->task_id) }}"
                                            aria-label="Lihat detail tugas {{ $activity->task_title }}"
                                            title="Lihat detail tugas"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#334155] text-slate-400 transition hover:border-blue-400/40 hover:bg-blue-500/10 hover:text-blue-300">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($activities->hasPages())
                    <div class="border-t border-[#29364A] px-5 py-5 sm:px-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-slate-500">
                                Halaman
                                <span class="font-semibold text-slate-300">{{ $activities->currentPage() }}</span>
                                dari
                                <span class="font-semibold text-slate-300">{{ $activities->lastPage() }}</span>
                            </p>

                            <div class="flex flex-wrap items-center gap-2">
                                @if ($activities->onFirstPage())
                                    <span
                                        class="cursor-not-allowed rounded-lg border border-[#29364A] px-3 py-2 text-xs font-semibold text-slate-600">
                                        &larr; Sebelumnya
                                    </span>
                                @else
                                    <a href="{{ $activities->previousPageUrl() }}"
                                        class="rounded-lg border border-[#334155] px-3 py-2 text-xs font-semibold text-slate-300 transition hover:border-blue-400/40 hover:bg-[#1B2940] hover:text-white">
                                        &larr; Sebelumnya
                                    </a>
                                @endif

                                @foreach ($activities->getUrlRange(max(1, $activities->currentPage() - 2), min($activities->lastPage(), $activities->currentPage() + 2)) as $page => $url)
                                    @if ($page === $activities->currentPage())
                                        <span aria-current="page"
                                            class="flex h-9 min-w-9 items-center justify-center rounded-lg border border-blue-400/30 bg-blue-500/15 px-3 text-xs font-bold text-blue-300">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="flex h-9 min-w-9 items-center justify-center rounded-lg border border-[#334155] px-3 text-xs font-semibold text-slate-400 transition hover:border-blue-400/40 hover:bg-[#1B2940] hover:text-white">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                @if ($activities->hasMorePages())
                                    <a href="{{ $activities->nextPageUrl() }}"
                                        class="rounded-lg border border-[#334155] px-3 py-2 text-xs font-semibold text-slate-300 transition hover:border-blue-400/40 hover:bg-[#1B2940] hover:text-white">
                                        Selanjutnya &rarr;
                                    </a>
                                @else
                                    <span
                                        class="cursor-not-allowed rounded-lg border border-[#29364A] px-3 py-2 text-xs font-semibold text-slate-600">
                                        Selanjutnya &rarr;
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="px-6 py-16 text-center sm:py-20">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-[#29364A] bg-[#0B1120] text-slate-500">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" d="m16 16 4 4" />
                            <path stroke-linecap="round" d="M8 11h6" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-base font-bold text-white">
                        Tidak ada aktivitas ditemukan
                    </h3>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-400">
                        Belum ada aktivitas yang sesuai dengan filter. Coba ubah kata kunci atau rentang tanggal yang
                        dipilih.
                    </p>

                    @if (count($filters))
                        <a href="{{ route('activity-logs.index') }}"
                            class="mt-5 inline-flex items-center justify-center rounded-xl border border-[#334155] px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:border-blue-400/40 hover:bg-[#1B2940] hover:text-white">
                            Hapus Filter
                        </a>
                    @endif
                </div>
            @endif
        </section>

        {{-- Information Note --}}
        <div class="flex items-start gap-3 rounded-xl border border-blue-400/15 bg-blue-500/5 px-4 py-4">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.7">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" d="M12 11v5m0-8h.01" />
            </svg>
            <p class="text-xs leading-6 text-slate-400">
                Activity Log menggabungkan catatan aktivitas dan riwayat perubahan status tugas. Data yang ditampilkan
                mengikuti hak akses akun yang sedang masuk.
            </p>
        </div>
    </div>
@endsection
