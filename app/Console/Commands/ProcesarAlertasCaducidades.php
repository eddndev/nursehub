<?php

namespace App\Console\Commands;

use App\Jobs\AlertasCaducidadesJob;
use Illuminate\Console\Command;

class ProcesarAlertasCaducidades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medicamentos:alertas-caducidades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa alertas de medicamentos próximos a caducar y marca caducados';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando procesamiento de alertas de caducidades...');

        AlertasCaducidadesJob::dispatch();

        $this->info('Job de alertas de caducidades despachado correctamente.');

        return Command::SUCCESS;
    }
}
