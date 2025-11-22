<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

require __DIR__.'/socialite.php';
require __DIR__.'/auth.php';
