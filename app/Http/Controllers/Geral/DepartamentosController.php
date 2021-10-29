<?php

namespace App\Http\Controllers\Geral;


use App\Evpar\Filtros;
use App\Models\Geral\Departamentos;
use App\Models\Geral\Clientes;
use App\Models\Geral\FileStorage;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

class DepartamentosController extends Controller
{

    /**
     * @api {get} /empresas Lista de Empresas
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
        $entrada->empresa_id = Filtros::filtrar('digitos', $entrada->empresa_id);
        $entrada->departamento_pai_id = Filtros::filtrar('digitos|null', $entrada->departamento_pai_id);

        /**
         * Validar entrada de dados
         * @var $validacao \Validator
         */
        $validacao = validator($entrada->toArray(), [
            'empresa_id' => 'required|integer',
            'departamento_pai_id' => 'integer',
            'nome' => 'required'
        ]);


        if ($validacao->fails()) {
            return response($validacao->errors()->toArray(), 402);
        }

        /**
         * Parsistir registro
         */
        $departamento = new Departamentos();
        $departamento->nome = $entrada->nome;
        $departamento->empresa_id = $entrada->empresa_id;
        $departamento->departamento_pai_id = $entrada->departamento_pai_id;
        $departamento->save();

        return $departamento;

    }

    public function excluir(Request $req)
    {

        $departamento = Departamentos::whereId((int)$req->id)->firstOrFail();

        if ($departamento->filhos()->count()) {
            return response('Existem departamentos a baixo deste. Exclusão nao permitida!', 400);
        }

        if ($departamento->vagasDepartamento()->count()) {
            return response('Existem vagas vinculadas. Exclusão nao permitida!', 400);
        }

        $departamento->delete();

        return response(['Departamento excluído!']);


    }

    public function alterar()
    {
        /**
         * Filtrar entrada de dados
         */
        $entrada = new Fluent(request()->all());
        $entrada->nome = Filtros::filtrar('trim:maiusculo', $entrada->nome);

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
        $departamento = Departamentos::whereId((int)request('id'))->firstOrFail();

        $departamento->nome = $entrada->nome;

        $departamento->save();

        return $departamento;

    }

    public function listar()
    {

        $query = Departamentos::query();

        $query->with('vagasDepartamento.departamento');
        $query->with('vagasDepartamento.vagaDepartamentoServico.servico');
        $query->with('vagasDepartamento.vaga');

        $entrada = new Fluent(request()->all());


        if ($entrada->busca) {
            $query->where(function ($query) use ($entrada) {
                $query->orWhere('nome', 'LIKE', "%{$entrada->busca}%");
            });
        }

        if ($entrada->id) {
            $query->where('id', (int)$entrada->id);
        }


        return $query->paginate(1000);
    }

    public function listarDepartamentosPorEmpresa(Request $request)
    {
        return Departamentos::getDepartamentosPorEmpresa($request)->get();
    }


    public function buscar(Request $req)
    {


//        $this->validate($req, ['empresa_id' => 'required']);

        $query = Departamentos::query();

        $query->orderBy('nome');

        if ($req->input('empresa_id')) {
            $query->where('empresa_id', (int)$req->empresa_id);
        }

        if ($req->input('filtro.id')) {
            $query->where('id', (int)$req->input('filtro.id'));
        }


        return $query->get();

    }

    public function salvar(Request $req)
    {

        if ($req->id) {

            $departamento = Departamentos::query()
                ->where('id', (int)$req->id)
                ->first();


            if ($departamento) {

                if ($departamento->departamento_pai_id == null && $req->departamento_pai_id) {

                    return
                        response('Departamento ASCENDENTE não pode ser alterado.', 421);

                }

                if ($departamento->id == $req->departamento_pai_id) {

                    return
                        response('O mesmo departamento não pode ser ASCENDENTE.', 421);

                }

            }


        } else {
            $departamento = new Departamentos();
            $departamento->empresa_id = $req->empresa_id;
        }

        if ($departamento) {


            $departamento->nome = Str::upper(trim($req->nome));
            $departamento->departamento_pai_id = (int)$req->departamento_pai_id ? $req->departamento_pai_id : null;

            $departamento->save();

            return $departamento;
        }

        return response('Departamento não encontrado.', 400);

    }

}
