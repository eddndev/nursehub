<?php

namespace App\Livewire\Enfermeria;

use App\Models\Paciente;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class GraficosTendencias extends Component
{
    public Paciente $paciente;
    public $periodo = '24h'; // 24h, 7d, 30d, todo
    public $datosGraficos = [];

    public function mount($pacienteId)
    {
        $this->paciente = Paciente::findOrFail($pacienteId);
        $this->cargarDatos();
    }

    #[On('signos-vitales-registrados')]
    public function refreshDatos()
    {
        $this->cargarDatos();
    }

    public function cambiarPeriodo($nuevoPeriodo)
    {
        $this->periodo = $nuevoPeriodo;
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $query = $this->paciente->registrosSignosVitales()
            ->orderBy('fecha_registro', 'asc');

        // Filtrar por período
        switch ($this->periodo) {
            case '24h':
                $query->where('fecha_registro', '>=', now()->subHours(24));
                break;
            case '7d':
                $query->where('fecha_registro', '>=', now()->subDays(7));
                break;
            case '30d':
                $query->where('fecha_registro', '>=', now()->subDays(30));
                break;
            case 'todo':
                // No aplicar filtro
                break;
        }

        $registros = $query->get();

        // Preparar datos para los gráficos
        $this->datosGraficos = [
            'labels' => [],
            'presion_sistolica' => [],
            'presion_diastolica' => [],
            'frecuencia_cardiaca' => [],
            'frecuencia_respiratoria' => [],
            'temperatura' => [],
            'saturacion_oxigeno' => [],
            'glucosa' => [],
            'triage_colors' => [],
            'triage_labels' => [],
        ];

        foreach ($registros as $registro) {
            // Formato de fecha según el período
            $fecha = $this->formatearFecha($registro->fecha_registro);
            $this->datosGraficos['labels'][] = $fecha;

            // Presión arterial
            if ($registro->presion_arterial) {
                [$sistolica, $diastolica] = explode('/', $registro->presion_arterial);
                $this->datosGraficos['presion_sistolica'][] = (int)$sistolica;
                $this->datosGraficos['presion_diastolica'][] = (int)$diastolica;
            } else {
                $this->datosGraficos['presion_sistolica'][] = null;
                $this->datosGraficos['presion_diastolica'][] = null;
            }

            // Otros signos vitales
            $this->datosGraficos['frecuencia_cardiaca'][] = $registro->frecuencia_cardiaca;
            $this->datosGraficos['frecuencia_respiratoria'][] = $registro->frecuencia_respiratoria;
            $this->datosGraficos['temperatura'][] = $registro->temperatura;
            $this->datosGraficos['saturacion_oxigeno'][] = $registro->saturacion_oxigeno;
            $this->datosGraficos['glucosa'][] = $registro->glucosa;

            // TRIAGE
            if ($registro->nivel_triage) {
                $this->datosGraficos['triage_colors'][] = $this->getTriageColor($registro->nivel_triage);
                $this->datosGraficos['triage_labels'][] = $registro->nivel_triage->getLabel();
            } else {
                $this->datosGraficos['triage_colors'][] = '#94a3b8';
                $this->datosGraficos['triage_labels'][] = 'Sin TRIAGE';
            }
        }

        // Calcular estadísticas
        $this->datosGraficos['estadisticas'] = $this->calcularEstadisticas($registros);
    }

    private function formatearFecha(Carbon $fecha)
    {
        switch ($this->periodo) {
            case '24h':
                return $fecha->format('H:i');
            case '7d':
                return $fecha->format('D H:i');
            case '30d':
                return $fecha->format('d/m');
            case 'todo':
                return $fecha->format('d/m/y');
            default:
                return $fecha->format('d/m H:i');
        }
    }

    private function getTriageColor($nivelTriage)
    {
        return match ($nivelTriage->value) {
            'rojo' => '#ef4444',
            'naranja' => '#f97316',
            'amarillo' => '#eab308',
            'verde' => '#22c55e',
            'azul' => '#3b82f6',
            default => '#94a3b8',
        };
    }

    private function calcularEstadisticas($registros)
    {
        if ($registros->isEmpty()) {
            return null;
        }

        $stats = [
            'total_registros' => $registros->count(),
            'presion_arterial' => [],
            'frecuencia_cardiaca' => [],
            'frecuencia_respiratoria' => [],
            'temperatura' => [],
            'saturacion_oxigeno' => [],
            'glucosa' => [],
        ];

        // Presión arterial
        $presiones = $registros->whereNotNull('presion_arterial');
        if ($presiones->isNotEmpty()) {
            $sistolicas = [];
            $diastolicas = [];
            foreach ($presiones as $r) {
                [$s, $d] = explode('/', $r->presion_arterial);
                $sistolicas[] = (int)$s;
                $diastolicas[] = (int)$d;
            }
            $stats['presion_arterial'] = [
                'promedio_sistolica' => round(array_sum($sistolicas) / count($sistolicas), 1),
                'promedio_diastolica' => round(array_sum($diastolicas) / count($diastolicas), 1),
                'max_sistolica' => max($sistolicas),
                'min_sistolica' => min($sistolicas),
                'max_diastolica' => max($diastolicas),
                'min_diastolica' => min($diastolicas),
            ];
        }

        // Frecuencia cardíaca
        $fc = $registros->whereNotNull('frecuencia_cardiaca')->pluck('frecuencia_cardiaca');
        if ($fc->isNotEmpty()) {
            $stats['frecuencia_cardiaca'] = [
                'promedio' => round($fc->avg(), 1),
                'max' => $fc->max(),
                'min' => $fc->min(),
            ];
        }

        // Frecuencia respiratoria
        $fr = $registros->whereNotNull('frecuencia_respiratoria')->pluck('frecuencia_respiratoria');
        if ($fr->isNotEmpty()) {
            $stats['frecuencia_respiratoria'] = [
                'promedio' => round($fr->avg(), 1),
                'max' => $fr->max(),
                'min' => $fr->min(),
            ];
        }

        // Temperatura
        $temp = $registros->whereNotNull('temperatura')->pluck('temperatura');
        if ($temp->isNotEmpty()) {
            $stats['temperatura'] = [
                'promedio' => round($temp->avg(), 2),
                'max' => $temp->max(),
                'min' => $temp->min(),
            ];
        }

        // Saturación de oxígeno
        $spo2 = $registros->whereNotNull('saturacion_oxigeno')->pluck('saturacion_oxigeno');
        if ($spo2->isNotEmpty()) {
            $stats['saturacion_oxigeno'] = [
                'promedio' => round($spo2->avg(), 1),
                'max' => $spo2->max(),
                'min' => $spo2->min(),
            ];
        }

        // Glucosa
        $glucosa = $registros->whereNotNull('glucosa')->pluck('glucosa');
        if ($glucosa->isNotEmpty()) {
            $stats['glucosa'] = [
                'promedio' => round($glucosa->avg(), 1),
                'max' => $glucosa->max(),
                'min' => $glucosa->min(),
            ];
        }

        return $stats;
    }

    public function render()
    {
        return view('livewire.enfermeria.graficos-tendencias');
    }
}
