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
        Schema::create('asistencia_capacitacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_id')->constrained('sesion_capacitacions')->cascadeOnDelete();
            $table->foreignId('inscripcion_id')->constrained('inscripcion_capacitacions')->cascadeOnDelete();
            $table->boolean('presente')->default(false);
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->integer('minutos_asistidos')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('tardanza')->default(false);
            $table->boolean('salida_temprana')->default(false);
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('registrado_at')->nullable();
            $table->timestamps();

            // Índices
            $table->index('sesion_id');
            $table->index('inscripcion_id');
            $table->unique(['sesion_id', 'inscripcion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia_capacitacions');
    }
};
