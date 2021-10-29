<?php

namespace App\Http\Controllers\Geral;


use App\Evpar\Filtros;
use App\Models\Beta\Everyday\Departamentos;
use App\Models\Beta\Everyday\Empresas;
use App\Models\Beta\Everyday\FileStorage;
use App\Models\Beta\Everyday\Vagas;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Fluent;

class VagasController extends Controller
{

    /**
     * @api {get} /empresas Lista de Vagas
     * @apiVersion 0.1.0
     * @apiName ListarEmpress
     * @apiGroup Empresas
     * @apiPermission Everyday.ConsultarEmpresas
     * @apiSuccess {Integer} id Id Empresa.
     * @apiSuccess {String} nome  Nome Empresa.
     * @apiSuccess {String} url_logo  URl imagem logo.
     * @apiSuccess {String} nome_fantasia  Nome fantasia.
     * @apiSuccess {String} inscr_municipal  Codigo Insc Municipal.
     * @apiSuccess {String} inscr_estadual  Codigo Insc Estadual.
     * @apiSuccess {String} data_abertua  Data Abertura (AAAA-MM-DD).
     * @apiSuccess {String} cnpj_cpf  Numero CNPJ/CPf .
     * @apiSuccess {Integer} empresa_matriz_id  Codigo Empresa Pai.
     * @apiSuccess {Integer} cadastrada_por  Id usuario fez cadastro.
     * @apiSuccess {String} cadastrada_em  Data do cadastro (AAAA-MM-DD hh:mm:ss).
     *
     *  * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [{
     *       "id": 99,
     *       "nome": "Evpar Investimentos",
     *       ...
     *     },{
     *       "id": 98,
     *       "nome": "Inovarti",
     *       ...
     *     }]
     */

    public function incluir()
    {

        /**
         * Filtrar entrada de dados
         */
        $entrada = new Fluent(request()->all());
        $entrada->nome = Filtros::filtrar('trim:maiusculo', $entrada->nome);
        $entrada->descricao = Filtros::filtrar('trim:maiusculo', $entrada->descricao);

        /**
         * Validar entrada de dados
         * @var $validacao \Validator
         */
        $validacao = validator($entrada->toArray(), [
            'nome' => 'required'
        ]);


        if ($validacao->fails()) {
            return response($validacao->errors()->toArray(), 402);
        }

        /**
         * Parsistir registro
         */
        $vaga = new Vagas();
        $vaga->nome = $entrada->nome;
        $vaga->descricao = $entrada->descricao;
        $vaga->save();

        return $vaga;

    }

    public function excluir()
    {

//        $departamento = Departamentos::whereId((int)request('id'))->firstOrFail();
//
//        if ($departamento->filhos->count()) {
//            return response('Existem departamentos a baixo deste. Exclusão nao permitida!', 400);
//        }
//
//        $departamento->delete();
//
//        return response('Departamento excluído!');


    }

    public function alterar()
    {
        /**
         * Filtrar entrada de dados
         */
        $entrada = new Fluent(request()->all());
        $entrada->nome = Filtros::filtrar('trim:maiusculo', $entrada->nome);
        $entrada->descricao = Filtros::filtrar('trim:maiusculo', $entrada->descricao);

        /**
         * Validar entrada de dados
         * @var $validacao \Validator
         */
        $validacao = validator($entrada->toArray(), [
            'nome' => 'required'
        ]);


        if ($validacao->fails()) {
            return response($validacao->errors()->toArray(), 402);
        }

        /**
         * Parsistir registro
         */
        $vaga = Vagas::whereId((int)request('id'))->firstOrFail();

        $vaga->nome = $entrada->nome;
        $vaga->descricao = $entrada->descricao;
        $vaga->save();

        return $vaga;

    }

    public function listar()
    {

        $query = Vagas::query();

        $entrada = new Fluent(request()->all());


        if ($entrada->busca) {
            $query->where(function ($query) use ($entrada) {
                $query->orWhere('nome', 'LIKE', "%{$entrada->busca}%");
                $query->orWhere('descricao', 'LIKE', "%{$entrada->busca}%");
            });
        }

        if ($entrada->id) {
            $query->where('id', (int)$entrada->id);
        }

        $query->orderBy('nome', 'ASC');

        return $query->paginate(1000);
    }

}
