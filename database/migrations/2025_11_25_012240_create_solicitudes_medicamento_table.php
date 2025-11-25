<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitudes_medicamento', function (Blueprint $table) {
            $table->id();
            $table->string('numero_solicitud')->unique(); // SOL-2025-0001
            $table->foreignId('enfermero_id')->constrained('enfermeros');
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('area_id')->constrained('areas');
            $table->string('prioridad')->default('normal'); // Enum PrioridadSolicitudMedicamento
            $table->string('estado')->default('pendiente'); // Enum EstadoSolicitudMedicamento
            $table->timestamp('fecha_solicitud');
            $table->foreignId('aprobado_por')->nullable()->constrained('users');
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->foreignId('despachado_por')->nullable()->constrained('users');
            $table->timestamp('fecha_despacho')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->boolean('es_urgencia')->default(false);
            $table->timestamps();

            $table->index(['estado', 'prioridad']);
            $table->index('fecha_solicitud');
            $table->index('numero_solicitud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_medicamento');
    }
};
