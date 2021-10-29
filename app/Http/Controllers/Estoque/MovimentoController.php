<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Models\Estoque\Movimento;
use App\Models\Estoque\MovimentoItem;
use App\Models\Estoque\Produtos;
use App\Models\Geral\FileStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovimentoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function transferir(Request $request)
    {

        $this->validate($request, [
            'origem.cliente_id' => 'required|int',
            'origem.armazem_id' => 'required|int',
            'destino.cliente_id' => 'required|int',
            'destino.armazem_id' => 'required|int',
            'itens' => 'array|min:1',
            'notas' => 'max:1000'
        ]);

        if ($request->input('origem.armazem_id') == $request->input('destino.armazem_id')) {
            return response('Armazem de "Origem" e "Destino" são os mesmos', 401);
        }


        foreach ($request->itens as $a) {

            $produto = Produtos::query()
                ->where('id', $a['produto_id'])
                ->firstOrFail();

            $saldo = MovimentoItem::saldo($a['produto_id'], $request->input('origem.armazem_id'));

            $saldo = $saldo - $a['quantidade'];
            if ($saldo < 0) {
                return response("Produto \"{$produto->nome}\" quantidade superior ao saldo de origem.", 401);
            }

        }

        app('db')->beginTransaction();

        $movimento = new Movimento();
        $movimento->tipo = 'TRANFERENCIA';


        $movimento->notas = Str::upper($request->notas);
        $movimento->doc_upload_uid = $request->upload_uid;
        $movimento->realizado_por = $this->getUsuario()->id;
        $movimento->realizado_em = Carbon::now();

        $movimento->cliente_id = $request->input('destino.cliente_id');
        $movimento->estoque_id = $request->input('destino.estoque_id');
        $movimento->armazem_id = $request->input('destino.armazem_id');

        /** Armazem de origem */
        $movimento->origem_empresa_id = $request->input('origem.cliente_id');
        $movimento->origem_estoque_id = $request->input('origem.estoque_id');
        $movimento->origem_armazem_id = $request->input('origem.armazem_id');


        $movimento->save();

        if ($request->upload_uid) {
            $file = FileStorage::query()->where('uid', $request->upload_uid)
                ->first();
            $file->setAsProduction();
            $file->save();
        }

        foreach ($request->itens as $a) {

            $prod = Produtos::query()
                ->where('id', $a['produto_id'])
                ->first();

            /** Descontar na origem  */
            $itemSaida = new MovimentoItem();
            $itemSaida->produto_id = $prod->id;
            //  $itemSaida->estoque_id = $request->input('origem.estoque_id');
            $itemSaida->armazem_id = $request->input('origem.armazem_id');
            $itemSaida->operacao = 'SAIDA';

            $itemSaida->unidade_id = $prod->unidade_id;
            $itemSaida->quantidade = $a['quantidade'];
            $itemSaida->realizado_em = Carbon::now();
            $itemSaida->realizado_por = $this->getUsuario()->id;
            $itemSaida->movimento_id = $movimento->id;
            $itemSaida->save();

            /** Adicionar no destinio */
            $itemEntrada = new MovimentoItem();
            $itemEntrada->produto_id = $prod->id;
            //       $itemEntrada->estoque_id = $request->input('destino.estoque_id');
            $itemEntrada->armazem_id = $request->input('destino.armazem_id');
            $itemEntrada->operacao = 'ENTRADA';

            $itemEntrada->unidade_id = $prod->unidade_id;
            $itemEntrada->quantidade = $a['quantidade'];
            $itemEntrada->realizado_em = Carbon::now();
            $itemEntrada->realizado_por = $this->getUsuario()->id;
            $itemEntrada->movimento_id = $movimento->id;
            $itemEntrada->save();

        }

        app('db')->commit();
        return $movimento;

    }

    public function ajustar(Request $request)
    {
        $this->validate($request, [
            'estoque_id' => 'required|int',
            'armazem_id' => 'required|int',
            'ajuste' => 'array|min:1'
        ]);

        foreach ($request->ajuste as $a) {

            $produto = Produtos::query()
                ->where('id', $a['produto_id'])
                ->firstOrFail();

            $saldo = MovimentoItem::saldo($a['produto_id'], $request->armazem_id);

            if ($a['tipo'] == 'REMOVER') {
                $saldo = $saldo - $a['quantidade'];
                if ($saldo < 0) {
                    return response("Produto \"{$produto->nome}\" com saldo negativo.", 401);
                }
            }

        }


        app('db')->beginTransaction();

        $movimento = new Movimento();
        $movimento->estoque_id = $request->estoque_id;
        $movimento->armazem_id = $request->armazem_id;
        $movimento->notas = Str::upper($request->notas);
        $movimento->doc_upload_uid = $request->upload_uid;
        $movimento->tipo = 'AJUSTE';
        $movimento->realizado_por = $this->getUsuario()->id;
        $movimento->realizado_em = Carbon::now();
        $movimento->save();

        if ($request->upload_uid) {
            $file = FileStorage::query()->where('uid', $request->upload_uid)
                ->first();
            $file->setAsProduction();
            $file->save();
        }

        foreach ($request->ajuste as $a) {

            $prod = Produtos::query()
                ->where('id', $a['produto_id'])
                ->first();

            $item = new MovimentoItem();
            $item->armazem_id = $request->armazem_id;
            $item->produto_id = $prod->id;
            $item->operacao = $a['tipo'] == 'ADICIONAR' ? 'ENTRADA' : 'SAIDA';
            $item->unidade_id = $prod->unidade_id;
            $item->quantidade = $a['quantidade'];
            $item->realizado_em = Carbon::now();
            $item->realizado_por = $this->getUsuario()->id;
            $item->movimento_id = $movimento->id;

            $item->save();

        }

        app('db')->commit();

        return $movimento;

    }

    public function buscar(Request $req)
    {

        $query = Movimento::query();
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

        if ($req->input('filtro.estoque_id')) {
            $query->where('id', $req->input('filtro.cliente_id'));
        }


        if ($req->input('filtro.contem')) {
            $contem = Util::sanitize($req->input('filtro.contem'));
            $query->where('nome', 'like', "%{$contem}%");
        }

        if ($req->input('filtro.tipo')) {
            $query->whereIn('tipo', (array)$req->input('filtro.tipo'));
        }


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            return $query->get();
        }

        return $query->paginate(500);

    }

}
