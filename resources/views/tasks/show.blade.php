@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
    @php
        $user = auth()->user();

        $statusLabels = [
            'open' => 'Belum Dimulai',
            'in_progress' => 'Sedang Dikerjakan',
            'blocked' => 'Terhambat',
            'pending_review' => 'Menunggu Verifikasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $statusColors = [
            'open' => 'border-slate-500/30 bg-slate-500/10 text-slate-300',
            'in_progress' => 'border-blue-400/30 bg-blue-400/10 text-blue-300',
            'blocked' => 'border-rose-400/30 bg-rose-400/10 text-rose-300',
            'pending_review' => 'border-amber-400/30 bg-amber-400/10 text-amber-300',
            'completed' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300',
            'cancelled' => 'border-slate-500/30 bg-slate-500/10 text-slate-400',
        ];

        $priorityLabels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak',
        ];

        $priorityColors = [
            'low' => 'border-slate-500/30 bg-slate-500/10 text-slate-300',
            'medium' => 'border-blue-400/30 bg-blue-400/10 text-blue-300',
            'high' => 'border-orange-400/30 bg-orange-400/10 text-orange-300',
            'urgent' => 'border-rose-400/30 bg-rose-400/10 text-rose-300',
        ];

        $panelClass = 'rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-sm';
        $inputClass =
            'w-full rounded-xl border border-[#35445B] bg-[#0B1120] px-4 py-3 text-sm text-[#E2E8F0] placeholder:text-[#64748B] transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20';
        $labelClass = 'mb-2 block text-sm font-medium text-[#CBD5E1]';
        $errorClass = 'mt-1.5 text-xs text-rose-400';

        $isAdmin = $user->role === 'admin';
        $isManager = $user->role === 'manager';
        $isStaff = $user->role === 'staff';

        $isVerifier =
            ($isAdmin || $isManager) && ($isAdmin || (int) $task->department_id === (int) $user->department_id);

        $isAssignedStaff = $isStaff && (int) $task->assigned_to === (int) $user->id;

        $isFinalStatus = in_array($task->status, ['completed', 'cancelled'], true);

        $canEditTask =
            ($isAdmin || $isManager) &&
            !$isFinalStatus &&
            ($isAdmin || (int) $task->department_id === (int) $user->department_id);

        $canSubmitProgress =
            $isAssignedStaff && !in_array($task->status, ['completed', 'cancelled', 'pending_review'], true);

        $isOverdue = $task->due_date && $task->due_date->isPast() && !$isFinalStatus;

        $updateTypeLabels = [
            'progress' => 'Progres',
            'blocker' => 'Kendala',
            'general' => 'Aktivitas',
        ];

        $updateTypeColors = [
            'progress' => 'border-blue-400/25 bg-blue-400/10 text-blue-300',
            'blocker' => 'border-rose-400/25 bg-rose-400/10 text-rose-300',
            'general' => 'border-slate-400/25 bg-slate-400/10 text-slate-300',
        ];

        $updateTypeIcons = [
            'progress' => '↗',
            'blocker' => '!',
            'general' => '•',
        ];
    @endphp

    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-sm">
            <a href="{{ route('tasks.index') }}" class="text-[#94A3B8] transition hover:text-blue-300">
                Manajemen Tugas
            </a>
            <span class="text-[#475569]">/</span>
            <span class="font-medium text-[#E2E8F0]">Detail Tugas</span>
        </nav>

        {{-- Page header --}}
        <header class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div class="h-1 bg-linear-to-r from-blue-600 via-blue-400 to-cyan-400"></div>

            <div class="p-5 sm:p-7">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $statusColors[$task->status] ?? $statusColors['open'] }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ $statusLabels[$task->status] ?? ucfirst($task->status) }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-full border border-[#35445B] bg-[#0B1120]/70 px-3 py-1.5 text-xs font-medium text-[#CBD5E1]">
                                {{ $task->category->name ?? 'Tanpa kategori' }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-semibold {{ $priorityColors[$task->priority] ?? $priorityColors['medium'] }}">
                                Prioritas {{ $priorityLabels[$task->priority] ?? ucfirst($task->priority) }}
                            </span>
                        </div>

                        <h1
                            class="mt-5 wrap-break-word text-2xl font-bold leading-tight tracking-tight text-[#F1F5F9] sm:text-3xl">
                            {{ $task->title }}
                        </h1>

                        <p class="mt-3 max-w-3xl text-sm leading-6 text-[#94A3B8]">
                            {{ \Illuminate\Support\Str::limit(strip_tags($task->description ?? ''), 180) ?: 'Detail dan informasi tugas operasional.' }}
                        </p>

                        <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-3 text-xs text-[#94A3B8]">
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#35445B] bg-[#0B1120] font-semibold text-blue-300">
                                    {{ strtoupper(substr($task->creator->name ?? 'P', 0, 1)) }}
                                </span>
                                <span>
                                    Dibuat oleh
                                    <span class="font-medium text-[#CBD5E1]">{{ $task->creator->name ?? 'Pengguna' }}</span>
                                </span>
                            </div>

                            <span class="hidden text-[#475569] sm:inline">•</span>

                            <span>
                                Dibuat {{ $task->created_at?->format('d M Y, H:i') ?? '-' }}
                            </span>

                            @if ($task->due_date)
                                <span class="hidden text-[#475569] sm:inline">•</span>
                                <span class="{{ $isOverdue ? 'font-semibold text-rose-300' : 'text-[#94A3B8]' }}">
                                    Tenggat {{ $task->due_date->format('d M Y') }}
                                    @if ($isOverdue)
                                        (Terlambat)
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Header actions --}}
                    <div class="flex shrink-0 flex-wrap gap-2">
                        @if ($canEditTask)
                            <a href="{{ route('tasks.edit', $task) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-400/30 bg-blue-500/10 px-4 py-2.5 text-sm font-semibold text-blue-300 transition hover:border-blue-400/60 hover:bg-blue-500/20">
                                <span aria-hidden="true">✎</span>
                                Edit Tugas
                            </a>

                            <form action="{{ route('tasks.cancel', $task) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin membatalkan tugas ini?')">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-400/25 bg-rose-500/10 px-4 py-2.5 text-sm font-semibold text-rose-300 transition hover:border-rose-400/50 hover:bg-rose-500/20">
                                    Batalkan
                                </button>
                            </form>
                        @endif

                        @if ($isAssignedStaff && $task->status === 'open')
                            <form action="{{ route('tasks.start', $task) }}" method="POST"
                                onsubmit="return confirm('Mulai mengerjakan tugas ini?')">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition hover:bg-blue-500">
                                    <span aria-hidden="true">▶</span>
                                    Mulai Kerjakan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if (session('success'))
            <div role="status"
                class="flex items-start gap-3 rounded-xl border border-emerald-400/25 bg-emerald-400/10 p-4 text-sm text-emerald-300">
                <span
                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-400/15 font-bold">✓</span>
                <div>
                    <p class="font-semibold">Berhasil</p>
                    <p class="mt-0.5 leading-6">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div role="alert"
                class="flex items-start gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-4 text-sm text-rose-300">
                <span class="font-bold">!</span>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-400/25 bg-rose-400/10 p-4">
                <p class="text-sm font-semibold text-rose-300">Periksa kembali data yang dimasukkan.</p>
                <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-rose-300/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Verification panel --}}
        @if ($task->status === 'pending_review' && $isVerifier)
            <section class="overflow-hidden rounded-2xl border border-amber-400/30 bg-[#111B2E] shadow-sm">
                <div class="border-b border-amber-400/15 bg-amber-400/5 px-5 py-4 sm:px-6">
                    <div class="flex items-start gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-amber-400/20 bg-amber-400/10 text-lg font-bold text-amber-300">
                            !
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.15em] text-amber-300">
                                Tindakan diperlukan
                            </p>
                            <h2 class="mt-1 text-lg font-semibold text-[#F1F5F9]">
                                Verifikasi Penyelesaian Tugas
                            </h2>
                            <p class="mt-1 text-sm leading-6 text-[#94A3B8]">
                                Periksa hasil pekerjaan dan riwayat aktivitas sebelum menyetujui atau mengembalikan tugas
                                kepada Staff.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('tasks.verify', $task) }}" method="POST" class="space-y-5 p-5 sm:p-6">
                    @csrf

                    <div>
                        <label for="decision" class="{{ $labelClass }}">
                            Keputusan <span class="text-rose-400">*</span>
                        </label>
                        <select id="decision" name="decision" required class="{{ $inputClass }}">
                            <option value="">Pilih keputusan verifikasi</option>
                            <option value="approve" @selected(old('decision') === 'approve')>
                                Setujui — Tugas Selesai
                            </option>
                            <option value="reject" @selected(old('decision') === 'reject')>
                                Kembalikan — Perlu Dikerjakan Lagi
                            </option>
                        </select>
                        @error('decision')
                            <p class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="rejection-note-wrapper" hidden>
                        <label for="note" class="{{ $labelClass }}">
                            Catatan atau Alasan Pengembalian <span class="text-rose-400">*</span>
                        </label>
                        <textarea id="note" name="note" rows="3" maxlength="5000"
                            placeholder="Jelaskan bagian yang perlu diperbaiki agar Staff dapat menindaklanjutinya..."
                            class="{{ $inputClass }}">{{ old('note') }}</textarea>
                        @error('note')
                            <p class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div
                        class="flex flex-col gap-3 border-t border-[#29364A] pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs leading-5 text-[#64748B]">
                            Keputusan verifikasi akan dicatat dalam riwayat tugas.
                        </p>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500"
                            onclick="return confirm('Yakin ingin memproses verifikasi tugas ini?')">
                            Proses Verifikasi
                            <span aria-hidden="true">→</span>
                        </button>
                    </div>
                </form>
            </section>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const decisionSelect = document.getElementById('decision');
                    const noteWrapper = document.getElementById('rejection-note-wrapper');
                    const noteInput = document.getElementById('note');

                    if (!decisionSelect || !noteWrapper || !noteInput) return;

                    function toggleRejectionNote() {
                        const isReject = decisionSelect.value === 'reject';

                        noteWrapper.hidden = !isReject;
                        noteInput.required = isReject;
                    }

                    decisionSelect.addEventListener('change', toggleRejectionNote);
                    toggleRejectionNote();
                });
            </script>
        @endif

        {{-- Main layout --}}
        <div class="grid grid-cols-1 items-start gap-5 xl:grid-cols-3">

            {{-- Main column --}}
            <div class="min-w-0 space-y-5 xl:col-span-2">

                {{-- Description --}}
                <section class="{{ $panelClass }} p-5 sm:p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-500/10 text-lg text-blue-300">
                            ☰
                        </div>
                        <div>
                            <h2 class="font-semibold text-[#F1F5F9]">Deskripsi Tugas</h2>
                            <p class="mt-0.5 text-xs text-[#64748B]">Rincian dan tujuan pekerjaan</p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl border border-[#29364A] bg-[#0B1120]/60 p-4 sm:p-5">
                        <p class="whitespace-pre-line wrap-break-word text-sm leading-7 text-[#CBD5E1]">
                            {{ $task->description ?: 'Tidak ada deskripsi untuk tugas ini.' }}</p>
                    </div>
                </section>

                {{-- Status history --}}
                <section class="{{ $panelClass }} p-5 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-400/10 text-lg text-sky-300">
                                ↻
                            </div>
                            <div>
                                <h2 class="font-semibold text-[#F1F5F9]">Riwayat Status</h2>
                                <p class="mt-0.5 text-xs text-[#64748B]">Rekam perubahan status tugas</p>
                            </div>
                        </div>

                        <span
                            class="w-fit rounded-full border border-[#35445B] bg-[#0B1120] px-3 py-1 text-xs font-medium text-[#94A3B8]">
                            {{ $task->statusHistories->count() }} perubahan
                        </span>
                    </div>

                    <div class="mt-6">
                        @forelse ($task->statusHistories->sortByDesc('changed_at') as $history)
                            <div class="relative border-l border-[#35445B] pb-6 pl-6 last:border-transparent last:pb-0">
                                <span
                                    class="absolute -left-1.25 top-1.5 h-2.5 w-2.5 rounded-full border-2 border-[#111B2E] bg-blue-400 ring-2 ring-blue-400/15"></span>

                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold leading-6 text-[#E2E8F0]">
                                            @if ($history->old_status)
                                                {{ $statusLabels[$history->old_status] ?? $history->old_status }}
                                                <span class="mx-1 text-[#64748B]">→</span>
                                            @else
                                                <span class="text-[#94A3B8]">Status awal:</span>
                                            @endif
                                            {{ $statusLabels[$history->new_status] ?? $history->new_status }}
                                        </p>

                                        <p class="mt-1.5 text-xs text-[#94A3B8]">
                                            Oleh
                                            <span
                                                class="font-medium text-[#CBD5E1]">{{ $history->changedBy->name ?? 'Pengguna' }}</span>
                                        </p>
                                    </div>

                                    <time class="shrink-0 text-xs text-[#64748B]">
                                        {{ $history->changed_at?->format('d M Y, H:i') ?? '-' }}
                                    </time>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-[#35445B] p-6 text-center">
                                <p class="text-sm text-[#94A3B8]">Belum ada riwayat perubahan status.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- Progress update form --}}
                <section class="{{ $panelClass }} overflow-hidden">
                    <div class="border-b border-[#29364A] p-5 sm:p-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-400/10 text-lg text-indigo-300">
                                ✎
                            </div>
                            <div>
                                <h2 class="font-semibold text-[#F1F5F9]">Update Progres</h2>
                                <p class="mt-0.5 text-xs text-[#64748B]">Catat perkembangan atau kendala pekerjaan</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        @if ($canSubmitProgress)
                            <div class="mb-5 rounded-xl border border-blue-400/20 bg-blue-400/5 p-4">
                                <p class="text-sm font-medium text-blue-200">Pembaruan pekerjaan</p>
                                <p class="mt-1 text-xs leading-5 text-[#94A3B8]">
                                    Sampaikan perkembangan yang sudah dicapai, kendala yang ditemui, atau ajukan tugas untuk
                                    diverifikasi setelah selesai.
                                </p>
                            </div>

                            <form action="{{ route('tasks.updates.store', $task) }}" method="POST" class="space-y-5">
                                @csrf

                                <div>
                                    <label for="action" class="{{ $labelClass }}">
                                        Jenis Pembaruan <span class="text-rose-400">*</span>
                                    </label>
                                    <select id="action" name="action" required class="{{ $inputClass }}">
                                        <option value="progress" @selected(old('action', 'progress') === 'progress')>
                                            Update Progres
                                        </option>
                                        <option value="blocker" @selected(old('action') === 'blocker')>
                                            Laporkan Kendala
                                        </option>
                                        <option value="submit_review" @selected(old('action') === 'submit_review')>
                                            Ajukan Tugas Selesai
                                        </option>
                                    </select>
                                    @error('action')
                                        <p class="{{ $errorClass }}">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="{{ $labelClass }}">
                                        Catatan Pekerjaan <span class="text-rose-400">*</span>
                                    </label>
                                    <textarea id="description" name="description" rows="4" required minlength="5" maxlength="5000"
                                        placeholder="Contoh: Validasi data telah selesai, pengujian berhasil, atau jelaskan kendala yang ditemukan..."
                                        class="{{ $inputClass }}">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="{{ $errorClass }}">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-[#64748B]">
                                        Minimal 5 karakter dan maksimal 5.000 karakter.
                                    </p>
                                </div>

                                <div
                                    class="flex flex-col gap-3 border-t border-[#29364A] pt-5 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-xs leading-5 text-[#64748B]">
                                        Pembaruan akan tersimpan dalam riwayat aktivitas tugas.
                                    </p>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500"
                                        onclick="return confirm('Simpan pembaruan tugas ini?')">
                                        Simpan Pembaruan
                                        <span aria-hidden="true">→</span>
                                    </button>
                                </div>
                            </form>
                        @elseif ($isAssignedStaff)
                            <div class="flex items-start gap-3 rounded-xl border border-[#35445B] bg-[#0B1120]/70 p-4">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-400/10 font-bold text-amber-300">
                                    i
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-[#E2E8F0]">
                                        @if ($task->status === 'pending_review')
                                            Tugas sedang diverifikasi
                                        @elseif ($isFinalStatus)
                                            Tugas tidak lagi menerima pembaruan
                                        @else
                                            Pembaruan belum tersedia
                                        @endif
                                    </p>
                                    <p class="mt-1 text-sm leading-6 text-[#94A3B8]">
                                        @if ($task->status === 'pending_review')
                                            Tugas sudah diajukan untuk verifikasi. Tunggu keputusan Admin atau Manager.
                                        @elseif ($isFinalStatus)
                                            Status tugas sudah final sehingga progres tidak dapat diperbarui.
                                        @else
                                            Pembaruan progres belum dapat dilakukan pada status saat ini.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-3 rounded-xl border border-[#35445B] bg-[#0B1120]/70 p-4">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-400/10 text-blue-300">
                                    i
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-[#E2E8F0]">Pemantauan progres</p>
                                    <p class="mt-1 text-sm leading-6 text-[#94A3B8]">
                                        Form pembaruan hanya tersedia untuk Staff yang ditugaskan. Kamu tetap dapat memantau
                                        perkembangan melalui riwayat aktivitas di bawah.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Activity history --}}
                <section class="{{ $panelClass }} p-5 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-400/10 text-lg text-cyan-300">
                                ≡
                            </div>
                            <div>
                                <h2 class="font-semibold text-[#F1F5F9]">Riwayat Aktivitas</h2>
                                <p class="mt-0.5 text-xs text-[#64748B]">Catatan progres, kendala, dan aktivitas tugas</p>
                            </div>
                        </div>

                        <span
                            class="w-fit rounded-full border border-[#35445B] bg-[#0B1120] px-3 py-1 text-xs font-medium text-[#94A3B8]">
                            {{ $task->updates->count() }} aktivitas
                        </span>
                    </div>

                    <div class="mt-6 space-y-3">
                        @forelse ($task->updates->sortByDesc('created_at') as $update)
                            @php
                                $updateType = $update->update_type ?? 'general';
                            @endphp

                            <article
                                class="rounded-xl border border-[#29364A] bg-[#0B1120]/50 p-4 transition hover:border-[#35445B] sm:p-5">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-400/10 text-sm font-bold text-blue-300">
                                        {{ strtoupper(substr($update->user->name ?? 'P', 0, 1)) }}
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                            <div class="min-w-0">
                                                <p class="wrap-break-word text-sm font-semibold text-[#E2E8F0]">
                                                    {{ $update->user->name ?? 'Pengguna' }}
                                                </p>
                                                <time class="mt-1 block text-xs text-[#64748B]">
                                                    {{ $update->created_at?->format('d M Y, H:i') ?? '-' }}
                                                </time>
                                            </div>

                                            <span
                                                class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium {{ $updateTypeColors[$updateType] ?? $updateTypeColors['general'] }}">
                                                <span>{{ $updateTypeIcons[$updateType] ?? '•' }}</span>
                                                {{ $updateTypeLabels[$updateType] ?? ucfirst($updateType) }}
                                            </span>
                                        </div>

                                        <p
                                            class="mt-4 whitespace-pre-line wrap-break-word text-sm leading-6 text-[#CBD5E1]">
                                            {{ $update->description }}</p>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-xl border border-dashed border-[#35445B] p-8 text-center">
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0B1120] text-xl text-[#64748B]">
                                    ≡
                                </div>
                                <p class="mt-3 text-sm font-medium text-[#CBD5E1]">Belum ada aktivitas</p>
                                <p class="mt-1 text-xs leading-5 text-[#64748B]">
                                    Catatan progres dan aktivitas terkait tugas akan muncul di sini.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <aside class="min-w-0 space-y-5 xl:sticky xl:top-5">

                {{-- Task information --}}
                <section class="{{ $panelClass }} overflow-hidden">
                    <div class="border-b border-[#29364A] p-5 sm:p-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-400/10 text-lg text-sky-300">
                                ⓘ
                            </div>
                            <div>
                                <h2 class="font-semibold text-[#F1F5F9]">Informasi Tugas</h2>
                                <p class="mt-0.5 text-xs text-[#64748B]">Ringkasan penugasan</p>
                            </div>
                        </div>
                    </div>

                    <dl class="divide-y divide-[#29364A] px-5 sm:px-6">
                        <div class="py-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-[#64748B]">Departemen</dt>
                            <dd class="mt-2 wrap-break-word text-sm font-medium text-[#E2E8F0]">
                                {{ $task->department->name ?? '-' }}
                            </dd>
                        </div>

                        <div class="py-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-[#64748B]">Penanggung Jawab</dt>
                            <dd class="mt-2 wrap-break-word text-sm font-medium text-[#E2E8F0]">
                                {{ $task->assignee->name ?? 'Belum ditentukan' }}
                            </dd>
                            @if ($task->assignee?->email)
                                <dd class="mt-1 break-all text-xs text-[#94A3B8]">
                                    {{ $task->assignee->email }}
                                </dd>
                            @endif
                        </div>

                        <div class="py-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-[#64748B]">Prioritas</dt>
                            <dd class="mt-2">
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $priorityColors[$task->priority] ?? $priorityColors['medium'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $priorityLabels[$task->priority] ?? ucfirst($task->priority) }}
                                </span>
                            </dd>
                        </div>

                        <div class="py-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-[#64748B]">Tanggal Mulai</dt>
                            <dd class="mt-2 text-sm text-[#CBD5E1]">
                                {{ $task->start_date?->format('d M Y') ?? '-' }}
                            </dd>
                        </div>

                        <div class="py-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-[#64748B]">Tenggat Waktu</dt>
                            <dd
                                class="mt-2 flex flex-wrap items-center gap-2 text-sm font-semibold {{ $isOverdue ? 'text-rose-300' : 'text-[#CBD5E1]' }}">
                                {{ $task->due_date?->format('d M Y') ?? '-' }}

                                @if ($isOverdue)
                                    <span
                                        class="rounded-full border border-rose-400/25 bg-rose-400/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-rose-300">
                                        Terlambat
                                    </span>
                                @endif
                            </dd>
                        </div>

                        @if ($task->completed_at)
                            <div class="py-4">
                                <dt class="text-xs font-medium uppercase tracking-wider text-[#64748B]">Waktu Selesai</dt>
                                <dd class="mt-2 text-sm font-semibold text-emerald-300">
                                    {{ $task->completed_at->format('d M Y, H:i') }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </section>

                {{-- Recording information --}}
                <section class="{{ $panelClass }} p-5 sm:p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-400/10 text-lg text-indigo-300">
                            ▤
                        </div>
                        <div>
                            <h2 class="font-semibold text-[#F1F5F9]">Informasi Pencatatan</h2>
                            <p class="mt-0.5 text-xs text-[#64748B]">Metadata tugas</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-5">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-[#64748B]">Dibuat pada</p>
                            <p class="mt-2 text-sm text-[#CBD5E1]">
                                {{ $task->created_at?->format('d M Y, H:i') ?? '-' }}
                            </p>
                        </div>

                        <div class="border-t border-[#29364A] pt-5">
                            <p class="text-xs font-medium uppercase tracking-wider text-[#64748B]">Terakhir diperbarui</p>
                            <p class="mt-2 text-sm text-[#CBD5E1]">
                                {{ $task->updated_at?->format('d M Y, H:i') ?? '-' }}
                            </p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
