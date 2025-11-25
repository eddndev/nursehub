<?php

namespace App\Console\Commands;

use App\Jobs\EnviarRecordatoriosSesiones;
use Illuminate\Console\Command;

class EnviarRecordatoriosCapacitacion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capacitacion:recordatorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia recordatorios por email de sesiones de capacitacion programadas para manana';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando envio de recordatorios de capacitacion...');

        EnviarRecordatoriosSesiones::dispatch();

        $this->info('Job de recordatorios despachado exitosamente.');
        $this->info('Los emails se enviaran en segundo plano.');

        return Command::SUCCESS;
    }
}
