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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_qr')->unique();
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->enum('sexo', ['M', 'F', 'Otro']);
            $table->date('fecha_nacimiento');
            $table->string('curp', 18)->unique()->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('contacto_emergencia_nombre')->nullable();
            $table->string('contacto_emergencia_telefono', 15)->nullable();
            $table->text('alergias')->nullable();
            $table->text('antecedentes_medicos')->nullable();
            $table->enum('estado', ['activo', 'dado_alta', 'transferido', 'fallecido'])->default('activo');
            $table->foreignId('cama_actual_id')->nullable()->constrained('camas')->nullOnDelete();
            $table->foreignId('admitido_por')->constrained('users');
            $table->timestamp('fecha_admision');
            $table->timestamp('fecha_alta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
