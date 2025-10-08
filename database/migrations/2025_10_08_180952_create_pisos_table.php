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
        Schema::create('pisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->onDelete('cascade')->comment('FK a la tabla areas');
            $table->string('nombre')->comment('Nombre descriptivo del piso');
            $table->integer('numero_piso')->comment('Número del piso en el edificio');
            $table->string('especialidad')->nullable()->comment('Especialidad médica del piso');
            $table->timestamps();

            // Índice para mejorar queries por área
            $table->index('area_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pisos');
    }
};
