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
        Schema::create('balance_liquidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->string('tipo'); // ingreso, egreso (enum compatible)
            $table->string('via'); // oral, parenteral, etc.
            $table->string('solucion')->nullable(); // Para ingresos: Solución Salina, Hartmann, etc.
            $table->integer('volumen_ml');
            $table->dateTime('fecha_hora');
            $table->string('turno'); // Matutino, Vespertino, Nocturno
            $table->foreignId('registrado_por')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_liquidos');
    }
};