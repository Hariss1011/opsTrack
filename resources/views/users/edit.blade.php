@extends('layouts.app')

@section('title', 'Edit Pengguna')

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
                                d="M16 4l4 4M4 20l4-.8L19.5 7.7a2.1 2.1 0 00-3-3L5 16.2 4 20z" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-100 sm:text-3xl">
                            Edit Pengguna
                        </h1>
                        <p class="mt-1 text-sm text-slate-400">
                            Perbarui informasi akun dan hak akses pengguna.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-xl border border-slate-700/70 bg-[#111B2E] p-3">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-500/10 text-sm font-bold text-blue-300">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="max-w-48 truncate text-sm font-semibold text-slate-100">
                        {{ $user->name }}
                    </p>
                    <p class="max-w-48 truncate text-xs text-slate-400">
                        {{ $user->email }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('users.update', $user) }}" method="POST"
            class="overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-xl shadow-black/10">
            @csrf
            @method('PUT')

            <div class="border-b border-[#29364A] px-5 py-5 sm:px-7">
                <h2 class="text-base font-semibold text-slate-100">Informasi Akun</h2>
                <p class="mt-1 text-sm text-slate-400">
                    Sesuaikan data identitas, role, departemen, dan status akun.
                </p>
            </div>

            <div class="p-5 sm:p-7">
                @include('users.form', ['user' => $user, 'isEdit' => true])
            </div>
        </form>

        <div class="flex items-start gap-3 rounded-xl border border-amber-400/15 bg-amber-400/5 p-4">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M12 9v4m0 4h.01M10.3 3.9L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-300">Perubahan kredensial</p>
                <p class="mt-1 text-xs leading-5 text-slate-400">
                    Password bersifat opsional ketika mengedit pengguna. Kosongkan kedua kolom
                    password jika tidak ingin menggantinya.
                </p>
            </div>
        </div>
    </div>
@endsection
