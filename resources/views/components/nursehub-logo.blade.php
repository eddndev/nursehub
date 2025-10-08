{{-- NurseHub Logo Component --}}
@props(['class' => 'h-8 w-auto'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 ' . $class]) }}>
    {{-- Icono médico (cruz + corazón) --}}
    <svg viewBox="0 0 40 40" fill="none" class="h-8 w-8">
        {{-- Modo claro --}}
        <g class="dark:hidden">
            <rect width="40" height="40" rx="8" fill="#3b82f6"/>
            <path d="M20 10v20M10 20h20" stroke="white" stroke-width="3" stroke-linecap="round"/>
            <circle cx="20" cy="20" r="6" fill="white" fill-opacity="0.3"/>
        </g>
        {{-- Modo oscuro --}}
        <g class="hidden dark:block">
            <rect width="40" height="40" rx="8" fill="#2563eb"/>
            <path d="M20 10v20M10 20h20" stroke="white" stroke-width="3" stroke-linecap="round"/>
            <circle cx="20" cy="20" r="6" fill="white" fill-opacity="0.4"/>
        </g>
    </svg>

    {{-- Texto del logo --}}
    <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">
        Nurse<span class="text-blue-600 dark:text-blue-400">Hub</span>
    </span>
</div>
