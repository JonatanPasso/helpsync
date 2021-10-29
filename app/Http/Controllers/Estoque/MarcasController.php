<?php

namespace App\Http\Controllers\Estoque;

use App\E\Mensagens;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Models\Estoque\Fabricantes;
use App\Models\Estoque\Marcas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function salvar(Request $request)
    {

        $this->validate($request, [
            'id' => 'int',
            'nome' => 'required',
            'fabricante_id' => 'int',
        ]);

        if ((int)$request->input('id')) {
            $registro = Marcas::where('id', (int)$request->id)->firstOrFail();
        } else {
            $registro = new Marcas();
        }

        $registro->nome = Str::upper(trim($request->nome));
        $registro->fabricante_id = (int)$request->fabricante_id ? $request->fabricante_id : null;

        if ($registro->isDirty()) {
            $registro->save();
        } else {
            return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }

        return $registro;
    }

    public function excluir(Request $req)
    {

        $registro = Marcas::where('id', (int)$req->id)->first();

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


        $query = Marcas::query();
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


        if ($req->filled('filtro.fabricante_id')) {


            if ((int)$req->input('filtro.fabricante_id')) {

                $query->where('fabricante_id', '=',
                    $req->input('filtro.fabricante_id'));

            } else {
                $query->whereNull('fabricante_id');
            }

        }


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {

            return $query->get();
        }

        return $query->paginate(500);

        return $paginator;
    }

}
