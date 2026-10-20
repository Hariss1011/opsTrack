@extends('layouts.app')

@section('title', 'Tambah Kategori Tugas')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">

        {{-- Header --}}
        <div>
            <a href="{{ route('task-categories.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-400 transition hover:text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Manajemen Kategori
            </a>

            <div class="mt-5 flex items-start gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-400/20 bg-blue-500/10 text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-400">
                        Pengaturan Sistem
                    </p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-100 sm:text-3xl">
                        Tambah Kategori Tugas
                    </h1>
                    <p class="mt-2 max-w-xl text-sm leading-6 text-slate-400">
                        Buat kategori baru untuk mengelompokkan pekerjaan operasional secara lebih terstruktur.
                    </p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('task-categories.store') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            @csrf

            <div class="border-b border-[#29364A] px-6 py-5 sm:px-7">
                <h2 class="font-semibold text-slate-100">Informasi Kategori</h2>
                <p class="mt-1 text-sm text-slate-400">
                    Lengkapi informasi kategori pada formulir berikut.
                </p>
            </div>

            <div class="space-y-6 p-6 sm:p-7">
                @include('task-categories.form', [
                    'category' => null,
                    'isEdit' => false,
                ])
            </div>
        </form>

        {{-- Supporting Note --}}
        <div class="flex items-start gap-3 rounded-xl border border-blue-400/15 bg-blue-500/6 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-blue-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 18h.01M12 14a4 4 0 10-4-4m4 4v-1m0 1a2 2 0 012 2" />
                <circle cx="12" cy="12" r="9" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-300">Tips pengelolaan kategori</p>
                <p class="mt-1 text-sm leading-6 text-slate-400">
                    Gunakan nama kategori yang singkat dan jelas. Deskripsi dapat membantu tim memahami jenis pekerjaan yang
                    termasuk di dalamnya.
                </p>
            </div>
        </div>

    </div>
@endsection
