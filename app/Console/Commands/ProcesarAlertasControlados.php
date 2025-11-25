<?php

namespace App\Console\Commands;

use App\Jobs\AlertasControladosJob;
use Illuminate\Console\Command;

class ProcesarAlertasControlados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medicamentos:alertas-controlados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera reporte semanal de medicamentos controlados';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando generación de reporte de medicamentos controlados...');

        AlertasControladosJob::dispatch();

        $this->info('Job de alertas de controlados despachado correctamente.');

        return Command::SUCCESS;
    }
}
