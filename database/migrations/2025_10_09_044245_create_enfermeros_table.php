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
        Schema::create('enfermeros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade')->comment('FK única a users (relación 1:1)');
            $table->string('cedula_profesional')->unique()->comment('Cédula profesional de enfermería');
            $table->enum('tipo_asignacion', ['fijo', 'rotativo'])->comment('Tipo de asignación del enfermero');
            $table->foreignId('area_fija_id')->nullable()->constrained('areas')->onDelete('set null')->comment('Área fija si tipo es fijo');
            $table->text('especialidades')->nullable()->comment('Especialidades del enfermero (JSON o texto)');
            $table->integer('anos_experiencia')->default(0)->comment('Años de experiencia profesional');
            $table->timestamps();

            // Índices para optimizar queries
            $table->index('user_id');
            $table->index('tipo_asignacion');
            $table->index('area_fija_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfermeros');
    }
};
