<?php

namespace App\Http\Controllers\Frota;

use App\Http\Controllers\Controller;
use App\Models\Frota\Logs;
use App\Models\Frota\Rastreadores;
use App\Models\Frota\Veiculos;
use App\Models\Geral\Clientes;
use App\Models\Geral\Usuarios;
use App\TrackerGateway\GtwCache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PainelAdminController extends Controller
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

        if ($req->input('texto_cliente')) {

            $query = Clientes::query()
                ->where(function ($subquery) use ($req) {
                    $subquery->where('nome', 'like', "%{$req->texto_cliente}%");
                    $subquery->orWhere('cpf_cnpj', 'like', "{$req->texto_cliente}%");
                    $subquery->orWhere('fone1', 'like', "{$req->texto_cliente}%");
                    $subquery->orWhere('fone2', 'like', "{$req->texto_cliente}%");
                    $subquery->orWhere('fone3', 'like', "{$req->texto_cliente}%");
                    $subquery->orWhere('email', 'like', "{$req->texto_cliente}%");
                });


        }


    }

    public function rastreadorStatus(Request $req)
    {

        $rastreador = Rastreadores::query()
            ->where('esn', $req->rastreador_esn)
            ->first();


        if ($rastreador) {

            $lastLog = $rastreador->logs()
                ->orderBy('gps_timestamp', 'DESC')
                ->first();

            $qtdDados = $rastreador->logs()->count();

            $diff = null;

            $alertaAtrazo = 1;

            if ($lastLog) {

                $now = Carbon::now();
                $logTime = Carbon::parse($lastLog->gps_timestamp);

                $diffCarbom = $now->diffAsCarbonInterval($logTime);

                $diff = $diffCarbom->copy()
                    ->locale('pt_BR')
                    ->forHumans();

                $alertaAtrazo = $diffCarbom->totalHours > 2 ? 1 : 0;

            }


            return [

                'alerta_atrazo' => $alertaAtrazo,
                'last_log' => $lastLog,
                'delay' => $diff,
                'count_logs' => $qtdDados,
                'veiculo' => $rastreador->veiculo,
                'rastreador' => $rastreador
            ];
        }


        return response('Equipamento não encontrado!', 404);


    }


    public function contadores()
    {

        return [
            'veiculos' => $this->contarVeiculos(),
            'rastreadores' => $this->contarRastreadores(),
            'clientes' => $this->contarClientes(),
            'usuarios' => $this->contarUsuarios(),
            'conexoes' => $this->contarConexoes(),
            'fila' => $this->contarFila()
        ];

    }

    private function contarVeiculos()
    {
        return Veiculos::query()->count();
    }

    private function contarRastreadores()
    {
        return Rastreadores::query()->count();
    }

    private function contarClientes()
    {
        return Clientes::query()->count();
    }

    private function contarUsuarios()
    {
        return Usuarios::query()->count();
    }

    private function contarConexoes()
    {
        $status = GtwCache::getRedisClient()->get('gw_status');
        if ($status) {
            $status = unserialize($status);
            return $status['connections'];
        }

        return 0;

    }

    private function contarFila()
    {
        $filaMsg = GtwCache::getRedisClient()->llen('FILA_GW');

        if ($filaMsg) {
            return $filaMsg;
        }

        return 0;

    }


}
