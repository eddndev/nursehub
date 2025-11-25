<?php

namespace App\Livewire\Medicamentos;

use App\Models\Medicamento;
use App\Models\InteraccionMedicamentosa;
use App\Enums\CategoriaMedicamento;
use App\Enums\ViaAdministracionMedicamento;
use App\Enums\SeveridadInteraccion;
use App\Services\InteraccionMedicamentosaService;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogoMedicamentos extends Component
{
    use WithPagination;

    // Filtros
    public $busqueda = '';
    public $categoriaFiltro = '';
    public $viaAdministracionFiltro = '';
    public $soloControlados = false;
    public $soloActivos = true;

    // Modal crear/editar
    public $modalAbierto = false;
    public $medicamentoId = null;
    public $codigo_medicamento = '';
    public $nombre_comercial = '';
    public $nombre_generico = '';
    public $principio_activo = '';
    public $laboratorio = '';
    public $presentacion = '';
    public $concentracion = '';
    public $via_administracion = '';
    public $categoria = '';
    public $es_controlado = false;
    public $precio_unitario = '';
    public $indicaciones = '';
    public $contraindicaciones = '';
    public $efectos_adversos = '';
    public $dosis_maxima_24h = '';
    public $unidad_dosis_maxima = 'mg';
    public $requiere_refrigeracion = false;
    public $activo = true;

    // Modal interacciones
    public $modalInteraccionesAbierto = false;
    public $medicamentoInteraccionId = null;
    public $medicamento_a_id = null;
    public $medicamento_b_id = null;
    public $severidad = '';
    public $descripcion_interaccion = '';
    public $recomendacion = '';
    public $fuente_referencia = '';

    protected $interaccionService;

    public function boot(InteraccionMedicamentosaService $service)
    {
        $this->interaccionService = $service;
    }

    protected $rules = [
        'codigo_medicamento' => 'required|string|max:50|unique:medicamentos,codigo_medicamento',
        'nombre_comercial' => 'required|string|max:255',
        'nombre_generico' => 'required|string|max:255',
        'principio_activo' => 'required|string|max:255',
        'laboratorio' => 'nullable|string|max:255',
        'presentacion' => 'required|string|max:100',
        'concentracion' => 'required|string|max:100',
        'via_administracion' => 'required',
        'categoria' => 'required',
        'precio_unitario' => 'nullable|numeric|min:0',
        'dosis_maxima_24h' => 'nullable|numeric|min:0',
        'unidad_dosis_maxima' => 'nullable|string|max:20',
    ];

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function updatingCategoriaFiltro()
    {
        $this->resetPage();
    }

    public function render()
    {
        $medicamentos = Medicamento::query()
            ->when($this->busqueda, function ($query) {
                $query->buscar($this->busqueda);
            })
            ->when($this->categoriaFiltro, function ($query) {
                $query->where('categoria', $this->categoriaFiltro);
            })
            ->when($this->viaAdministracionFiltro, function ($query) {
                $query->where('via_administracion', $this->viaAdministracionFiltro);
            })
            ->when($this->soloControlados, function ($query) {
                $query->where('es_controlado', true);
            })
            ->when($this->soloActivos, function ($query) {
                $query->where('activo', true);
            })
            ->orderBy('nombre_comercial')
            ->paginate(15);

        return view('livewire.medicamentos.catalogo-medicamentos', [
            'medicamentos' => $medicamentos,
            'categorias' => CategoriaMedicamento::cases(),
            'viasAdministracion' => ViaAdministracionMedicamento::cases(),
            'severidades' => SeveridadInteraccion::cases(),
        ]);
    }

    public function abrirModal($medicamentoId = null)
    {
        $this->resetValidation();
        $this->medicamentoId = $medicamentoId;

        if ($medicamentoId) {
            $medicamento = Medicamento::findOrFail($medicamentoId);
            $this->fill($medicamento->toArray());
        } else {
            $this->reset([
                'codigo_medicamento',
                'nombre_comercial',
                'nombre_generico',
                'principio_activo',
                'laboratorio',
                'presentacion',
                'concentracion',
                'via_administracion',
                'categoria',
                'es_controlado',
                'precio_unitario',
                'indicaciones',
                'contraindicaciones',
                'efectos_adversos',
                'dosis_maxima_24h',
                'unidad_dosis_maxima',
                'requiere_refrigeracion',
                'activo',
            ]);
            $this->activo = true;
            $this->unidad_dosis_maxima = 'mg';
        }

        $this->modalAbierto = true;
    }

    public function guardar()
    {
        $rules = $this->rules;

        if ($this->medicamentoId) {
            $rules['codigo_medicamento'] = 'required|string|max:50|unique:medicamentos,codigo_medicamento,' . $this->medicamentoId;
        }

        $this->validate($rules);

        $datos = [
            'codigo_medicamento' => $this->codigo_medicamento,
            'nombre_comercial' => $this->nombre_comercial,
            'nombre_generico' => $this->nombre_generico,
            'principio_activo' => $this->principio_activo,
            'laboratorio' => $this->laboratorio,
            'presentacion' => $this->presentacion,
            'concentracion' => $this->concentracion,
            'via_administracion' => $this->via_administracion,
            'categoria' => $this->categoria,
            'es_controlado' => $this->es_controlado,
            'precio_unitario' => $this->precio_unitario,
            'indicaciones' => $this->indicaciones,
            'contraindicaciones' => $this->contraindicaciones,
            'efectos_adversos' => $this->efectos_adversos,
            'dosis_maxima_24h' => $this->dosis_maxima_24h,
            'unidad_dosis_maxima' => $this->unidad_dosis_maxima,
            'requiere_refrigeracion' => $this->requiere_refrigeracion,
            'activo' => $this->activo,
        ];

        if ($this->medicamentoId) {
            $medicamento = Medicamento::findOrFail($this->medicamentoId);
            $medicamento->update($datos);
            session()->flash('message', 'Medicamento actualizado exitosamente.');
        } else {
            Medicamento::create($datos);
            session()->flash('message', 'Medicamento creado exitosamente.');
        }

        $this->modalAbierto = false;
        $this->reset(['medicamentoId']);
    }

    public function toggleActivo($medicamentoId)
    {
        $medicamento = Medicamento::findOrFail($medicamentoId);
        $medicamento->update(['activo' => !$medicamento->activo]);

        session()->flash('message', 'Estado del medicamento actualizado.');
    }

    public function abrirModalInteracciones($medicamentoId)
    {
        $this->medicamentoInteraccionId = $medicamentoId;
        $this->medicamento_a_id = $medicamentoId;
        $this->modalInteraccionesAbierto = true;
    }

    public function guardarInteraccion()
    {
        $this->validate([
            'medicamento_a_id' => 'required|exists:medicamentos,id',
            'medicamento_b_id' => 'required|exists:medicamentos,id|different:medicamento_a_id',
            'severidad' => 'required',
            'descripcion_interaccion' => 'required|string',
            'recomendacion' => 'nullable|string',
            'fuente_referencia' => 'nullable|string',
        ]);

        $this->interaccionService->registrarInteraccion(
            $this->medicamento_a_id,
            $this->medicamento_b_id,
            SeveridadInteraccion::from($this->severidad),
            $this->descripcion_interaccion,
            $this->recomendacion,
            $this->fuente_referencia
        );

        session()->flash('message', 'Interacción medicamentosa registrada.');

        $this->reset(['medicamento_b_id', 'severidad', 'descripcion_interaccion', 'recomendacion', 'fuente_referencia']);
    }

    public function cerrarModal()
    {
        $this->modalAbierto = false;
        $this->modalInteraccionesAbierto = false;
        $this->reset(['medicamentoId', 'medicamentoInteraccionId']);
    }
}
