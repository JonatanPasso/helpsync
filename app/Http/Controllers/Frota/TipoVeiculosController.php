<?php

namespace App\Http\Controllers\Frota;

use App\E\Dic\Frota;
use App\E\Dic\Geral;
use App\E\Mensagens;
use App\E\Sistema;
use App\Http\Controllers\Controller;
use App\Models\Frota\Rastreadores;
use App\Models\Frota\TipoVeiculos;
use App\Models\Geral\Clientes;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TipoVeiculosController extends Controller
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
            unset($dados['esn']);
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

        $query = TipoVeiculos::query();
        //wheres
        //orders
        //includes


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            return $query->get();
        }
        return $query->paginate(100);
    }
}
