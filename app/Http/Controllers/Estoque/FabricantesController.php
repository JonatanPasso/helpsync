<?php

namespace App\Http\Controllers\Estoque;

use App\E\Mensagens;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Models\Estoque\Fabricantes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FabricantesController extends Controller
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
        ]);

        if ((int)$request->input('id')) {
            $registro = Fabricantes::where('id', (int)$request->id)->firstOrFail();
        } else {
            $registro = new Fabricantes();
        }

        $registro->nome = Str::upper(trim($request->nome));

        if ($registro->isDirty()) {
            $registro->save();
        } else {
            return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }

        return $registro;
    }

    public function excluir(Request $req)
    {

        $registro = Fabricantes::where('id', (int)$req->id)->first();

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


        $query = Fabricantes::query();
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

        if ($req->input('filtro.contem')) {

            $contem = Util::sanitize($req->input('filtro.contem'));

            $query->where('nome', 'like', "%{$contem}%");
        }


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {

            return $query->get();
        }

        return $query->paginate(500);

        return $paginator;
    }

}
