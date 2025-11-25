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
        Schema::create('administraciones_medicamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('enfermero_id')->constrained('enfermeros');
            $table->foreignId('medicamento_id')->constrained('medicamentos');
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes_medicamento');
            $table->unsignedBigInteger('admision_id')->nullable(); // TODO: FK cuando Sprint 2 esté implementado
            $table->timestamp('fecha_hora_administracion');
            $table->string('dosis_administrada'); // "500mg"
            $table->string('via_administracion');
            $table->text('observaciones')->nullable();
            $table->boolean('tuvo_reaccion_adversa')->default(false);
            $table->text('descripcion_reaccion')->nullable();
            $table->foreignId('verificado_por')->nullable()->constrained('users'); // Segunda verificación
            $table->timestamps();

            $table->index(['paciente_id', 'fecha_hora_administracion'], 'adm_med_paciente_fecha_idx');
            $table->index(['medicamento_id', 'fecha_hora_administracion'], 'adm_med_medicamento_fecha_idx');
            $table->index(['enfermero_id', 'fecha_hora_administracion'], 'adm_med_enfermero_fecha_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administraciones_medicamento');
    }
};
