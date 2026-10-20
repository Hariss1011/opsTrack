@extends('layouts.app')

@section('title', 'Tambah Departemen')

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
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-400/20 bg-blue-400/10 text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 21h18M5 21V7l8-4v18M13 10h6v11M8 9h1m-1 4h1m-1 4h1m7-4h1m-1 4h1M12 5v6m-3-3h6" />
                    </svg>
                </div>

                <div>
                    <div class="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">
                        Pengaturan Sistem
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-[#E2E8F0] sm:text-3xl">
                        Tambah Departemen
                    </h1>

                    <p class="mt-2 max-w-xl text-sm leading-6 text-[#94A3B8]">
                        Buat departemen baru untuk mengelompokkan pengguna dan mendukung pengelolaan tugas operasional.
                    </p>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-xl shadow-black/10">

            <div class="flex items-center gap-3 border-b border-[#29364A] px-5 py-5 sm:px-7">
                <div>
                    <h2 class="text-base font-semibold text-[#E2E8F0]">
                        Informasi Departemen
                    </h2>
                    <p class="mt-1 text-xs text-[#94A3B8]">
                        Lengkapi informasi dasar departemen yang akan dibuat.
                    </p>
                </div>

                <span
                    class="ml-auto inline-flex items-center gap-1.5 rounded-lg border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1.5 text-xs font-medium text-emerald-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Data Baru
                </span>
            </div>

            <form action="{{ route('departments.store') }}" method="POST" class="p-5 sm:p-7">
                @csrf

                @include('departments.form', [
                    'department' => null,
                    'isEdit' => false,
                ])
            </form>
        </section>

        {{-- Information Note --}}
        <div class="flex items-start gap-3 rounded-xl border border-blue-400/15 bg-blue-400/5 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-blue-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5m0-8h.01" />
            </svg>

            <div>
                <p class="text-sm font-semibold text-blue-300">Informasi</p>
                <p class="mt-1 text-xs leading-5 text-slate-400">
                    Gunakan nama departemen yang jelas dan unik agar pengelompokan pengguna serta tugas lebih mudah
                    dikelola.
                </p>
            </div>
        </div>

    </div>
@endsection
