<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Cama;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class HospitalMap extends Component
{
    public $areas = [];
    public $estadisticas = [];

    // Filtros
    public $filtroArea = '';
    public $filtroEstado = '';
    public $soloDisponibles = false;

    public function mount()
    {
        $this->cargarDatos();
        $this->calcularEstadisticas();
    }

    public function cargarDatos()
    {
        $query = Area::with([
            'pisos' => function ($query) {
                $query->orderBy('numero_piso');
            },
            'pisos.cuartos' => function ($query) {
                $query->orderBy('numero_cuarto');
            },
            'pisos.cuartos.camas' => function ($query) {
                if ($this->filtroEstado) {
                    $query->where('estado', $this->filtroEstado);
                }
                if ($this->soloDisponibles) {
                    $query->where('estado', 'libre');
                }
                $query->orderBy('numero_cama');
            }
        ])->orderBy('nombre');

        if ($this->filtroArea) {
            $query->where('id', $this->filtroArea);
        }

        $this->areas = $query->get();
    }

    public function calcularEstadisticas()
    {
        $this->estadisticas = [
            'total_areas' => Area::count(),
            'total_pisos' => \App\Models\Piso::count(),
            'total_cuartos' => \App\Models\Cuarto::count(),
            'total_camas' => Cama::count(),
            'camas_libres' => Cama::where('estado', 'libre')->count(),
            'camas_ocupadas' => Cama::where('estado', 'ocupada')->count(),
            'camas_limpieza' => Cama::where('estado', 'en_limpieza')->count(),
            'camas_mantenimiento' => Cama::where('estado', 'en_mantenimiento')->count(),
        ];

        // Calcular porcentajes
        if ($this->estadisticas['total_camas'] > 0) {
            $this->estadisticas['porcentaje_ocupacion'] = round(
                ($this->estadisticas['camas_ocupadas'] / $this->estadisticas['total_camas']) * 100,
                1
            );
            $this->estadisticas['porcentaje_disponibilidad'] = round(
                ($this->estadisticas['camas_libres'] / $this->estadisticas['total_camas']) * 100,
                1
            );
        } else {
            $this->estadisticas['porcentaje_ocupacion'] = 0;
            $this->estadisticas['porcentaje_disponibilidad'] = 0;
        }
    }

    public function aplicarFiltros()
    {
        $this->cargarDatos();
    }

    public function limpiarFiltros()
    {
        $this->filtroArea = '';
        $this->filtroEstado = '';
        $this->soloDisponibles = false;
        $this->cargarDatos();
    }

    public function render()
    {
        return view('livewire.hospital-map', [
            'todasAreas' => Area::orderBy('nombre')->get(),
        ]);
    }
}