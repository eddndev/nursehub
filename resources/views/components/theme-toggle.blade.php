{{-- NurseHub Theme Toggle Component --}}
<div
    x-data="{ isDark: localStorage.getItem('theme') === 'dark' || (localStorage.getItem('theme') !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    class="group relative inline-flex w-11 shrink-0 rounded-full bg-slate-200 p-0.5 inset-ring inset-ring-slate-900/5 outline-offset-2 outline-blue-600 transition-colors duration-200 ease-in-out focus-within:outline-2 dark:bg-white/5 dark:inset-ring-white/10 dark:outline-blue-500 dark:focus-within:outline-blue-500"
    :class="{ 'bg-blue-600 dark:bg-blue-500': isDark }"
>
    <span
        class="relative size-5 rounded-full bg-white shadow-xs ring-1 ring-slate-900/5 transition-transform duration-200 ease-in-out"
        :class="{ 'translate-x-5': isDark }"
    >
        {{-- Sol (Light Mode) --}}
        <span
            aria-hidden="true"
            class="absolute inset-0 flex size-full items-center justify-center transition-opacity duration-200 ease-in"
            :class="isDark ? 'opacity-0 duration-100 ease-out' : 'opacity-100'"
        >
            <svg viewBox="0 0 24 24" fill="currentColor" class="size-3 text-slate-400 dark:text-slate-600">
                <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" />
            </svg>
        </span>

        {{-- Luna (Dark Mode) --}}
        <span
            aria-hidden="true"
            class="absolute inset-0 flex size-full items-center justify-center transition-opacity duration-100 ease-out"
            :class="isDark ? 'opacity-100 duration-200 ease-in' : 'opacity-0'"
        >
            <svg viewBox="0 0 24 24" fill="currentColor" class="size-3 text-blue-600 dark:text-blue-400">
                <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" />
            </svg>
        </span>
    </span>

    <button
        @click="isDark = !isDark; window.toggleTheme(isDark ? 'dark' : 'light')"
        type="button"
        aria-label="Toggle dark mode"
        class="absolute inset-0 appearance-none focus:outline-hidden"
    ></button>
</div>