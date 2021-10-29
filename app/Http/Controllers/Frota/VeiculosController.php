<?php

namespace App\Http\Controllers\Frota;

use App\E\Dic\Frota;
use App\E\Mensagens;
use App\E\Sistema;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessarLogRastreador;
use App\Models\Frota\Veiculos;
use App\Models\Frota\VeiculosXClientes;
use App\TrackerGateway\GtwCache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VeiculosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function salvar(Request $eq)
    {

        $registro = Veiculos::where('id', (int)$eq->id)->first();

        $config = Frota::veiculos($registro);

        $acao = __FUNCTION__;

        $agi = Sistema::agi($config, $acao, $eq);

        $validator = app('validator')
            ->make($eq->all(), $agi['validacao']);

        if ($validator->fails()) {

            return response($validator->errors(), 400);
        }

        $dados = $agi['dados'];

        if ($registro) {
            unset($dados['cadastrado_em']);
            unset($dados['cadastrado_por']);
        } else {
            $registro = new Veiculos();
            $dados['cadastrado_em'] = Carbon::now();
        }

        unset($dados['id']);

        $registro->forceFill($dados);

        app('db')->beginTransaction();

        if (!$registro->isDirty()) {

            $qtdNovos = $this->vincularVeiculoClientes($registro, $eq);

            if ($qtdNovos) {
                app('db')->commit();

                $registro->load('clientes');

                return $registro;
            } else
                return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }

        $registro->save();

        $this->vincularVeiculoClientes($registro, $eq);

        app('db')->commit();

        $registro->load('clientes');

        return $registro;


    }

    public function excluir(Request $req)
    {

        $registro = Veiculos::where('id', (int)$req->id)->first();

        if ($registro) {

//            if ($registro->veiculosXClientes->count()) {
//                return response(Mensagens::ERRO_DB_DELETE, 421);
//            }

            app('db')->beginTransaction();

            $registro->veiculosXClientes()->delete();

            $registro->delete();

            app('db')->commit();

            return response(['msg' => Mensagens::OK_DB_DELETE]);

        }

        return response(Mensagens::ERRO_DB_FIND, 421);


    }

    public function buscar(Request $req)
    {

        $query = Veiculos::query();
        //wheres
        //orders
        //includes

        if (is_array($req->include)) {

            foreach ($req->include as $inc) {
                $query->with($inc);
            }

        }

        if ($req->input('filtro.cliente_id')) {


            $query->whereHas('veiculosXClientes', function ($query) use ($req) {

                $query->where('cliente_id', $req->input('filtro.cliente_id'));
            });
        }

        if ($req->input('filtro.id')) {
            $query->where('id', $req->input('filtro.id'));
        }
        if ($req->input('filtro.tipo_id')) {
            $query->where('tipo_id', $req->input('filtro.tipo_id'));
        }


        if ($req->input('filtro.marca_id')) {
            $query->where('marca_id', $req->input('filtro.marca_id'));
        }

        if ($req->input('filtro.rastreador_esn')) {
            $query->where('rastreador_esn', $req->input('filtro.rastreador_esn'));
        }

        if ($req->input('filtro.modelo_id')) {
            $query->where('modelo_id', $req->input('filtro.modelo_id'));
        }

        if ($req->input('filtro.tipo_id')) {
            $query->where('tipo_id', $req->input('filtro.tipo_id'));
        }

        if ($req->input('filtro.contem')) {

            $contem = Util::sanitize($req->input('filtro.contem'));

            $query->where(function ($where) use ($contem) {
                $where->whereRaw('REPLACE(placa," ","") like ?', [str_replace(" ", "", $contem) . '%']);
                $where->orWhere('renavan', 'like', "%{$contem}%");
            });
        }


        if ($this->getUsuario()->is_root != 'Y') {

            $clienteId = $this->getClienteAtivo();

            $query->whereHas('veiculosXClientes', function ($query) use ($req, $clienteId) {

                $query->where('cliente_id', $clienteId->id);
            });

            $query->where('status', 'ATIVO');

        }


        $transfFunc = function ($item) use ($req) {

            if (!($req->last_log == 'nao')) {

                $item->last_logs = GtwCache::getLastLog(
                    $item->rastreador_esn
                );
                return $item;
            }
            return $item;

        };

        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {

            return $query->get()->transform($transfFunc);
        }

        $paginator = $query->paginate(500);

        $transformed = $paginator->getCollection()->transform($transfFunc);
        $paginator->setCollection($transformed);

        return $paginator;
    }


    private function vincularVeiculoClientes(Veiculos $registro, $request)
    {

        if (is_array($request->clientes)) {
            $temp = [];
            $inserts = 0;

            foreach ($request->clientes as $i) {

                $aux = VeiculosXClientes::where('cliente_id', $i['id'])
                    ->where('veiculo_id', $registro->id)
                    ->first();

                if (!$aux) {
                    $aux = new VeiculosXClientes();
                    $aux->vinculado_em = Carbon::now();
                    $inserts++;
                }

                $aux->cliente_id = $i['id'];
                $aux->veiculo_id = $registro->id;

                $aux->save();

                $temp[] = $aux->id;

            }


            $deletes = VeiculosXClientes::where('veiculo_id', $registro->id)
                ->whereNotIn('id', $temp)
                ->delete();


            return $deletes || $inserts;

        }

        return false;

    }
}
