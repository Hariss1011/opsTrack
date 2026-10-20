@extends('layouts.app')

@section('title', 'Manajemen Kategori Tugas')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
            <div>
                <div class="mb-2 flex items-center gap-2">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-blue-400/20 bg-blue-500/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6.5A2.5 2.5 0 016.5 4h3A2.5 2.5 0 0112 6.5v3A2.5 2.5 0 019.5 12h-3A2.5 2.5 0 014 9.5v-3zM15 6h5m-5 4h5M4 17h16M4 21h10" />
                        </svg>
                    </span>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-400">
                        Pengaturan Sistem
                    </p>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-100 sm:text-3xl">
                    Manajemen Kategori Tugas
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-400">
                    Kelola dan kelompokkan pekerjaan operasional agar pemantauan tugas lebih terstruktur.
                </p>
            </div>

            <a href="{{ route('task-categories.create') }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/30 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#0B1120]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                </svg>
                Tambah Kategori
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/8 p-4 text-sm text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div
                class="flex items-start gap-3 rounded-xl border border-rose-500/20 bg-rose-500/8 p-4 text-sm text-rose-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.3 3.9L1.8 18.6A1.6 1.6 0 003.2 21h17.6a1.6 1.6 0 001.4-2.4L13.7 3.9a1.97 1.97 0 00-3.4 0z" />
                </svg>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Summary --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Total Kategori</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-100">
                            {{ number_format($categories->total()) }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Sesuai hasil pencarian</p>
                    </div>
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl border border-blue-400/20 bg-blue-500/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6.5A2.5 2.5 0 016.5 4h3A2.5 2.5 0 0112 6.5v3A2.5 2.5 0 019.5 12h-3A2.5 2.5 0 014 9.5v-3zM15 6h5m-5 4h5M4 17h16M4 21h10" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Kategori Aktif</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-emerald-400">
                            {{ $categories->getCollection()->where('is_active', true)->count() }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Pada halaman ini</p>
                    </div>
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl border border-emerald-400/20 bg-emerald-500/10 text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Kategori Nonaktif</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-amber-400">
                            {{ $categories->getCollection()->where('is_active', false)->count() }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Pada halaman ini</p>
                    </div>
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl border border-amber-400/20 bg-amber-500/10 text-amber-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.3 3.9L1.8 18.6A1.6 1.6 0 003.2 21h17.6a1.6 1.6 0 001.4-2.4L13.7 3.9a1.97 1.97 0 00-3.4 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filters --}}
        <section class="rounded-2xl border border-[#29364A] bg-[#111B2E] p-5">
            <div class="mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h2 class="text-sm font-semibold text-slate-200">Cari dan Filter Kategori</h2>
            </div>

            <form method="GET" action="{{ route('task-categories.index') }}"
                class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_200px_auto_auto]">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Cari nama atau deskripsi kategori..."
                        class="w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-500 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                </div>

                <select name="status"
                    class="rounded-xl border border-[#29364A] bg-[#0B1120] px-4 py-3 text-sm text-slate-200 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Semua Status</option>
                    <option value="active" @selected($status === 'active')>Aktif</option>
                    <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                </select>

                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari
                </button>

                <a href="{{ route('task-categories.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-[#29364A] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-[#151F32]">
                    Reset
                </a>
            </form>
        </section>

        {{-- Category Table --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div
                class="flex flex-col justify-between gap-3 border-b border-[#29364A] px-5 py-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="font-semibold text-slate-100">Daftar Kategori</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Informasi kategori dan pengelolaan status pekerjaan.
                    </p>
                </div>

                <span
                    class="inline-flex w-fit items-center gap-2 rounded-lg border border-[#29364A] bg-[#0B1120] px-3 py-2 text-xs font-medium text-slate-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                    {{ number_format($categories->total()) }} kategori
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-237.5 text-left text-sm">
                    <thead
                        class="border-b border-[#29364A] bg-[#0B1120]/70 text-[11px] uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Nama Kategori</th>
                            <th class="px-5 py-4 font-semibold">Deskripsi</th>
                            <th class="px-5 py-4 font-semibold">Jumlah Tugas</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#29364A]/70">
                        @forelse ($categories as $category)
                            <tr class="transition hover:bg-[#151F32]/70">
                                {{-- Category Name --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-400/15 bg-blue-500/10 text-sm font-bold text-blue-300">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 pt-0.5">
                                            <p class="font-semibold text-slate-100">
                                                {{ $category->name }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                Dibuat {{ $category->created_at?->format('d M Y') ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Description --}}
                                <td class="max-w-xs px-5 py-4">
                                    <p class="line-clamp-2 leading-6 text-slate-400">
                                        {{ $category->description ?: 'Tidak ada deskripsi' }}
                                    </p>
                                </td>

                                {{-- Task Count --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-lg border border-blue-400/15 bg-blue-500/10 px-3 py-2 text-xs font-semibold text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                        </svg>
                                        {{ $category->tasks_count }} tugas
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4">
                                    @if ($category->is_active)
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1.5 text-xs font-semibold text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-slate-500/20 bg-slate-500/10 px-3 py-1.5 text-xs font-semibold text-slate-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('task-categories.edit', $category) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-blue-400/20 bg-blue-500/5 px-3 py-2 text-xs font-semibold text-blue-300 transition hover:border-blue-400/40 hover:bg-blue-500/15">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l2.651 2.651M4 20l4.5-1 11-11a2.121 2.121 0 00-3-3l-11 11L4 20z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('task-categories.toggle-status', $category) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin mengubah status kategori ini?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-[#29364A] px-3 py-2 text-xs font-semibold text-slate-300 transition hover:border-amber-400/30 hover:bg-amber-500/10 hover:text-amber-300">
                                                @if ($category->is_active)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M18.364 5.636A9 9 0 115.636 5.636m12.728 0L12 12m6.364-6.364V11m0-5.364H13" />
                                                    </svg>
                                                    Nonaktifkan
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Aktifkan
                                                @endif
                                            </button>
                                        </form>

                                        @if ($category->tasks_count === 0)
                                            <form action="{{ route('task-categories.destroy', $category) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-rose-400/20 bg-rose-500/5 px-3 py-2 text-xs font-semibold text-rose-300 transition hover:border-rose-400/40 hover:bg-rose-500/15">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 7h12m-10 0l.7 12h6.6L16 7M9 7V4h6v3m-5 4v5m4-5v5" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-[#29364A] bg-[#0B1120] text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 6.5A2.5 2.5 0 016.5 4h3A2.5 2.5 0 0112 6.5v3A2.5 2.5 0 019.5 12h-3A2.5 2.5 0 014 9.5v-3zM15 6h5m-5 4h5M4 17h16M4 21h10" />
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 font-semibold text-slate-200">
                                        Kategori tidak ditemukan
                                    </h3>
                                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                                        Tidak ada kategori yang sesuai dengan pencarian atau filter saat ini.
                                        Coba ubah kata kunci atau tambahkan kategori baru.
                                    </p>

                                    <a href="{{ route('task-categories.create') }}"
                                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                        </svg>
                                        Tambah Kategori
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($categories->hasPages())
                <div
                    class="flex flex-col justify-between gap-3 border-t border-[#29364A] px-5 py-4 sm:flex-row sm:items-center">
                    <p class="text-xs text-slate-500">
                        Menampilkan
                        <span class="font-semibold text-slate-300">{{ $categories->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-semibold text-slate-300">{{ $categories->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-slate-300">{{ $categories->total() }}</span>
                        kategori
                    </p>

                    <div class="opstrack-pagination">
                        {{ $categories->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </section>

    </div>
@endsection
