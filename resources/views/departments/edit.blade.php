@extends('layouts.app')

@section('title', 'Edit Departemen')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">

        {{-- Page Heading --}}
        <div>
            <a href="{{ route('departments.index') }}"
                class="group inline-flex items-center gap-2 text-sm font-medium text-[#94A3B8] transition hover:text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition group-hover:-translate-x-1" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7" />
                </svg>
                Kembali ke Manajemen Departemen
            </a>

            <div class="mt-5 flex items-start gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-amber-400/20 bg-amber-400/10 text-amber-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.5 3.5 4 4M4 20l4.5-1 11-11a2.12 2.12 0 0 0-3-3l-11 11L4 20Z" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <div class="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">
                        Pengaturan Sistem
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-[#E2E8F0] sm:text-3xl">
                        Edit Departemen
                    </h1>

                    <p class="mt-2 text-sm leading-6 text-[#94A3B8]">
                        Perbarui informasi departemen
                        <span class="font-semibold text-slate-200">{{ $department->name }}</span>.
                    </p>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-xl shadow-black/10">

            <div class="flex items-center gap-3 border-b border-[#29364A] px-5 py-5 sm:px-7">
                <div>
                    <h2 class="text-base font-semibold text-[#E2E8F0]">
                        Perbarui Informasi
                    </h2>
                    <p class="mt-1 text-xs text-[#94A3B8]">
                        Periksa kembali nama departemen sebelum menyimpan perubahan.
                    </p>
                </div>

                <span
                    class="ml-auto inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-amber-400/20 bg-amber-400/10 px-2.5 py-1.5 text-xs font-medium text-amber-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.5 3.5 4 4M4 20l4.5-1 11-11a2.12 2.12 0 0 0-3-3l-11 11L4 20Z" />
                    </svg>
                    Mode Edit
                </span>
            </div>

            <form action="{{ route('departments.update', $department) }}" method="POST" class="p-5 sm:p-7">
                @csrf
                @method('PUT')

                @include('departments.form', [
                    'department' => $department,
                    'isEdit' => true,
                ])
            </form>
        </section>

        {{-- Information Note --}}
        <div class="flex items-start gap-3 rounded-xl border border-amber-400/15 bg-amber-400/5 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-amber-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5m0-8h.01" />
            </svg>

            <div>
                <p class="text-sm font-semibold text-amber-300">Perhatikan Perubahan</p>
                <p class="mt-1 text-xs leading-5 text-slate-400">
                    Pastikan nama baru tetap jelas dan unik. Perubahan nama tidak dimaksudkan untuk menghapus riwayat tugas
                    atau pengguna yang terkait dengan departemen ini.
                </p>
            </div>
        </div>

    </div>
@endsection
