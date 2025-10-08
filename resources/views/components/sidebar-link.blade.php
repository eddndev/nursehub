@props(['href' => '#', 'icon', 'active' => false])

<a
    href="{{ $href }}"
    @class([
        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold transition-colors duration-150',
        // Estados activos - Usa blue (Medical Blue de NurseHub)
        'bg-slate-50 text-blue-600 dark:bg-white/5 dark:text-white' => $active,
        // Estados default
        'text-slate-700 hover:bg-slate-50 hover:text-blue-600 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !$active
    ])
>
    {{-- Icono Heroicons --}}
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
        aria-hidden="true"
        @class([
            'size-6 shrink-0 transition-colors duration-150',
            'text-blue-600 dark:text-white' => $active,
            'text-slate-400 group-hover:text-blue-600 dark:group-hover:text-white' => !$active
        ])
    >
        {!! $icon !!}
    </svg>

    {{-- Texto del enlace --}}
    {{ $slot }}
</a>
