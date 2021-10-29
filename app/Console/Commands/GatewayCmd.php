<?php


namespace App\Console\Commands;


use App\TrackerGateway\Server;
use Illuminate\Console\Command;

class GatewayCmd extends  Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateway {operacao=start}';

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


        new Server();



    }

}
