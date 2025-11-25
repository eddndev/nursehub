<?php

namespace App\Console\Commands;

use App\Jobs\AlertasStockMinimoJob;
use Illuminate\Console\Command;

class ProcesarAlertasStockMinimo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medicamentos:alertas-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa alertas de medicamentos con stock bajo o agotado';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando procesamiento de alertas de stock mínimo...');

        AlertasStockMinimoJob::dispatch();

        $this->info('Job de alertas de stock mínimo despachado correctamente.');

        return Command::SUCCESS;
    }
}
