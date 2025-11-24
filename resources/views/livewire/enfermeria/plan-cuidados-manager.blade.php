<div class="p-6 bg-white rounded-lg shadow-lg min-h-[500px]">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">Planes de Cuidado</h3>
        <button wire:click="toggleCreateForm" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded">
            {{ $showCreateForm ? 'Cancelar' : 'Nuevo Plan' }}
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Lista de Planes -->
        <div class="lg:col-span-1 border-r border-gray-200 pr-4">
            @if($showCreateForm)
                <div class="bg-indigo-50 p-4 rounded-lg mb-4">
                    <h4 class="font-bold text-indigo-800 mb-2">Crear Nuevo Plan</h4>
                    <form wire:submit.prevent="createPlan">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Diagnóstico</label>
                            <select wire:model="selectedDiagnosticoId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Seleccione...</option>
                                @foreach($diagnosticos as $d)
                                    <option value="{{ $d->id }}">{{ $d->codigo }} - {{ Str::limit($d->descripcion, 40) }}</option>
                                @endforeach
                            </select>
                            @if($diagnosticos->isEmpty())
                                <p class="text-xs text-red-500 mt-1">No hay diagnósticos en el catálogo. Contacte admin.</p>
                            @endif
                            @error('selectedDiagnosticoId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white text-sm py-2 rounded">Crear Plan</button>
                    </form>
                </div>
            @endif

            <h4 class="text-gray-500 uppercase text-xs font-bold mb-3">Planes Activos</h4>
            <div class="space-y-2">
                @forelse($planes as $plan)
                    <div wire:click="selectPlan({{ $plan->id }})" 
                         class="cursor-pointer p-3 rounded-lg transition {{ $activePlanId === $plan->id ? 'bg-indigo-100 border-l-4 border-indigo-600' : 'bg-gray-50 hover:bg-gray-100' }}">
                        <p class="text-sm font-bold text-gray-800">{{ $plan->diagnostico->codigo ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-600 truncate">{{ $plan->diagnostico->descripcion ?? 'Sin descripción' }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-400">{{ $plan->fecha_inicio->format('d/m/Y') }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $plan->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-200' }}">
                                {{ ucfirst($plan->estado) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic">No hay planes registrados.</p>
                @endforelse
            </div>
        </div>

        <!-- Columna Derecha: Detalle del Plan -->
        <div class="lg:col-span-2 pl-4">
            @if($activePlan)
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800">{{ $activePlan->diagnostico->descripcion }}</h2>
                    <p class="text-sm text-gray-500">Código: {{ $activePlan->diagnostico->codigo }} | Dominio: {{ $activePlan->diagnostico->dominio ?? '-' }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-6">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                        <h4 class="font-bold text-gray-700">Intervenciones (NIC)</h4>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($activePlan->intervenciones as $intervencion)
                            <div class="p-4 flex items-start space-x-3 {{ $intervencion->realizado ? 'bg-green-50' : '' }}">
                                <input type="checkbox" 
                                       wire:click="toggleIntervencion({{ $intervencion->id }})"
                                       {{ $intervencion->realizado ? 'checked' : '' }}
                                       class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 {{ $intervencion->realizado ? 'line-through text-gray-500' : '' }}">
                                        {{ $intervencion->descripcion }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $intervencion->frecuencia }}</p>
                                    @if($intervencion->realizado)
                                        <p class="text-xs text-green-600 mt-1">
                                            Realizado: {{ $intervencion->realizado_at->format('H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-sm text-gray-500">No hay intervenciones asignadas aún.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Agregar Intervención -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h5 class="text-sm font-bold text-gray-700 mb-3">Agregar Intervención</h5>
                    <div class="flex gap-2">
                        <input type="text" wire:model="newIntervencionDesc" placeholder="Descripción de la intervención" class="flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                        <input type="text" wire:model="newIntervencionFrec" placeholder="Frecuencia (ej: 8h)" class="w-24 rounded-md border-gray-300 shadow-sm text-sm">
                        <button wire:click="addIntervencion" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-bold">
                            +
                        </button>
                    </div>
                    @error('newIntervencionDesc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

            @else
                <div class="flex items-center justify-center h-full text-gray-400">
                    <p>Seleccione un plan para ver detalles o cree uno nuevo.</p>
                </div>
            @endif
        </div>
    </div>
</div>
