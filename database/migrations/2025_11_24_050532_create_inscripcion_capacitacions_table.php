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
        Schema::create('inscripcion_capacitacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividad_capacitacions')->cascadeOnDelete();
            $table->foreignId('enfermero_id')->constrained('enfermeros')->cascadeOnDelete();
            $table->string('tipo'); // TipoInscripcion enum
            $table->string('estado')->default('pendiente'); // EstadoInscripcion enum
            $table->text('motivacion')->nullable();
            $table->text('expectativas')->nullable();
            $table->integer('prioridad')->default(0);
            $table->timestamp('fecha_inscripcion');
            $table->foreignId('inscrito_por')->constrained('users')->cascadeOnDelete();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('aprobado_at')->nullable();
            $table->text('notas_aprobacion')->nullable();
            $table->foreignId('rechazado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rechazado_at')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->foreignId('cancelado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelado_at')->nullable();
            $table->text('motivo_cancelacion')->nullable();
            $table->decimal('calificacion_final', 5, 2)->nullable();
            $table->decimal('porcentaje_asistencia', 5, 2)->nullable();
            $table->boolean('aprobado')->nullable();
            $table->text('observaciones_finales')->nullable();
            $table->timestamps();

            // Índices
            $table->index('actividad_id');
            $table->index('enfermero_id');
            $table->index('estado');
            $table->index(['actividad_id', 'enfermero_id']);
            $table->unique(['actividad_id', 'enfermero_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcion_capacitacions');
    }
};
