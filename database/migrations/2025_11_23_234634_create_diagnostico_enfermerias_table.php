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
        Schema::create('diagnostico_enfermerias', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Ej: 00001
            $table->text('descripcion'); // Ej: Desequilibrio nutricional...
            $table->string('dominio')->nullable();
            $table->string('clase')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostico_enfermerias');
    }
};