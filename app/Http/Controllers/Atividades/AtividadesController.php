<?php

namespace App\Http\Controllers\Atividades;

use App\Evpar\Filtros;
use App\Evpar\Util;
use App\Models\Atividades\Atendimento;
use App\Models\Atividades\Atividades;
use App\Models\Atividades\Chamados;
use App\Models\Atividades\ChamadosAnexos;
use App\Models\Atividades\ChamadosModerados;
use App\Models\Atividades\ColunasPainel;
use App\Models\Atividades\ColunasPainelUsuarios;
use App\Models\Atividades\Compartilhados;
use App\Models\Atividades\FiltrosUsuarios;
use App\Models\Atividades\Iteracao;
use App\Models\Atividades\Subatividades;
use App\Models\Geral\Departamentos;
use App\Models\Geral\FileStorage;
use App\Models\Geral\Usuarios;
use App\Models\Atividades\Filtros as FiltrosModel;
use App\Models\Geral\VagaDepartamento;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtividadesController extends Controller
{
    /**
     * @api {post} /atividade/cadastro/incluir Cadastrar novas Atividades e Subatividades
     * @apiDescription Para cadastrar novas atividades e subatividades.
     * @apiVersion 0.1.0
     * @apiName IncluirAtividadesSubatividades
     * @apiGroup Atividades
     * @apiPermission Atividade.Cadastro.Incluir
     *
     * @apiParam  {String} nome Nome
     * @apiParam  {String} email Nome
     * @apiParam  {String} [tipo=usuario] master -> usuário master, usuario -> usuário Padrão
     * @apiParam  {String} [senha] Senha do usuário
     *
     * @apiSuccess {Integer} id  Id novo usuario
     * @apiSuccess {String} nome  Nome
     * @apiSuccess {String} email Email
     * @apiSuccess {String} [pefil=usuario]  master|usuario
     * @apiSuccess {String} criado_em  Data do cadastro (AAAA-MM-DD hh:mm:ss)
     *
     *  * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *    {
     *       "id":99
     *      "nome":"Fulano de Tal",
     *      "email":"endereco@dominio.com",
     *      "tipo":"usuario",
     *      "criado_em":{
     *              "date":"2018-02-24 20:13:05.000000",
     *              "timezone_type":3,
     *              "timezone":"America\/Sao_Paulo"},
     *      }
     */

    public function incluir(Request $request)
    {
        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        \DB::beginTransaction();

        $atividade = new Atividades();

        $atividade->nm_atividade = mb_strtoupper($request->nm_atividade, 'UTF-8');
        $atividade->id_departamento = $request->id_departamento;
        $atividade->moderada = $request->moderada;
//        $atividade->privada = $request->privada;
        $atividade->privada = 'N';
        $atividade->criado_em = Carbon::now();
        $atividade->criado_por = $betaUser->id;

        $atividade->save();

        \DB::commit();

        return $atividade;
    }

    public function listarAtividadesCadastradas(Request $request)
    {
        if ($request->ids) {
            return Atividades::getAtividadesPorUsuario((array)$request->ids);
        } else {
            return Atividades::atividadesPermitidas($request->idDepartamento);
        }
    }

    public function getAtividadePorId()
    {
        return Atividades::getAtividadePorId(request('id'));
    }

    public function getAtividadePorDepartamento()
    {
        return Atividades::getAtividadePorDepartamento(request('idDepartamento'))->get();
    }

    public function listarSubAtividadePorAtividade(Request $request)
    {
        return Subatividades::getSubAtividadePorAtividade($request->idAtividade)->get();
    }

    public function incluirSubAtividade(Request $request)
    {

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        \DB::beginTransaction();

        $atividade = new Subatividades();

        $atividade->id_atividade = $request->id_atividade;
        $atividade->nm_subatividade = strtoupper($request->nm_subatividade);
        $atividade->criado_em = Carbon::now();
        $atividade->criado_por = $betaUser->id;

        $atividade->save();

        \DB::commit();

        return $atividade;
    }

    public function alterarSubatividade(Request $request)
    {

        $subAtividade = Subatividades::getSubatividadePorId($request->id_subatividade);

        if (!$subAtividade) {
            return response('Subatividade não encontrada', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $subAtividade->nm_subatividade = $request->nm_subatividade;
        $subAtividade->alterado_em = Carbon::now();
        $subAtividade->alterado_por = $betaUser->id;

        \DB::beginTransaction();

        $subAtividade->save();

        \DB::commit();

        return $subAtividade;
    }

    public function buscarSubAtividades(Request $request)
    {

        if ($request->id_subatividade) {
            return Subatividades::getSubatividadePorId($request->id_subatividade);
        }

        return Subatividades::getSubatividadePorIdAtividade($request->id_atividade);
    }

    public function listaSubatividadePorId(Request $request)
    {
        return Subatividades::getSubatividadePorId($request->id_subatividade);
    }


    public function criarChamado(Request $request)
    {
        $this->validate($request, [
            'id_empresa' => 'required',
            'id_atividade' => 'required',
            'id_departamento' => 'required',
            'id_subatividade' => 'required',
            'id_usuario_criador' => 'required',
            'descricao_chamado' => 'required',
            'titulo_chamado' => 'required',
            'tipo_chamado' => 'required',
            'prioridade_chamado' => 'required',
            'data_inicio_chamado' => 'required|dateFormat:d/m/Y'
        ]);


        $betaUser = $this->getUsuario();

        \DB::beginTransaction();

        $chamado = new Chamados();

        $atividade = Atividades::query()
            ->where('id', $request->id_atividade)
            ->first();

        $executor = $request->id_usuario_executor;

        if ($atividade->moderada != 'S') {

            $this->validate($request, ['id_usuario_executor' => 'required']);

        } else {

            /**
             * @todo Melhorar logica desta atribuiçao
             */
            $vagaDepartamento = VagaDepartamento::query()
                ->where('departamento_id', $request->id_departamento)
                ->whereNull('vaga_departamento_pai')
                ->whereNotNull('usuario_id')
                ->first();

            if (!$vagaDepartamento) {
                return response('Departamento sem gestor definido', 420);
            }

            $executor = $vagaDepartamento->usuario_id;

        }

        $chamado->id_empresa = $request->id_empresa;
        $chamado->id_departamento = $request->id_departamento;
        $chamado->id_atividade = $request->id_atividade;
        $chamado->id_subatividade = $request->id_subatividade;
        $chamado->id_usuario_executor = $executor;
        $chamado->id_usuario_criador = $request->id_usuario_criador;
//		$chamado->mes_dia_controle_chamado = Carbon::now()->format('Yd');
        $chamado->titulo_chamado = mb_strtoupper($request->titulo_chamado, 'UTF-8');
        $chamado->tipo_chamado = $request->tipo_chamado;
        $chamado->prioridade_chamado = $request->prioridade_chamado;
        $chamado->status_chamado = $request->status_chamado;
        $chamado->data_inicio_chamado = Carbon::createFromFormat('d/m/Y', $request->data_inicio_chamado)->format('Y-m-d');
//		$chamado->data_inicio_chamado = Filtro::formata_data($request->data_inicio_chamado, 'd/m/Y', 'Y-m-d');
        $chamado->descricao_chamado = $request->descricao_chamado;
        $chamado->chamado_privado = $request->chamado_privado;
        $chamado->moderou_chamado = $request->moderou_chamado;
//        $chamado->uid_anexo = $request->uidAnexo;
        $chamado->criado_por = $betaUser->id;
        $chamado->criado_em = Carbon::now();
        $chamado->quem_observa = 'N'; //$request->quem_observa

        $chamado->save();

        if (count($request->uidAnexo) >= 1) {

            foreach ($request->uidAnexo as $ks => $lt) {


                $fileStorage = FileStorage::whereUid($lt['token'])->first();
                if ($fileStorage) {

                    $anexos = new ChamadosAnexos();

                    $anexos->uid = $lt['token'];
                    $anexos->chamado_id = $chamado->id;
                    $anexos->nome_anexo = $lt['titulo_anexo'];
                    $anexos->nome_original = $lt['original_name'];
                    $anexos->token_url = $lt['img_thumb'];
                    $anexos->url = $lt['url'];
                    $anexos->mime_type = $lt['mimeType'];
                    $anexos->criado_por = $betaUser->id;
                    $anexos->criado_em = Carbon::now();

                    $fileStorage->setAsProduction();
                    $fileStorage->save();

                    $anexos->save();
                }
            }
        }

        \DB::commit();

        return $chamado;
    }

    public function alterarChamado(Request $request)
    {

        $this->validate($request, [
            'id_empresa' => 'required',
            'id_atividade' => 'required',
            'id_departamento' => 'required',
            'id_subatividade' => 'required',
            'id_usuario_criador' => 'required',
            'descricao_chamado' => 'required',
            'titulo_chamado' => 'required',
            'tipo_chamado' => 'required',
            'prioridade_chamado' => 'required',
            'id_usuario_executor' => 'required',
            'data_inicio_chamado' => 'required|dateFormat:d/m/Y'
        ]);


        $betaUser = $this->getUsuario();
        $chamado = Chamados::listarChamadosPorNr($request->nr_chamado);

        if (!$chamado) {
            return response('Chamado não encontrado', 400);
        }

        $chamado->id_empresa = $request->id_empresa;
        $chamado->id_departamento = $request->id_departamento;
        $chamado->id_atividade = $request->id_atividade;
        $chamado->id_subatividade = $request->id_subatividade;
        $chamado->id_usuario_executor = $request->id_usuario_executor;
        $chamado->id_usuario_criador = $request->id_usuario_criador;
        $chamado->titulo_chamado = $request->titulo_chamado;
        $chamado->tipo_chamado = $request->tipo_chamado;
        $chamado->prioridade_chamado = $request->prioridade_chamado;
        $chamado->status_chamado = $request->status_chamado;

        $chamado->data_inicio_chamado =
            Carbon::createFromFormat('d/m/Y',
                $request->data_inicio_chamado);

        $chamado->descricao_chamado = $request->descricao_chamado;
        $chamado->chamado_privado = $request->chamado_privado;
        $chamado->uid_anexo = $request->uidAnexo;
        $chamado->criado_por = $betaUser->id;
        $chamado->criado_em = Carbon::now();
        $chamado->quem_observa = $request->quem_observa;


        \DB::beginTransaction();

        $fileStorage = FileStorage::whereUid($request->uidAnexo)->first();

        if ($fileStorage) {
            $fileStorage->setAsProduction();
            $fileStorage->save();
        }

        $chamado->save();

        \DB::commit();

        return $chamado;
    }

    public function criarListaAtendimentoChamado(Request $request)
    {
        $betaUser = Usuarios::where('id', Auth::user()->id)->firstOrFail();

        \DB::beginTransaction();

        $atendimento = new Atendimento();

        $atendimento->id_chamado = $request->id_chamado;
        $atendimento->id_usuario_chamado = $request->id_usuario_chamado;
        $atendimento->criado_por = $betaUser->id;
        $atendimento->criado_em = Carbon::now();

        $atendimento->save();

        \DB::commit();

        return $atendimento;
    }

    public function buscarAtividades(Request $request)
    {

        if ($request->id) {
            return Atividades::getAtividadePorId($request->id);
        }

        if ($request->ids) {
            return Atividades::getAtividadesPorUsuario((array)$request->ids);
        } else {
            return Atividades::atividadesPermitidas($request->idDepartamento);
        }
    }

    public function listarChamados(Request $request)
    {
        return Chamados::listarChamados($request);
    }

    public function listarChamadosPorNr(Request $request)
    {

        $chamado = Chamados::listarChamadosPorNr($request->nr_chamado);

        /**
         *
         */
        if ($request->input('gravar_leitura') == 'sim') {
            $chamado->leitura_em = Carbon::now();
            $chamado->save();
        }

        $chamado->moderar_ou_iniciar = false;

        // verifica se é gestor do departamento */
        $moderador = VagaDepartamento::where('departamento_id',
            $chamado->id_departamento)
            ->whereNotNull('vaga_departamento_pai')
            ->where('usuario_id', $this->getUsuario()->id)
            ->count();

        /**
         * Verifica se o chamado é moderado
         */
        $moderado = $chamado->atividadeChamado->moderada == 'S' ? true : false;

        if ($chamado->status_chamado == 'CE' && $moderador && $moderado) {
            $chamado->moderar_ou_iniciar = true;
        }

        return $chamado;
    }

    public function iniciarChamado(Request $request)
    {

        $iteracao = Chamados::listarChamadosPorNr($request->nr_chamado);

        if (!$iteracao) {
            return response('Chamado não encontrado!', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $iteracao->estimativa_horas = $request->estimativa_horas;
        $iteracao->status_chamado = $request->status_chamado;
        $iteracao->alterado_em = Carbon::now();
        $iteracao->alterado_por = $betaUser->id;
        $iteracao->leitura_em = null;

        \DB::beginTransaction();

        $iteracao->save();

        \DB::commit();

        return $iteracao;
    }

    public function finalizaIteracaoChamado(Request $req)
    {
        $iteracao = Chamados::listarChamadosPorNr($req->nr_chamado);

        if (!$iteracao) {
            return response('Chamado não encontrado!', 400);
        }

        $iteracao->status_chamado = $req->status_chamado;
        $iteracao->avaliacao_chamado = $req->avaliacao_chamado;
        $iteracao->alterado_em = Carbon::now();
        $iteracao->alterado_por = Auth::user()->id;
        $iteracao->leitura_em = null;

        \DB::beginTransaction();

        $iteracao->save();

        \DB::commit();

        return $iteracao;
    }

    public function incluirIteracao(Request $request)
    {

        if ($request->justificativa_iteracao_chamado == '') {
            $this->validate($request,
                ['justificativa_iteracao_chamado' => 'required'],
                ['justificativa_iteracao_chamado.required' => 'O campo justificativa de interação obrigatória!']
            );
        }

        $betaUser = $this->getUsuario();

        \DB::beginTransaction();

        $iteracao = new Iteracao();

        $iteracao->id_chamado = $request->id_chamado;
//        $iteracao->uid_anexo = $request->uidAnexo;
        $iteracao->tempo_executado = $request->tempo_executado ? $request->tempo_executado : $this->tempoTrabalhoExecutado($request);
        $iteracao->justificativa_iteracao_chamado = $request->justificativa_iteracao_chamado;
        $iteracao->criado_por = $betaUser->id;
        $iteracao->criado_em = Carbon::now();

        $iteracao->save();
        $iteracao->id;

        if (is_array($request->uidAnexo)) {
            if (count($request->uidAnexo) >= 1) {

                foreach ($request->uidAnexo as $ks => $lt) {

                    $fileStorage = FileStorage::whereUid($lt['token'])->first();

                    if ($fileStorage) {

                        $anexos = new ChamadosAnexos();

                        $anexos->uid = $lt['token'];
                        $anexos->chamado_id = $request->id_chamado;
                        $anexos->iteracao_id = $iteracao->id;
                        $anexos->nome_anexo = $lt['titulo_anexo'];
                        $anexos->nome_original = $lt['original_name'];
                        $anexos->token_url = $lt['img_thumb'];
                        $anexos->url = $lt['url'];
                        $anexos->mime_type = $lt['mimeType'];
                        $anexos->criado_por = $betaUser->id;
                        $anexos->criado_em = Carbon::now();

                        $fileStorage->setAsProduction();
                        $fileStorage->save();
                    }

                    $anexos->save();
                }
            }
        }

        /**
         * Buscando usuários por departamento
         */

        if (empty($request->detartamento_id)) {

            /**
             * If para moderador de chamado.
             */
            if ($request->input('id_usuario_chamado')) {
                if (!is_array($request->id_usuario_chamado)) {
                    $atendimento = new Atendimento();

                    $atendimento->id_chamado = $request->id_chamado;
                    $atendimento->id_usuario_chamado = $request->id_usuario_chamado;
                    $atendimento->criado_por = $betaUser->id;
                    $atendimento->criado_em = Carbon::now();

                    $atendimento->save();

                } else {

                    $variavelAux = $request->id_usuario_chamado;

                    /**
                     * Usuários Compartilhados
                     */
                    if (count($variavelAux) >= 1) {

                        foreach ($variavelAux as $k => $v) {
                            $atendimento = new Atendimento();

                            $atendimento->id_chamado = $request->id_chamado;
                            $atendimento->id_usuario_chamado = $v;
                            $atendimento->criado_por = $betaUser->id;
                            $atendimento->criado_em = Carbon::now();

                            $atendimento->save();
                        }

                        foreach ($variavelAux as $c => $l) {
                            $compartilhado = new Compartilhados();

                            $compartilhado->id_chamado = $request->id_chamado;
                            $compartilhado->id_usuario_compartilhado = $l;
                            $compartilhado->id_iteracao_chamado = $iteracao->id;
                            $compartilhado->criado_por = $betaUser->id;
                            $compartilhado->criado_em = Carbon::now();

                            $compartilhado->save();
                        }
                    }
                }
            }
        } else {

            if (is_array($request->detartamento_id)) {

                foreach ($request->detartamento_id as $ks => $lt) {
                    $variavelUd = VagaDepartamento::query()
                        ->where('departamento_id', $lt)
                        ->where('usuario_id', '<>', null)
                        ->get(['departamento_id', 'usuario_id'])
                        ->groupBy('departamento_id');

                    if (count($variavelUd) > 0) {
                        $variavelAux[] = $variavelUd;
                    }
                }
                /**
                 * Usuários Compartilhados
                 */

                if (count($variavelAux) >= 1) {

                    foreach ($variavelAux as $k => $v) {
                        foreach ($v as $i => $s) {
                            foreach ($s as $tt => $kk) {

                                $atendimento = new Atendimento();

                                $atendimento->id_chamado = $request->id_chamado;
                                $atendimento->id_usuario_chamado = $kk['usuario_id'];
                                $atendimento->criado_por = $betaUser->id;
                                $atendimento->criado_em = Carbon::now();

                                $atendimento->save();

                                $compartilhado = new Compartilhados();

                                $compartilhado->id_chamado = $request->id_chamado;
                                $compartilhado->id_usuario_compartilhado = $kk['usuario_id'];
                                $compartilhado->departamento_id = $kk['departamento_id'];
                                $compartilhado->id_iteracao_chamado = $iteracao->id;
                                $compartilhado->criado_por = $betaUser->id;
                                $compartilhado->criado_em = Carbon::now();

                                $compartilhado->save();
                            }
                        }
                    }
                }
            }
        }

        \DB::commit();

        return $iteracao;
    }

    protected function tempoTrabalhoExecutado($request)
    {
        $ultimaIteracao = Iteracao::getUltima($request->id_chamado);

        $lastDate = Carbon::createFromFormat('Y-m-d H:i:s', $ultimaIteracao->last()['criado_em']);
        $dateAtual = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());

        $resto = $lastDate->diffInSeconds($dateAtual);

        $horas = floor($resto / 3600);
        $minutos = floor(($resto - ($horas * 3600)) / 60);
        $segundos = floor($resto % 60);

        return $horas . ":" . $minutos . ":" . $segundos;
    }

    public function listarHistorico(Request $request)
    {
        return Iteracao::getAllIteracoes($request->id_chamado);
    }

    public function consultaAtendimento(Request $req)
    {

        $hasAtende = Atendimento::hasAtendimento($req);

        if (count($hasAtende) == 0) {
            return response('Este chamado somente poderá ser visualizado!', 400);
        }

        return $hasAtende;

    }

    public function listaUsuariosCompartilhados(Request $request)
    {
        return Atendimento::listaUsuariosCompartilhados($request->id_chamado);
    }

    public function atualizaUsuarioExecutor(Request $request)
    {
        $moderacao = Chamados::listarChamadosPorNr($request->nr_chamado);

        if (!$moderacao) {
            return response('Chamado não encontrado!', 400);
        }

        $usuario = $this->getUsuario();

        $moderacao->id_usuario_executor = $request->id_usuario_executor;
        $moderacao->moderou_chamado = '1';
        $moderacao->alterado_em = Carbon::now();
        $moderacao->alterado_por = $usuario->id;

        \DB::beginTransaction();

        $moderacao->save();

        \DB::commit();

        return $moderacao;
    }

    public function consultaAtividade(Request $request)
    {
        return Atividades::consultaAtividadePorId($request);
    }

    public function salvarChamadoModerado(Request $request)
    {
        $betaUser = $this->getUsuario();

        \DB::beginTransaction();

        $moderacao = new ChamadosModerados();

        $moderacao->id_chamado = $request->id_chamado;
        $moderacao->id_usuario_moderado = $request->id_usuario_moderado;
        $moderacao->id_iteracao = $request->id_iteracao;
        $moderacao->criado_por = $betaUser->id;
        $moderacao->criado_em = Carbon::now();

        $moderacao->save();

        \DB::commit();

        return $moderacao;
    }

    public function salvarAnexos(Request $request)
    {
        $betaUser = $this->getUsuario();

        \DB::beginTransaction();

        $anexos = new ChamadosAnexos();


    }

    public function hasMovement(Request $request)
    {
        $retorno = Chamados::hasMovementActivity($request);

        if ($retorno) {
            return response('Esta atividade já foi usada em outro chamado e não poderá ser modificada ou excluida.', 421);
        }

        return response(['Pode editar'], 200);
    }

    public function excluirAtividade(Request $request)
    {
        return Atividades::excluirAtividade($request);
    }

    public function hasMovementSubactivity(Request $request)
    {
        $retorno = Chamados::hasMovementSubactivity($request);

        if ($retorno) {
            return response('Esta subatividade já foi usada em outro chamado e não poderá ser modificada ou excluida.', 421);
        }

        return response(['Exclusão permitida'], 200);
    }

    public function excluirSubAtividade(Request $request)
    {
        $resultDel = Subatividades::excluirSubAtividade($request);

        if ($resultDel >= 1) {
            return response('Excluido com sucesso!', 200);
        }

        return response('Nenhum registro excluido', 421);
    }

    public function atualizaAtividade(Request $request)
    {

        $atividade = Atividades::consultaAtividadePorId($request);

        if (!$atividade) {
            return response('Atividade não encontrada!', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $atividade->nm_atividade = $request->nm_atividade;
        $atividade->id_departamento = $request->id_departamento;
        $atividade->moderada = $request->moderada;
        $atividade->privada = $request->privada;
        $atividade->alterado_em = Carbon::now();
        $atividade->alterado_por = $betaUser->id;

        \DB::beginTransaction();

        $atividade->save();

        \DB::commit();

        return $atividade;
    }

    public function listarDepartamentoPorId(Request $request)
    {
        return Departamentos::getDepartamentoPorId($request->id_departamento);
    }

    public function listarFiltros(Request $request)
    {
        return FiltrosModel::getAllFiltros($request);
    }

    public function listarColunas(Request $request)
    {
        return ColunasPainel::getAllColunas($request);
    }

    public function atualizaStatusFiltros(Request $request)
    {

        $filtro = FiltrosUsuarios::consultaFiltroUsuarioPorId($request);


        if (!$filtro) {
            return self::incluiFiltro($request);
        }


        $filtro->status_filtro = $request->status == 'true' ? 'A' : 'I';

        \DB::beginTransaction();

        $filtro->save();

        \DB::commit();

        return $filtro;
    }

    public function atualizaStatusColunas(Request $request)
    {
        $colunas = ColunasPainelUsuarios::consultaColunasUsuarioPorId($request);

        if (!$colunas) {
            return self::incluirColuna($request);
        }

        $colunas->status = $request->status == 'true' ? 'A' : 'I';

        \DB::beginTransaction();

        $colunas->save();

        \DB::commit();

        return $colunas;
    }

    public function incluiFiltro($request)
    {
        $betaUser = $this->getUsuario();


        \DB::beginTransaction();

        $filtrosUsuarios = new FiltrosUsuarios();

        $filtrosUsuarios->id_filtro = $request->idFiltro;
        $filtrosUsuarios->id_usuario = $request->idUsuario;
        $filtrosUsuarios->status_filtro = $request->status == 'true' ? 'A' : 'I';

        $filtrosUsuarios->save();

        \DB::commit();

        return $filtrosUsuarios;

    }

    public function incluirColuna($request)
    {
        $betaUser = $this->getUsuario();

        \DB::beginTransaction();

        $colunaUsuario = new ColunasPainelUsuarios();

        $colunaUsuario->id_coluna_painel = $request->idColuna;
        $colunaUsuario->id_usuario = $request->idUsuario;
        $colunaUsuario->status = $request->status == 'true' ? 'A' : 'I';

        $colunaUsuario->save();

        \DB::commit();

        return $colunaUsuario;

    }

    public function buscar(Request $req)
    {
        $query = Atividades::query();


        $query->whereHas('departamento', function (Builder $query) use ($req) {

            $query->where('empresa_id', $req->empresa_id);
        });

        if ($req->include) {
            foreach ((array)$req->include as $inc) {
                $query->with($inc);
            }
        }

        return $query->get();

    }

    public function tranferirAtividadeExecutor(Request $request)
    {
        $chamadoAtividadeExec = Chamados::listarChamadosPorNr($request->nr_chamado);

        if (!$chamadoAtividadeExec) {
            return response('Chamado não encontrada', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        if ($request->tipo_alteracao == 'A') {
            $chamadoAtividadeExec->id_atividade = $request->id_atividade;
            $chamadoAtividadeExec->id_subatividade = $request->id_subatividade;
            $chamadoAtividadeExec->alterado_em = Carbon::now();
            $chamadoAtividadeExec->alterado_por = $betaUser->id;
        } else {
            $chamadoAtividadeExec->id_usuario_executor = $request->id_usuario_executor;
            $chamadoAtividadeExec->alterado_em = Carbon::now();
            $chamadoAtividadeExec->alterado_por = $betaUser->id;
        }

        \DB::beginTransaction();

        $chamadoAtividadeExec->save();

        \DB::commit();

        return $chamadoAtividadeExec;
    }

    public function listarTotalChamadosPorDepartamento(Request $request)
    {
        return Chamados::listarTotalChamadosPorDepartamento();
    }

    public function listarTotalChamadosPorStatus(Request $request)
    {
        return Chamados::listarTotalChamadosPorStatus();
    }

    public function listarTotalAtividades(Request $request)
    {
        return Chamados::listarTotalAtividades();
    }

    public function filtrosRelatorioChamados(Request $request)
    {

        $query = Chamados::query();

        if($request->empresa_id){
            $query->where('id_empresa', $request->empresa_id);
        }

        if($request->departamento_id){
            $query->where('id_departamento', $request->departamento_id);
        }

        if($request->atividade_id){
            $query->where('id_atividade', $request->atividade_id);
        }

        if($request->subatividade_id){
            $query->where('id_atividade', $request->subatividade_id);
        }

        if($request->criador_id){
            $query->where('id_usuario_criador', $request->criador_id);
        }

        if($request->executor_id){
            $query->where('id_usuario_executor', $request->executor_id);
        }

        if($request->tipo){
            $query->where('tipo_chamado', $request->tipo);
        }

        if($request->prioridade){
            $query->where('prioridade_chamado', $request->prioridade);
        }

        if($request->data_inicio){
            $query->whereBetween(\DB::raw('DATE(criado_em)'),[
                Carbon::createFromFormat('d/m/Y', $request->data_inicio)->format('Y-m-d'),
                Carbon::createFromFormat('d/m/Y', $request->data_fim)->format('Y-m-d')
            ]);
        }

        return $query->get();
    }
}
