<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white dark:bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NurseHub') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    @livewireStyles
</head>
<body class="h-full font-sans antialiased">
    <div class="h-full" x-data="{ sidebarOpen: false }">
        {{-- Mobile sidebar --}}
        <div
            x-show="sidebarOpen"
            @click.away="sidebarOpen = false"
            x-cloak
            class="relative z-50 lg:hidden"
            role="dialog"
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/80"
            ></div>

            <div class="fixed inset-0 flex">
                {{-- Sidebar panel --}}
                <div
                    x-show="sidebarOpen"
                    x-transition:enter="transition ease-in-out duration-300 transform"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-300 transform"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    class="relative mr-16 flex w-full max-w-xs flex-1"
                >
                    {{-- Close button --}}
                    <div
                        x-show="sidebarOpen"
                        x-transition:enter="ease-in-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in-out duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute left-full top-0 flex w-16 justify-center pt-5"
                    >
                        <button @click="sidebarOpen = false" type="button" class="-m-2.5 p-2.5">
                            <span class="sr-only">Cerrar sidebar</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 text-white">
                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    {{-- Sidebar content mobile --}}
                    @include('layouts.partials.admin-sidebar')
                </div>
            </div>
        </div>

        {{-- Static sidebar for desktop --}}
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col dark:bg-slate-900">
            @include('layouts.partials.admin-sidebar')
        </div>

        {{-- Main content area --}}
        <div class="lg:pl-72">
            {{-- Top header bar --}}
            <div class="sticky top-0 z-40 lg:mx-auto lg:max-w-7xl">
                <div class="flex h-16 items-center gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-0 lg:shadow-none dark:border-white/10 dark:bg-slate-900">
                    {{-- Mobile menu button --}}
                    <button @click="sidebarOpen = true" type="button" class="-m-2.5 p-2.5 text-slate-700 hover:text-slate-900 lg:hidden dark:text-slate-400 dark:hover:text-white">
                        <span class="sr-only">Abrir sidebar</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    {{-- Separator --}}
                    <div aria-hidden="true" class="h-6 w-px bg-slate-200 lg:hidden dark:bg-slate-700"></div>

                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        {{-- Search bar --}}
                        <form action="#" method="GET" class="grid flex-1 grid-cols-1">
                            <input
                                name="search"
                                placeholder="Buscar pacientes, enfermeros, áreas..."
                                aria-label="Buscar"
                                class="col-start-1 row-start-1 block size-full bg-white pl-8 text-base text-slate-900 outline-hidden placeholder:text-slate-400 sm:text-sm/6 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500"
                            />
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 size-5 self-center text-slate-400">
                                <path d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </form>

                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            {{-- Theme Toggle --}}
                            <x-theme-toggle />

                            {{-- Notifications --}}
                            <button type="button" class="-m-2.5 p-2.5 text-slate-400 hover:text-slate-500 dark:hover:text-white">
                                <span class="sr-only">Ver notificaciones</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                                    <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>

                            {{-- Separator --}}
                            <div aria-hidden="true" class="hidden lg:block lg:h-6 lg:w-px lg:bg-slate-200 dark:lg:bg-white/10"></div>

                            {{-- User menu dropdown --}}
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center gap-3 text-sm focus:outline-hidden">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=3b82f6&background=dbeafe" alt="{{ Auth::user()->name }}" class="size-8 rounded-full outline outline-1 -outline-offset-1 outline-black/5 dark:outline-white/10" />
                                        <span class="hidden lg:block">
                                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</span>
                                        </span>
                                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="hidden size-5 text-slate-400 lg:block">
                                            <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Perfil') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Cerrar sesión') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Page content --}}
            <main class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
