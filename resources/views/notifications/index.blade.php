@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium text-blue-600">Pusat Informasi</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-800">
                    Notifikasi
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Informasi tugas, progres, dan hasil verifikasi.
                </p>
            </div>

            @if (auth()->user()->unreadNotifications()->exists())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp

                <div
                    class="flex gap-4 border-b border-slate-100 p-5 last:border-b-0
                    {{ $isUnread ? 'bg-blue-50/60' : 'bg-white' }}">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                        {{ $isUnread ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                        <span class="text-lg">●</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="font-semibold text-slate-800">
                                {{ $data['title'] ?? 'Notifikasi OpsTrack' }}
                            </h2>

                            @if ($isUnread)
                                <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">
                                    Baru
                                </span>
                            @endif
                        </div>

                        <p class="mt-1 text-sm leading-6 text-slate-600">
                            {{ $data['message'] ?? 'Ada pembaruan pada sistem.' }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            {{ $notification->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    @if ($isUnread)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                Buka
                            </button>
                        </form>
                    @elseif (!empty($data['url']))
                        <a href="{{ $data['url'] }}"
                            class="shrink-0 text-sm font-semibold text-slate-500 hover:text-blue-600">
                            Lihat
                        </a>
                    @endif
                </div>
            @empty
                <div class="px-5 py-14 text-center">
                    <h2 class="font-semibold text-slate-700">
                        Belum ada notifikasi
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Notifikasi terkait tugas akan muncul di sini.
                    </p>
                </div>
            @endforelse

            @if ($notifications->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
