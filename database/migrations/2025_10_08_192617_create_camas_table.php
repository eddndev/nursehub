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
        Schema::create('camas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuarto_id')->constrained()->onDelete('cascade')->comment('FK a la tabla cuartos');
            $table->string('numero_cama')->comment('Número identificador de la cama');
            $table->enum('estado', ['libre', 'ocupada', 'en_limpieza', 'en_mantenimiento'])->default('libre')->comment('Estado actual de la cama');
            $table->timestamps();

            // Índice para mejorar queries por cuarto
            $table->index('cuarto_id');
            // Índice para buscar camas por estado
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camas');
    }
};
