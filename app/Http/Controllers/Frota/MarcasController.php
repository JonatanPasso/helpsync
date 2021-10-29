<?php

namespace App\Http\Controllers\Frota;

use App\E\Dic\Geral;
use App\E\Mensagens;
use App\E\Sistema;
use App\Http\Controllers\Controller;
use App\Models\Frota\Marcas;
use App\Models\Geral\Clientes;
use Illuminate\Http\Request;

class MarcasController extends Controller
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

        $registro = Clientes::where('id', (int)$eq->id)->first();
        $config = Geral::clientes($registro);
        $acao = __FUNCTION__;

        $agi = Sistema::agi($config, $acao, $eq);


        $validator = app('validator')
            ->make($eq->all(), $agi['validacao']);

        if ($validator->fails()) {

            return response($validator->errors(), 400);
        }

        $registro = $registro ? $registro : new Clientes();

        $registro->forceFill($agi['dados']);

        if (!$registro->isDirty()) {
            return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }
        $registro->save();


        return $registro;


    }

    public function excluir(Request $req)
    {

        $cliente = Clientes::where('id', (int)$req->id)->first();

        if ($cliente) {

           if($cliente->usuarios->count()){
               return response(Mensagens::ERRO_DB_DELETE, 421);
           }

            $cliente->delete();

            return response(['msg' => Mensagens::OK_DB_DELETE]);

        }

        return response(Mensagens::ERRO_DB_FIND, 421);


    }

    public function buscar(Request $req)
    {

        $query = Marcas::query();
        //wheres
        //orders
        //includes


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            return $query->get();
        }
        return $query->paginate(100);
    }
}
