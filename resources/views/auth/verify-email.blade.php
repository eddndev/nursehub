<x-guest-layout>
    <div class="space-y-6">
        {{-- Encabezado --}}
        <div class="text-center lg:text-left">
            <div class="mx-auto lg:mx-0 w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                Verificar Correo Electrónico
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Gracias por registrarte. Antes de comenzar, por favor verifica tu correo electrónico haciendo clic en el enlace que te enviamos. Si no recibiste el correo, con gusto te enviaremos otro.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 dark:focus:ring-offset-slate-900"
                >
                    Reenviar correo de verificación
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex justify-center py-2.5 px-4 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 dark:focus:ring-offset-slate-900"
                >
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
