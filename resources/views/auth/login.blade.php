<x-guest-layout>
    <div class="space-y-6">
        {{-- Encabezado --}}
        <div class="text-center lg:text-left">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                Iniciar Sesión
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Ingresa tus credenciales para acceder al sistema
            </p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email Address --}}
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Correo electrónico
                </label>
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="tu@correo.com"
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white dark:placeholder-slate-500 dark:focus:ring-blue-400 dark:focus:border-blue-400"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Contraseña
                </label>
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white dark:placeholder-slate-500 dark:focus:ring-blue-400 dark:focus:border-blue-400"
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input
                        id="remember_me"
                        name="remember"
                        type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded dark:border-slate-600 dark:bg-slate-800"
                    >
                    <span class="ml-2 text-sm text-slate-600 dark:text-slate-400">Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 dark:focus:ring-offset-slate-900"
            >
                Iniciar Sesión
            </button>
        </form>

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-500 dark:text-slate-400 lg:hidden">
            &copy; {{ date('Y') }} NurseHub. Todos los derechos reservados.
        </p>
    </div>
</x-guest-layout>
