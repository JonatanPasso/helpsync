<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

abstract class Job implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "queueOn" and "delay" queue helper methods.
    |
    */

    use InteractsWithQueue, Queueable, SerializesModels;


    public function log($msg, $tipo = 'info')
    {

        $whoIam = get_called_class();

        if (php_sapi_name() === 'cli') {
            Log::$tipo($whoIam .' > '. $msg);
        } else {
            echo date('Y-m-d H:i') . ' > ' . $whoIam . ' ' . $msg . '<br>';
        }

    }
}
