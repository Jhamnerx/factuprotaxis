<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tenancy:run tenant:run')
            ->everyMinute();
        // Se ejecutara por hora guardando estado de cpu y memoria (windows/linux)
        $schedule->command('status:server')->hourly();
        // Llena las tablas para libro mayor - Se desactiva CMAR - buscar opcion de url
        // $schedule->command('account_ledger:fill')->hourly();

        //restaurar base de datos demo para restaurant
        // $schedule->command('database:restoredemo')->dailyAt('23:50');

        // Jobs de WhatsApp para el sistema de taxis
        $schedule->command('taxi:send-birthday-messages')->dailyAt('08:00');
        $schedule->command('taxi:check-driver-license-expiration')->dailyAt('09:00');
        $schedule->command('taxi:check-vehicle-services-expiration')->dailyAt('10:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
