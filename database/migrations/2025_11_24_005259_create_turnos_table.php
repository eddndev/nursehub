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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->date('fecha');
            $table->enum('tipo', ['matutino', 'vespertino', 'nocturno']);
            $table->time('hora_inicio'); // 07:00, 15:00, 23:00
            $table->time('hora_fin'); // 15:00, 23:00, 07:00
            $table->foreignId('jefe_turno_id')->constrained('users');
            $table->text('novedades_relevo')->nullable();
            $table->enum('estado', ['activo', 'cerrado'])->default('activo');
            $table->timestamp('cerrado_at')->nullable();
            $table->foreignId('cerrado_por')->nullable()->constrained('users');
            $table->timestamps();

            // Un solo turno por área/fecha/tipo
            $table->unique(['area_id', 'fecha', 'tipo']);

            // Índices para consultas frecuentes
            $table->index(['area_id', 'estado']);
            $table->index(['fecha', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
