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
        Schema::create('valoracion_escalas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->string('tipo_escala'); // EVA, BRADEN, GLASGOW
            $table->integer('puntaje_total');
            $table->json('detalle_json')->nullable(); // Guardar respuestas específicas
            $table->string('riesgo_interpretado')->nullable(); // Bajo, Medio, Alto
            $table->dateTime('fecha_hora');
            $table->foreignId('registrado_por')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valoracion_escalas');
    }
};