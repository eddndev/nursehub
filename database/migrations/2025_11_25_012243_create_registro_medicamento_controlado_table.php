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
        Schema::create('registro_medicamento_controlado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained('medicamentos');
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes_medicamento');
            $table->foreignId('administracion_id')->nullable()->constrained('administraciones_medicamento');
            $table->string('tipo_operacion'); // entrada, salida, ajuste, destruccion
            $table->integer('cantidad');
            $table->foreignId('usuario_id')->constrained('users'); // Quien solicitó/despachó
            $table->foreignId('autorizado_por')->constrained('users'); // Segunda firma
            $table->string('numero_receta')->nullable();
            $table->text('justificacion');
            $table->timestamp('fecha_operacion');
            $table->timestamps();

            $table->index(['medicamento_id', 'fecha_operacion'], 'reg_med_ctrl_med_fecha_idx');
            $table->index(['usuario_id', 'fecha_operacion'], 'reg_med_ctrl_usr_fecha_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_medicamento_controlado');
    }
};
