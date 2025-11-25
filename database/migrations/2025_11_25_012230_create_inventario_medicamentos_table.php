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
        Schema::create('inventario_medicamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('set null'); // NULL = almacén general
            $table->string('lote');
            $table->date('fecha_caducidad');
            $table->integer('cantidad_actual');
            $table->integer('cantidad_inicial');
            $table->integer('stock_minimo')->default(10);
            $table->integer('stock_maximo')->nullable();
            $table->decimal('costo_unitario', 10, 2);
            $table->string('estado')->default('disponible'); // Enum EstadoInventarioMedicamento
            $table->string('ubicacion_fisica')->nullable(); // Estante, Anaquel, etc.
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['medicamento_id', 'area_id', 'estado']);
            $table->index('fecha_caducidad');
            $table->index('lote');
            $table->unique(['medicamento_id', 'area_id', 'lote'], 'unique_inventario_lote');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_medicamentos');
    }
};
