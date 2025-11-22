<?php

namespace App\Livewire\Admin;

use App\Enums\TipoAsignacion;
use App\Enums\UserRole;
use App\Models\Area;
use App\Models\Enfermero;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class UserManager extends Component
{
    use WithPagination;

    // Propiedades del formulario - User
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'enfermero';
    public $is_active = true;

    // Propiedades del formulario - Enfermero (solo si role=enfermero)
    public $cedula_profesional = '';
    public $tipo_asignacion = 'fijo';
    public $area_fija_id = '';
    public $especialidades = '';
    public $anos_experiencia = 0;

    // Control de UI
    public $showForm = false;
    public $editingUserId = null;
    public $areas = [];

    // Filtros
    public $filterRole = '';
    public $filterStatus = '';
    public $search = '';

    public function mount()
    {
        $this->areas = Area::orderBy('nombre')->get();
    }

    /**
     * Mostrar formulario de creación
     */
    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    /**
     * Actualizar búsqueda
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Actualizar filtros
     */
    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    /**
     * Limpiar filtros
     */
    public function clearFilters()
    {
        $this->reset(['filterRole', 'filterStatus', 'search']);
        $this->resetPage();
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,coordinador,jefe_piso,enfermero,jefe_capacitacion',
            'is_active' => 'boolean',
        ];

        // Validación de email
        if ($this->editingUserId) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->editingUserId;
        } else {
            $rules['email'] = 'required|email|max:255|unique:users,email';
        }

        // Validación de password
        if ($this->editingUserId) {
            $rules['password'] = 'nullable|string|min:8';
        } else {
            $rules['password'] = 'required|string|min:8';
        }

        // Validaciones específicas para enfermeros
        if ($this->role === 'enfermero') {
            $rules['cedula_profesional'] = 'required|string|max:50';
            $rules['tipo_asignacion'] = 'required|in:fijo,rotativo';
            $rules['anos_experiencia'] = 'nullable|integer|min:0|max:50';

            // Si es enfermero fijo, area_fija_id es requerida
            if ($this->tipo_asignacion === 'fijo') {
                $rules['area_fija_id'] = 'required|exists:areas,id';
            }

            // Validar cédula única
            if ($this->editingUserId) {
                $user = User::find($this->editingUserId);
                if ($user && $user->enfermero) {
                    $rules['cedula_profesional'] .= '|unique:enfermeros,cedula_profesional,' . $user->enfermero->id;
                } else {
                    $rules['cedula_profesional'] .= '|unique:enfermeros,cedula_profesional';
                }
            } else {
                $rules['cedula_profesional'] .= '|unique:enfermeros,cedula_profesional';
            }
        }

        return $rules;
    }

    public function create()
    {
        $this->validate();

        DB::transaction(function () {
            // Crear usuario
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => UserRole::from($this->role),
                'is_active' => $this->is_active,
            ]);

            // Si es enfermero, crear perfil de enfermero
            if ($this->role === 'enfermero') {
                Enfermero::create([
                    'user_id' => $user->id,
                    'cedula_profesional' => $this->cedula_profesional,
                    'tipo_asignacion' => TipoAsignacion::from($this->tipo_asignacion),
                    'area_fija_id' => $this->tipo_asignacion === 'fijo' ? $this->area_fija_id : null,
                    'especialidades' => $this->especialidades,
                    'anos_experiencia' => $this->anos_experiencia ?? 0,
                ]);
            }
        });

        session()->flash('message', 'Usuario creado exitosamente.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $user = User::with('enfermero')->findOrFail($id);

        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // No prellenar password
        $this->role = $user->role->value;
        $this->is_active = $user->is_active;

        // Limpiar campos de enfermero primero
        $this->cedula_profesional = '';
        $this->tipo_asignacion = 'fijo';
        $this->area_fija_id = '';
        $this->especialidades = '';
        $this->anos_experiencia = 0;

        // Si tiene perfil de enfermero, cargar datos
        if ($user->enfermero) {
            $this->cedula_profesional = $user->enfermero->cedula_profesional;
            $this->tipo_asignacion = $user->enfermero->tipo_asignacion->value;
            $this->area_fija_id = $user->enfermero->area_fija_id ?? '';
            $this->especialidades = $user->enfermero->especialidades ?? '';
            $this->anos_experiencia = $user->enfermero->anos_experiencia;
        }

        $this->showForm = true;
    }

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $user = User::with('enfermero')->findOrFail($this->editingUserId);

            // Actualizar usuario
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => UserRole::from($this->role),
                'is_active' => $this->is_active,
            ];

            // Solo actualizar password si se proporcionó uno nuevo
            if ($this->password) {
                $userData['password'] = Hash::make($this->password);
            }

            $user->update($userData);

            // Si el rol es enfermero
            if ($this->role === 'enfermero') {
                if ($user->enfermero) {
                    // Ya tiene perfil, actualizar
                    $user->enfermero->update([
                        'cedula_profesional' => $this->cedula_profesional,
                        'tipo_asignacion' => TipoAsignacion::from($this->tipo_asignacion),
                        'area_fija_id' => $this->tipo_asignacion === 'fijo' ? $this->area_fija_id : null,
                        'especialidades' => $this->especialidades,
                        'anos_experiencia' => $this->anos_experiencia ?? 0,
                    ]);
                } else {
                    // No tiene perfil, crearlo
                    Enfermero::create([
                        'user_id' => $user->id,
                        'cedula_profesional' => $this->cedula_profesional,
                        'tipo_asignacion' => TipoAsignacion::from($this->tipo_asignacion),
                        'area_fija_id' => $this->tipo_asignacion === 'fijo' ? $this->area_fija_id : null,
                        'especialidades' => $this->especialidades,
                        'anos_experiencia' => $this->anos_experiencia ?? 0,
                    ]);
                }
            } else {
                // Si el rol ya no es enfermero, eliminar perfil si existe
                if ($user->enfermero) {
                    $user->enfermero->delete();
                }
            }
        });

        session()->flash('message', 'Usuario actualizado exitosamente.');
        $this->resetForm();
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'Usuario eliminado exitosamente.');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('message', 'Estado del usuario actualizado.');
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'role',
            'is_active',
            'cedula_profesional',
            'tipo_asignacion',
            'area_fija_id',
            'especialidades',
            'anos_experiencia',
            'showForm',
            'editingUserId',
        ]);
    }

    public function render()
    {
        // Query base con eager loading
        $query = User::with('enfermero.areaFija')
            ->orderBy('created_at', 'desc');

        // Aplicar búsqueda
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Aplicar filtro por rol
        if (!empty($this->filterRole)) {
            $query->where('role', $this->filterRole);
        }

        // Aplicar filtro por estado
        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === 'active');
        }

        return view('livewire.admin.user-manager', [
            'users' => $query->paginate(10),
        ]);
    }
}