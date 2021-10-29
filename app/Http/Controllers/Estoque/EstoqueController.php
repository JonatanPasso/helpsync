<?php

namespace App\Http\Controllers\Estoque;

use App\E\Mensagens;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Models\Estoque\Estoque;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EstoqueController extends Controller
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
            'cliente_id' => 'required|int',
            'nome' => 'required',
            'descricao' => 'string',
//            'criado_por' => 'required|int',
//            'criado_em' => 'required|int',
        ]);

        if ((int)$request->input('id')) {
            $registro = Estoque::where('id', (int)$request->id)->firstOrFail();
        } else {
            $registro = new Estoque();
            $registro->cliente_id = (int)$request->cliente_id;
            $registro->criado_por = $this->getUsuario()->id;
            $registro->criado_em = Carbon::now();
        }

        $registro->nome = Str::upper(trim($request->nome));
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

        $registro = Estoque::where('id', (int)$req->id)->first();

        if ($registro) {

            if ($registro->armazens->count()) {
                return response(Mensagens::ERRO_DB_DELETE, 421);
            }

            app('db')->beginTransaction();

            if ($registro->armazens()->count()) {
                return response(Mensagens::ERRO_DB_DELETE, 402);
            }

            $registro->delete();

            app('db')->commit();

            return response(['msg' => Mensagens::OK_DB_DELETE]);

        }

        return response(Mensagens::ERRO_DB_FIND, 421);


    }

    public function buscar(Request $req)
    {

        $query = Estoque::query();
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

        if ($req->input('filtro.cliente_id')) {
            $query->where('cliente_id', $req->input('filtro.cliente_id'));
        }


        if ($req->input('filtro.contem')) {
            $contem = Util::sanitize($req->input('filtro.contem'));
            $query->where('nome', 'like', "%{$contem}%");

        }


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {

            return $query->get();
        }

        return $query->paginate(500);


    }

}
