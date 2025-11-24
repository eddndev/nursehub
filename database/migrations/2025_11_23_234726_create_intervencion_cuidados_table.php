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
        Schema::create('intervencion_cuidados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_cuidado_id')->constrained('plan_cuidados')->onDelete('cascade');
            $table->text('descripcion');
            $table->string('frecuencia')->nullable(); // Ej: Cada 8 horas, Turno Matutino
            $table->text('observaciones')->nullable();
            $table->boolean('realizado')->default(false);
            $table->dateTime('realizado_at')->nullable();
            $table->foreignId('realizado_por')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervencion_cuidados');
    }
};