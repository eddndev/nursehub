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
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_medicamento')->unique();
            $table->string('nombre_comercial');
            $table->string('nombre_generico');
            $table->string('principio_activo');
            $table->string('laboratorio')->nullable();
            $table->string('presentacion'); // Tableta, Ampolleta, Jarabe, Crema, etc.
            $table->string('concentracion'); // 500mg, 10ml, etc.
            $table->string('via_administracion'); // Enum ViaAdministracionMedicamento
            $table->string('categoria'); // Enum CategoriaMedicamento
            $table->boolean('es_controlado')->default(false);
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->text('indicaciones')->nullable();
            $table->text('contraindicaciones')->nullable();
            $table->text('efectos_adversos')->nullable();
            $table->decimal('dosis_maxima_24h', 10, 2)->nullable();
            $table->string('unidad_dosis_maxima')->default('mg'); // mg, ml, UI, etc.
            $table->boolean('requiere_refrigeracion')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nombre_comercial', 'nombre_generico']);
            $table->index(['es_controlado', 'activo']);
            $table->index('categoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};
