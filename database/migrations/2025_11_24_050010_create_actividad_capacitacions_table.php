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
        Schema::create('actividad_capacitacions', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('tipo'); // TipoActividad enum
            $table->string('estado')->default('planificada'); // EstadoActividad enum
            $table->string('modalidad'); // presencial, virtual, hibrida
            $table->string('ubicacion')->nullable();
            $table->string('url_virtual')->nullable();
            $table->integer('duracion_horas');
            $table->integer('cupo_minimo')->default(5);
            $table->integer('cupo_maximo')->default(30);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->date('fecha_limite_inscripcion')->nullable();
            $table->decimal('porcentaje_asistencia_minimo', 5, 2)->default(80.00);
            $table->decimal('calificacion_minima_aprobacion', 5, 2)->nullable();
            $table->boolean('otorga_certificado')->default(true);
            $table->string('instructor_nombre')->nullable();
            $table->string('instructor_credenciales')->nullable();
            $table->text('objetivos')->nullable();
            $table->text('contenido_tematico')->nullable();
            $table->text('recursos_necesarios')->nullable();
            $table->text('evaluacion_metodo')->nullable();
            $table->text('notas_adicionales')->nullable();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('creado_por')->constrained('users')->cascadeOnDelete();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('aprobado_at')->nullable();
            $table->foreignId('cancelado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelado_at')->nullable();
            $table->text('motivo_cancelacion')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices para búsquedas frecuentes
            $table->index('tipo');
            $table->index('estado');
            $table->index('fecha_inicio');
            $table->index('fecha_fin');
            $table->index(['area_id', 'fecha_inicio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_capacitacions');
    }
};
