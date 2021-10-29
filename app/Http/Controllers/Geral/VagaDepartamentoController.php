<?php

namespace App\Http\Controllers\Geral;

use App\Evpar\Filtros;
use App\Models\Geral\Departamentos;
use App\Models\Geral\Empresas;
use App\Models\Geral\FileStorage;
use App\Models\Geral\VagaDepartamento;
use App\Models\Geral\VagaDepartamentoServico;
use App\Models\Geral\Vagas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

class VagaDepartamentoController extends Controller
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

        $entrada = new Fluent(request()->all());

        $entrada->vaga_id = Filtros::filtrar('trim|digitos|null', $entrada->vaga_id);
        $entrada->departamento_id = Filtros::filtrar('trim|digitos|null', $entrada->departamento_id);
        $entrada->vaga_departamento_pai = Filtros::filtrar('trim|digitos|null', $entrada->vaga_departamento_pai);
        $entrada->usuario_id = Filtros::filtrar('trim|digitos|null', $entrada->usuario_id);

        $validar = validator($entrada->toArray(), [
            'servicos' => 'array',
            'vaga_id' => 'required',
            'departamento_id' => 'required',
            'vaga_departamento_pai' => 'integer',
            'usuario_id' => 'integer'
        ]);

        if ($validar->fails()) {
            return response($validar->errors(), 402);
        }

        \DB::beginTransaction();

        $vagaDepatamento = new VagaDepartamento();

        $vagaDepatamento->vaga_id = $entrada->vaga_id;
        $vagaDepatamento->departamento_id = $entrada->departamento_id;
        $vagaDepatamento->vaga_departamento_pai = $entrada->vaga_departamento_pai;
        $vagaDepatamento->usuario_id = $entrada->usuario_id;

        $vagaDepatamento->save();

        if ($entrada->servicos && is_array($entrada->servicos)) {

            foreach ($entrada->servicos as $servico_id) {

                $servico = new VagaDepartamentoServico();
                $servico->vaga_departamento_id = $vagaDepatamento->id;
                $servico->servico_id = $servico_id;
                $servico->save();
            }
        }

        \DB::commit();

        return $vagaDepatamento;

    }

    public function excluir(Request $req)
    {

        $row = VagaDepartamento::whereId((int)$req->id)->first();

        if ($row) {


            if ($row->filhos()->count()) {
                return response('Existem vagas filhas. Exclusão não permitida!', 401);
            }

            \DB::beginTransaction();
//            $row->acessos()->delete();
//            $row->vagaDepartamentoServico()->delete();
            $row->delete();
            \DB::commit();

            return $row;

        }

        return response('Vaga não encontrada.', 404);


    }

    public function alterar()
    {

        $entrada = new Fluent(request()->all());

        $entrada->id = (int)$entrada->id;
        $entrada->vaga_id = Filtros::filtrar('trim|digitos|null', $entrada->vaga_id);

        $validar = validator($entrada->toArray(), [
            'servicos' => 'required|array',
            'vaga_id' => 'required'
        ]);

        if ($validar->fails()) {
            return response($validar->errors(), 402);
        }

        \DB::beginTransaction();

        $vagaDepatamento = VagaDepartamento::whereId($entrada->id)->first();

        if ($vagaDepatamento) {

            $vagaDepatamento->vaga_id = $entrada->vaga_id;

            $vagaDepatamento->vagaDepartamentoServico()->delete();


            foreach ($entrada->servicos as $servico_id) {

                $servico = new vagaDepartamentoServico();
                $servico->vaga_departamento_id = $vagaDepatamento->id;
                $servico->servico_id = $servico_id;
                $servico->save();
            }

            $vagaDepatamento->save();

            \DB::commit();

            return $vagaDepatamento;


        }

    }

    public function listaVagaDepartamento(Request $request)
    {
        $entrada = new Fluent($request->all());

        if ($entrada->hierarquia == 'sim') {

            return VagaDepartamento::whereNull('vaga_departamento_pai')
                ->whereDepartamentoId($entrada->departamento_id)
                ->with('vaga')
                ->with('departamento')
                ->with('usuario')
                ->with('filhos')
                ->with('vagaDepartamentoServico.servico')
                ->get();
        }

        $query = VagaDepartamento::query();

        if ($entrada->id) {
            $query->where('id', (int)$entrada->id);
        }

        if ($entrada->departamento_id && !isset($entrada->opcao)) {
            $query->where('departamento_id', $entrada->departamento_id);
        }

        if ($entrada->departamento_id && $entrada->opcao == 'usuarios') {
            $retorno = $query->where('departamento_id', $entrada->departamento_id)
                ->with('usuario');

            return $retorno->get();
        }

        if ($entrada->usuario_id && $entrada->opcao == 'usuarios') {
            $retorno = $query->where('usuario_id', $entrada->usuario_id)
                ->with('vaga');

            return $retorno->get();
        }

        if ($entrada->departamento_id && $entrada->opcao == 'gestor') {
            $retorno = $query->whereDepartamentoId($entrada->departamento_id)
                ->whereUsuarioId($entrada->usuario_id);

            return $retorno->get();
        }

        if ($entrada->usuario_id) {
            $query->with('vaga');
            $query->where('usuario_id', $entrada->usuario_id);
        }

        return $query->paginate(20);
    }


    public function vincularUsuario()
    {

        $entrada = new Fluent(request()->all());

        $entrada->id = (int)$entrada->id;
        $entrada->usuario_id = Filtros::filtrar('trim|digitos|null', $entrada->usuario_id);

        $validar = validator($entrada->toArray(), [
            'id' => 'required|int',
            'usuario_id' => 'required|int'
        ]);

        if ($validar->fails()) {
            return response($validar->errors(), 402);
        }

        $vagaDepatamento = VagaDepartamento::whereId($entrada->id)->first();

        if ($vagaDepatamento) {

            $vagaDepatamento->usuario_id = $entrada->usuario_id;

            $vagaDepatamento->save();

            return $vagaDepatamento;

        }

        return response('Departamento não encontrado', 404);

    }


    public function removerUsuario()
    {

        $entrada = new Fluent(request()->all());

        $entrada->id = (int)$entrada->id;

        $validar = validator($entrada->toArray(), [
            'id' => 'required|int'
        ]);

        if ($validar->fails()) {
            return response($validar->errors(), 402);
        }

        $vagaDepatamento = VagaDepartamento::whereId($entrada->id)->first();

        if ($vagaDepatamento) {

            $vagaDepatamento->usuario_id = null;

            $vagaDepatamento->save();

            return $vagaDepatamento;

        }

        return response('Departamento não encontrado', 404);

    }


    public function salvar(Request $req)
    {

        \DB::beginTransaction();

        $this->validate($req, ['nome' => 'required']);

        if ($req->id) {
            //update

            $vagaDepartamento = VagaDepartamento::query()
                ->where('id', $req->id)
                ->first();

            if ($vagaDepartamento->vaga_departamento_pai == $req->id) {
                return response('A vaga nao pode ser superior a si mesma.', 401);
                return;
            }


            $vagaDepartamento->vaga->nome = Str::upper($req->nome);
            $vagaDepartamento->vaga->descricao = Str::upper($req->descricao);

            $vagaDepartamento->vaga->save();

            $vagaDepartamento->vaga_departamento_pai = (int)$req->vaga_departamento_pai ? (int)$req->vaga_departamento_pai : null;
            $vagaDepartamento->usuario_id = (int)$req->usuario_id ? (int)$req->usuario_id : null;
            $vagaDepartamento->save();

            $haVagaPai = VagaDepartamento::query()
                ->where('departamento_id', $req->departamento_id)
                ->whereNull('vaga_departamento_pai')
                ->count();

            if (!$haVagaPai) {

                \DB::rollback();

                return response('Nível de ascendencia não pode ser alterado.', 401);
            }


            \DB::commit();

            return $vagaDepartamento;


        } else {
            //insert

            $vaga = new Vagas();
            $vaga->nome = Str::upper($req->nome);
            $vaga->descricao = Str::upper($req->descricao);
            $vaga->save();

            $vagaDepartamento = new VagaDepartamento();
            $vagaDepartamento->departamento_id = $req->departamento_id;

            $vagaDepartamento->usuario_id = (int)$req->usuario_id ? (int)$req->usuario_id : null;
            $vagaDepartamento->vaga_departamento_pai = (int)$req->vaga_departamento_pai ? (int)$req->vaga_departamento_pai : null;
            $vagaDepartamento->vaga_id = $vaga->id;


            $haVagaPai = VagaDepartamento::query()
                ->where('departamento_id', $req->departamento_id)
                ->whereNull('vaga_departamento_pai')
                ->count();

            if ($haVagaPai && $vagaDepartamento->vaga_departamento_pai === null) {
                return response('Já existe uma vaga superior. Novas vagas devem ser criadas abaixo desta!', 402);
            }


            $vagaDepartamento->save();

            \DB::commit();

            return $vagaDepartamento;


        }


    }

    public function buscar(Request $req)
    {

        $query = VagaDepartamento::query();

        if ($req->input('filtro.departamento_id')) {
            $query->where('departamento_id',
                $req->input('filtro.departamento_id'));
        }

        if ($req->input('departamento_id')) {
            $query->where('departamento_id',
                $req->input('departamento_id'));
        }

        if ($req->departamento_id && $req->opcao == 'gestor') {
            $retorno = $query->whereDepartamentoId($req->departamento_id)
                ->whereUsuarioId($req->usuario_id);

            return $retorno->get();
        }

        $query->with('departamento');
        $query->with('vaga');
        $query->with('usuario');
        $query->with('filhos');

        return $query->get();
    }

}
