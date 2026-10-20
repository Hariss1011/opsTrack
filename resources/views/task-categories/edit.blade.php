@extends('layouts.app')

@section('title', 'Edit Kategori Tugas')

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
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l2.651 2.651M4 20l4.5-1 11-11a2.121 2.121 0 00-3-3l-11 11L4 20z" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-400">
                        Pengaturan Sistem
                    </p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-100 sm:text-3xl">
                        Edit Kategori Tugas
                    </h1>
                    <p class="mt-2 text-sm leading-6 text-slate-400">
                        Perbarui informasi kategori
                        <span class="font-semibold text-slate-200">{{ $category->name }}</span>
                        sesuai kebutuhan operasional.
                    </p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('task-categories.update', $category) }}" method="POST"
            class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            @csrf
            @method('PUT')

            <div class="border-b border-[#29364A] px-6 py-5 sm:px-7">
                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="font-semibold text-slate-100">Informasi Kategori</h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Periksa kembali perubahan sebelum menyimpannya.
                        </p>
                    </div>

                    <span
                        class="inline-flex w-fit items-center gap-2 rounded-lg border border-[#29364A] bg-[#0B1120] px-3 py-2 text-xs text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z" />
                        </svg>
                        Dibuat {{ $category->created_at?->format('d M Y') ?? '—' }}
                    </span>
                </div>
            </div>

            <div class="space-y-6 p-6 sm:p-7">
                @include('task-categories.form', [
                    'category' => $category,
                    'isEdit' => true,
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
                <p class="text-sm font-semibold text-blue-300">Perhatikan perubahan status</p>
                <p class="mt-1 text-sm leading-6 text-slate-400">
                    Menonaktifkan kategori dapat memengaruhi ketersediaannya untuk tugas baru. Pastikan perubahan status
                    sesuai kebutuhan sistem.
                </p>
            </div>
        </div>

    </div>
@endsection
