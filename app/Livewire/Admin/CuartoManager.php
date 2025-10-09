<?php

namespace App\Livewire\Admin;

use App\Models\Cuarto;
use App\Models\Piso;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class CuartoManager extends Component
{
    use WithPagination;

    #[Validate('required|exists:pisos,id')]
    public $piso_id = '';

    #[Validate('required|string|max:20')]
    public $numero_cuarto = '';

    #[Validate('required|in:individual,doble,multiple')]
    public $tipo = 'individual';

    public $editingCuartoId = null;
    public $showForm = false;

    // Para listar pisos en el dropdown
    public $pisos = [];

    public function mount()
    {
        $this->loadPisos();
    }

    public function loadPisos()
    {
        $this->pisos = Piso::with('area')
            ->orderBy('numero_piso')
            ->get();
    }

    public function create()
    {
        $this->validate();

        Cuarto::create([
            'piso_id' => $this->piso_id,
            'numero_cuarto' => $this->numero_cuarto,
            'tipo' => $this->tipo,
        ]);

        session()->flash('message', 'Cuarto creado exitosamente.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $cuarto = Cuarto::findOrFail($id);
        $this->editingCuartoId = $cuarto->id;
        $this->piso_id = $cuarto->piso_id;
        $this->numero_cuarto = $cuarto->numero_cuarto;
        $this->tipo = $cuarto->tipo;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate();

        $cuarto = Cuarto::findOrFail($this->editingCuartoId);
        $cuarto->update([
            'piso_id' => $this->piso_id,
            'numero_cuarto' => $this->numero_cuarto,
            'tipo' => $this->tipo,
        ]);

        session()->flash('message', 'Cuarto actualizado exitosamente.');
        $this->resetForm();
    }

    public function delete($id)
    {
        Cuarto::findOrFail($id)->delete();
        session()->flash('message', 'Cuarto eliminado exitosamente.');
    }

    public function save()
    {
        if ($this->editingCuartoId) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['piso_id', 'numero_cuarto', 'tipo', 'editingCuartoId', 'showForm']);
        $this->resetValidation();
    }

    public function render()
    {
        $cuartos = Cuarto::with(['piso.area'])
            ->orderBy('numero_cuarto')
            ->paginate(10);

        return view('livewire.admin.cuarto-manager', [
            'cuartos' => $cuartos,
        ]);
    }
}