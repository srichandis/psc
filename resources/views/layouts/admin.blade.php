@php
    $navigation = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'active' => request()->routeIs('admin.dashboard'),
            'icon' => 'grid',
        ],
        [
            'label' => 'Appointments',
            'route' => 'admin.appointments.index',
            'active' => request()->routeIs('admin.appointments.*'),
            'icon' => 'calendar',
        ],
        [
            'label' => 'Specialists',
            'route' => 'admin.specialists.index',
            'active' => request()->routeIs('admin.specialists.*'),
            'icon' => 'users',
        ],
        [
            'label' => 'Specialties',
            'route' => 'admin.specialties.index',
            'active' => request()->routeIs('admin.specialties.*'),
            'icon' => 'tag',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('heading', 'Dashboard') &bull; {{ config('admin.title') }}</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Newsreader:ital,opsz,wght@0,6..72,400..700;1,6..72,400..700&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-100">
<div x-data="{ sidebarOpen: false }" class="flex min-h-full">

    {{-- Mobile backdrop --}}
    <div x-show="sidebarOpen" x-cloak x-transition.opacity.duration.200ms
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside x-cloak
           class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-brand-dark transition-transform duration-200 lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Brand --}}
        <div class="flex h-16 flex-shrink-0 items-center gap-3 border-b border-white/10 px-5">
            <img src="/images/logo-light.png" alt=""
                 class="h-9 w-9 flex-shrink-0 object-contain">
            <div class="min-w-0">
                <p class="truncate font-editorial text-lg leading-none font-bold text-white">{{ config('clinic.short_name') }}</p>
                <p class="mt-0.5 truncate text-[10px] font-semibold tracking-[0.18em] text-teal-200 uppercase">{{ config('admin.title') }}</p>
            </div>

            <button type="button" @click="sidebarOpen = false"
                    class="ml-auto rounded-lg p-1.5 text-slate-400 hover:bg-white/10 hover:text-white lg:hidden"
                    aria-label="Close navigation">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5" aria-label="Admin navigation">
            @foreach ($navigation as $item)
                <a href="{{ route($item['route']) }}"
                   @class([
                       'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                       'bg-white/10 text-white shadow-sm ring-1 ring-white/10' => $item['active'],
                       'text-slate-300 hover:bg-white/5 hover:text-white' => ! $item['active'],
                   ])
                   @if ($item['active']) aria-current="page" @endif>
                    <span @class([
                        'flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg transition-colors',
                        'bg-brand-accent text-white' => $item['active'],
                        'bg-white/5 text-teal-200 group-hover:bg-white/10' => ! $item['active'],
                    ])>
                        @switch($item['icon'])
                            @case('grid')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                    <rect x="14" y="14" width="7" height="7" rx="1.5" />
                                </svg>
                                @break
                            @case('calendar')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                </svg>
                                @break
                            @case('users')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9.5" cy="7" r="3.5" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                </svg>
                                @break
                            @default
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20.59 13.41 12 22l-9-9V4h9l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                    <path d="M7 7h.01" />
                                </svg>
                        @endswitch
                    </span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- Signed in user --}}
        <div class="flex-shrink-0 border-t border-white/10 p-3">
            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-white/5 text-teal-200">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                        <path d="M15 3h6v6M10 14 21 3" />
                    </svg>
                </span>
                <span>View website</span>
            </a>

            <div class="flex items-center gap-2 rounded-lg bg-white/5 p-2.5">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-brand-accent text-xs font-bold text-white">
                    {{ str(auth()->user()->name)->substr(0, 1)->upper() }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-[11px] text-slate-400">{{ auth()->user()->email }}</p>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-white/10 hover:text-white"
                            title="Sign out" aria-label="Sign out">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <path d="m16 17 5-5-5-5M21 12H9" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main column --}}
    <div class="flex min-w-0 flex-1 flex-col lg:pl-64">

        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                <button type="button" @click="sidebarOpen = true"
                        class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
                        aria-label="Open navigation">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate font-editorial text-lg font-bold text-brand-teal sm:text-xl">
                        @yield('heading', 'Dashboard')
                    </h1>
                    @hasSection('subheading')
                        <p class="truncate text-xs text-slate-500">@yield('subheading')</p>
                    @endif
                </div>

                <div class="flex flex-shrink-0 items-center gap-2">
                    @yield('header-actions')
                </div>
            </div>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mx-auto max-w-7xl">
                <x-admin.flash />
                @yield('content')
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
