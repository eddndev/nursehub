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
        Schema::create('registros_signos_vitales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('registrado_por')->constrained('users');
            $table->decimal('presion_arterial_sistolica', 5, 2)->nullable();
            $table->decimal('presion_arterial_diastolica', 5, 2)->nullable();
            $table->integer('frecuencia_cardiaca')->nullable();
            $table->integer('frecuencia_respiratoria')->nullable();
            $table->decimal('temperatura', 4, 2)->nullable();
            $table->decimal('saturacion_oxigeno', 5, 2)->nullable();
            $table->decimal('glucosa', 6, 2)->nullable();
            $table->enum('nivel_triage', ['rojo', 'naranja', 'amarillo', 'verde', 'azul'])->nullable();
            $table->boolean('triage_override')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_registro');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_signos_vitales');
    }
};
