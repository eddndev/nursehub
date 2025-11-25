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
        Schema::create('sesion_capacitacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividad_capacitacions')->cascadeOnDelete();
            $table->integer('numero_sesion');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('duracion_minutos');
            $table->string('ubicacion')->nullable();
            $table->string('url_virtual')->nullable();
            $table->string('instructor_nombre')->nullable();
            $table->text('contenido')->nullable();
            $table->text('recursos_utilizados')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('asistencia_registrada')->default(false);
            $table->foreignId('registrada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('registrada_at')->nullable();
            $table->timestamps();

            // Índices
            $table->index('actividad_id');
            $table->index('fecha');
            $table->unique(['actividad_id', 'numero_sesion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_capacitacions');
    }
};
