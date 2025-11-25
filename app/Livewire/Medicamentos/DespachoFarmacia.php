<?php

namespace App\Livewire\Medicamentos;

use App\Models\SolicitudMedicamento;
use App\Models\DetalleSolicitudMedicamento;
use App\Models\InventarioMedicamento;
use App\Models\MovimientoInventario;
use App\Models\Medicamento;
use App\Models\RegistroMedicamentoControlado;
use App\Models\User;
use App\Enums\EstadoSolicitudMedicamento;
use App\Enums\TipoMovimientoInventario;
use App\Enums\EstadoInventarioMedicamento;
use App\Services\InteraccionMedicamentosaService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DespachoFarmacia extends Component
{
    use WithPagination;

    public $estadoFiltro = 'pendiente';
    public $prioridadFiltro = '';
    public $fechaDesde = '';
    public $fechaHasta = '';

    public $modalRevisar = false;
    public $modalDespacho = false;
    public $modalDobleVerificacion = false;

    public $solicitudSeleccionada = null;
    public $motivo_rechazo = '';

    // Para despacho
    public $detallesDespacho = [];
    public $observaciones_despacho = '';
    public $interaccionesMedicamentosas = [];

    // Para doble verificación de controlados
    public $tieneControlados = false;
    public $verificadorEmail = '';
    public $verificadorPassword = '';
    public $verificadorId = null;
    public $verificacionCompletada = false;
    public $numero_receta = '';
    public $justificacion_controlado = '';

    protected $interaccionService;

    public function boot(InteraccionMedicamentosaService $service)
    {
        $this->interaccionService = $service;
    }

    public function render()
    {
        $solicitudes = SolicitudMedicamento::with(['paciente', 'enfermero.user', 'area', 'detalles.medicamento'])
            ->when($this->estadoFiltro, function ($query) {
                $query->where('estado', $this->estadoFiltro);
            })
            ->when($this->prioridadFiltro, function ($query) {
                $query->where('prioridad', $this->prioridadFiltro);
            })
            ->when($this->fechaDesde, function ($query) {
                $query->whereDate('fecha_solicitud', '>=', $this->fechaDesde);
            })
            ->when($this->fechaHasta, function ($query) {
                $query->whereDate('fecha_solicitud', '<=', $this->fechaHasta);
            })
            ->orderByRaw("CASE prioridad WHEN 'stat' THEN 1 WHEN 'urgente' THEN 2 WHEN 'normal' THEN 3 ELSE 4 END")
            ->orderBy('fecha_solicitud', 'asc')
            ->paginate(15);

        return view('livewire.medicamentos.despacho-farmacia', [
            'solicitudes' => $solicitudes,
        ]);
    }

    public function revisarSolicitud($solicitudId)
    {
        $this->solicitudSeleccionada = SolicitudMedicamento::with([
            'paciente',
            'enfermero.user',
            'area',
            'detalles.medicamento'
        ])->findOrFail($solicitudId);

        // Verificar si tiene medicamentos controlados
        $this->tieneControlados = $this->solicitudSeleccionada->detalles
            ->contains(fn($d) => $d->medicamento->es_controlado);

        // Verificar interacciones medicamentosas entre los medicamentos de la solicitud
        $this->interaccionesMedicamentosas = [];
        $medicamentos = $this->solicitudSeleccionada->detalles->pluck('medicamento');

        // Verificar interacciones entre pares de medicamentos
        foreach ($medicamentos as $i => $medA) {
            foreach ($medicamentos as $j => $medB) {
                if ($i >= $j) continue;

                $interacciones = \App\Models\InteraccionMedicamentosa::where(function ($q) use ($medA, $medB) {
                    $q->where('medicamento_a_id', $medA->id)->where('medicamento_b_id', $medB->id);
                })->orWhere(function ($q) use ($medA, $medB) {
                    $q->where('medicamento_a_id', $medB->id)->where('medicamento_b_id', $medA->id);
                })->get();

                foreach ($interacciones as $int) {
                    $this->interaccionesMedicamentosas[] = $int;
                }
            }
        }

        $this->modalRevisar = true;
    }

    public function aprobarSolicitud()
    {
        if (!$this->solicitudSeleccionada) {
            session()->flash('error', 'No hay solicitud seleccionada.');
            return;
        }

        $this->solicitudSeleccionada->update([
            'estado' => EstadoSolicitudMedicamento::APROBADA,
            'aprobado_por' => auth()->id(),
            'fecha_aprobacion' => now(),
        ]);

        session()->flash('message', "Solicitud {$this->solicitudSeleccionada->numero_solicitud} aprobada exitosamente.");
        $this->modalRevisar = false;
        $this->reset(['solicitudSeleccionada', 'interaccionesMedicamentosas', 'tieneControlados']);
    }

    public function rechazarSolicitud()
    {
        $this->validate([
            'motivo_rechazo' => 'required|string|min:10',
        ], [
            'motivo_rechazo.required' => 'Debe especificar el motivo del rechazo.',
            'motivo_rechazo.min' => 'El motivo debe tener al menos 10 caracteres.',
        ]);

        if (!$this->solicitudSeleccionada) {
            session()->flash('error', 'No hay solicitud seleccionada.');
            return;
        }

        $this->solicitudSeleccionada->update([
            'estado' => EstadoSolicitudMedicamento::RECHAZADA,
            'aprobado_por' => auth()->id(),
            'fecha_aprobacion' => now(),
            'motivo_rechazo' => $this->motivo_rechazo,
        ]);

        session()->flash('message', "Solicitud {$this->solicitudSeleccionada->numero_solicitud} rechazada.");
        $this->modalRevisar = false;
        $this->reset(['solicitudSeleccionada', 'motivo_rechazo', 'interaccionesMedicamentosas', 'tieneControlados']);
    }

    public function abrirModalDespacho($solicitudId)
    {
        $this->solicitudSeleccionada = SolicitudMedicamento::with([
            'paciente',
            'enfermero.user',
            'area',
            'detalles.medicamento'
        ])->findOrFail($solicitudId);

        if ($this->solicitudSeleccionada->estado !== EstadoSolicitudMedicamento::APROBADA) {
            session()->flash('error', 'Solo se pueden despachar solicitudes aprobadas.');
            return;
        }

        // Verificar si tiene medicamentos controlados
        $this->tieneControlados = $this->solicitudSeleccionada->detalles
            ->contains(fn($d) => $d->medicamento->es_controlado);

        $this->verificacionCompletada = false;
        $this->verificadorId = null;

        // Inicializar detalles de despacho con inventarios disponibles
        $this->detallesDespacho = [];
        foreach ($this->solicitudSeleccionada->detalles as $detalle) {
            // Buscar inventario disponible con FIFO (más antiguo primero)
            $inventariosDisponibles = InventarioMedicamento::where('medicamento_id', $detalle->medicamento_id)
                ->where('estado', EstadoInventarioMedicamento::DISPONIBLE)
                ->where('cantidad_actual', '>', 0)
                ->where('fecha_caducidad', '>', now()->addDays(30)) // No despachar si caduca en menos de 30 días
                ->orderBy('fecha_caducidad', 'asc')
                ->get();

            $this->detallesDespacho[] = [
                'detalle_id' => $detalle->id,
                'medicamento_id' => $detalle->medicamento_id,
                'medicamento_nombre' => $detalle->medicamento->nombre_comercial,
                'es_controlado' => $detalle->medicamento->es_controlado,
                'cantidad_solicitada' => $detalle->cantidad_solicitada,
                'cantidad_despachada' => min($detalle->cantidad_solicitada, $inventariosDisponibles->sum('cantidad_actual')),
                'inventario_id' => $inventariosDisponibles->first()?->id ?? null,
                'inventarios_disponibles' => $inventariosDisponibles,
                'stock_disponible' => $inventariosDisponibles->sum('cantidad_actual'),
            ];
        }

        $this->modalDespacho = true;
    }

    public function abrirDobleVerificacion()
    {
        if (!$this->tieneControlados) {
            $this->despacharSolicitud();
            return;
        }

        // Validar número de receta para controlados
        $this->validate([
            'numero_receta' => 'required|string|min:3',
            'justificacion_controlado' => 'required|string|min:10',
        ], [
            'numero_receta.required' => 'El número de receta es obligatorio para medicamentos controlados.',
            'justificacion_controlado.required' => 'La justificación es obligatoria para medicamentos controlados.',
        ]);

        $this->modalDobleVerificacion = true;
    }

    public function verificarSegundoUsuario()
    {
        $this->validate([
            'verificadorEmail' => 'required|email',
            'verificadorPassword' => 'required|string',
        ], [
            'verificadorEmail.required' => 'Ingrese el correo del verificador.',
            'verificadorPassword.required' => 'Ingrese la contraseña del verificador.',
        ]);

        // Buscar usuario verificador
        $verificador = User::where('email', $this->verificadorEmail)->first();

        if (!$verificador) {
            $this->addError('verificadorEmail', 'Usuario no encontrado.');
            return;
        }

        // Verificar que no sea el mismo usuario
        if ($verificador->id === auth()->id()) {
            $this->addError('verificadorEmail', 'El verificador debe ser un usuario diferente.');
            return;
        }

        // Verificar contraseña
        if (!Hash::check($this->verificadorPassword, $verificador->password)) {
            $this->addError('verificadorPassword', 'Contraseña incorrecta.');
            return;
        }

        // Verificar que tenga rol de farmacéutico o superior
        $rolesPermitidos = ['farmaceutico', 'farmaceutico_senior', 'coordinador', 'admin'];
        $tieneRol = $verificador->roles()->whereIn('name', $rolesPermitidos)->exists();

        if (!$tieneRol) {
            $this->addError('verificadorEmail', 'El usuario no tiene permisos para verificar medicamentos controlados.');
            return;
        }

        $this->verificadorId = $verificador->id;
        $this->verificacionCompletada = true;
        $this->modalDobleVerificacion = false;

        // Proceder con el despacho
        $this->despacharSolicitud();
    }

    public function despacharSolicitud()
    {
        // Validar que si hay controlados, se haya completado la verificación
        if ($this->tieneControlados && !$this->verificacionCompletada) {
            $this->abrirDobleVerificacion();
            return;
        }

        // Validar que todos los detalles tengan inventario asignado
        foreach ($this->detallesDespacho as $index => $detalle) {
            if (empty($detalle['inventario_id'])) {
                session()->flash('error', "Debe seleccionar un lote para {$detalle['medicamento_nombre']}.");
                return;
            }

            if ($detalle['cantidad_despachada'] <= 0) {
                session()->flash('error', "La cantidad a despachar de {$detalle['medicamento_nombre']} debe ser mayor a 0.");
                return;
            }

            $inventario = InventarioMedicamento::find($detalle['inventario_id']);
            if (!$inventario || $inventario->cantidad_actual < $detalle['cantidad_despachada']) {
                session()->flash('error', "Stock insuficiente para {$detalle['medicamento_nombre']}.");
                return;
            }
        }

        DB::transaction(function () {
            // Actualizar estado de la solicitud
            $this->solicitudSeleccionada->update([
                'estado' => EstadoSolicitudMedicamento::DESPACHADA,
                'despachado_por' => auth()->id(),
                'fecha_despacho' => now(),
            ]);

            // Procesar cada detalle
            foreach ($this->detallesDespacho as $detalle) {
                $inventario = InventarioMedicamento::find($detalle['inventario_id']);
                $medicamento = Medicamento::find($detalle['medicamento_id']);

                // Actualizar detalle de solicitud
                DetalleSolicitudMedicamento::where('id', $detalle['detalle_id'])->update([
                    'cantidad_despachada' => $detalle['cantidad_despachada'],
                    'inventario_id' => $detalle['inventario_id'],
                ]);

                // Decrementar inventario
                $cantidadAnterior = $inventario->cantidad_actual;
                $inventario->decrement('cantidad_actual', $detalle['cantidad_despachada']);

                // Registrar movimiento
                MovimientoInventario::create([
                    'inventario_id' => $inventario->id,
                    'tipo_movimiento' => TipoMovimientoInventario::SALIDA,
                    'cantidad' => $detalle['cantidad_despachada'],
                    'cantidad_anterior' => $cantidadAnterior,
                    'cantidad_nueva' => $inventario->cantidad_actual,
                    'area_destino_id' => $this->solicitudSeleccionada->area_id,
                    'motivo' => "Despacho solicitud {$this->solicitudSeleccionada->numero_solicitud}",
                    'referencia' => $this->solicitudSeleccionada->numero_solicitud,
                    'usuario_id' => auth()->id(),
                    'fecha_movimiento' => now(),
                ]);

                // Registrar medicamento controlado si aplica
                if ($medicamento->es_controlado) {
                    RegistroMedicamentoControlado::create([
                        'medicamento_id' => $medicamento->id,
                        'solicitud_id' => $this->solicitudSeleccionada->id,
                        'tipo_operacion' => 'salida',
                        'cantidad' => $detalle['cantidad_despachada'],
                        'usuario_id' => auth()->id(),
                        'autorizado_por' => $this->verificadorId ?? auth()->id(),
                        'numero_receta' => $this->numero_receta,
                        'justificacion' => $this->justificacion_controlado,
                        'fecha_operacion' => now(),
                    ]);
                }

                // Si el inventario llega a 0, marcarlo como agotado
                if ($inventario->cantidad_actual == 0) {
                    $inventario->update(['estado' => EstadoInventarioMedicamento::AGOTADO]);
                }
            }

            // Agregar observaciones si hay
            if (!empty($this->observaciones_despacho)) {
                $this->solicitudSeleccionada->update([
                    'observaciones' => ($this->solicitudSeleccionada->observaciones ? $this->solicitudSeleccionada->observaciones . "\n\n" : '')
                        . "DESPACHO: " . $this->observaciones_despacho,
                ]);
            }
        });

        session()->flash('message', "Solicitud {$this->solicitudSeleccionada->numero_solicitud} despachada exitosamente.");
        $this->modalDespacho = false;
        $this->reset([
            'solicitudSeleccionada', 'detallesDespacho', 'observaciones_despacho',
            'tieneControlados', 'verificadorEmail', 'verificadorPassword',
            'verificadorId', 'verificacionCompletada', 'numero_receta', 'justificacion_controlado'
        ]);
    }

    public function cerrarModal()
    {
        $this->modalRevisar = false;
        $this->modalDespacho = false;
        $this->modalDobleVerificacion = false;
        $this->reset([
            'solicitudSeleccionada',
            'motivo_rechazo',
            'detallesDespacho',
            'observaciones_despacho',
            'interaccionesMedicamentosas',
            'tieneControlados',
            'verificadorEmail',
            'verificadorPassword',
            'verificadorId',
            'verificacionCompletada',
            'numero_receta',
            'justificacion_controlado'
        ]);
    }
}
