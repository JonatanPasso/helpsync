<?php

namespace App\Http\Controllers\Comercial;

use App\E\NFE;
use App\Http\Controllers\Controller;

use App\Models\Comercial\CabecalhoNota;
use Illuminate\Http\Request;


class NotaFiscalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    public function buscar(Request $req)
    {
//        set_time_limit ( 3600 );
//        NFE::sincronizarXml(storage_path('teste.notas/2020'));


        $query = CabecalhoNota::query();

        return $query->paginate(100);


    }

    public function contadores()
    {
        return CabecalhoNota::query()
            ->selectRaw('sum(total_icmstot_vprod) as total')
            ->selectRaw('dest_enderdest_uf as uf')
            ->selectRaw('dest_enderdest_xmun as munic')
            ->groupBy('dest_enderdest_cmun',
                'dest_enderdest_uf',
                'dest_enderdest_xmun',
                'dest_enderdest_xmun')
        ->orderBy('total','desc')
            ->get();


    }

}
