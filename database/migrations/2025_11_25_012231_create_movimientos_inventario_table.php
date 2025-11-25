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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->constrained('inventario_medicamentos')->onDelete('cascade');
            $table->string('tipo_movimiento'); // Enum TipoMovimientoInventario
            $table->integer('cantidad');
            $table->integer('cantidad_anterior');
            $table->integer('cantidad_nueva');
            $table->foreignId('area_origen_id')->nullable()->constrained('areas')->onDelete('set null');
            $table->foreignId('area_destino_id')->nullable()->constrained('areas')->onDelete('set null');
            $table->text('motivo')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamp('fecha_movimiento');
            $table->string('referencia')->nullable(); // # de factura, # de solicitud, etc.
            $table->timestamps();

            $table->index(['inventario_id', 'tipo_movimiento']);
            $table->index('fecha_movimiento');
            $table->index('referencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
