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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique()->comment('Nombre del área del hospital');
            $table->string('codigo', 10)->unique()->comment('Código corto del área');
            $table->text('descripcion')->nullable()->comment('Descripción del área');
            $table->boolean('opera_24_7')->default(true)->comment('¿Opera 24/7?');
            $table->decimal('ratio_enfermero_paciente', 4, 2)->default(1.00)->comment('Ratio enfermero-paciente');
            $table->boolean('requiere_certificacion')->default(false)->comment('¿Requiere certificación especial?');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
