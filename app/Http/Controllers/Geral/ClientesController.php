<?php

namespace App\Http\Controllers\Geral;

use App\E\Dic\Geral;
use App\E\Mensagens;
use App\E\Sistema;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Models\Geral\Clientes;
use App\Models\Geral\FileStorage;
use Illuminate\Http\Request;


class ClientesController extends Controller
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

        // var_dump($eq->toArray());exit;

        $agi = Sistema::agi($config, $acao, $eq);


        $validator = app('validator')
            ->make($eq->all(), $agi['validacao']);

        if ($validator->fails()) {

            return response($validator->errors(), 400);
        }

        $registro = $registro ? $registro : new Clientes();

        $registro->forceFill($agi['dados']);

        if ($eq->input('foto_upload_id') &&
            $fileFotoUpload = FileStorage::query()
                ->where('uid', $eq->foto_upload_id)
                ->first()) {

            $fileFotoUpload->setAsProduction();
            $fileFotoUpload->save();

            $registro->url_foto = $fileFotoUpload->url;

        }

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

            if ($cliente->usuarios->count()) {
                return response(Mensagens::ERRO_DB_DELETE, 421);
            }

            $cliente->delete();

            return response(['msg' => Mensagens::OK_DB_DELETE]);

        }

        return response(Mensagens::ERRO_DB_FIND, 421);


    }

    public function buscar(Request $req)
    {

        $query = Clientes::query();


        /**
         * Permite busca somente clients vinculados ao usuario
         * caso mesmo nao seja root
         */
        if ($this->getUsuario()->is_root == 'N') {
            $cliente_ids = $this->getUsuario()->clientes->pluck('id');
            $query->whereIn('id', $cliente_ids);
        }
        //wheres
        //orders
        //includes
        if ($req->has('includes') && is_array($req->get('includes'))) {
            foreach ($req->includes as $rel) {
                $query->with($rel);
            }
        }

        //filtros
        if ($req->has('filtro.contem')) {

            $contem = Util::sanitize($req->input('filtro.contem'));

            $query->where(function ($where) use ($contem, $req) {
                $where->where('nome', 'like', "%{$contem}%");
                $where->orWhere('cpf_cnpj', 'like', "%{$contem}%");
                $where->orWhere('fone1', 'like', "%{$contem}%");
                $where->orWhere('fone2', 'like', "%{$contem}%");
                $where->orWhere('fone3', 'like', "%{$contem}%");

                if ($req->has('filtro.contem_endereco')) {
                    $where->orWhere('cidade', 'like', "%{$contem}%");
                    $where->orWhere('estado', 'like', "%{$contem}%");
                }

            });
        }

        if ($req->input('filtro.estado')) {

            $query->where('estado', trim($req->input('filtro.estado')));
        }

        if ($req->input('filtro.cidade')) {
            $query->where('cidade', trim($req->input('filtro.cidade')));
        }

        if ($req->input('filtro.id')) {
            $query->where('id', (int)Util::sanitize($req->input('filtro.id')));
        }


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            return $query->get();
        }

        $items_por_pagina = (int)$req->items_por_pagina;

        $items_por_pagina = $items_por_pagina < 1 || $items_por_pagina > 999999 ? 1000 : $items_por_pagina;

        return $query->paginate($items_por_pagina);
    }

    public function listarTodasEmpresas()
    {
        return Clientes::all();
    }
}
