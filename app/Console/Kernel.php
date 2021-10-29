<?php

namespace App\Console;

use App\Console\Commands\GatewayCmd;
use App\Console\Commands\GatewaySuntech;
use App\Console\Commands\Importar;
use App\Jobs\GerarEventos;
use App\Jobs\ProcessarLogRastreador;
use App\TrackerGateway\TesteCmd;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GatewaySuntech::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

//        Job processamento logs enviados pelos equipamentos de rastreamento
        $schedule->job(new ProcessarLogRastreador())
//            // ->withoutOverlapping()
            ->everyMinute();

        $schedule->job(new GerarEventos())
            ->everyThirtyMinutes();
    }
}
