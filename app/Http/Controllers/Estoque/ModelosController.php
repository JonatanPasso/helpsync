<?php

namespace App\Http\Controllers\Estoque;

use App\E\Mensagens;
use App\Http\Controllers\Controller;
use App\Models\Estoque\Marcas;
use App\Models\Estoque\Modelos;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModelosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function salvar(Request $request)
    {

        $this->validate($request, [
            'id' => 'int',
            'nome' => 'required',
            'marca_id' => 'required|int',
        ]);

        if ((int)$request->input('id')) {
            $registro = Modelos::where('id', (int)$request->id)->firstOrFail();
        } else {
            $registro = new Modelos();
        }

        $registro->nome = Str::upper(trim($request->nome));
        $registro->marca_id = (int)$request->marca_id;

        if ($registro->isDirty()) {
            $registro->save();
        } else {
            return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }

        return $registro;
    }

    public function excluir(Request $req)
    {

        $registro = Modelos::where('id', (int)$req->id)->first();

        if ($registro) {

//            if ($registro->veiculosXClientes->count()) {
//                return response(Mensagens::ERRO_DB_DELETE, 421);
//            }

            app('db')->beginTransaction();

            $registro->delete();

            app('db')->commit();

            return response(['msg' => Mensagens::OK_DB_DELETE]);

        }

        return response(Mensagens::ERRO_DB_FIND, 421);


    }

    public function buscar(Request $req)
    {


        $query = Modelos::query();
        //wheres
        //orders
        //includes

        if (is_array($req->include)) {

            foreach ($req->include as $inc) {
                $query->with($inc);
            }

        }

        if ($req->input('filtro.id')) {
            $query->where('id', $req->input('filtro.id'));
        }


        if ($req->input('filtro.marca_id')) {


            $query->where('marca_id', '=',
                $req->input('filtro.marca_id'));

        }


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {

            return $query->get();
        }

        return $query->paginate(500);

        return $paginator;
    }

}
