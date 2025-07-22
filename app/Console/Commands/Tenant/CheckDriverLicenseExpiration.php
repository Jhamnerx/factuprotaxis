<?php

namespace App\Console\Commands\Tenant;

use Illuminate\Console\Command;
use App\Jobs\Tenant\CheckDriverLicenseExpirationJob;

class CheckDriverLicenseExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taxi:check-driver-license-expiration {--days=30 : Días de anticipación para avisar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el vencimiento de licencias de conductores y envía notificaciones';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dias = $this->option('days');

        $this->info("Verificando vencimiento de licencias con {$dias} días de anticipación...");

        CheckDriverLicenseExpirationJob::dispatch($dias);

        $this->info('Job de verificación de licencias programado exitosamente.');

        return 0;
    }
}
