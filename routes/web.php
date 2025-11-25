<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas por rol (para testing)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/test', fn() => response('Admin access granted'));
});

Route::middleware(['auth', 'role:coordinador'])->group(function () {
    Route::get('/coordinador/test', fn() => response('Coordinador access granted'));
});

Route::middleware(['auth', 'role:admin,coordinador,jefe_piso'])->group(function () {
    Route::get('/jefes/test', fn() => response('Jefes access granted'));
});

// Rutas de administración - Admins y Coordinadores
Route::middleware(['auth', 'role:admin,coordinador'])->prefix('admin')->group(function () {
    Route::get('/areas', \App\Livewire\Admin\AreaManager::class)->name('admin.areas');
    Route::get('/pisos', \App\Livewire\Admin\PisoManager::class)->name('admin.pisos');
    Route::get('/cuartos', \App\Livewire\Admin\CuartoManager::class)->name('admin.cuartos');
    Route::get('/camas', \App\Livewire\Admin\CamaManager::class)->name('admin.camas');
    Route::get('/users', \App\Livewire\Admin\UserManager::class)->name('admin.users');
});

// Mapa del Hospital - Coordinadores y Admins
Route::middleware(['auth', 'role:admin,coordinador'])->group(function () {
    Route::get('/hospital-map', \App\Livewire\HospitalMap::class)->name('hospital.map');
});

// Rutas de Urgencias - Enfermeros, Jefes de Piso, Coordinadores y Admins
Route::middleware(['auth', 'role:enfermero,jefe_piso,coordinador,admin'])->prefix('urgencias')->group(function () {
    Route::get('/admision', \App\Livewire\Urgencias\AdmisionPaciente::class)->name('urgencias.admision');
});

// Rutas de Enfermería - Enfermeros, Jefes de Piso, Coordinadores y Admins
Route::middleware(['auth', 'role:enfermero,jefe_piso,coordinador,admin'])->prefix('enfermeria')->group(function () {
    Route::get('/pacientes', \App\Livewire\Enfermeria\ListaPacientes::class)->name('enfermeria.pacientes');
    Route::get('/paciente/{id}', \App\Livewire\Enfermeria\ExpedientePaciente::class)->name('enfermeria.expediente');
    Route::get('/mis-asignaciones', \App\Livewire\MisAsignaciones::class)->name('enfermeria.mis-asignaciones');
});

// Rutas de Gestión de Turnos - Jefes de Piso, Coordinadores y Admins
Route::middleware(['auth', 'role:jefe_piso,coordinador,admin'])->prefix('turnos')->group(function () {
    Route::get('/gestor', \App\Livewire\GestorTurnos::class)->name('turnos.gestor');
    Route::get('/relevo', \App\Livewire\RelevoTurno::class)->name('turnos.relevo');
});

// Rutas de Capacitación - Coordinadores y Admins
Route::middleware(['auth', 'role:coordinador,admin'])->prefix('capacitacion')->group(function () {
    Route::get('/actividades', \App\Livewire\Capacitacion\GestorActividades::class)->name('capacitacion.actividades');
    Route::get('/inscripciones/{actividadId}', \App\Livewire\Capacitacion\GestorInscripciones::class)->name('capacitacion.inscripciones');
    Route::get('/asistencia/{actividadId}/{sesionId}', \App\Livewire\Capacitacion\ControlAsistencia::class)->name('capacitacion.asistencia');
    Route::get('/aprobaciones/{actividadId}', \App\Livewire\Capacitacion\GestorAprobaciones::class)->name('capacitacion.aprobaciones');
    Route::get('/reportes', \App\Livewire\Capacitacion\ReportesCapacitacion::class)->name('capacitacion.reportes');
});

// Rutas de Capacitación - Enfermeros
Route::middleware(['auth', 'role:enfermero,jefe_piso,coordinador,admin'])->prefix('capacitacion')->group(function () {
    Route::get('/dashboard', \App\Livewire\Capacitacion\DashboardCapacitacion::class)->name('capacitacion.dashboard');
    Route::get('/certificacion/{certificacionId}/pdf', function ($certificacionId) {
        $certificacion = \App\Models\Certificacion::findOrFail($certificacionId);
        $service = new \App\Services\CertificacionPDFService();
        return $service->visualizarPDF($certificacion);
    })->name('capacitacion.certificacion.pdf');
});

// Rutas de Capacitación - Jefes de Piso
Route::middleware(['auth', 'role:jefe_piso,coordinador,admin'])->prefix('capacitacion')->group(function () {
    Route::get('/calendario', \App\Livewire\Capacitacion\CalendarioCapacitaciones::class)->name('capacitacion.calendario');
});

// Rutas de Farmacia - Catálogo e Inventario (Farmacéuticos, Coordinadores, Admins)
Route::middleware(['auth', 'role:farmaceutico,coordinador,admin'])->prefix('medicamentos')->group(function () {
    Route::get('/catalogo', \App\Livewire\Medicamentos\CatalogoMedicamentos::class)->name('medicamentos.catalogo');
    Route::get('/inventario', \App\Livewire\Medicamentos\GestorInventario::class)->name('medicamentos.inventario');
    Route::get('/despacho', \App\Livewire\Medicamentos\DespachoFarmacia::class)->name('medicamentos.despacho');
    Route::get('/reportes', \App\Livewire\Medicamentos\ReportesFarmacia::class)->name('medicamentos.reportes');
});

// Rutas de Farmacia - Solicitudes (Enfermeros, Jefes de Piso, Farmacéuticos, Coordinadores, Admins)
Route::middleware(['auth', 'role:enfermero,jefe_piso,farmaceutico,coordinador,admin'])->prefix('medicamentos')->group(function () {
    Route::get('/solicitudes', \App\Livewire\Medicamentos\SolicitudesMedicamentos::class)->name('medicamentos.solicitudes');
    Route::get('/administrar', \App\Livewire\Medicamentos\AdministracionMedicamentos::class)->name('medicamentos.administrar');
});

require __DIR__.'/socialite.php';
require __DIR__.'/auth.php';
