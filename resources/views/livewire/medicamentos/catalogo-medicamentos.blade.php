<div class="p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Catálogo de Medicamentos</h1>
                <p class="text-gray-600 mt-1">Gestiona el catálogo maestro de medicamentos del hospital</p>
            </div>
            <button wire:click="abrirModal" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Nuevo Medicamento
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md flex justify-between items-center">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live="busqueda" placeholder="Buscar medicamento..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select wire:model.live="categoriaFiltro" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->value }}">{{ $categoria->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="viaAdministracionFiltro" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todas las vías</option>
                    @foreach ($viasAdministracion as $via)
                        <option value="{{ $via->value }}">{{ $via->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="flex items-center space-x-2 px-3 py-2">
                    <input type="checkbox" wire:model.live="soloControlados" class="rounded">
                    <span class="text-sm text-gray-700">Solo Controlados</span>
                </label>
            </div>
            <div>
                <label class="flex items-center space-x-2 px-3 py-2">
                    <input type="checkbox" wire:model.live="soloActivos" class="rounded">
                    <span class="text-sm text-gray-700">Solo Activos</span>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Presentación</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Controlado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($medicamentos as $medicamento)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $medicamento->codigo_medicamento }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $medicamento->nombre_comercial }}</div>
                            <div class="text-sm text-gray-500">{{ $medicamento->nombre_generico }}</div>
                            <div class="text-xs text-gray-400">{{ $medicamento->principio_activo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                style="background-color: {{ $medicamento->categoria->getColor() }}20; color: {{ $medicamento->categoria->getColor() }};">
                                {{ $medicamento->categoria->getLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $medicamento->presentacion }}</div>
                            <div class="text-xs text-gray-500">{{ $medicamento->concentracion }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if ($medicamento->es_controlado)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-lock mr-1"></i> Sí
                                </span>
                            @else
                                <span class="text-xs text-gray-400">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:click="toggleActivo({{ $medicamento->id }})"
                                    {{ $medicamento->activo ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="abrirModal({{ $medicamento->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="abrirModalInteracciones({{ $medicamento->id }})"
                                class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-pills text-4xl mb-2"></i>
                            <p>No se encontraron medicamentos</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $medicamentos->links() }}
        </div>
    </div>

    @if ($modalAbierto)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium">{{ $medicamentoId ? 'Editar' : 'Nuevo' }} Medicamento</h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-8">
                            <button onclick="showTab('basica')" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Información Básica
                            </button>
                            <button onclick="showTab('clinica')" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Información Clínica
                            </button>
                            <button onclick="showTab('dosificacion')" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Dosificación
                            </button>
                        </nav>
                    </div>

                    <div id="tab-basica" class="tab-content">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Código Medicamento *</label>
                                <input type="text" wire:model="codigo_medicamento" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('codigo_medicamento') border-red-500 @enderror">
                                @error('codigo_medicamento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Comercial *</label>
                                <input type="text" wire:model="nombre_comercial" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('nombre_comercial') border-red-500 @enderror">
                                @error('nombre_comercial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Genérico *</label>
                                <input type="text" wire:model="nombre_generico" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('nombre_generico') border-red-500 @enderror">
                                @error('nombre_generico') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Principio Activo *</label>
                                <input type="text" wire:model="principio_activo" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('principio_activo') border-red-500 @enderror">
                                @error('principio_activo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Laboratorio</label>
                                <input type="text" wire:model="laboratorio" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Presentación *</label>
                                <input type="text" wire:model="presentacion" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('presentacion') border-red-500 @enderror" placeholder="Ej: Tableta">
                                @error('presentacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Concentración *</label>
                                <input type="text" wire:model="concentracion" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('concentracion') border-red-500 @enderror" placeholder="Ej: 500mg">
                                @error('concentracion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Precio Unitario</label>
                                <input type="number" step="0.01" wire:model="precio_unitario" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Vía de Administración *</label>
                                <select wire:model="via_administracion" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('via_administracion') border-red-500 @enderror">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($viasAdministracion as $via)
                                        <option value="{{ $via->value }}">{{ $via->getLabel() }}</option>
                                    @endforeach
                                </select>
                                @error('via_administracion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                                <select wire:model="categoria" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('categoria') border-red-500 @enderror">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($categorias as $cat)
                                        <option value="{{ $cat->value }}">{{ $cat->getLabel() }}</option>
                                    @endforeach
                                </select>
                                @error('categoria') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Opciones</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="es_controlado" class="rounded">
                                        <span class="ml-2 text-sm text-gray-700">Medicamento Controlado</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="requiere_refrigeracion" class="rounded">
                                        <span class="ml-2 text-sm text-gray-700">Requiere Refrigeración</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="activo" class="rounded">
                                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-clinica" class="tab-content hidden">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Indicaciones</label>
                                <textarea wire:model="indicaciones" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contraindicaciones</label>
                                <textarea wire:model="contraindicaciones" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Efectos Adversos</label>
                                <textarea wire:model="efectos_adversos" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                            </div>
                        </div>
                    </div>

                    <div id="tab-dosificacion" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dosis Máxima en 24h</label>
                                <input type="number" step="0.01" wire:model="dosis_maxima_24h" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unidad de Dosis</label>
                                <input type="text" wire:model="unidad_dosis_maxima" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="mg, ml, UI">
                            </div>
                            <div class="md:col-span-2">
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <p class="text-sm text-blue-800">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        La dosis máxima en 24 horas es una medida de seguridad para prevenir sobredosis.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="guardar" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ $medicamentoId ? 'Actualizar' : 'Crear' }} Medicamento
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($modalInteraccionesAbierto)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium">Registrar Interacción Medicamentosa</h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento A</label>
                            <input type="text" value="{{ \App\Models\Medicamento::find($medicamento_a_id)?->nombre_comercial }}" readonly class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento B *</label>
                            <select wire:model="medicamento_b_id" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('medicamento_b_id') border-red-500 @enderror">
                                <option value="">Seleccionar medicamento...</option>
                                @foreach (\App\Models\Medicamento::where('id', '!=', $medicamento_a_id)->where('activo', true)->orderBy('nombre_comercial')->get() as $med)
                                    <option value="{{ $med->id }}">{{ $med->nombre_comercial }}</option>
                                @endforeach
                            </select>
                            @error('medicamento_b_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Severidad *</label>
                        <select wire:model="severidad" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('severidad') border-red-500 @enderror">
                            <option value="">Seleccionar severidad...</option>
                            @foreach ($severidades as $sev)
                                <option value="{{ $sev->value }}">{{ $sev->getLabel() }} - {{ $sev->getDescripcion() }}</option>
                            @endforeach
                        </select>
                        @error('severidad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la Interacción *</label>
                        <textarea wire:model="descripcion_interaccion" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('descripcion_interaccion') border-red-500 @enderror" placeholder="Describa qué ocurre cuando estos medicamentos se combinan..."></textarea>
                        @error('descripcion_interaccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recomendación</label>
                        <textarea wire:model="recomendacion" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Recomendaciones para el manejo..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fuente/Referencia</label>
                        <input type="text" wire:model="fuente_referencia" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Ej: Micromedex, UpToDate...">
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="guardarInteraccion" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Registrar Interacción
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    document.getElementById('tab-' + tabName).classList.remove('hidden');
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.classList.add('border-blue-500', 'text-blue-600');
}
</script>
