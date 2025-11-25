<x-admin-layout>
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Mi Perfil</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Gestiona tu información personal y seguridad de cuenta</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        {{-- Update Profile Information --}}
        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Información del Perfil
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Actualiza tu nombre y correo electrónico de la cuenta.
                </p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password --}}
        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Actualizar Contraseña
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Asegúrate de usar una contraseña segura y única para tu cuenta.
                </p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-red-200 dark:border-red-900/50 overflow-hidden">
            <div class="px-6 py-4 border-b border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-900/20">
                <h2 class="text-lg font-semibold text-red-900 dark:text-red-200">
                    Zona de Peligro
                </h2>
                <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                    Una vez eliminada tu cuenta, todos los recursos y datos serán eliminados permanentemente.
                </p>
            </div>
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-admin-layout>
