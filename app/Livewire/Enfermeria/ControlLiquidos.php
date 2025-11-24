<?php

namespace App\Livewire\Enfermeria;

use App\Enums\TipoBalance;
use App\Enums\ViaAdministracion;
use App\Models\BalanceLiquido;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ControlLiquidos extends Component
{
    public $pacienteId;
    public $fecha;
    public $turno; // 'M', 'V', 'N'

    // Form inputs
    public $tipo = 'ingreso'; // Default
    public $via;
    public $solucion;
    public $volumen_ml;
    public $observaciones;
    public $meta;

    protected $rules = [
        'tipo' => 'required',
        'via' => 'required',
        'volumen_ml' => 'required|integer|min:1',
        'solucion' => 'nullable|string',
    ];

    public function mount($pacienteId)
    {
        $this->pacienteId = $pacienteId;
        $this->fecha = Carbon::now()->format('Y-m-d');
        $this->meta = Paciente::find($pacienteId)->meta_balance_hidrico;
        $this->determineTurno();
    }

    public function saveMeta()
    {
        $this->validate(['meta' => 'nullable|integer']);
        $paciente = Paciente::find($this->pacienteId);
        $paciente->meta_balance_hidrico = $this->meta;
        $paciente->save();
        $this->dispatch('meta-saved');
    }

    public function determineTurno()
    {
        $hour = Carbon::now()->hour;
        if ($hour >= 7 && $hour < 14) {
            $this->turno = 'Matutino';
        } elseif ($hour >= 14 && $hour < 21) {
            $this->turno = 'Vespertino';
        } else {
            $this->turno = 'Nocturno';
        }
    }

    public function registrar()
    {
        $this->validate();

        BalanceLiquido::create([
            'paciente_id' => $this->pacienteId,
            'tipo' => $this->tipo,
            'via' => $this->via,
            'solucion' => $this->solucion,
            'volumen_ml' => $this->volumen_ml,
            'fecha_hora' => Carbon::now(),
            'turno' => $this->turno,
            'registrado_por' => Auth::id(),
        ]);

        $this->reset(['via', 'solucion', 'volumen_ml']);
        $this->dispatch('registro-guardado');
    }

    public function delete($id)
    {
        $registro = BalanceLiquido::find($id);
        if ($registro && $registro->registrado_por == Auth::id()) {
            $registro->delete();
        }
    }

    public function render()
    {
        $registros = BalanceLiquido::where('paciente_id', $this->pacienteId)
            ->whereDate('fecha_hora', $this->fecha)
            ->orderBy('fecha_hora', 'desc')
            ->get();

        $totalIngresos = $registros->where('tipo', TipoBalance::INGRESO)->sum('volumen_ml');
        $totalEgresos = $registros->where('tipo', TipoBalance::EGRESO)->sum('volumen_ml');
        $balance = $totalIngresos - $totalEgresos;

        return view('livewire.enfermeria.control-liquidos', [
            'registros' => $registros,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'balance' => $balance,
            'viasIngreso' => ViaAdministracion::ingresos(),
            'viasEgreso' => ViaAdministracion::egresos(),
        ]);
    }
}
