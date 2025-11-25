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
        Schema::create('interacciones_medicamentosas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_a_id')->constrained('medicamentos')->onDelete('cascade');
            $table->foreignId('medicamento_b_id')->constrained('medicamentos')->onDelete('cascade');
            $table->string('severidad'); // Enum SeveridadInteraccion
            $table->text('descripcion');
            $table->text('recomendacion')->nullable();
            $table->string('fuente')->nullable(); // Referencia bibliográfica
            $table->timestamps();

            $table->unique(['medicamento_a_id', 'medicamento_b_id'], 'unique_interaccion');
            $table->index('severidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interacciones_medicamentosas');
    }
};
