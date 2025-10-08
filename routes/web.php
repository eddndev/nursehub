<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

require __DIR__.'/socialite.php';
require __DIR__.'/auth.php';
