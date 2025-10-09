<?php

namespace Database\Seeders;

use App\Enums\TipoAsignacion;
use App\Enums\UserRole;
use App\Models\Area;
use App\Models\Enfermero;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnfermeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las áreas para asignación
        $areas = Area::all();

        if ($areas->isEmpty()) {
            $this->command->warn('No hay áreas disponibles. Ejecuta AreaSeeder primero.');
            return;
        }

        // Obtener todos los usuarios con rol ENFERMERO que no tienen perfil de enfermero
        $usuariosEnfermeros = User::where('role', UserRole::ENFERMERO)
            ->whereDoesntHave('enfermero')
            ->get();

        if ($usuariosEnfermeros->isEmpty()) {
            $this->command->info('No hay usuarios enfermeros sin perfil. Creando nuevos...');

            // Crear 30 usuarios enfermeros con sus perfiles
            for ($i = 1; $i <= 30; $i++) {
                $user = User::create([
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'password' => bcrypt('password'),
                    'role' => UserRole::ENFERMERO,
                    'is_active' => fake()->boolean(90), // 90% activos
                ]);

                $this->crearPerfilEnfermero($user, $areas);
            }
        } else {
            // Crear perfiles para usuarios enfermeros existentes
            foreach ($usuariosEnfermeros as $user) {
                $this->crearPerfilEnfermero($user, $areas);
            }
        }

        $totalFijos = Enfermero::where('tipo_asignacion', TipoAsignacion::FIJO)->count();
        $totalRotativos = Enfermero::where('tipo_asignacion', TipoAsignacion::ROTATIVO)->count();

        $this->command->info("Enfermeros creados exitosamente:");
        $this->command->info("- Fijos: {$totalFijos}");
        $this->command->info("- Rotativos: {$totalRotativos}");
    }

    /**
     * Crear perfil de enfermero para un usuario
     */
    private function crearPerfilEnfermero(User $user, $areas): void
    {
        // 60% fijos, 40% rotativos
        $tipoAsignacion = fake()->boolean(60) ? TipoAsignacion::FIJO : TipoAsignacion::ROTATIVO;

        $especialidades = fake()->optional(0.7)->passthrough(
            fake()->randomElements(
                [
                    'Cuidados Intensivos',
                    'Urgencias',
                    'Pediatría',
                    'Geriatría',
                    'Oncología',
                    'Quirófano',
                    'Medicina Interna',
                    'Cardiología',
                    'Neonatología',
                ],
                fake()->numberBetween(1, 3)
            )
        );

        Enfermero::create([
            'user_id' => $user->id,
            'cedula_profesional' => fake()->unique()->numerify('########'),
            'tipo_asignacion' => $tipoAsignacion,
            'area_fija_id' => $tipoAsignacion === TipoAsignacion::FIJO
                ? $areas->random()->id
                : null,
            'especialidades' => $especialidades ? implode(', ', $especialidades) : null,
            'anos_experiencia' => fake()->numberBetween(0, 30),
        ]);
    }
}
