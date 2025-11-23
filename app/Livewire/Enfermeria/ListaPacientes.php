<?php

namespace App\Livewire\Enfermeria;

use App\Enums\NivelTriage;
use App\Enums\PacienteEstado;
use App\Models\Paciente;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListaPacientes extends Component
{
    use WithPagination;

    #[Url(as: 'buscar')]
    public $search = '';

    #[Url(as: 'triage')]
    public $filtroTriage = '';

    #[Url(as: 'estado')]
    public $filtroEstado = 'activo';

    public $perPage = 20;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroTriage()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'filtroTriage', 'filtroEstado']);
        $this->filtroEstado = 'activo';
        $this->resetPage();
    }

    public function render()
    {
        $query = Paciente::query()
            ->with([
                'camaActual.cuarto.piso.area',
                'registrosSignosVitales' => function ($q) {
                    $q->latest('fecha_registro')->limit(1);
                },
                'admitidoPor'
            ]);

        // Filtro por estado
        if ($this->filtroEstado && $this->filtroEstado !== 'todos') {
            $query->where('estado', $this->filtroEstado);
        }

        // Búsqueda por nombre, CURP o código QR
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido_paterno', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido_materno', 'like', '%' . $this->search . '%')
                  ->orWhere('curp', 'like', '%' . $this->search . '%')
                  ->orWhere('codigo_qr', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por nivel de TRIAGE
        if ($this->filtroTriage) {
            $query->whereHas('registrosSignosVitales', function ($q) {
                $q->where('nivel_triage', $this->filtroTriage)
                  ->whereRaw('id IN (
                      SELECT MAX(id)
                      FROM registros_signos_vitales
                      GROUP BY paciente_id
                  )');
            });
        }

        // Ordenamiento: primero por TRIAGE (críticos primero), luego por fecha de admisión
        $pacientes = $query->get()->sortBy(function ($paciente) {
            $ultimoRegistro = $paciente->registrosSignosVitales->first();
            $prioridad = $ultimoRegistro?->nivel_triage?->getPrioridad() ?? 999;
            return $prioridad;
        })->values();

        // Paginación manual
        $currentPage = $this->getPage();
        $items = $pacientes->forPage($currentPage, $this->perPage);
        $total = $pacientes->count();

        $pacientesPaginados = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $this->perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        // Estadísticas
        $stats = [
            'total' => Paciente::count(),
            'activos' => Paciente::where('estado', PacienteEstado::ACTIVO)->count(),
            'por_triage' => [
                'rojo' => 0,
                'naranja' => 0,
                'amarillo' => 0,
                'verde' => 0,
                'azul' => 0,
            ],
        ];

        foreach (Paciente::activos()->with('registrosSignosVitales')->get() as $paciente) {
            $ultimoRegistro = $paciente->registrosSignosVitales()->latest('fecha_registro')->first();
            if ($ultimoRegistro && $ultimoRegistro->nivel_triage) {
                $nivel = $ultimoRegistro->nivel_triage->value;
                if (isset($stats['por_triage'][$nivel])) {
                    $stats['por_triage'][$nivel]++;
                }
            }
        }

        return view('livewire.enfermeria.lista-pacientes', [
            'pacientes' => $pacientesPaginados,
            'stats' => $stats,
            'nivelesTriage' => NivelTriage::cases(),
            'estadosPaciente' => PacienteEstado::cases(),
        ])->layout('layouts.admin');
    }
}
