<?php

namespace App\Http\Controllers\Rastreio;

use App\Http\Controllers\Controller;
use App\Models\Frota\Logs;

class LogsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function ultimaPosicao()
    {

        $equipamentos = Logs::query()
            ->groupBy('rastreador_esn')
            ->get(['rastreador_esn']);

        $ultimaPosicao = [];
        foreach ($equipamentos as $eqp) {
            $ultimaPosicao[] = Logs::query()
                ->where('rastreador_esn', $eqp->rastreador_esn)
                ->orderBy('gps_timestamp', 'desc')
                ->first();

        }

        return $ultimaPosicao;
    }
    //
}
