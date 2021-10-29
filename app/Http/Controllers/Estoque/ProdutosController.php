<?php

namespace App\Http\Controllers\Estoque;

use App\E\Mensagens;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Models\Estoque\Armazem;
use App\Models\Estoque\Categorias;
use App\Models\Estoque\CodigosReferencia;
use App\Models\Estoque\MovimentoItem;
use App\Models\Estoque\Produtos;
use App\Models\Geral\FileStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProdutosController extends Controller
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

        /**
         * Validacao campos
         */
        $this->validate($request, [
            'id' => 'int',
            'nome' => 'required',
            'ncm' => 'required',
            'fabricante_id' => 'int',
            'modelo_id' => 'required|int',
            'categoria_id' => 'required|int',
            'unidade_id' => 'required|int',
            'unidade_venda_id' => 'required|int',
            'unidade_compra_id' => 'required|int',
            'marca_id' => 'required|int',
            'imagem' => '',
            'codigo_referencia' => '',
            //'tipo_cadastro'=>'',
            //'cadastrado_por'=>'',
            //'cadastrado_em'=>'',
            'linha_id' => 'int',
            'descricao' => 'required',
        ]);


        /**
         * Codigos de refencias (mais de um registro )
         */
        $codigos_referencia = [];
        if ($request->input('codigos_referencia') && is_array($request->input('codigos_referencia'))) {
            $codigos_referencia = $request->input('codigos_referencia');
        }


        /**
         * Update (registro ja cadastrado e possui id
         */
        if ((int)$request->input('id')) {
            $registro = Produtos::where('id', (int)$request->id)->firstOrFail();

        } else {

            /**
             * Novo registro
             */

            $registro = new Produtos();
            /**
             * Valor padrao para novos registros
             */
            $registro->tipo_cadastro = 'MANUAL';
            $registro->cadastrado_por = $this->getUsuario()->id;
            $registro->cadastrado_em = Carbon::now();

        }

        /**
         * Campos a serem atualizados ou inseridos
         */
        $registro->nome = trim($request->nome);
        $registro->ncm = trim($request->ncm);
        $registro->fabricante_id = (int)$request->fabricante_id ? $request->fabricante_id : null;
        $registro->modelo_id = (int)$request->modelo_id ? $request->modelo_id : null;
        $registro->categoria_id = (int)$request->categoria_id ? $request->categoria_id : null;
        $registro->unidade_id = (int)$request->unidade_id ? $request->unidade_id : null;
        $registro->unidade_venda_id = (int)$request->unidade_venda_id ? $request->unidade_venda_id : null;
        $registro->unidade_compra_id = (int)$request->unidade_compra_id ? $request->unidade_compra_id : null;
        $registro->marca_id = (int)$request->marca_id ? $request->marca_id : null;
        $registro->codigo_referencia = trim($request->codigo_referencia) ? Str::upper(trim($request->codigo_referencia)) : null;
        $registro->linha_id = (int)$request->linha_id ? $request->linha_id : null;
        $registro->descricao = trim($request->descricao);
        if (trim($request->tipo_codigo_barras)) {
            $registro->tipo_codigo_barras = $request->tipo_codigo_barras;
            $registro->codigo_barras = $request->codigo_barras;
        } else {
            $registro->tipo_codigo_barras = null;
            $registro->codigo_barras = null;
        }

        /**
         * Iniciar transação
         */
        app('db')->beginTransaction();

        /**
         * atualizar imagem de produto se upload id enviado
         */
        if ($request->input('upload_image.uid')) {
            $fileStorage = FileStorage::query()
                ->where('uid', $request->input('upload_image.uid'))
                ->first();
            $registro->imagem = $fileStorage->url;
            $fileStorage->setAsProduction();
            $fileStorage->save();
        }

        /**
         * Se houver algo pra salvar em produtos
         */
        if ($registro->isDirty()) {

            /**
             * insert ou update
             */
            $registro->save();
            /**
             * atualizar codigos de refencia
             */
            $this->atualizarCodigosReferencia($codigos_referencia, $registro);

        } else if (!$this->atualizarCodigosReferencia($codigos_referencia, $registro)) {

            /**
             * nada para alterar . enviar mensgaem
             */
            return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }

        /**
         * Alterações foram realizadas. Commitar
         */
        app('db')->commit();

        $registro->load('codigosReferencia');

        return $registro;
    }

    public function excluir(Request $req)
    {

        $registro = Categorias::where('id', (int)$req->id)->first();

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


        $query = Produtos::query();
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

//        if ($req->input('filtro.armazem_id')) {
//            $query->whereHas('movimentoItem', function ($subquery) use ($req) {
//                $subquery->where('armazem_id', $req->input('filtro.armazem_id'));
//            });
//        }


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {

            return $query->get()
                ->transform(function ($item) use ($req) {
                    $item->saldo = MovimentoItem::saldo($item->id, $req->input('filtro.armazem_id'));
                    return $item;
                });
        }

        $paginator = $query->paginate(500);

        return $paginator;
    }

    /**
     * Atualizar codigos de refencia
     * @param $codigos_referencia Lista de codigos de referencia
     * @param Produtos $registro Produto
     * @return int Qtd registros alterados
     */
    private function atualizarCodigosReferencia($codigos_referencia, Produtos $registro)
    {

        /**
         * Deletar os codigos referencia removidos
         */
        $qtd_codigo_referencia_count = CodigosReferencia::query()
            ->where('produto_id', $registro->id)
            ->whereNotIn('id', collect($codigos_referencia)->pluck('id')->filter())
            ->delete();

        /**
         * Atualizar/inserir os codigos referencia
         */
        foreach (collect($codigos_referencia) as $item) {

            $codigoRef = CodigosReferencia::query()
                ->where('id', (int)Arr::get($item, 'id', null))
                ->firstOrNew([]);

            $codigoRef->produto_id = $registro->id;
            $codigoRef->codigo = $item['codigo'];
            $codigoRef->nome = Str::upper($item['nome']);

            if ($codigoRef->isDirty()) {
                $codigoRef->save();
                $qtd_codigo_referencia_count++;
            }

        }

        return $qtd_codigo_referencia_count;

    }
}
