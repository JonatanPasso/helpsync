<?php

namespace App\Http\Controllers\Frota;


use App\Http\Controllers\Controller;

use App\Models\Frota\Geocode;
use App\Models\Frota\Logs;
use App\Models\Frota\Veiculos;
use App\TrackerGateway\GtwCache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
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

        $items_por_pagina = (int)$req->get('items_por_pagina', 500);

        /**
         * limit range to 1 - 10000
         */
        $items_por_pagina = $items_por_pagina > 0 && $items_por_pagina <= 10000 ? $items_por_pagina : 500;

        $query = Logs::query();
        //wheres
        //orders
        //includes

        //  $query->where('gps_fixo',1);

        $veiculo = Veiculos::whereId($req->veiculo_id)->first();

        $lastLog = GtwCache::getLastLog($veiculo->rastreador_esn);

        if ($lastLog) {
            $auxDataFinal = Carbon::parse($lastLog->gps_timestamp);
            $auxDataInicial = $auxDataFinal->copy()->subHours(12);
        }

        $query->orderBy('gps_timestamp', 'desc');

        $query->where('gps_fixo', 1);

        if (is_array($req->include)) {
            foreach ($req->include as $inc) {
                $query->with($inc);
            }

        }

        if ($req->data_inicial) {
            $auxDataInicial = Carbon::createFromFormat('d/m/Y', $req->data_inicial)
                ->startOfDay();
        }

        if ($req->data_final) {
            $auxDataFinal = Carbon::createFromFormat('d/m/Y', $req->data_final)
                ->endOfDay();
        }

        $query->where('gps_timestamp', '>=', $auxDataInicial);
        $query->where('gps_timestamp', '<=', $auxDataFinal);


        if ((int)$req->veiculo_id) {
            $query->where('veiculo_id', $req->veiculo_id);
        }

        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            return $query->get();
        }


        return $query->paginate($items_por_pagina);
    }

    public function geocodeSave(Request $req)
    {

        $geocode = new Geocode();

        $geocode->from = 'google';
        $geocode->formated_address = $req->formated_address;
        $geocode->lat = $req->lat;
        $geocode->lng = $req->lng;
        $geocode->data = json_encode($req->data);
        $geocode->point = DB::raw("PointFromText( 'POINT({$req->lat} {$req->lng})' )");
        $geocode->save();

        return $geocode;


    }

    public function geocodeBuscar(Request $req)
    {
        $result = Geocode::geo_cache($req->lat, $req->lng);

        if ($result) {
            return (array)$result;

        }

        return response('nehum endreço próximo', 404);

    }
}
