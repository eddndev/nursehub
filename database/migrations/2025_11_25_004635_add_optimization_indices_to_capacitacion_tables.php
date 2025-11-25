<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Índices compuestos para optimización de consultas frecuentes en reportes

        // Optimización para filtrado por fecha y estado en actividades
        if (!$this->indexExists('actividad_capacitacions', 'idx_actividad_fecha_estado')) {
            Schema::table('actividad_capacitacions', function (Blueprint $table) {
                $table->index(['fecha_inicio', 'estado'], 'idx_actividad_fecha_estado');
            });
        }

        if (!$this->indexExists('actividad_capacitacions', 'idx_actividad_tipo_fecha')) {
            Schema::table('actividad_capacitacions', function (Blueprint $table) {
                $table->index(['tipo', 'fecha_inicio'], 'idx_actividad_tipo_fecha');
            });
        }

        // Optimización para consultas de inscripciones por estado y actividad
        if (!$this->indexExists('inscripcion_capacitacions', 'idx_inscripcion_actividad_estado_asist')) {
            Schema::table('inscripcion_capacitacions', function (Blueprint $table) {
                $table->index(['actividad_id', 'estado', 'porcentaje_asistencia'], 'idx_inscripcion_actividad_estado_asist');
            });
        }

        if (!$this->indexExists('inscripcion_capacitacions', 'idx_inscripcion_enfermero_estado')) {
            Schema::table('inscripcion_capacitacions', function (Blueprint $table) {
                $table->index(['enfermero_id', 'estado'], 'idx_inscripcion_enfermero_estado');
            });
        }

        // Optimización para consultas de certificaciones por fecha y vigencia
        if (!$this->indexExists('certificacions', 'idx_cert_emision_vigencia')) {
            Schema::table('certificacions', function (Blueprint $table) {
                $table->index(['fecha_emision', 'fecha_vigencia_fin'], 'idx_cert_emision_vigencia');
            });
        }

        // Optimización para consultas de asistencias
        if (!$this->indexExists('asistencia_capacitacions', 'idx_asistencia_sesion_presente')) {
            Schema::table('asistencia_capacitacions', function (Blueprint $table) {
                $table->index(['sesion_id', 'presente'], 'idx_asistencia_sesion_presente');
            });
        }
    }

    protected function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support custom index names in the same way - skip check for tests
            $result = $connection->selectOne(
                "SELECT COUNT(*) as count FROM sqlite_master WHERE type = 'index' AND name = ?",
                [$index]
            );
            return $result->count > 0;
        }

        // MySQL / MariaDB
        $database = $connection->getDatabaseName();
        $result = DB::selectOne(
            "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?",
            [$database, $table, $index]
        );

        return $result->count > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->indexExists('actividad_capacitacions', 'idx_actividad_fecha_estado')) {
            Schema::table('actividad_capacitacions', function (Blueprint $table) {
                $table->dropIndex('idx_actividad_fecha_estado');
            });
        }

        if ($this->indexExists('actividad_capacitacions', 'idx_actividad_tipo_fecha')) {
            Schema::table('actividad_capacitacions', function (Blueprint $table) {
                $table->dropIndex('idx_actividad_tipo_fecha');
            });
        }

        if ($this->indexExists('inscripcion_capacitacions', 'idx_inscripcion_actividad_estado_asist')) {
            Schema::table('inscripcion_capacitacions', function (Blueprint $table) {
                $table->dropIndex('idx_inscripcion_actividad_estado_asist');
            });
        }

        if ($this->indexExists('inscripcion_capacitacions', 'idx_inscripcion_enfermero_estado')) {
            Schema::table('inscripcion_capacitacions', function (Blueprint $table) {
                $table->dropIndex('idx_inscripcion_enfermero_estado');
            });
        }

        if ($this->indexExists('certificacions', 'idx_cert_emision_vigencia')) {
            Schema::table('certificacions', function (Blueprint $table) {
                $table->dropIndex('idx_cert_emision_vigencia');
            });
        }

        if ($this->indexExists('asistencia_capacitacions', 'idx_asistencia_sesion_presente')) {
            Schema::table('asistencia_capacitacions', function (Blueprint $table) {
                $table->dropIndex('idx_asistencia_sesion_presente');
            });
        }
    }
};
