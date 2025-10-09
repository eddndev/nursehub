<?php

namespace App\Livewire\Admin;

use App\Models\Cama;
use App\Models\Cuarto;
use App\Enums\CamaEstado;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class CamaManager extends Component
{
    use WithPagination;

    #[Validate('required|exists:cuartos,id')]
    public $cuarto_id = '';

    #[Validate('required|string|max:10')]
    public $numero_cama = '';

    #[Validate('required|in:libre,ocupada,en_limpieza,en_mantenimiento')]
    public $estado = 'libre';

    public $editingCamaId = null;
    public $showForm = false;

    // Para filtrar por cuarto específico (opcional)
    public $filterCuartoId = null;

    // Para listar cuartos en el dropdown
    public $cuartos = [];

    public function mount($cuarto_id = null)
    {
        $this->filterCuartoId = $cuarto_id;
        $this->cuarto_id = $cuarto_id ?? '';
        $this->loadCuartos();
    }

    public function loadCuartos()
    {
        $this->cuartos = Cuarto::with(['piso.area'])
            ->orderBy('numero_cuarto')
            ->get();
    }

    public function create()
    {
        $this->validate();

        Cama::create([
            'cuarto_id' => $this->cuarto_id,
            'numero_cama' => $this->numero_cama,
            'estado' => $this->estado,
        ]);

        session()->flash('message', 'Cama creada exitosamente.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $cama = Cama::findOrFail($id);
        $this->editingCamaId = $cama->id;
        $this->cuarto_id = $cama->cuarto_id;
        $this->numero_cama = $cama->numero_cama;
        $this->estado = $cama->estado->value;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate();

        $cama = Cama::findOrFail($this->editingCamaId);
        $cama->update([
            'cuarto_id' => $this->cuarto_id,
            'numero_cama' => $this->numero_cama,
            'estado' => $this->estado,
        ]);

        session()->flash('message', 'Cama actualizada exitosamente.');
        $this->resetForm();
    }

    public function delete($id)
    {
        Cama::findOrFail($id)->delete();
        session()->flash('message', 'Cama eliminada exitosamente.');
    }

    public function updateEstado($id, $nuevoEstado)
    {
        $cama = Cama::findOrFail($id);
        $cama->update(['estado' => $nuevoEstado]);
        session()->flash('message', 'Estado de cama actualizado exitosamente.');
    }

    public function save()
    {
        if ($this->editingCamaId) {
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
        // Mantener el filtro de cuarto si existe
        $keepCuartoId = $this->filterCuartoId;
        $this->reset(['cuarto_id', 'numero_cama', 'estado', 'editingCamaId', 'showForm']);
        if ($keepCuartoId) {
            $this->cuarto_id = $keepCuartoId;
        }
        $this->resetValidation();
    }

    public function render()
    {
        $query = Cama::with(['cuarto.piso.area']);

        // Filtrar por cuarto si está especificado
        if ($this->filterCuartoId) {
            $query->where('cuarto_id', $this->filterCuartoId);
        }

        $camas = $query->orderBy('numero_cama')->paginate(15);

        // Información del cuarto si hay filtro
        $cuarto = $this->filterCuartoId ? Cuarto::with('piso.area')->find($this->filterCuartoId) : null;

        return view('livewire.admin.cama-manager', [
            'camas' => $camas,
            'cuarto' => $cuarto,
            'estados' => CamaEstado::cases(),
        ]);
    }
}
