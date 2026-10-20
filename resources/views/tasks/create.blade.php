@extends('layouts.app')

@section('title', 'Buat Tugas')

@section('content')
    @php
        $inputClass =
            'w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-4 py-3 text-sm text-[#E2E8F0] placeholder:text-slate-500 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20';
        $labelClass = 'mb-2 block text-sm font-medium text-slate-300';
        $errorClass = 'mt-2 text-xs font-medium text-rose-300';
    @endphp

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Header --}}
        <div>
            <a href="{{ route('tasks.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-blue-400 transition hover:text-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6M9 12h12" />
                </svg>
                Kembali ke Manajemen Tugas
            </a>

            <div class="mt-5 flex items-start gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-400/20 bg-blue-400/10 text-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                        <rect x="3" y="3" width="18" height="18" rx="4" />
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-400">
                        Manajemen Operasional
                    </p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#E2E8F0] sm:text-3xl">
                        Buat Tugas Baru
                    </h1>
                    <p class="mt-2 text-sm leading-6 text-[#94A3B8]">
                        Tentukan detail pekerjaan, departemen, penanggung jawab, dan tenggat penyelesaian.
                    </p>
                </div>
            </div>
        </div>

        {{-- Informasi validasi --}}
        @if ($errors->any())
            <div class="flex gap-3 rounded-xl border border-rose-400/20 bg-rose-400/[0.07] p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-rose-300" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-rose-200">Data belum dapat disimpan</p>
                    <p class="mt-1 text-sm text-rose-300/80">
                        Periksa kembali kolom yang ditandai dan pastikan seluruh informasi sudah benar.
                    </p>
                </div>
            </div>
        @endif

        <form action="{{ route('tasks.store') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-sm">
            @csrf

            {{-- Bagian 1: Informasi tugas --}}
            <div class="border-b border-[#29364A] px-5 py-5 sm:px-7">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-400/10 text-sm font-bold text-blue-300">
                        01
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-[#E2E8F0]">Informasi Tugas</h2>
                        <p class="mt-1 text-xs text-[#94A3B8]">
                            Informasi utama dan tujuan pekerjaan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-5 p-5 sm:p-7">
                <div>
                    <label for="title" class="{{ $labelClass }}">Judul Tugas <span
                            class="text-rose-400">*</span></label>
                    <input id="title" name="title" type="text" required maxlength="255" value="{{ old('title') }}"
                        placeholder="Contoh: Pemeriksaan Stok Gudang Mingguan"
                        class="{{ $inputClass }} @error('title') border-rose-400/70 @enderror">

                    @error('title')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="{{ $labelClass }}">Deskripsi Pekerjaan</label>
                    <textarea id="description" name="description" rows="4"
                        placeholder="Jelaskan ruang lingkup pekerjaan, target, atau instruksi yang perlu diperhatikan..."
                        class="{{ $inputClass }} resize-y @error('description') border-rose-400/70 @enderror">{{ old('description') }}</textarea>

                    <p class="mt-2 text-xs text-slate-500">
                        Tuliskan instruksi yang jelas agar penanggung jawab memahami hasil yang diharapkan.
                    </p>

                    @error('description')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Bagian 2: Penugasan --}}
            <div class="border-y border-[#29364A] bg-[#0B1120]/40 px-5 py-5 sm:px-7">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-sky-400/20 bg-sky-400/10 text-sm font-bold text-sky-300">
                        02
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-[#E2E8F0]">Penugasan</h2>
                        <p class="mt-1 text-xs text-[#94A3B8]">
                            Tentukan unit kerja, kategori, dan staff yang bertanggung jawab.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-7">

                <div>
                    <label for="department_id" class="{{ $labelClass }}">
                        Departemen <span class="text-rose-400">*</span>
                    </label>

                    <select id="department_id" name="department_id" required
                        class="{{ $inputClass }} @error('department_id') border-rose-400/70 @enderror">
                        <option value="">Pilih departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) old('department_id') === (string) $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('department_id')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="{{ $labelClass }}">
                        Kategori Tugas <span class="text-rose-400">*</span>
                    </label>

                    <select id="category_id" name="category_id" required
                        class="{{ $inputClass }} @error('category_id') border-rose-400/70 @enderror">
                        <option value="">Pilih kategori tugas</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id) @disabled(!$category->is_active)>
                                {{ $category->name }}{{ !$category->is_active ? ' (Nonaktif)' : '' }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-2 text-xs text-slate-500">
                        Pilih kategori aktif yang sesuai dengan jenis pekerjaan.
                    </p>

                    @error('category_id')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="assigned_to" class="{{ $labelClass }}">
                        Staff Penanggung Jawab <span class="text-rose-400">*</span>
                    </label>

                    <select id="assigned_to" name="assigned_to" required
                        class="{{ $inputClass }} @error('assigned_to') border-rose-400/70 @enderror">
                        <option value="">Pilih staff</option>
                        @foreach ($users as $staff)
                            <option value="{{ $staff->id }}" data-department="{{ $staff->department_id }}"
                                @selected((string) old('assigned_to') === (string) $staff->id)>
                                {{ $staff->name }} — {{ $staff->department->name ?? 'Tanpa departemen' }}
                            </option>
                        @endforeach
                    </select>

                    <p id="staffHelp" class="mt-2 text-xs text-slate-500">
                        Pilih departemen terlebih dahulu untuk memfilter staff.
                    </p>

                    @error('assigned_to')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="{{ $labelClass }}">
                        Prioritas <span class="text-rose-400">*</span>
                    </label>

                    <select id="priority" name="priority" required
                        class="{{ $inputClass }} @error('priority') border-rose-400/70 @enderror">
                        @foreach (['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi', 'urgent' => 'Mendesak'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority', 'medium') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @error('priority')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Bagian 3: Jadwal --}}
            <div class="border-y border-[#29364A] bg-[#0B1120]/40 px-5 py-5 sm:px-7">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-indigo-400/20 bg-indigo-400/10 text-sm font-bold text-indigo-300">
                        03
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-[#E2E8F0]">Jadwal Pengerjaan</h2>
                        <p class="mt-1 text-xs text-[#94A3B8]">
                            Tentukan awal pekerjaan dan batas waktu penyelesaian.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-7">
                <div>
                    <label for="start_date" class="{{ $labelClass }}">
                        Tanggal Mulai <span class="text-rose-400">*</span>
                    </label>

                    <input id="start_date" name="start_date" type="date" required
                        value="{{ old('start_date', now()->toDateString()) }}"
                        class="{{ $inputClass }} @error('start_date') border-rose-400/70 @enderror">

                    @error('start_date')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="{{ $labelClass }}">
                        Tenggat Waktu <span class="text-rose-400">*</span>
                    </label>

                    <input id="due_date" name="due_date" type="date" required value="{{ old('due_date') }}"
                        class="{{ $inputClass }} @error('due_date') border-rose-400/70 @enderror">

                    @error('due_date')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol aksi --}}
            <div
                class="flex flex-col-reverse gap-3 border-t border-[#29364A] bg-[#0B1120]/40 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                <p class="text-xs text-slate-500">
                    <span class="text-rose-400">*</span> Wajib diisi
                </p>

                <div class="flex flex-col-reverse gap-3 sm:flex-row">
                    <a href="{{ route('tasks.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-[#35445B] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:bg-white/5 hover:text-white">
                        Batal
                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#111B2E]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                        </svg>
                        Simpan Tugas
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('department_id');
            const staffSelect = document.getElementById('assigned_to');
            const staffHelp = document.getElementById('staffHelp');

            if (!departmentSelect || !staffSelect) return;

            function filterStaff() {
                const departmentId = departmentSelect.value;

                Array.from(staffSelect.options).forEach(option => {
                    if (!option.value) return;

                    const belongsToDepartment =
                        departmentId === '' ||
                        option.dataset.department === departmentId;

                    option.hidden = !belongsToDepartment;
                    option.disabled = !belongsToDepartment;
                });

                const selectedOption = staffSelect.options[staffSelect.selectedIndex];

                if (selectedOption && selectedOption.value && selectedOption.disabled) {
                    staffSelect.value = '';
                }

                if (staffHelp) {
                    staffHelp.textContent = departmentId ?
                        'Daftar staff disesuaikan dengan departemen yang dipilih.' :
                        'Pilih departemen terlebih dahulu untuk memfilter staff.';
                }
            }

            departmentSelect.addEventListener('change', filterStaff);
            filterStaff();
        });
    </script>
@endsection
