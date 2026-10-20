@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium text-blue-400">OVERVIEW</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-100 sm:text-3xl">
                    Dashboard
                </h1>
                <p class="mt-2 text-sm text-slate-400">
                    Pantau progres, tenggat, dan performa operasional dalam satu tempat.
                </p>
            </div>

            <div
                class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-700 bg-slate-900/70 px-4 py-2.5 text-sm text-slate-300">
                <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                </svg>
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        {{-- KPI utama --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border border-slate-700/80 bg-[#111B2E] p-5 shadow-lg shadow-black/10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Total Tugas</p>
                        <h2 class="mt-3 text-3xl font-bold text-slate-100">
                            {{ number_format($totalTasks) }}
                        </h2>
                    </div>
                    <div class="rounded-xl bg-blue-500/10 p-3 text-blue-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500">Seluruh tugas dalam cakupan akses</p>
            </div>

            <div class="rounded-2xl border border-slate-700/80 bg-[#111B2E] p-5 shadow-lg shadow-black/10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Sedang Dikerjakan</p>
                        <h2 class="mt-3 text-3xl font-bold text-blue-400">
                            {{ number_format($inProgressTasks) }}
                        </h2>
                    </div>
                    <div class="rounded-xl bg-blue-500/10 p-3 text-blue-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500">Tugas yang sedang berlangsung</p>
            </div>

            <div class="rounded-2xl border border-slate-700/80 bg-[#111B2E] p-5 shadow-lg shadow-black/10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Menunggu Verifikasi</p>
                        <h2 class="mt-3 text-3xl font-bold text-violet-400">
                            {{ number_format($pendingReviewTasks) }}
                        </h2>
                    </div>
                    <div class="rounded-xl bg-violet-500/10 p-3 text-violet-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="m9 12 2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500">Menunggu pemeriksaan hasil</p>
            </div>

            <div class="rounded-2xl border border-slate-700/80 bg-[#111B2E] p-5 shadow-lg shadow-black/10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Tugas Selesai</p>
                        <h2 class="mt-3 text-3xl font-bold text-emerald-400">
                            {{ number_format($completedTasks) }}
                        </h2>
                    </div>
                    <div class="rounded-xl bg-emerald-500/10 p-3 text-emerald-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 4 4L19 6" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500">Tugas dengan status selesai saat ini</p>
            </div>

        </div>

        {{-- Indikator perhatian --}}
        <div>
            <div class="mb-3">
                <h2 class="text-lg font-semibold text-slate-100">Perlu Perhatian</h2>
                <p class="mt-1 text-sm text-slate-400">Ringkasan tugas yang perlu dipantau atau ditindaklanjuti.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="flex items-center gap-4 rounded-2xl border border-rose-500/20 bg-rose-500/6 p-5">
                    <div class="rounded-xl bg-rose-500/10 p-3 text-rose-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Tugas Terlambat</p>
                        <p class="mt-1 text-2xl font-bold text-rose-400">{{ number_format($overdueTasks) }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 rounded-2xl border border-amber-500/20 bg-amber-500/6 p-5">
                    <div class="rounded-xl bg-amber-500/10 p-3 text-amber-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 9v4m0 4h.01M4.9 19h14.2a2 2 0 0 0 1.7-3L13.7 4a2 2 0 0 0-3.4 0L3.2 16a2 2 0 0 0 1.7 3Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Tugas Terhambat</p>
                        <p class="mt-1 text-2xl font-bold text-amber-400">{{ number_format($blockedTasks) }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 rounded-2xl border border-sky-500/20 bg-sky-500/6 p-5">
                    <div class="rounded-xl bg-sky-500/10 p-3 text-sky-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Tenggat 3 Hari</p>
                        <p class="mt-1 text-2xl font-bold text-sky-400">{{ number_format($upcomingTasks) }}</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Grafik --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">

            <div class="rounded-2xl border border-slate-700/80 bg-[#111B2E] p-5 xl:col-span-3">
                <div class="mb-5">
                    <h2 class="font-semibold text-slate-100">Tren Penyelesaian Tugas</h2>
                    <p class="mt-1 text-sm text-slate-400">Jumlah perubahan status menjadi selesai selama 7 hari terakhir.
                    </p>
                </div>
                <div class="relative h-72">
                    <canvas id="completionTrendChart"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-700/80 bg-[#111B2E] p-5 xl:col-span-2">
                <div class="mb-5">
                    <h2 class="font-semibold text-slate-100">Distribusi Status</h2>
                    <p class="mt-1 text-sm text-slate-400">Komposisi status seluruh tugas dalam cakupan akses.</p>
                </div>
                <div class="relative mx-auto h-64 max-w-sm">
                    <canvas id="statusDistributionChart"></canvas>
                </div>
                <div id="statusLegend" class="mt-5 grid grid-cols-2 gap-x-3 gap-y-2"></div>
            </div>

        </div>

        {{-- Kinerja staff --}}
        @if (in_array(auth()->user()->role, ['admin', 'manager'], true))
            <div class="rounded-2xl border border-slate-700/80 bg-[#111B2E] p-5">
                <div class="mb-5">
                    <h2 class="font-semibold text-slate-100">Kinerja Penyelesaian Staff</h2>
                    <p class="mt-1 text-sm text-slate-400">
                        Jumlah transisi tugas menjadi selesai dalam 7 hari terakhir, dikelompokkan menurut penanggung jawab
                        saat ini.
                    </p>
                </div>

                @if (count($staffPerformanceLabels) > 0)
                    <div class="relative h-80">
                        <canvas id="staffPerformanceChart"></canvas>
                    </div>
                @else
                    <div
                        class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-700 px-4 py-10 text-center">
                        <svg class="h-10 w-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 18 18 20l4-4M12 14a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM4 20a8 8 0 0 1 12-6.9" />
                        </svg>
                        <p class="mt-3 text-sm font-medium text-slate-300">Belum ada data kinerja staff</p>
                        <p class="mt-1 text-sm text-slate-500">Data akan tampil setelah tersedia staff aktif.</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Tugas yang perlu dipantau --}}
        <div class="overflow-hidden rounded-2xl border border-slate-700/80 bg-[#111B2E]">
            <div class="flex flex-col justify-between gap-3 border-b border-slate-700/80 p-5 sm:flex-row sm:items-center">
                <div>
                    <h2 class="font-semibold text-slate-100">Tugas yang Perlu Dipantau</h2>
                    <p class="mt-1 text-sm text-slate-400">Tugas terlambat, terhambat, atau mendekati tenggat.</p>
                </div>
                <span class="w-fit rounded-lg border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-xs text-slate-300">
                    {{ $attentionTasks->count() }} tugas ditampilkan
                </span>
            </div>

            @if ($attentionTasks->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700/70">
                        <thead class="bg-slate-900/40">
                            <tr>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Tugas</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Departemen</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Penanggung Jawab</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Tenggat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @foreach ($attentionTasks as $task)
                                @php
                                    $statusLabels = [
                                        'open' => 'Belum Dimulai',
                                        'in_progress' => 'Sedang Dikerjakan',
                                        'blocked' => 'Terhambat',
                                        'pending_review' => 'Menunggu Verifikasi',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];

                                    $statusClasses = [
                                        'open' => 'bg-slate-500/10 text-slate-300 ring-slate-500/20',
                                        'in_progress' => 'bg-blue-500/10 text-blue-300 ring-blue-500/20',
                                        'blocked' => 'bg-amber-500/10 text-amber-300 ring-amber-500/20',
                                        'pending_review' => 'bg-violet-500/10 text-violet-300 ring-violet-500/20',
                                        'completed' => 'bg-emerald-500/10 text-emerald-300 ring-emerald-500/20',
                                        'cancelled' => 'bg-rose-500/10 text-rose-300 ring-rose-500/20',
                                    ];

                                    $isOverdue =
                                        $task->due_date &&
                                        $task->due_date->lt(today()) &&
                                        !in_array($task->status, ['completed', 'cancelled'], true);
                                @endphp

                                <tr class="transition hover:bg-slate-800/40">
                                    <td class="px-5 py-4">
                                        <p class="max-w-xs truncate text-sm font-medium text-slate-200">
                                            {{ $task->title }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $task->category?->name ?? 'Tanpa kategori' }}
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-300">
                                        {{ $task->department?->name ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-300">
                                        {{ $task->assignee?->name ?? 'Belum ditugaskan' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $statusClasses[$task->status] ?? 'bg-slate-500/10 text-slate-300 ring-slate-500/20' }}">
                                            {{ $statusLabels[$task->status] ?? ucfirst($task->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($task->due_date)
                                            <p
                                                class="text-sm {{ $isOverdue ? 'font-semibold text-rose-400' : 'text-slate-300' }}">
                                                {{ $task->due_date->translatedFormat('d M Y') }}
                                            </p>
                                            @if ($isOverdue)
                                                <p class="mt-1 text-xs text-rose-400">Terlambat</p>
                                            @elseif ($task->due_date->isToday())
                                                <p class="mt-1 text-xs text-amber-400">Tenggat hari ini</p>
                                            @endif
                                        @else
                                            <span class="text-sm text-slate-500">Tidak ditentukan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-5 py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-emerald-500/70" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m5 12 4 4L19 6" />
                    </svg>
                    <p class="mt-3 text-sm font-medium text-slate-200">Tidak ada tugas yang perlu dipantau</p>
                    <p class="mt-1 text-sm text-slate-500">Belum ada tugas terlambat, terhambat, atau mendekati tenggat.
                    </p>
                </div>
            @endif
        </div>

        {{-- Tugas terbaru --}}
        <div class="overflow-hidden rounded-2xl border border-slate-700/80 bg-[#111B2E]">
            <div class="border-b border-slate-700/80 p-5">
                <h2 class="font-semibold text-slate-100">Tugas Terbaru</h2>
                <p class="mt-1 text-sm text-slate-400">Tugas yang baru ditambahkan atau diperbarui.</p>
            </div>

            @if ($recentTasks->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700/70">
                        <thead class="bg-slate-900/40">
                            <tr>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Nama Tugas</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Penanggung Jawab</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Prioritas</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Tenggat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @foreach ($recentTasks as $task)
                                @php
                                    $statusLabels = [
                                        'open' => 'Belum Dimulai',
                                        'in_progress' => 'Sedang Dikerjakan',
                                        'blocked' => 'Terhambat',
                                        'pending_review' => 'Menunggu Verifikasi',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];

                                    $statusClasses = [
                                        'open' => 'bg-slate-500/10 text-slate-300 ring-slate-500/20',
                                        'in_progress' => 'bg-blue-500/10 text-blue-300 ring-blue-500/20',
                                        'blocked' => 'bg-amber-500/10 text-amber-300 ring-amber-500/20',
                                        'pending_review' => 'bg-violet-500/10 text-violet-300 ring-violet-500/20',
                                        'completed' => 'bg-emerald-500/10 text-emerald-300 ring-emerald-500/20',
                                        'cancelled' => 'bg-rose-500/10 text-rose-300 ring-rose-500/20',
                                    ];

                                    $priorityLabels = [
                                        'low' => 'Rendah',
                                        'medium' => 'Sedang',
                                        'high' => 'Tinggi',
                                        'urgent' => 'Mendesak',
                                    ];

                                    $priorityClasses = [
                                        'low' => 'text-slate-400',
                                        'medium' => 'text-sky-300',
                                        'high' => 'text-amber-300',
                                        'urgent' => 'text-rose-400',
                                    ];
                                @endphp

                                <tr class="transition hover:bg-slate-800/40">
                                    <td class="px-5 py-4">
                                        <p class="max-w-xs truncate text-sm font-medium text-slate-200">
                                            {{ $task->title }}</p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $task->department?->name ?? 'Tanpa departemen' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-300">
                                        {{ $task->assignee?->name ?? 'Belum ditugaskan' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span
                                            class="text-sm font-medium {{ $priorityClasses[$task->priority] ?? 'text-slate-300' }}">
                                            {{ $priorityLabels[$task->priority] ?? ucfirst($task->priority ?? '—') }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $statusClasses[$task->status] ?? 'bg-slate-500/10 text-slate-300 ring-slate-500/20' }}">
                                            {{ $statusLabels[$task->status] ?? ucfirst($task->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-300">
                                        {{ $task->due_date?->translatedFormat('d M Y') ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-5 py-10 text-center text-sm text-slate-500">
                    Belum ada tugas untuk ditampilkan.
                </div>
            @endif
        </div>

        {{-- Aktivitas terbaru --}}
        <div class="rounded-2xl border border-slate-700/80 bg-[#111B2E] p-5">
            <div class="mb-5">
                <h2 class="font-semibold text-slate-100">Aktivitas Terbaru</h2>
                <p class="mt-1 text-sm text-slate-400">Pembaruan terbaru terkait tugas dalam cakupan akses.</p>
            </div>

            @if ($recentActivities->isNotEmpty())
                <div class="space-y-5">
                    @foreach ($recentActivities as $activity)
                        <div class="flex gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10 text-sm font-semibold text-blue-300">
                                {{ strtoupper(substr($activity->user?->name ?? '?', 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col justify-between gap-1 sm:flex-row sm:items-start">
                                    <p class="text-sm font-medium text-slate-200">
                                        {{ $activity->user?->name ?? 'Pengguna tidak tersedia' }}
                                    </p>
                                    <span class="text-xs text-slate-500">
                                        {{ $activity->created_at?->diffForHumans() ?? 'Waktu tidak tersedia' }}
                                    </span>
                                </div>

                                <p class="mt-1 text-sm leading-6 text-slate-400">
                                    {{ $activity->description }}
                                </p>

                                @if ($activity->task)
                                    <p
                                        class="mt-2 inline-flex max-w-full items-center gap-1.5 rounded-lg border border-slate-700 bg-slate-900/50 px-2.5 py-1.5 text-xs text-blue-300">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                        </svg>
                                        <span class="truncate">{{ $activity->task->title }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center">
                    <p class="text-sm text-slate-400">Belum ada aktivitas terbaru.</p>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartTextColor = '#94A3B8';
            const chartGridColor = 'rgba(148, 163, 184, 0.12)';

            Chart.defaults.color = chartTextColor;
            Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, sans-serif';

            const completionLabels = @json($completionTrendLabels);
            const completionData = @json($completionTrendData);

            const trendCanvas = document.getElementById('completionTrendChart');

            if (trendCanvas && typeof Chart !== 'undefined') {
                new Chart(trendCanvas, {
                    type: 'line',
                    data: {
                        labels: completionLabels,
                        datasets: [{
                            label: 'Tugas Selesai',
                            data: completionData,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.14)',
                            pointBackgroundColor: '#7DD3FC',
                            pointBorderColor: '#111B2E',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#080E1A',
                                borderColor: '#29364A',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 0
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    stepSize: 1
                                },
                                grid: {
                                    color: chartGridColor
                                },
                                border: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            const statusLabels = @json($statusDistributionLabels);
            const statusData = @json($statusDistributionData);
            const statusColors = @json($statusDistributionColors);

            const statusCanvas = document.getElementById('statusDistributionChart');

            if (statusCanvas && typeof Chart !== 'undefined') {
                new Chart(statusCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusData,
                            backgroundColor: statusColors,
                            borderColor: '#111B2E',
                            borderWidth: 4,
                            hoverOffset: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#080E1A',
                                borderColor: '#29364A',
                                borderWidth: 1,
                                padding: 12
                            }
                        }
                    }
                });

                const legendContainer = document.getElementById('statusLegend');

                if (legendContainer) {
                    statusLabels.forEach((label, index) => {
                        const item = document.createElement('div');
                        item.className = 'flex min-w-0 items-center gap-2 text-xs text-slate-400';

                        const dot = document.createElement('span');
                        dot.className = 'h-2.5 w-2.5 shrink-0 rounded-full';
                        dot.style.backgroundColor = statusColors[index];

                        const labelElement = document.createElement('span');
                        labelElement.className = 'truncate';
                        labelElement.textContent = `${label} (${statusData[index]})`;

                        item.appendChild(dot);
                        item.appendChild(labelElement);
                        legendContainer.appendChild(item);
                    });
                }
            }

            const staffLabels = @json($staffPerformanceLabels);
            const staffData = @json($staffPerformanceData);
            const staffCanvas = document.getElementById('staffPerformanceChart');

            if (staffCanvas && typeof Chart !== 'undefined') {
                new Chart(staffCanvas, {
                    type: 'bar',
                    data: {
                        labels: staffLabels,
                        datasets: [{
                            label: 'Tugas Selesai',
                            data: staffData,
                            backgroundColor: 'rgba(59, 130, 246, 0.75)',
                            hoverBackgroundColor: '#60A5FA',
                            borderRadius: 6,
                            maxBarThickness: 38
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#080E1A',
                                borderColor: '#29364A',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    stepSize: 1
                                },
                                grid: {
                                    color: chartGridColor
                                },
                                border: {
                                    display: false
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
