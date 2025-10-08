@props(['href' => '#', 'initial', 'active' => false])

<a
    href="{{ $href }}"
    @class([
        'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold transition-colors duration-150',
        'text-slate-700 hover:bg-slate-50 hover:text-blue-600 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white'
    ])
>
    {{-- Inicial en círculo --}}
    <span
        @class([
            'flex size-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium transition-colors duration-150',
            'border-slate-200 bg-white text-slate-400 group-hover:border-blue-600 group-hover:text-blue-600',
            'dark:border-white/10 dark:bg-white/5 dark:group-hover:border-white/20 dark:group-hover:text-white'
        ])
    >
        {{ $initial }}
    </span>

    {{-- Nombre del equipo --}}
    <span class="truncate">{{ $slot }}</span>
</a>
