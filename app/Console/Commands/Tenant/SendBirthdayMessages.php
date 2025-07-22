<?php

namespace App\Console\Commands\Tenant;

use Illuminate\Console\Command;
use App\Jobs\Tenant\SendBirthdayMessagesJob;

class SendBirthdayMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taxi:send-birthday-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía mensajes de feliz cumpleaños a propietarios, conductores y personal';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando envío de mensajes de cumpleaños...');

        SendBirthdayMessagesJob::dispatch();

        $this->info('Job de mensajes de cumpleaños programado exitosamente.');

        return 0;
    }
}
