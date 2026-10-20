<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | OpsTrack</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        :root {
            color-scheme: dark;
            --ot-bg: #0B1120;
            --ot-surface: #111B2E;
            --ot-border: #29364A;
            --ot-blue: #60A5FA;
        }

        html {
            scroll-behavior: smooth;
            scrollbar-color: #34445E var(--ot-bg);
            scrollbar-width: thin;
        }

        body {
            background-image:
                radial-gradient(ellipse at 50% 0%,
                    rgba(37, 99, 235, 0.07),
                    transparent 48%);
            background-attachment: fixed;
        }

        ::selection {
            color: #FFFFFF;
            background: rgba(59, 130, 246, 0.45);
        }

        :focus-visible {
            outline: 2px solid var(--ot-blue);
            outline-offset: 3px;
        }

        summary::-webkit-details-marker {
            display: none;
        }

        summary::marker {
            content: "";
        }

        [data-navbar-dropdown]>div,
        details>div {
            animation: ot-dropdown-in 150ms ease-out;
            transform-origin: top right;
        }

        @keyframes ot-dropdown-in {
            from {
                opacity: 0;
                transform: translateY(-5px) scale(0.99);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-[#0B1120] text-[#E2E8F0] antialiased">

    @php
        $currentUser = auth()->user();

        $unreadNotifications = $currentUser->unreadNotifications()->latest()->take(5)->get();
        $unreadCount = $currentUser->unreadNotifications()->count();

        $navigationItems = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'pattern' => 'dashboard',
                'icon' => 'dashboard',
                'admin_only' => false,
            ],
            [
                'label' => 'Manajemen Tugas',
                'route' => 'tasks.index',
                'pattern' => 'tasks.*',
                'icon' => 'tasks',
                'admin_only' => false,
            ],
            [
                'label' => 'Laporan Operasional',
                'route' => 'reports.index',
                'pattern' => 'reports.*',
                'icon' => 'reports',
                'admin_only' => false,
            ],
            [
                'label' => 'Manajemen Pengguna',
                'route' => 'users.index',
                'pattern' => 'users.*',
                'icon' => 'users',
                'admin_only' => true,
            ],
            [
                'label' => 'Departemen',
                'route' => 'departments.index',
                'pattern' => 'departments.*',
                'icon' => 'departments',
                'admin_only' => true,
            ],
            [
                'label' => 'Kategori Tugas',
                'route' => 'task-categories.index',
                'pattern' => 'task-categories.*',
                'icon' => 'categories',
                'admin_only' => true,
            ],
            [
                'label' => 'Activity Log',
                'route' => 'activity-logs.index',
                'pattern' => 'activity-logs.*',
                'icon' => 'activity',
                'admin_only' => false,
            ],
        ];
    @endphp

    <div class="min-h-screen">

        {{-- Sidebar Desktop --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-[#29364A] bg-[#080E1A] text-slate-300 lg:flex">

            {{-- Brand --}}
            <div class="flex h-20 shrink-0 items-center gap-3 border-b border-[#29364A] px-6">
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#3B82F6] text-xl font-extrabold text-white shadow-lg shadow-blue-950/40">
                    O
                </div>

                <div class="min-w-0">
                    <h1 class="text-xl font-bold tracking-tight text-white">
                        OpsTrack
                    </h1>
                    <p class="mt-0.5 text-[10px] font-semibold tracking-[0.12em] text-slate-500">
                        OPERATIONAL MANAGEMENT
                    </p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">

                <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">
                    Menu Utama
                </p>

                @foreach ($navigationItems as $item)
                    @if (!$item['admin_only'] || $currentUser->role === 'admin')
                        @php
                            $isActive = request()->routeIs($item['pattern']);
                        @endphp

                        <a href="{{ route($item['route']) }}"
                            @if ($isActive) aria-current="page" @endif
                            class="group flex items-center gap-3 rounded-xl border px-3 py-3 text-[13px] font-medium transition duration-200
                            {{ $isActive
                                ? 'border-blue-400/20 bg-[#172B4D] text-[#93C5FD] shadow-sm shadow-black/10'
                                : 'border-transparent text-slate-400 hover:border-[#29364A] hover:bg-[#151F32] hover:text-white' }}">

                            <span class="flex h-5 w-5 shrink-0 items-center justify-center">
                                @switch($item['icon'])
                                    @case('dashboard')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                            <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                            <rect x="14" y="14" width="7" height="7" rx="1.5" />
                                            <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                        </svg>
                                    @break

                                    @case('tasks')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <rect x="4" y="4" width="16" height="17" rx="2" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 9h8M8 13h8M8 17h5M9 4V2m6 2V2" />
                                        </svg>
                                    @break

                                    @case('reports')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 19V5m0 14h16M8 15v-4m4 4V7m4 8v-6m4 6V4" />
                                        </svg>
                                    @break

                                    @case('users')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
                                        </svg>
                                    @break

                                    @case('departments')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 21h18M5 21V5l7-3 7 3v16M9 9h1m4 0h1m-6 4h1m4 0h1m-5 8v-4h4v4" />
                                        </svg>
                                    @break

                                    @case('categories')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 13V7a2 2 0 0 0-2-2h-6L4 13l7 7 9-7Z" />
                                            <circle cx="15.5" cy="9.5" r="1" />
                                        </svg>
                                    @break

                                    @case('activity')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l3-8 4 16 3-8h4" />
                                        </svg>
                                    @break
                                @endswitch
                            </span>

                            <span class="flex-1">{{ $item['label'] }}</span>

                            @if ($isActive)
                                <span class="h-1.5 w-1.5 rounded-full bg-[#60A5FA]"></span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Sidebar Footer --}}
            <div class="shrink-0 border-t border-[#29364A] p-4">
                <div class="rounded-xl border border-[#29364A] bg-[#111B2E] p-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-500/15 text-sm font-bold text-[#93C5FD]">
                            {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <p class="truncate text-xs font-semibold text-white">
                                {{ $currentUser->name }}
                            </p>
                            <p class="mt-1 text-[10px] capitalize text-slate-400">
                                {{ str_replace('_', ' ', $currentUser->role) }}
                            </p>
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-center text-[10px] text-slate-500">
                    OpsTrack &copy; {{ date('Y') }}
                </p>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="min-w-0 lg:pl-64">

            {{-- Top Navbar --}}
            <header class="sticky top-0 z-30 border-b border-[#29364A] bg-[#0B1120]/95 backdrop-blur-xl">
                <div class="flex min-h-20 items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">

                    {{-- Mobile Brand --}}
                    <div class="flex min-w-0 items-center gap-3 lg:hidden">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#3B82F6] text-lg font-extrabold text-white">
                            O
                        </div>

                        <div class="min-w-0">
                            <h1 class="truncate text-lg font-bold tracking-tight text-white">
                                OpsTrack
                            </h1>
                            <p class="text-[10px] text-slate-400">
                                Operational Management
                            </p>
                        </div>
                    </div>

                    {{-- Desktop Page Heading --}}
                    <div class="hidden min-w-0 lg:block">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                            Operational Management System
                        </p>
                        <h2 class="mt-1 truncate text-lg font-bold text-[#E2E8F0]">
                            @yield('page-heading', 'Dashboard')
                        </h2>
                    </div>

                    {{-- Header Actions --}}
                    <div class="ml-auto flex shrink-0 items-center gap-2 sm:gap-3">

                        {{-- Mobile Navigation --}}
                        <details class="relative lg:hidden" data-mobile-menu>
                            <summary
                                class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-[#29364A] text-slate-300 transition hover:border-blue-400/50 hover:bg-[#151F32] hover:text-[#93C5FD]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </summary>

                            <div
                                class="absolute right-0 z-50 mt-3 w-64 overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] p-3 shadow-2xl shadow-black/30">
                                <p
                                    class="px-3 pb-2 pt-1 text-[10px] font-bold uppercase tracking-widest text-slate-500">
                                    Menu Navigasi
                                </p>

                                <nav class="space-y-1">
                                    @foreach ($navigationItems as $item)
                                        @if (!$item['admin_only'] || $currentUser->role === 'admin')
                                            <a href="{{ route($item['route']) }}"
                                                class="flex items-center rounded-xl px-3 py-3 text-sm font-medium transition
                                                {{ request()->routeIs($item['pattern'])
                                                    ? 'bg-[#172B4D] text-[#93C5FD]'
                                                    : 'text-slate-300 hover:bg-[#1B2639] hover:text-white' }}">
                                                {{ $item['label'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                </nav>
                            </div>
                        </details>

                        {{-- Notifications --}}
                        <details class="relative" data-navbar-dropdown>
                            <summary aria-label="Buka notifikasi"
                                class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-[#29364A] text-slate-300 transition hover:border-blue-400/50 hover:bg-[#151F32] hover:text-[#93C5FD]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                                </svg>

                                @if ($unreadCount > 0)
                                    <span
                                        class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full border-2 border-[#0B1120] bg-rose-500 px-1 text-[10px] font-bold text-white">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                @endif
                            </summary>

                            <div
                                class="absolute right-0 z-50 mt-3 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-2xl shadow-black/30 sm:w-96">

                                <div class="flex items-center justify-between border-b border-[#29364A] px-4 py-4">
                                    <div>
                                        <h3 class="text-sm font-bold text-[#E2E8F0]">
                                            Notifikasi
                                        </h3>
                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $unreadCount }} belum dibaca
                                        </p>
                                    </div>

                                    <a href="{{ route('notifications.index') }}"
                                        class="text-xs font-semibold text-[#60A5FA] transition hover:text-[#93C5FD]">
                                        Lihat Semua
                                    </a>
                                </div>

                                <div class="max-h-96 overflow-y-auto">
                                    @forelse ($unreadNotifications as $notification)
                                        <form method="POST"
                                            action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf

                                            <button type="submit"
                                                class="flex w-full gap-3 border-b border-[#29364A] px-4 py-4 text-left transition last:border-0 hover:bg-[#1B2639]">

                                                <span
                                                    class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-500/15 text-[#93C5FD]">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                </span>

                                                <span class="min-w-0 flex-1">
                                                    <span class="block text-sm font-semibold text-[#E2E8F0]">
                                                        {{ $notification->data['title'] ?? 'Notifikasi OpsTrack' }}
                                                    </span>

                                                    <span class="mt-1 block text-xs leading-5 text-slate-400">
                                                        {{ $notification->data['message'] ?? '' }}
                                                    </span>

                                                    <span class="mt-2 block text-[11px] text-slate-500">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                </span>
                                            </button>
                                        </form>
                                    @empty
                                        <div class="px-5 py-10 text-center">
                                            <div
                                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#1B2639] text-slate-500">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                                                </svg>
                                            </div>

                                            <p class="mt-3 text-sm font-semibold text-[#E2E8F0]">
                                                Semua sudah diperiksa
                                            </p>
                                            <p class="mt-1 text-xs text-slate-400">
                                                Belum ada notifikasi baru.
                                            </p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </details>

                        {{-- User Profile --}}

                        {{-- User Account Dropdown --}}
                        <details class="group relative" data-navbar-dropdown>
                            <summary
                                class="flex cursor-pointer list-none items-center gap-2 rounded-xl border border-transparent p-1.5 transition hover:border-[#29364A] hover:bg-[#151F32] sm:gap-3 sm:px-3 sm:py-2">

                                <div class="hidden text-right sm:block">
                                    <p class="max-w-36 truncate text-sm font-semibold text-[#E2E8F0]">
                                        {{ $currentUser->name }}
                                    </p>
                                    <p class="mt-0.5 text-xs capitalize text-slate-400">
                                        {{ str_replace('_', ' ', $currentUser->role) }}
                                    </p>
                                </div>

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-blue-400/20 bg-blue-500/15 text-sm font-bold text-[#93C5FD]">
                                    {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                                </div>

                                <svg class="hidden h-4 w-4 text-slate-400 transition group-open:rotate-180 sm:block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                                </svg>
                            </summary>

                            <div
                                class="absolute right-0 z-50 mt-3 w-72 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-[#29364A] bg-[#111B2E] shadow-2xl shadow-black/40">

                                {{-- Dropdown identity --}}
                                <div class="border-b border-[#29364A] p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-500/10 text-lg font-bold text-blue-300">
                                            {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-100">
                                                {{ $currentUser->name }}
                                            </p>
                                            <p class="mt-1 truncate text-xs text-slate-400">
                                                {{ $currentUser->email }}
                                            </p>
                                            <p class="mt-1 text-[11px] capitalize text-blue-300">
                                                {{ str_replace('_', ' ', $currentUser->role) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Account settings --}}
                                <div class="p-2">
                                    <a href="{{ route('account.settings') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-[#1B2940]">
                                        <span
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-500/10 text-blue-300">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.4 15a1.7 1.7 0 00.34 1.88l.06.06-1.8 3.12-.08-.02a1.7 1.7 0 00-1.72.48l-.06.06h-3.6l-.04-.08a1.7 1.7 0 00-1.48-1.02 1.7 1.7 0 00-.9.25l-.07.04-3.12-1.8.02-.08a1.7 1.7 0 00-.48-1.72l-.06-.06v-3.6l.08-.04a1.7 1.7 0 001.02-1.48 1.7 1.7 0 00-.25-.9l-.04-.07 1.8-3.12.08.02a1.7 1.7 0 001.72-.48l.06-.06h3.6l.04.08a1.7 1.7 0 001.48 1.02 1.7 1.7 0 00.9-.25l.07-.04 3.12 1.8-.02.08a1.7 1.7 0 00.48 1.72l.06.06v3.6l-.08.04a1.7 1.7 0 00-1.02 1.48z" />
                                            </svg>
                                        </span>

                                        <span class="min-w-0 flex-1">
                                            <span class="block text-sm font-semibold text-slate-100">
                                                Pengaturan Akun
                                            </span>
                                            <span class="mt-1 block text-xs text-slate-400">
                                                Informasi pribadi dan keamanan
                                            </span>
                                        </span>

                                        <svg class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                                        </svg>
                                    </a>
                                </div>

                                {{-- Logout --}}
                                <div class="border-t border-[#29364A] p-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <button type="submit"
                                            class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left transition hover:bg-rose-500/10">
                                            <span
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-500/10 text-rose-300">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M10 17l5-5-5-5m5 5H3m9-9h6a3 3 0 013 3v12a3 3 0 01-3 3h-6" />
                                                </svg>
                                            </span>

                                            <span class="text-sm font-semibold text-rose-300">
                                                Keluar dari OpsTrack
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </details>
                    </div>
                </div>

                {{-- Mobile Page Heading --}}
                <div class="border-t border-[#29364A] px-4 py-3 sm:px-6 lg:hidden">
                    <h2 class="text-sm font-bold text-[#E2E8F0]">
                        @yield('page-heading', 'Dashboard')
                    </h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">
                        Operational Task Management System
                    </p>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="min-w-0 min-h-[calc(100vh-10rem)] bg-transparent p-4 sm:p-6 lg:p-8">
                <div class="mx-auto w-full max-w-[1600px]">

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div role="status"
                            class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-500/25 bg-emerald-500/10 px-4 py-4 text-sm text-emerald-200">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-400" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                            </svg>

                            <p class="flex-1 leading-6">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if (session('error'))
                        <div role="alert"
                            class="mb-6 flex items-start gap-3 rounded-xl border border-rose-500/25 bg-rose-500/10 px-4 py-4 text-sm text-rose-200">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-400" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" />
                            </svg>

                            <p class="flex-1 leading-6">{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')

                </div>
            </main>

            {{-- Footer --}}
            <footer class="border-t border-[#29364A] bg-[#080E1A] px-4 py-5 sm:px-6 lg:px-8">
                <div
                    class="mx-auto flex max-w-[1600px] flex-col gap-2 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                    <p>
                        &copy; {{ date('Y') }} OpsTrack. Operational Task Management System.
                    </p>
                    <p>
                        Kelola tugas. Pantau progres. Tingkatkan kinerja.
                    </p>
                </div>
            </footer>
        </div>
    </div>


    <script>
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll(
                '[data-navbar-dropdown]'
            );

            dropdowns.forEach(function(dropdown) {
                // Klik di luar dropdown: tutup
                if (!dropdown.contains(event.target)) {
                    dropdown.open = false;
                    return;
                }

                // Klik di dalam dropdown yang sedang dibuka:
                // biarkan tetap terbuka.
                // Jika dropdown ini baru dibuka, tutup dropdown lainnya.
                if (dropdown.open) {
                    dropdowns.forEach(function(otherDropdown) {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.open = false;
                        }
                    });
                }
            });
        });
        document.addEventListener('click', function(event) {
            document.querySelectorAll('[data-mobile-menu]').forEach(function(menu) {
                if (!menu.contains(event.target)) {
                    menu.open = false;
                }
            });
        });

        document.querySelectorAll('[data-mobile-menu] a').forEach(function(link) {
            link.addEventListener('click', function() {
                const menu = link.closest('[data-mobile-menu]');

                if (menu) {
                    menu.open = false;
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
