<?php

namespace App\Http\Controllers\Estoque;

use App\E\Mensagens;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Models\Estoque\Unidades;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnidadesController extends Controller
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
            'unidade' => "required|unique:estoque_unidades,unidade,{$request->id}",
            'descricao' => 'required'
        ]);

        if ((int)$request->input('id')) {
            $registro = Unidades::where('id', (int)$request->id)->firstOrFail();
        } else {
            $registro = new Unidades();
        }

        $registro->unidade = Str::upper(trim($request->unidade));
        $registro->descricao = Str::upper(trim($request->descricao));

        if ($registro->isDirty()) {
            $registro->save();
        } else {
            return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }

        return $registro;
    }

    public function excluir(Request $req)
    {

        /**
         * @var Unidades $registro
         */
        $registro = Unidades::where('id', (int)$req->id)->first();

        if ($registro) {

            if ($registro->produtos()->count() ||
                $registro->produtosVenda()->count() ||
                $registro->produtosCompra()->count()) {
                return response(Mensagens::ERRO_DB_DELETE, 421);
            }

            app('db')->beginTransaction();

            $registro->delete();

            app('db')->commit();

            return response(['msg' => Mensagens::OK_DB_DELETE]);

        }

        return response(Mensagens::ERRO_DB_FIND, 421);


    }

    public function buscar(Request $req)
    {


        $query = Unidades::query();
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

            $query->where(function ($query) use ($contem) {
                $query->where('unidade', 'like', "%{$contem}%");
                $query->orWhere('descricao', 'like', "%{$contem}%");
            });
        }

        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {

            return $query->get();
        }

        return $query->paginate(500);

    }

}
