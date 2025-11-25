<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programar el envío de recordatorios de capacitación
// Se ejecuta todos los días a las 18:00 para recordar sesiones del día siguiente
Schedule::command('capacitacion:recordatorios')
    ->dailyAt('18:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/capacitacion-recordatorios.log'));
