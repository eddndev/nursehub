<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">Control de Líquidos ({{ $fecha }}) - Turno: {{ $turno }}</h3>
        <span class="text-sm text-gray-500">Paciente ID: {{ $pacienteId }}</span>
    </div>

    <!-- Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h4 class="text-blue-800 font-semibold">Total Ingresos</h4>
            <p class="text-2xl font-bold text-blue-600">{{ $totalIngresos }} ml</p>
        </div>
        <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
            <h4 class="text-orange-800 font-semibold">Total Egresos</h4>
            <p class="text-2xl font-bold text-orange-600">{{ $totalEgresos }} ml</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h4 class="text-gray-800 font-semibold">Balance Acumulado</h4>
            <p class="text-2xl font-bold {{ $balance > 0 ? 'text-green-600' : ($balance < 0 ? 'text-red-600' : 'text-gray-600') }}">
                {{ $balance > 0 ? '+' : '' }}{{ $balance }} ml
            </p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <h4 class="text-purple-800 font-semibold">Meta de Balance (24h)</h4>
            <div class="flex gap-2 mt-1">
                <input type="number" wire:model="meta" class="w-full rounded border-purple-300 text-sm p-1" placeholder="ml">
                <button wire:click="saveMeta" class="bg-purple-600 text-white px-2 rounded text-xs">Guardar</button>
            </div>
            @if($meta)
                <p class="text-xs text-purple-600 mt-1">
                    Restante: {{ $meta - $balance }} ml
                </p>
            @endif
        </div>
    </div>

    <!-- Formulario -->
    <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <h4 class="font-semibold mb-4">Nuevo Registro</h4>
        <form wire:submit.prevent="registrar" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                <select wire:model.live="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="ingreso">Ingreso</option>
                    <option value="egreso">Egreso</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Vía / Origen</label>
                <select wire:model="via" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Seleccione...</option>
                    @if($tipo === 'ingreso')
                        @foreach($viasIngreso as $v)
                            <option value="{{ $v->value }}">{{ $v->getLabel() }}</option>
                        @endforeach
                    @else
                        @foreach($viasEgreso as $v)
                            <option value="{{ $v->value }}">{{ $v->getLabel() }}</option>
                        @endforeach
                    @endif
                </select>
                @error('via') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Solución / Detalle</label>
                <input type="text" wire:model="solucion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Ej. Salina 0.9%">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Volumen (ml)</label>
                <input type="number" wire:model="volumen_ml" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('volumen_ml') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Registrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla de Registros -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vía</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volumen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Turno</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($registros as $registro)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registro->fecha_hora->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $registro->tipo === \App\Enums\TipoBalance::INGRESO ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $registro->tipo->getLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registro->via->getLabel() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registro->solucion ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                            {{ $registro->volumen_ml }} ml
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registro->turno }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($registro->registrado_por == auth()->id())
                                <button wire:click="delete({{ $registro->id }})" class="text-red-600 hover:text-red-900" onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()">Eliminar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay registros para hoy.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
