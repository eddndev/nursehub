<div class="p-6 bg-white rounded-lg shadow-lg">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Escalas de Valoración</h3>

    <!-- Selector de Escala -->
    <div class="mb-6">
        <div class="flex space-x-4 border-b border-gray-200">
            <button wire:click="$set('tipoEscala', 'EVA')" class="py-2 px-4 {{ $tipoEscala === 'EVA' ? 'border-b-2 border-indigo-500 text-indigo-600 font-bold' : 'text-gray-500' }}">
                EVA (Dolor)
            </button>
            <button wire:click="$set('tipoEscala', 'GLASGOW')" class="py-2 px-4 {{ $tipoEscala === 'GLASGOW' ? 'border-b-2 border-indigo-500 text-indigo-600 font-bold' : 'text-gray-500' }}">
                Glasgow (Conciencia)
            </button>
            <button wire:click="$set('tipoEscala', 'BRADEN')" class="py-2 px-4 {{ $tipoEscala === 'BRADEN' ? 'border-b-2 border-indigo-500 text-indigo-600 font-bold' : 'text-gray-500' }}">
                Braden (UPP)
            </button>
        </div>
    </div>

    <!-- Formularios -->
    <div class="mb-8">
        @if($tipoEscala === 'EVA')
            <div class="bg-blue-50 p-6 rounded-lg">
                <label class="block text-lg font-medium text-gray-700 mb-4">Nivel de Dolor (0-10)</label>
                <input type="range" min="0" max="10" wire:model.live="evaPuntaje" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                <div class="text-center mt-4">
                    <span class="text-4xl font-bold text-blue-600">{{ $evaPuntaje }}</span>
                    <p class="text-gray-600 mt-2">
                        @if($evaPuntaje == 0) Sin Dolor
                        @elseif($evaPuntaje <= 3) Dolor Leve
                        @elseif($evaPuntaje <= 7) Dolor Moderado
                        @else Dolor Severo
                        @endif
                    </p>
                </div>
            </div>
        @elseif($tipoEscala === 'GLASGOW')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-green-50 p-6 rounded-lg">
                <div>
                    <label class="block font-medium">Apertura Ocular</label>
                    <select wire:model="glasgowOcular" class="w-full mt-1 rounded border-gray-300">
                        <option value="4">4 - Espontánea</option>
                        <option value="3">3 - A la voz</option>
                        <option value="2">2 - Al dolor</option>
                        <option value="1">1 - Ninguna</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Respuesta Verbal</label>
                    <select wire:model="glasgowVerbal" class="w-full mt-1 rounded border-gray-300">
                        <option value="5">5 - Orientado</option>
                        <option value="4">4 - Confuso</option>
                        <option value="3">3 - Inapropiada</option>
                        <option value="2">2 - Incomprensible</option>
                        <option value="1">1 - Ninguna</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Respuesta Motora</label>
                    <select wire:model="glasgowMotora" class="w-full mt-1 rounded border-gray-300">
                        <option value="6">6 - Obedece órdenes</option>
                        <option value="5">5 - Localiza dolor</option>
                        <option value="4">4 - Retirada al dolor</option>
                        <option value="3">3 - Flexión anormal</option>
                        <option value="2">2 - Extensión anormal</option>
                        <option value="1">1 - Ninguna</option>
                    </select>
                </div>
                <div class="col-span-3 text-center mt-4">
                    <p class="text-lg font-bold">Total: {{ $glasgowOcular + $glasgowVerbal + $glasgowMotora }} / 15</p>
                </div>
            </div>
        @elseif($tipoEscala === 'BRADEN')
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-orange-50 p-6 rounded-lg">
                <!-- Simplificado para demostración -->
                <div>
                    <label class="block font-medium">Percepción Sensorial</label>
                    <select wire:model="bradenPercepcion" class="w-full mt-1 rounded border-gray-300">
                        <option value="4">4 - Sin alteraciones</option>
                        <option value="3">3 - Ligeramente limitada</option>
                        <option value="2">2 - Muy limitada</option>
                        <option value="1">1 - Completamente limitada</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Exposición a la Humedad</label>
                    <select wire:model="bradenExposicion" class="w-full mt-1 rounded border-gray-300">
                        <option value="4">4 - Raramente húmeda</option>
                        <option value="3">3 - Ocasionalmente húmeda</option>
                        <option value="2">2 - A menudo húmeda</option>
                        <option value="1">1 - Constantemente húmeda</option>
                    </select>
                </div>
                <!-- ... otros campos simplificados ... -->
                <div class="col-span-2 text-center mt-4 text-sm text-gray-500">
                    * Formulario simplificado para el prototipo (solo muestra algunos campos)
                </div>
             </div>
        @endif

        <div class="mt-6">
            <button wire:click="guardar" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded shadow">
                Guardar Valoración
            </button>
        </div>
    </div>

    <!-- Histórico Reciente -->
    <h4 class="font-semibold text-gray-700 mb-2">Histórico Reciente</h4>
    <ul class="divide-y divide-gray-200">
        @forelse($historico as $val)
            <li class="py-3 flex justify-between items-center">
                <div>
                    <span class="font-bold text-gray-800">{{ $val->tipo_escala }}</span>
                    <span class="text-sm text-gray-500 ml-2">{{ $val->fecha_hora->diffForHumans() }}</span>
                </div>
                <div class="text-right">
                    <span class="block font-bold text-indigo-600">{{ $val->puntaje_total }} pts</span>
                    <span class="text-xs text-gray-500">{{ $val->riesgo_interpretado }}</span>
                </div>
            </li>
        @empty
            <li class="py-3 text-gray-500 text-sm">No hay valoraciones recientes.</li>
        @endforelse
    </ul>
</div>
