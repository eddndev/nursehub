<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ============================================
// MÓDULO DE CAPACITACIÓN
// ============================================

// Programar el envío de recordatorios de capacitación
// Se ejecuta todos los días a las 18:00 para recordar sesiones del día siguiente
Schedule::command('capacitacion:recordatorios')
    ->dailyAt('18:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/capacitacion-recordatorios.log'));

// ============================================
// MÓDULO DE FARMACIA / MEDICAMENTOS
// ============================================

// Alertas de caducidades - Diario a las 06:00
// Verifica medicamentos próximos a caducar (60 días) y marca los ya caducados
Schedule::command('medicamentos:alertas-caducidades')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/medicamentos-caducidades.log'));

// Alertas de stock mínimo - Diario a las 07:00
// Verifica medicamentos con stock bajo o agotado
Schedule::command('medicamentos:alertas-stock')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/medicamentos-stock.log'));

// Reporte de medicamentos controlados - Semanal (Lunes a las 08:00)
// Genera reporte de auditoría de medicamentos controlados
Schedule::command('medicamentos:alertas-controlados')
    ->weeklyOn(1, '08:00') // Lunes a las 08:00
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/medicamentos-controlados.log'));
