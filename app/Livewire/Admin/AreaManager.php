<?php

namespace App\Livewire\Admin;

use App\Models\Area;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class AreaManager extends Component
{
    use WithPagination;

    // Propiedades del formulario
    #[Validate('required|string|max:255|unique:areas,nombre')]
    public $nombre = '';

    #[Validate('required|string|max:10|unique:areas,codigo')]
    public $codigo = '';

    #[Validate('nullable|string|max:1000')]
    public $descripcion = '';

    #[Validate('boolean')]
    public $opera_24_7 = true;

    #[Validate('required|numeric|min:0.01|max:99.99')]
    public $ratio_enfermero_paciente = 1.00;

    #[Validate('boolean')]
    public $requiere_certificacion = false;

    // Control de UI
    public $isEditing = false;
    public $editingAreaId = null;
    public $showForm = false;

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->reset(['nombre', 'codigo', 'descripcion', 'opera_24_7', 'ratio_enfermero_paciente', 'requiere_certificacion']);
        $this->opera_24_7 = true;
        $this->ratio_enfermero_paciente = 1.00;
        $this->requiere_certificacion = false;
        $this->isEditing = false;
        $this->showForm = true;
        $this->resetValidation();
    }

    /**
     * Guardar nueva área
     */
    public function store()
    {
        $this->validate();

        Area::create([
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'descripcion' => $this->descripcion,
            'opera_24_7' => $this->opera_24_7,
            'ratio_enfermero_paciente' => $this->ratio_enfermero_paciente,
            'requiere_certificacion' => $this->requiere_certificacion,
        ]);

        session()->flash('message', 'Área creada exitosamente.');
        $this->reset();
        $this->showForm = false;
    }

    /**
     * Editar área existente
     */
    public function edit($id)
    {
        $area = Area::findOrFail($id);

        $this->editingAreaId = $id;
        $this->nombre = $area->nombre;
        $this->codigo = $area->codigo;
        $this->descripcion = $area->descripcion;
        $this->opera_24_7 = $area->opera_24_7;
        $this->ratio_enfermero_paciente = $area->ratio_enfermero_paciente;
        $this->requiere_certificacion = $area->requiere_certificacion;
        $this->isEditing = true;
        $this->showForm = true;
        $this->resetValidation();
    }

    /**
     * Actualizar área existente
     */
    public function update()
    {
        $this->validate([
            'nombre' => 'required|string|max:255|unique:areas,nombre,' . $this->editingAreaId,
            'codigo' => 'required|string|max:10|unique:areas,codigo,' . $this->editingAreaId,
            'descripcion' => 'nullable|string|max:1000',
            'opera_24_7' => 'boolean',
            'ratio_enfermero_paciente' => 'required|numeric|min:0.01|max:99.99',
            'requiere_certificacion' => 'boolean',
        ]);

        $area = Area::findOrFail($this->editingAreaId);

        $area->update([
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'descripcion' => $this->descripcion,
            'opera_24_7' => $this->opera_24_7,
            'ratio_enfermero_paciente' => $this->ratio_enfermero_paciente,
            'requiere_certificacion' => $this->requiere_certificacion,
        ]);

        session()->flash('message', 'Área actualizada exitosamente.');
        $this->reset();
        $this->showForm = false;
    }

    /**
     * Eliminar área
     */
    public function delete($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        session()->flash('message', 'Área eliminada exitosamente.');
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
        return view('livewire.admin.area-manager', [
            'areas' => Area::paginate(10),
        ])->layout('layouts.admin');
    }
}
