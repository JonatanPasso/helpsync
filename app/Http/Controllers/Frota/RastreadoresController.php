<?php

namespace App\Http\Controllers\Frota;

use App\E\Dic\Frota;
use App\E\Dic\Geral;
use App\E\Mensagens;
use App\E\Sistema;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Models\Frota\Rastreadores;
use App\Models\Geral\Clientes;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class RastreadoresController extends Controller
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

        $registro = Rastreadores::where('id', (int)$eq->id)->first();

        $config = Frota::rastreadores($eq);

        $acao = __FUNCTION__;

        $agi = Sistema::agi($config, $acao, $eq);


        $validator = app('validator')
            ->make($eq->all(), $agi['validacao']);

        if ($validator->fails()) {

            return response($validator->errors(), 400);
        }

        $dados = $agi['dados'];

        if ($registro) {
//            unset($dados['esn']);
            unset($dados['id']);
        } else {
            $registro = new Rastreadores();
            $registro->cadastrado_em = Carbon::now();
        }

        $registro->forceFill($dados);

        if (!$registro->isDirty()) {
            return response(Mensagens::ERRO_DB_NOT_UPDATE,
                421);
        }
        $registro->save();


        return $registro;


    }

    public function excluir(Request $req)
    {

        $registro = Rastreadores::where('id', (int)$req->id)->first();

        if ($registro) {

            if ($registro->veiculos && $registro->veiculos->count()) {
                return response(Mensagens::ERRO_DB_DELETE, 421);
            }

            $registro->delete();

            return response(['msg' => Mensagens::OK_DB_DELETE]);

        }

        return response(Mensagens::ERRO_DB_FIND, 421);


    }

    public function buscar(Request $req)
    {

        $query = Rastreadores::query();
        //wheres
        //orders
        //includes

        if ($req->contem) {
            $query->where(function ($query) use ($req) {
                $query->where('esn', $req->contem);
                $query->orWhere('fone_chip_gsm', $req->contem);
            });
            $query->orWhereHas('veiculo', function ($query) use ($req) {
                $query->where('placa', 'like', $req->contem . '%');
            });

        }

        if ($req->operadora_gsm) {
            $query->where('operadora_gsm', $req->operadora_gsm);
        }
        if ($req->esn) {
            $query->where('esn', $req->esn);
        }
        if ($req->fone_chip_gsm) {
            $query->where('fone_chip_gsm', $req->fone_chip_gsm);
        }
        if ($req->modelo) {
            $query->where('modelo', $req->modelo);
        }
        if ($req->status) {
            $query->where('status', $req->status);
        }

        if ($req->veiculo_id) {

            $query->whereHas('veiculo', function ($query) use ($req) {

                $query->where('id', $req->veiculo_id);

            });
        }

        if ($req->input('cliente_id.id')) {

            $query->whereHas('veiculo', function ($query) use ($req) {
                $query->whereHas('veiculosXClientes', function ($query) use ($req) {

                    $query->where('cliente_id', $req->input('cliente_id.id'));
                });
            });
        }


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            return $query->get();
        }

        if ($req->include && is_array($req->include)) {
            foreach ($req->include as $inc) {
                $query->with($inc);
            }
        }

        return $query->paginate(100);
    }
}
