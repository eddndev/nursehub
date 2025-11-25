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
        Schema::create('certificacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->constrained('inscripcion_capacitacions')->cascadeOnDelete();
            $table->string('numero_certificado')->unique();
            $table->date('fecha_emision');
            $table->date('fecha_vigencia_inicio');
            $table->date('fecha_vigencia_fin')->nullable();
            $table->integer('horas_certificadas');
            $table->decimal('calificacion_obtenida', 5, 2)->nullable();
            $table->decimal('porcentaje_asistencia', 5, 2);
            $table->text('competencias_desarrolladas')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('hash_verificacion')->unique();
            $table->string('url_descarga')->nullable();
            $table->foreignId('emitido_por')->constrained('users')->cascadeOnDelete();
            $table->timestamp('emitido_at');
            $table->foreignId('revocado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('revocado_at')->nullable();
            $table->text('motivo_revocacion')->nullable();
            $table->timestamps();

            // Índices
            $table->index('inscripcion_id');
            $table->index('numero_certificado');
            $table->index('hash_verificacion');
            $table->index('fecha_emision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificacions');
    }
};
