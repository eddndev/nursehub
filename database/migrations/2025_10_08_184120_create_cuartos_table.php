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
        Schema::create('cuartos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piso_id')->constrained()->onDelete('cascade')->comment('FK a la tabla pisos');
            $table->string('numero_cuarto')->comment('Número identificador del cuarto');
            $table->enum('tipo', ['individual', 'doble', 'multiple'])->default('individual')->comment('Tipo de cuarto');
            $table->timestamps();

            // Índice para mejorar queries por piso
            $table->index('piso_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuartos');
    }
};
