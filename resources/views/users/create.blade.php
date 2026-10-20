@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="mx-auto max-w-5xl space-y-7">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-400 transition hover:text-blue-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Manajemen Pengguna
                </a>

                <div class="mt-4 flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-blue-400/20 bg-blue-500/10 text-blue-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-13a4 4 0 11-8 0 4 4 0 018 0zm2 4h6m-3-3v6" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-100 sm:text-3xl">
                            Tambah Pengguna
                        </h1>
                        <p class="mt-1 text-sm text-slate-400">
                            Buat akun baru dan atur akses pengguna di OpsTrack.
                        </p>
                    </div>
                </div>
            </div>

            <div class="hidden items-center gap-2 rounded-xl border border-slate-700/70 bg-[#111B2E] px-4 py-3 sm:flex">
                <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                <span class="text-xs font-medium text-slate-300">Administrasi Akun</span>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('users.store') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-xl shadow-black/10">
            @csrf

            <div class="border-b border-[#29364A] px-5 py-5 sm:px-7">
                <h2 class="text-base font-semibold text-slate-100">Informasi Pengguna</h2>
                <p class="mt-1 text-sm text-slate-400">
                    Lengkapi identitas, hak akses, dan kredensial akun.
                </p>
            </div>

            <div class="p-5 sm:p-7">
                @include('users.form', ['user' => null, 'isEdit' => false])
            </div>
        </form>

        <div class="flex items-start gap-3 rounded-xl border border-blue-400/15 bg-blue-500/5 p-4">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-300">Perhatikan hak akses</p>
                <p class="mt-1 text-xs leading-5 text-slate-400">
                    Pilih role dan departemen sesuai tanggung jawab pengguna. Password awal harus
                    memenuhi ketentuan minimal 8 karakter.
                </p>
            </div>
        </div>
    </div>
@endsection
