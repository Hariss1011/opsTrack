@extends('layouts.app')

@section('title', 'Laporan Operasional')

@section('content')
    @php
        $statusLabels = [
            'open' => 'Terbuka',
            'in_progress' => 'Dalam Proses',
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
            'open' => 'border-slate-500/30 bg-slate-500/10 text-slate-300',
            'in_progress' => 'border-blue-400/30 bg-blue-400/10 text-blue-300',
            'blocked' => 'border-rose-400/30 bg-rose-400/10 text-rose-300',
            'pending_review' => 'border-amber-400/30 bg-amber-400/10 text-amber-300',
            'completed' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300',
            'cancelled' => 'border-slate-500/30 bg-slate-500/10 text-slate-400',
        ];

        $priorityColors = [
            'low' => 'border-slate-500/30 bg-slate-500/10 text-slate-300',
            'medium' => 'border-sky-400/30 bg-sky-400/10 text-sky-300',
            'high' => 'border-amber-400/30 bg-amber-400/10 text-amber-300',
            'urgent' => 'border-rose-400/30 bg-rose-400/10 text-rose-300',
        ];

        $inputClass =
            'mt-2 block w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-3.5 py-2.5 text-sm text-[#E2E8F0] outline-none transition placeholder:text-slate-600 focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20';
        $labelClass = 'text-xs font-semibold uppercase tracking-wider text-[#94A3B8]';
        $thClass =
            'whitespace-nowrap px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500';
        $tdClass = 'px-5 py-4 text-sm text-slate-300';
    @endphp

    <div class="space-y-6">

        {{-- Page Heading --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                    Operational Analytics
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-[#E2E8F0] sm:text-3xl">
                    Laporan Operasional
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-[#94A3B8]">
                    Pantau penyelesaian tugas, evaluasi kinerja tim, dan analisis aktivitas operasional berdasarkan periode
                    yang dipilih.
                </p>
            </div>

            <a href="{{ route('reports.export', request()->query()) }}"
                class="inline-flex items-center justify-center gap-2 self-start rounded-xl bg-[#3B82F6] px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#0B1120] sm:self-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3" />
                </svg>
                Ekspor Laporan
            </a>
        </div>

        {{-- Filters --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div class="flex items-center gap-3 border-b border-[#29364A] px-5 py-4 sm:px-6">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-500/10 text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16M7 12h10m-7 7h4" />
                    </svg>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-[#E2E8F0]">Filter Laporan</h2>
                    <p class="mt-0.5 text-xs text-[#94A3B8]">Sesuaikan parameter untuk melihat data yang dibutuhkan.</p>
                </div>
            </div>

            <form action="{{ route('reports.index') }}" method="GET" class="p-5 sm:p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                    <div>
                        <label for="date_from" class="{{ $labelClass }}">Tanggal Mulai</label>
                        <input id="date_from" type="date" name="date_from"
                            value="{{ $filters['date_from'] ?? request('date_from') }}" class="{{ $inputClass }}"
                            style="color-scheme: dark">
                    </div>

                    <div>
                        <label for="date_to" class="{{ $labelClass }}">Tanggal Selesai</label>
                        <input id="date_to" type="date" name="date_to"
                            value="{{ $filters['date_to'] ?? request('date_to') }}" class="{{ $inputClass }}"
                            style="color-scheme: dark">
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <div>
                            <label for="department_id" class="{{ $labelClass }}">Departemen</label>
                            <select id="department_id" name="department_id" class="{{ $inputClass }}">
                                <option value="">Semua Departemen</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected((string) ($filters['department_id'] ?? request('department_id')) === (string) $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label for="category_id" class="{{ $labelClass }}">Kategori Tugas</label>
                        <select id="category_id" name="category_id" class="{{ $inputClass }}">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? request('category_id')) === (string) $category->id)>
                                    {{ $category->name }}{{ !$category->is_active ? ' (Nonaktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="{{ $labelClass }}">Status Tugas</label>
                        <select id="status" name="status" class="{{ $inputClass }}">
                            <option value="">Semua Status</option>
                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? request('status')) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="{{ $labelClass }}">Prioritas</label>
                        <select id="priority" name="priority" class="{{ $inputClass }}">
                            <option value="">Semua Prioritas</option>
                            @foreach ($priorityLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['priority'] ?? request('priority')) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div
                    class="mt-6 flex flex-col gap-3 border-t border-[#29364A] pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs leading-5 text-slate-500">
                        Kosongkan filter yang tidak ingin digunakan untuk memperluas hasil laporan.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('reports.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-[#35445B] bg-[#151F32] px-4 py-2.5 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:bg-[#1B2940] hover:text-white">
                            Reset
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#3B82F6] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#111B2E]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <circle cx="10.8" cy="10.8" r="6.8" />
                                <path stroke-linecap="round" d="m16 16 4 4" />
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </section>

        {{-- KPI Cards --}}
        <section>
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-[#E2E8F0]">Ringkasan Kinerja</h2>
                    <p class="mt-1 text-xs text-[#94A3B8]">Gambaran umum tugas berdasarkan filter aktif.</p>
                </div>
                <span
                    class="hidden rounded-lg border border-[#29364A] bg-[#111B2E] px-3 py-1.5 text-xs text-slate-400 sm:inline-flex">
                    Data Laporan
                </span>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                {{-- Total Tasks --}}
                <div class="relative overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-full bg-blue-500/5 blur-2xl"></div>
                    <div class="relative flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#94A3B8]">Total Tugas</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-[#E2E8F0]">
                                {{ number_format($totalTasks) }}</p>
                            <p class="mt-2 text-xs text-slate-500">Seluruh tugas yang sesuai filter</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-400/10 text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-5 h-1 overflow-hidden rounded-full bg-[#29364A]">
                        <div class="h-full w-full rounded-full bg-blue-500"></div>
                    </div>
                </div>

                {{-- Completed --}}
                <div class="relative overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-full bg-emerald-500/5 blur-2xl"></div>
                    <div class="relative flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#94A3B8]">Tugas Selesai</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-emerald-300">
                                {{ number_format($completedTasks) }}</p>
                            <p class="mt-2 text-xs text-slate-500">Tugas yang telah diselesaikan</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-emerald-400/20 bg-emerald-400/10 text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-5 h-1 overflow-hidden rounded-full bg-[#29364A]">
                        <div class="h-full rounded-full bg-emerald-400"
                            style="width: {{ min(100, max(0, $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0)) }}%">
                        </div>
                    </div>
                </div>

                {{-- In Progress --}}
                <div class="relative overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-full bg-sky-500/5 blur-2xl"></div>
                    <div class="relative flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#94A3B8]">Dalam Proses</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-sky-300">
                                {{ number_format($inProgressTasks) }}</p>
                            <p class="mt-2 text-xs text-slate-500">Tugas yang sedang dikerjakan</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-sky-400/20 bg-sky-400/10 text-sky-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-5 h-1 overflow-hidden rounded-full bg-[#29364A]">
                        <div class="h-full rounded-full bg-sky-400"
                            style="width: {{ min(100, max(0, $totalTasks > 0 ? ($inProgressTasks / $totalTasks) * 100 : 0)) }}%">
                        </div>
                    </div>
                </div>

                {{-- Pending Review --}}
                <div class="relative overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-full bg-amber-500/5 blur-2xl"></div>
                    <div class="relative flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#94A3B8]">Menunggu Verifikasi</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-amber-300">
                                {{ number_format($pendingReviewTasks) }}</p>
                            <p class="mt-2 text-xs text-slate-500">Tugas yang perlu ditinjau</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-amber-400/20 bg-amber-400/10 text-amber-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4m0 4h.01M10.3 3.8 2.9 17a2 2 0 0 0 1.8 3h14.6a2 2 0 0 0 1.8-3L13.7 3.8a2 2 0 0 0-3.4 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-5 h-1 overflow-hidden rounded-full bg-[#29364A]">
                        <div class="h-full rounded-full bg-amber-400"
                            style="width: {{ min(100, max(0, $totalTasks > 0 ? ($pendingReviewTasks / $totalTasks) * 100 : 0)) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Completion Rate and Overdue --}}
        <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5 sm:p-6 lg:col-span-2">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <p class="text-sm font-medium text-[#94A3B8]">Tingkat Penyelesaian</p>
                        <p class="mt-2 text-4xl font-bold tracking-tight text-[#E2E8F0]">
                            {{ number_format($completionRate, 1) }}<span class="ml-1 text-2xl text-blue-400">%</span>
                        </p>
                        <p class="mt-2 text-sm text-slate-500">Persentase tugas yang telah selesai dari total tugas.</p>
                    </div>
                    <div
                        class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-blue-400/20 bg-blue-400/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M7 15l4-4 3 3 6-7" />
                        </svg>
                    </div>
                </div>

                <div class="mt-6 h-3 overflow-hidden rounded-full bg-[#29364A]">
                    <div class="h-full rounded-full bg-linear-to-r from-blue-600 to-sky-400 transition-all duration-500"
                        style="width: {{ min(100, max(0, $completionRate)) }}%"></div>
                </div>

                <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                    <span>0%</span>
                    <span>Target penyelesaian: 100%</span>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-rose-400/20 bg-[#111B2E] p-5 sm:p-6">
                <div class="absolute -right-5 -top-5 h-32 w-32 rounded-full bg-rose-500/5 blur-2xl"></div>
                <div class="relative flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-[#94A3B8]">Tugas Terlambat</p>
                        <p class="mt-3 text-4xl font-bold tracking-tight text-rose-300">{{ number_format($overdueTasks) }}
                        </p>
                        <p class="mt-2 text-sm leading-5 text-slate-500">Tugas yang telah melewati tenggat waktu.</p>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-rose-400/20 bg-rose-400/10 text-rose-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                        </svg>
                    </div>
                </div>
                <div class="relative mt-5 border-t border-[#29364A] pt-4">
                    <p class="text-xs leading-5 text-slate-400">
                        Periksa tugas yang terlambat untuk menentukan tindak lanjut operasional.
                    </p>
                </div>
            </div>
        </section>

        {{-- Department Performance --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
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
                        <h2 class="text-base font-semibold text-[#E2E8F0]">Performa Departemen</h2>
                        <p class="mt-1 text-xs text-[#94A3B8]">Perbandingan penyelesaian tugas di setiap departemen.</p>
                    </div>
                </div>
                <span
                    class="self-start rounded-lg border border-[#29364A] bg-[#0B1120] px-3 py-1.5 text-xs text-slate-400 sm:self-center">
                    {{ $departmentPerformance->count() }} Departemen
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#29364A]">
                    <thead class="bg-[#0B1120]/70">
                        <tr>
                            <th class="{{ $thClass }}">Departemen</th>
                            <th class="{{ $thClass }} text-right">Total Tugas</th>
                            <th class="{{ $thClass }} text-right">Tugas Selesai</th>
                            <th class="{{ $thClass }}">Tingkat Penyelesaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#29364A]">
                        @forelse ($departmentPerformance as $department)
                            @php
                                $departmentRate =
                                    $department->total_tasks > 0
                                        ? ($department->completed_tasks / $department->total_tasks) * 100
                                        : 0;
                            @endphp
                            <tr class="transition hover:bg-[#151F32]">
                                <td class="{{ $tdClass }}">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-blue-400/15 bg-blue-400/10 text-xs font-bold text-blue-300">
                                            {{ strtoupper(substr($department->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-[#E2E8F0]">{{ $department->name }}</span>
                                    </div>
                                </td>
                                <td class="{{ $tdClass }} text-right tabular-nums">
                                    {{ number_format($department->total_tasks) }}
                                </td>
                                <td class="{{ $tdClass }} text-right tabular-nums">
                                    <span
                                        class="font-semibold text-emerald-300">{{ number_format($department->completed_tasks) }}</span>
                                </td>
                                <td class="{{ $tdClass }} min-w-52">
                                    <div class="flex items-center gap-3">
                                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-[#29364A]">
                                            <div class="h-full rounded-full bg-blue-500"
                                                style="width: {{ min(100, max(0, $departmentRate)) }}%"></div>
                                        </div>
                                        <span class="w-12 text-right text-xs font-semibold tabular-nums text-slate-300">
                                            {{ number_format($departmentRate, 1) }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-[#29364A] bg-[#0B1120] text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 21h18M5 21V7l8-4v18" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 text-sm font-medium text-slate-300">Belum ada data departemen</p>
                                    <p class="mt-1 text-xs text-slate-500">Coba sesuaikan filter laporan yang digunakan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Staff Performance --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div class="flex items-center gap-3 border-b border-[#29364A] px-5 py-5 sm:px-6">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-sky-400/20 bg-sky-400/10 text-sky-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-[#E2E8F0]">Performa Staf</h2>
                    <p class="mt-1 text-xs text-[#94A3B8]">Ringkasan tugas yang ditangani dan diselesaikan oleh staf.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#29364A]">
                    <thead class="bg-[#0B1120]/70">
                        <tr>
                            <th class="{{ $thClass }}">Staf</th>
                            <th class="{{ $thClass }} text-right">Total Tugas</th>
                            <th class="{{ $thClass }} text-right">Tugas Selesai</th>
                            <th class="{{ $thClass }}">Tingkat Penyelesaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#29364A]">
                        @forelse ($staffPerformance as $staff)
                            @php
                                $staffRate =
                                    $staff->total_tasks > 0 ? ($staff->completed_tasks / $staff->total_tasks) * 100 : 0;
                            @endphp
                            <tr class="transition hover:bg-[#151F32]">
                                <td class="{{ $tdClass }}">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-sky-400/20 bg-sky-400/10 text-xs font-bold text-sky-300">
                                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-[#E2E8F0]">{{ $staff->name }}</span>
                                    </div>
                                </td>
                                <td class="{{ $tdClass }} text-right tabular-nums">
                                    {{ number_format($staff->total_tasks) }}
                                </td>
                                <td class="{{ $tdClass }} text-right tabular-nums">
                                    <span
                                        class="font-semibold text-emerald-300">{{ number_format($staff->completed_tasks) }}</span>
                                </td>
                                <td class="{{ $tdClass }} min-w-52">
                                    <div class="flex items-center gap-3">
                                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-[#29364A]">
                                            <div class="h-full rounded-full bg-sky-400"
                                                style="width: {{ min(100, max(0, $staffRate)) }}%"></div>
                                        </div>
                                        <span class="w-12 text-right text-xs font-semibold tabular-nums text-slate-300">
                                            {{ number_format($staffRate, 1) }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-[#29364A] bg-[#0B1120] text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 text-sm font-medium text-slate-300">Belum ada data staf</p>
                                    <p class="mt-1 text-xs text-slate-500">Data performa akan muncul jika tersedia tugas
                                        yang sesuai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Task Details --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div
                class="flex flex-col gap-3 border-b border-[#29364A] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-400/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-[#E2E8F0]">Detail Tugas</h2>
                        <p class="mt-1 text-xs text-[#94A3B8]">Daftar tugas berdasarkan parameter laporan yang aktif.</p>
                    </div>
                </div>

                <span
                    class="self-start rounded-lg border border-[#29364A] bg-[#0B1120] px-3 py-1.5 text-xs text-slate-400 sm:self-center">
                    {{ number_format($tasks->total()) }} data
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#29364A]">
                    <thead class="bg-[#0B1120]/70">
                        <tr>
                            <th class="{{ $thClass }}">Tugas</th>
                            <th class="{{ $thClass }}">Departemen</th>
                            <th class="{{ $thClass }}">Penanggung Jawab</th>
                            <th class="{{ $thClass }}">Status</th>
                            <th class="{{ $thClass }}">Prioritas</th>
                            <th class="{{ $thClass }}">Tenggat</th>
                            <th class="{{ $thClass }}">Dibuat</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#29364A]">
                        @forelse ($tasks as $task)
                            @php
                                $taskStatusColor =
                                    $statusColors[$task->status] ??
                                    'border-slate-500/30 bg-slate-500/10 text-slate-300';
                                $taskPriorityColor =
                                    $priorityColors[$task->priority] ??
                                    'border-slate-500/30 bg-slate-500/10 text-slate-300';
                            @endphp

                            <tr class="transition hover:bg-[#151F32]">
                                <td class="{{ $tdClass }} min-w-64">
                                    <a href="{{ route('tasks.show', $task) }}"
                                        class="font-semibold text-[#E2E8F0] transition hover:text-blue-400">
                                        {{ $task->title }}
                                    </a>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $task->category?->name ?? 'Tanpa kategori' }}
                                    </p>
                                </td>

                                <td class="{{ $tdClass }} whitespace-nowrap">
                                    {{ $task->department?->name ?? '-' }}
                                </td>

                                <td class="{{ $tdClass }} whitespace-nowrap">
                                    @if ($task->assignee)
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-[#35445B] bg-[#0B1120] text-xs font-semibold text-slate-300">
                                                {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                            </div>
                                            <span>{{ $task->assignee->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-500">Belum ditugaskan</span>
                                    @endif
                                </td>

                                <td class="{{ $tdClass }} whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-xs font-medium {{ $taskStatusColor }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        {{ $statusLabels[$task->status] ?? str($task->status)->replace('_', ' ')->title() }}
                                    </span>
                                </td>

                                <td class="{{ $tdClass }} whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center rounded-lg border px-2.5 py-1 text-xs font-medium {{ $taskPriorityColor }}">
                                        {{ $priorityLabels[$task->priority] ?? str($task->priority)->title() }}
                                    </span>
                                </td>

                                <td class="{{ $tdClass }} whitespace-nowrap">
                                    @if ($task->due_date)
                                        <span
                                            class="{{ !in_array($task->status, ['completed', 'cancelled']) && $task->due_date->isPast() ? 'font-medium text-rose-300' : 'text-slate-300' }}">
                                            {{ $task->due_date->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-500">-</span>
                                    @endif
                                </td>

                                <td class="{{ $tdClass }} whitespace-nowrap text-slate-400">
                                    {{ $task->created_at?->format('d M Y') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-14 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-[#29364A] bg-[#0B1120] text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 text-sm font-medium text-slate-300">Tidak ada tugas yang ditemukan</p>
                                    <p class="mt-1 text-xs text-slate-500">Coba ubah atau reset filter untuk melihat data
                                        lainnya.</p>
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
