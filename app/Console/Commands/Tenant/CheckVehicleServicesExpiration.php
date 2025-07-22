<?php

namespace App\Console\Commands\Tenant;

use Illuminate\Console\Command;
use App\Jobs\Tenant\CheckVehicleServicesExpirationJob;

class CheckVehicleServicesExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taxi:check-vehicle-services-expiration {--days=30 : Días de anticipación para avisar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el vencimiento de servicios vehiculares (SOAT, Revisión Técnica) y envía notificaciones';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dias = $this->option('days');

        $this->info("Verificando vencimiento de servicios vehiculares con {$dias} días de anticipación...");

        CheckVehicleServicesExpirationJob::dispatch($dias);

        $this->info('Job de verificación de servicios vehiculares programado exitosamente.');

        return 0;
    }
}
