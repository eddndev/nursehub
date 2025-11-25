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
        Schema::create('detalles_solicitud_medicamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes_medicamento')->onDelete('cascade');
            $table->foreignId('medicamento_id')->constrained('medicamentos');
            $table->integer('cantidad_solicitada');
            $table->integer('cantidad_despachada')->default(0);
            $table->foreignId('inventario_id')->nullable()->constrained('inventario_medicamentos'); // Lote despachado
            $table->text('indicaciones_medicas')->nullable(); // "Cada 8 horas por 7 días"
            $table->timestamps();

            $table->index(['solicitud_id', 'medicamento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_solicitud_medicamento');
    }
};
