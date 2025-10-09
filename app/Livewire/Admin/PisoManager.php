<?php

namespace App\Livewire\Admin;

use App\Models\Area;
use App\Models\Piso;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class PisoManager extends Component
{
    use WithPagination;

    // Propiedades del formulario
    #[Validate('required|string|max:255')]
    public $nombre = '';

    #[Validate('required|integer|min:1|max:50')]
    public $numero_piso = 1;

    #[Validate('required|exists:areas,id')]
    public $area_id = '';

    #[Validate('nullable|string|max:255')]
    public $especialidad = '';

    // Control de UI
    public $isEditing = false;
    public $editingPisoId = null;
    public $showForm = false;

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->reset(['nombre', 'numero_piso', 'area_id', 'especialidad']);
        $this->numero_piso = 1;
        $this->isEditing = false;
        $this->showForm = true;
        $this->resetValidation();
    }

    /**
     * Guardar nuevo piso
     */
    public function store()
    {
        $this->validate();

        Piso::create([
            'nombre' => $this->nombre,
            'numero_piso' => $this->numero_piso,
            'area_id' => $this->area_id,
            'especialidad' => $this->especialidad,
        ]);

        session()->flash('message', 'Piso creado exitosamente.');
        $this->reset();
        $this->showForm = false;
    }

    /**
     * Editar piso existente
     */
    public function edit($id)
    {
        $piso = Piso::findOrFail($id);

        $this->editingPisoId = $id;
        $this->nombre = $piso->nombre;
        $this->numero_piso = $piso->numero_piso;
        $this->area_id = $piso->area_id;
        $this->especialidad = $piso->especialidad;
        $this->isEditing = true;
        $this->showForm = true;
        $this->resetValidation();
    }

    /**
     * Actualizar piso existente
     */
    public function update()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'numero_piso' => 'required|integer|min:1|max:50',
            'area_id' => 'required|exists:areas,id',
            'especialidad' => 'nullable|string|max:255',
        ]);

        $piso = Piso::findOrFail($this->editingPisoId);

        $piso->update([
            'nombre' => $this->nombre,
            'numero_piso' => $this->numero_piso,
            'area_id' => $this->area_id,
            'especialidad' => $this->especialidad,
        ]);

        session()->flash('message', 'Piso actualizado exitosamente.');
        $this->reset();
        $this->showForm = false;
    }

    /**
     * Eliminar piso
     */
    public function delete($id)
    {
        $piso = Piso::findOrFail($id);
        $piso->delete();

        session()->flash('message', 'Piso eliminado exitosamente.');
    }

    /**
     * Cancelar edición
     */
    public function cancel()
    {
        $this->reset();
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.admin.piso-manager', [
            'pisos' => Piso::with('area')->paginate(10),
            'areas' => Area::orderBy('nombre')->get(),
        ])->layout('layouts.app');
    }
}
