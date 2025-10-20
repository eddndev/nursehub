<?php

namespace App\Livewire\Admin;

use App\Models\Area;
use App\Models\Piso;
use App\Models\Cuarto;
use App\Models\Cama;
use App\Models\User;
use App\Models\Enfermero;
use App\Enums\CamaEstado;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // Estadísticas generales del hospital
        $this->stats['total_areas'] = Area::count();
        $this->stats['total_pisos'] = Piso::count();
        $this->stats['total_cuartos'] = Cuarto::count();
        $this->stats['total_camas'] = Cama::count();

        // Estadísticas de camas por estado (usando scopes del modelo)
        $this->stats['camas_libres'] = Cama::libre()->count();
        $this->stats['camas_ocupadas'] = Cama::ocupada()->count();
        $this->stats['camas_limpieza'] = Cama::where('estado', CamaEstado::EN_LIMPIEZA)->count();
        $this->stats['camas_mantenimiento'] = Cama::where('estado', CamaEstado::EN_MANTENIMIENTO)->count();

        // Porcentaje de ocupación
        $totalOperativas = $this->stats['camas_libres'] + $this->stats['camas_ocupadas'];
        $this->stats['porcentaje_ocupacion'] = $totalOperativas > 0
            ? round(($this->stats['camas_ocupadas'] / $totalOperativas) * 100, 1)
            : 0;

        // Estadísticas de personal
        $this->stats['total_usuarios'] = User::where('is_active', true)->count();
        $this->stats['total_enfermeros'] = Enfermero::count();
        $this->stats['enfermeros_fijos'] = Enfermero::where('tipo_asignacion', 'fijo')->count();
        $this->stats['enfermeros_rotativos'] = Enfermero::where('tipo_asignacion', 'rotativo')->count();

        // Top 5 áreas por cantidad de pisos
        $this->stats['top_areas'] = Area::withCount('pisos')
            ->orderBy('pisos_count', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.admin');
    }
}
