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
        Schema::create('asignacion_pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
            $table->foreignId('enfermero_id')->constrained('enfermeros')->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->timestamp('fecha_hora_asignacion');
            $table->timestamp('fecha_hora_liberacion')->nullable();
            $table->foreignId('asignado_por')->constrained('users');
            $table->foreignId('liberado_por')->nullable()->constrained('users');
            $table->text('motivo_liberacion')->nullable();
            $table->timestamps();

            // Índices para consultas frecuentes
            $table->index(['turno_id', 'enfermero_id']);
            $table->index(['paciente_id', 'fecha_hora_liberacion']); // Para encontrar asignación actual
            $table->index(['fecha_hora_asignacion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_pacientes');
    }
};
