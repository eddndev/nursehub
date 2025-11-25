<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NurseHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased">
        <div class="min-h-full flex">
            {{-- Panel izquierdo - Branding --}}
            <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-between bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-12 relative overflow-hidden">
                {{-- Patrón decorativo --}}
                <div class="absolute inset-0 opacity-10">
                    <svg class="absolute top-0 left-0 w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs>
                            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid)"/>
                    </svg>
                </div>

                {{-- Círculos decorativos --}}
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>

                {{-- Logo y título --}}
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg viewBox="0 0 40 40" fill="none" class="h-10 w-10">
                                <rect width="40" height="40" rx="8" fill="white"/>
                                <path d="M20 10v20M10 20h20" stroke="#3b82f6" stroke-width="3" stroke-linecap="round"/>
                                <circle cx="20" cy="20" r="6" fill="#3b82f6" fill-opacity="0.2"/>
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-white tracking-tight">
                            Nurse<span class="text-blue-200">Hub</span>
                        </span>
                    </div>
                    <p class="mt-3 text-blue-100 text-lg">Sistema de Gestión Hospitalaria de Enfermería</p>
                </div>

                {{-- Contenido central --}}
                <div class="relative z-10 space-y-8">
                    <div class="space-y-6">
                        <h1 class="text-4xl font-bold text-white leading-tight">
                            Gestiona tu equipo de enfermería de manera eficiente
                        </h1>
                        <p class="text-xl text-blue-100">
                            Optimiza turnos, registro clínico, capacitaciones y farmacia en una sola plataforma.
                        </p>
                    </div>

                    {{-- Features --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 text-white/90">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Registro Clínico</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Gestión de Turnos</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Farmacia</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Capacitaciones</span>
                        </div>
                    </div>
                </div>

                {{-- Footer del panel --}}
                <div class="relative z-10 text-blue-200 text-sm">
                    <p>&copy; {{ date('Y') }} NurseHub. Todos los derechos reservados.</p>
                </div>
            </div>

            {{-- Panel derecho - Formulario --}}
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 bg-white dark:bg-slate-900">
                <div class="mx-auto w-full max-w-sm lg:max-w-md">
                    {{-- Logo móvil --}}
                    <div class="lg:hidden mb-10 text-center">
                        <div class="inline-flex items-center gap-3">
                            <div class="p-2 bg-blue-600 rounded-xl">
                                <svg viewBox="0 0 40 40" fill="none" class="h-8 w-8">
                                    <rect width="40" height="40" rx="8" fill="#3b82f6"/>
                                    <path d="M20 10v20M10 20h20" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                    <circle cx="20" cy="20" r="6" fill="white" fill-opacity="0.3"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                                Nurse<span class="text-blue-600 dark:text-blue-400">Hub</span>
                            </span>
                        </div>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
