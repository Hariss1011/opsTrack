@extends('layouts.app')

@section('title', 'Pengaturan Akun')
@section('page-heading', 'Pengaturan Akun')

@section('content')
    <div class="mx-auto max-w-5xl space-y-7">

        {{-- Page heading --}}
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-blue-400">
                Pengaturan Pengguna
            </p>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-100 sm:text-3xl">
                Pengaturan Akun
            </h1>
            <p class="mt-2 text-sm text-slate-400">
                Kelola informasi pribadi dan keamanan akun OpsTrack.
            </p>
        </div>

        {{-- Account overview --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:p-7">
                <div
                    class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-blue-400/20 bg-blue-500/10 text-2xl font-bold text-blue-300">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <h2 class="truncate text-lg font-bold text-slate-100">
                        {{ $user->name }}
                    </h2>
                    <p class="mt-1 break-all text-sm text-slate-400">
                        {{ $user->email }}
                    </p>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span
                            class="rounded-full border border-blue-400/20 bg-blue-500/10 px-3 py-1 text-xs font-semibold capitalize text-blue-300">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>

                        <span class="rounded-full border border-[#334155] bg-[#0B1120] px-3 py-1 text-xs text-slate-300">
                            {{ $user->department?->name ?? 'Tanpa departemen' }}
                        </span>
                    </div>
                </div>

                <div
                    class="flex items-center gap-2 self-start rounded-xl border border-emerald-400/20 bg-emerald-400/5 px-3 py-2 sm:self-center">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span class="text-xs font-medium text-emerald-300">Akun terautentikasi</span>
                </div>
            </div>
        </section>

        {{-- Personal information --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div class="flex items-center gap-3 border-b border-[#29364A] px-5 py-5 sm:px-7">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-13a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-100">Informasi Pribadi</h2>
                    <p class="mt-1 text-xs text-slate-400">Perbarui nama dan alamat email akun.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-5 p-5 sm:p-7">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-300">
                        Nama lengkap
                    </label>
                    <input id="name" name="name" type="text" required maxlength="255"
                        value="{{ old('name', $user->name) }}" autocomplete="name"
                        class="w-full rounded-xl border border-[#334155] bg-[#0B1120] px-4 py-3 text-sm text-slate-100 outline-none transition placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                    @error('name')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-300">
                        Alamat email
                    </label>
                    <input id="email" name="email" type="email" required maxlength="255"
                        value="{{ old('email', $user->email) }}" autocomplete="email"
                        class="w-full rounded-xl border border-[#334155] bg-[#0B1120] px-4 py-3 text-sm text-slate-100 outline-none transition placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                    @error('email')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl border border-[#29364A] bg-[#0B1120]/60 p-4">
                    <p class="text-xs leading-5 text-slate-400">
                        Role dan departemen dikelola oleh Admin melalui Manajemen Pengguna.
                        Perubahan informasi pribadi tidak mengubah hak akses akun.
                    </p>
                </div>

                <div class="flex justify-end border-t border-[#29364A] pt-5">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Informasi
                    </button>
                </div>
            </form>
        </section>

        {{-- Password security --}}
        <section class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E]">
            <div class="flex items-center gap-3 border-b border-[#29364A] px-5 py-5 sm:px-7">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-11V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-100">Keamanan Akun</h2>
                    <p class="mt-1 text-xs text-slate-400">Ganti password untuk menjaga keamanan akun.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('account.password.update') }}" class="space-y-5 p-5 sm:p-7">
                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium text-slate-300">
                        Password saat ini
                    </label>
                    <input id="current_password" name="current_password" type="password" required
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-[#334155] bg-[#0B1120] px-4 py-3 text-sm text-slate-100 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                    @error('current_password')
                        <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-300">
                            Password baru
                        </label>
                        <input id="password" name="password" type="password" required minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-[#334155] bg-[#0B1120] px-4 py-3 text-sm text-slate-100 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                        @error('password')
                            <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-slate-500">Minimal 8 karakter.</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-300">
                            Konfirmasi password baru
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            minlength="8" autocomplete="new-password"
                            class="w-full rounded-xl border border-[#334155] bg-[#0B1120] px-4 py-3 text-sm text-slate-100 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>

                <div class="rounded-xl border border-amber-400/15 bg-amber-400/5 p-4">
                    <p class="text-xs leading-5 text-amber-200/90">
                        Pastikan password baru berbeda dari password yang sudah diketahui orang lain.
                        Jangan membagikan password kepada siapa pun.
                    </p>
                </div>

                <div class="flex justify-end border-t border-[#29364A] pt-5">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-11V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Perbarui Password
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
