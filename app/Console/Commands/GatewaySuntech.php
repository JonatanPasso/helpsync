<?php


namespace App\Console\Commands;


use App\TrackerGateway\Server;
use App\TrackerGateway\ServerSuntech;
use Illuminate\Console\Command;

class GatewaySuntech extends  Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateway-suntech {operacao=start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Iniciar/Parar tracker gateway';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $operacao = $this->argument('operacao');


        new ServerSuntech();



    }

}
