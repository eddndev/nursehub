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
        // Agregar campo de evaluación requerida en actividades
        Schema::table('actividad_capacitacions', function (Blueprint $table) {
            $table->boolean('requiere_evaluacion')->default(false)->after('porcentaje_asistencia_minimo');
        });

        // Agregar campos de evaluación en inscripciones
        Schema::table('inscripcion_capacitacions', function (Blueprint $table) {
            $table->decimal('calificacion_evaluacion', 5, 2)->nullable()->after('porcentaje_asistencia');
            $table->text('retroalimentacion')->nullable()->after('calificacion_evaluacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividad_capacitacions', function (Blueprint $table) {
            $table->dropColumn('requiere_evaluacion');
        });

        Schema::table('inscripcion_capacitacions', function (Blueprint $table) {
            $table->dropColumn(['calificacion_evaluacion', 'retroalimentacion']);
        });
    }
};
