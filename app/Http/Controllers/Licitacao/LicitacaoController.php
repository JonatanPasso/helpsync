<?php

namespace App\Http\Controllers\Licitacao;

use App\Evpar\Filtros;
use App\Evpar\Util;
use App\Http\Controllers\Controller;
use App\Models\Contrato\Proposta;
use App\Models\Geral\FileStorage;
use App\Models\Geral\Usuarios;
use App\Models\Contrato\PreContrato;
use App\Models\Licitacao\Licitacao;
use App\Models\Licitacao\Lotes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicitacaoController extends Controller
{

    public function salvarLicitacao(Request $request)
    {
        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        \DB::beginTransaction();

        $licitacao = new Licitacao();

        $licitacao->id_contratante = $request->id_contratante;
        $licitacao->status_licitacao = $request->status_licitacao;
        $licitacao->modalidade = $request->modalidade;
        $licitacao->tipo_licitacao = $request->tipo_licitacao;
        $licitacao->regime = $request->regime;
        $licitacao->numero_licitacao = $request->numero_licitacao;
        $licitacao->numero_edital = $request->numero_edital;
        $licitacao->local_licitacao = $request->local_licitacao;
        $licitacao->data_abertura = $request->data_abertura;
        $licitacao->valor_licitacao = $request->valor_licitacao;
        $licitacao->objeto = $request->objeto;
        $licitacao->historico = $request->historico;
        $licitacao->criado_em = Carbon::now();
        $licitacao->criado_por = $betaUser->id;

        $licitacao->save();

        \DB::commit();

        return $licitacao;
    }

    public function salvarEdicaoPreContrato(Request $request)
    {

        $precontrato = PreContrato::buscaPreContratoPorId($request->id_precontrato);

        if (!$precontrato) {
            return response('Pré-contrato não encontrada!', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $precontrato->nome_contrato = $request->nome_contrato;
        $precontrato->id_contratante = $request->id_contratante;
        $precontrato->id_responsavel = $request->id_responsavel;
        $precontrato->id_executante = $request->id_executante;
        $precontrato->cep = $request->cep;
        $precontrato->uf = $request->uf;
        $precontrato->cidade = $request->cidade;
        $precontrato->endereco = $request->endereco;
        $precontrato->complemento = $request->complemento;
        $precontrato->tipo_contrato = $request->tipo_contrato;
        $precontrato->token_url = $request->token_url;
        $precontrato->status = $request->status;
        $precontrato->descricao_pre_contrato = $request->descricao_pre_contrato;
        $precontrato->alterado_em = Carbon::now();
        $precontrato->alterado_por = $betaUser->id;

        \DB::beginTransaction();

        $precontrato->save();

        \DB::commit();

        return $precontrato;
    }

    public function excluirPreContrato(Request $request)
    {
        $precontrato = PreContrato::buscaPreContratoPorId($request->id_precontrato);

        if (!$precontrato) {
            return response('Pré-contrato não encontrada!', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $precontrato->contrato_ativo = '0';
        $precontrato->alterado_em = Carbon::now();
        $precontrato->alterado_por = $betaUser->id;

        \DB::beginTransaction();

        $precontrato->save();

        \DB::commit();

        return $precontrato;
    }

    public function aprovarReprovarPreContrato(Request $request)
    {
        $precontrato = PreContrato::buscaPreContratoPorId($request->id_precontrato);

        if (!$precontrato) {
            return response('Pré-contrato não encontrado!', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $precontrato->status = $request->status;
        $precontrato->alterado_em = Carbon::now();
        $precontrato->alterado_por = $betaUser->id;

        \DB::beginTransaction();

        $precontrato->save();

        \DB::commit();

        return $precontrato;
    }

    public function aprovarReprovarProposta(Request $request)
    {
        $proposta = Proposta::buscaPreContratoPorId($request->id_proposta);

        if (!$proposta) {
            return response('Proposta não encontrada!', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $proposta->status_proposta = $request->status_proposta;
        $proposta->alterado_em = Carbon::now();
        $proposta->alterado_por = $betaUser->id;

        \DB::beginTransaction();

        $proposta->save();

        \DB::commit();

        return $proposta;
    }

    public function listarLicitacoes(Request $request)
    {
        $query = Licitacao::query()
            ->orderBy('id', 'desc');

        if ($request->include) {
            foreach ((array)$request->include as $inc) {
                $query->with($inc);
            }
        }

        $query->paginate(100);
        return $query->get();
    }

    public function listarPreContratoPorId(Request $request)
    {
        $query = PreContrato::query()
            ->where('id', '=', $request->id_precontrato)
            ->where('contrato_ativo', '=', '1')
            ->orderBy('id', 'desc');

        if ($request->include) {
            foreach ((array)$request->include as $inc) {
                $query->with($inc);
            }
        }

        $query->paginate(100);
        return $query->first();
    }

    public function salvarPropostas(Request $request)
    {
        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        \DB::beginTransaction();

        $proposta = new Proposta();

        $proposta->descricao_proposta = $request->descricao_proposta;
        $proposta->data_inicio_proposta = $request->data_inicio_proposta;
        $proposta->data_fim_proposta = $request->data_fim_proposta;
        $proposta->nome_documento = $request->nome_documento;
        $proposta->id_pre_contrato = $request->id_pre_contrato;
        $proposta->img_thumb = $request->img_thumb;
        $proposta->criado_em = Carbon::now();
        $proposta->criado_por = $betaUser->id;

        $proposta->save();

        \DB::commit();

        return $proposta;
    }

    public function salvarEdicaoLicitacao(Request $request)
    {
        $licitacao = Licitacao::buscaLicitacaoPorId($request->id_licitacao);

        if (!$licitacao) {
            return response('Licitação não encontrada!', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $licitacao->id_contratante = $request->id_contratante;
        $licitacao->status_licitacao = $request->status_licitacao;
        $licitacao->modalidade = $request->modalidade;
        $licitacao->tipo_licitacao = $request->tipo_licitacao;
        $licitacao->regime = $request->regime;
        $licitacao->numero_licitacao = $request->numero_licitacao;
        $licitacao->numero_edital = $request->numero_edital;
        $licitacao->local_licitacao = $request->local_licitacao;
        $licitacao->data_abertura = $request->data_abertura;
        $licitacao->valor_licitacao = $request->valor_licitacao;
        $licitacao->objeto = $request->objeto;
        $licitacao->historico = $request->historico;
        $licitacao->alterado_em = Carbon::now();
        $licitacao->alterado_por = $betaUser->id;

        \DB::beginTransaction();

        $licitacao->save();

        \DB::commit();

        return $licitacao;
    }

    public function listaPropostasPorPreContrato(Request $request)
    {
        $query = Proposta::query()
            ->where('id_pre_contrato', '=', $request->id_pre_contrato)
            ->orderBy('id', 'desc');

        $query->paginate(100);
        return $query->get();
    }

    public function incluirItemLoteLicitacao(Request $request)
    {
        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $lote = new Lotes();

        \DB::beginTransaction();

        $lote->cliente_id = $request->cliente_id;
        $lote->licitacao_id = $request->licitacao_id;
        $lote->subtotal = 0.00;
        $lote->vlr_pago = 0.00;
        $lote->prazo = $request->prazo;
        $lote->vencedor = $request->vencedor;
        $lote->criado_em = Carbon::now();
        $lote->criado_por = $betaUser->id;

        $lote->save();

        \DB::commit();
    }
}
