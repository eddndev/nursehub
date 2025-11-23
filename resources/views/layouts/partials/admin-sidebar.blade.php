{{-- Admin Sidebar - Reutilizable para mobile y desktop --}}
<div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-slate-200 bg-white px-6 pb-4 dark:border-white/10 dark:bg-slate-900">
    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center">
        <x-nursehub-logo />
    </div>

    {{-- Navigation --}}
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            {{-- Main navigation --}}
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            <x-slot name="icon">
                                <path d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Dashboard
                        </x-sidebar-link>
                    </li>

                    <li>
                        <x-sidebar-link href="{{ route('admin.users') }}" :active="request()->routeIs('admin.users')">
                            <x-slot name="icon">
                                <path d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Usuarios y Enfermeros
                        </x-sidebar-link>
                    </li>

                    <li>
                        <x-sidebar-link href="{{ route('urgencias.admision') }}" :active="request()->routeIs('urgencias.admision')">
                            <x-slot name="icon">
                                <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                <path d="M21 10.5h.375c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125H21M4.125 12H4.5a1.125 1.125 0 0 0 1.125-1.125v-2.25A1.125 1.125 0 0 0 4.5 7.5h-.375m15 0H20.5a1.125 1.125 0 0 0-1.125 1.125v2.25c0 .621.504 1.125 1.125 1.125h.375" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Admisión Urgencias
                        </x-sidebar-link>
                    </li>

                    <li>
                        <x-sidebar-link href="{{ route('enfermeria.pacientes') }}" :active="request()->routeIs('enfermeria.pacientes') || request()->routeIs('enfermeria.expediente')">
                            <x-slot name="icon">
                                <path d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Pacientes
                        </x-sidebar-link>
                    </li>

                    <li>
                        <x-sidebar-link href="#">
                            <x-slot name="icon">
                                <path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Turnos
                        </x-sidebar-link>
                    </li>

                    <li>
                        <x-sidebar-link href="#">
                            <x-slot name="icon">
                                <path d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6 6h.008v.008H6V6Z" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Medicamentos
                        </x-sidebar-link>
                    </li>

                    <li>
                        <x-sidebar-link href="#">
                            <x-slot name="icon">
                                <path d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Capacitaciones
                        </x-sidebar-link>
                    </li>

                    <li>
                        <x-sidebar-link href="#">
                            <x-slot name="icon">
                                <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Reportes
                        </x-sidebar-link>
                    </li>
                </ul>
            </li>

            {{-- Hospital Configuration section --}}
            <li>
                <div class="text-xs/6 font-semibold text-slate-400 dark:text-slate-500">Configuración del Hospital</div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li>
                        <x-sidebar-link href="{{ route('hospital.map') }}" :active="request()->routeIs('hospital.map')">
                            <x-slot name="icon">
                                <path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Mapa del Hospital
                        </x-sidebar-link>
                    </li>
                    <li>
                        <x-sidebar-link href="{{ route('admin.areas') }}" :active="request()->routeIs('admin.areas')">
                            <x-slot name="icon">
                                <path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Áreas
                        </x-sidebar-link>
                    </li>
                    <li>
                        <x-sidebar-link href="{{ route('admin.pisos') }}" :active="request()->routeIs('admin.pisos')">
                            <x-slot name="icon">
                                <path d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Pisos
                        </x-sidebar-link>
                    </li>
                    <li>
                        <x-sidebar-link href="{{ route('admin.cuartos') }}" :active="request()->routeIs('admin.cuartos')">
                            <x-slot name="icon">
                                <path d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Cuartos
                        </x-sidebar-link>
                    </li>
                    <li>
                        <x-sidebar-link href="{{ route('admin.camas') }}" :active="request()->routeIs('admin.camas')">
                            <x-slot name="icon">
                                <path d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" stroke-linecap="round" stroke-linejoin="round" />
                            </x-slot>
                            Camas
                        </x-sidebar-link>
                    </li>
                </ul>
            </li>

            {{-- Settings at bottom --}}
            <li class="mt-auto">
                <x-sidebar-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')">
                    <x-slot name="icon">
                        <path d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke-linecap="round" stroke-linejoin="round" />
                    </x-slot>
                    Configuración
                </x-sidebar-link>
            </li>
        </ul>
    </nav>
</div>
