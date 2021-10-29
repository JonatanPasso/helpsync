<?php

namespace App\Http\Controllers\Contrato;

use App\Evpar\Filtros;
use App\Evpar\Util;
use App\Http\Controllers\Controller;
use App\Models\Contrato\Contrato;
use App\Models\Contrato\ContratoAnexos;
use App\Models\Contrato\ContratoItens;
use App\Models\Contrato\Proposta;
use App\Models\Geral\FileStorage;
use App\Models\Geral\Usuarios;
use App\Models\Contrato\PreContrato;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratoController extends Controller
{

    public function salvarPreContrato(Request $request)
    {

//        $this->validate($request, [
//            'nm_contrato' => 'required',
//            'contratante' => 'required'
//        ]);

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        \DB::beginTransaction();

        $precontrato = new PreContrato();

        $precontrato->nome_contrato = $request->nome_contrato;
        $precontrato->id_contratante = $request->id_contratante;
        $precontrato->id_responsavel = $request->id_responsavel;
        $precontrato->id_executante = $request->id_executante;
        $precontrato->id_licitacao = $request->id_licitacao;
        $precontrato->tipo_contrato = $request->tipo_contrato;
        $precontrato->token_url = $request->token_url;
        $precontrato->mimeType = $request->mimeType;
        $precontrato->status = $request->status;
        $precontrato->descricao_pre_contrato = $request->descricao_pre_contrato;
        $precontrato->criado_em = Carbon::now();
        $precontrato->criado_por = $betaUser->id;

        $precontrato->save();

//        $filestorage = FileStorage::whereUid($request->token)->first();
//
//        if($filestorage){
//            $filestorage->setAsProduction();
//            $filestorage->save();
//        }

        if (count($request->uidAnexo) >= 1) {

            foreach ($request->uidAnexo as $ks => $lt) {

                $fileStorage = FileStorage::whereUid($lt['token'])->first();

                if ($fileStorage) {

                    $anexos = new ContratoAnexos();

                    $anexos->uid = $lt['token'];
                    $anexos->pre_contrato_id = $precontrato->id;
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

        return $precontrato;
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

        $filestorage = FileStorage::whereUid($request->token)->first();

        if($filestorage){
            $filestorage->setAsProduction();
            $filestorage->save();
        }

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

    public function listarPreContratos(Request $request)
    {
        $query = PreContrato::query()
            ->where('contrato_ativo', '=', '1')
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

    public function listaAnexosPreContrato(Request $request)
    {
        $query = ContratoAnexos::query()
            ->where('pre_contrato_id', '=', $request->id_precontrato)
            ->orderBy('id', 'desc');

        return $query->get();
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
        $proposta->uid = $request->uid;
        $proposta->criado_em = Carbon::now();
        $proposta->criado_por = $betaUser->id;

        $proposta->save();

        $filestorage = FileStorage::whereUid($request->uid)->first();

        if($filestorage){
            $filestorage->setAsProduction();
            $filestorage->save();
        }

        \DB::commit();

        return $proposta;
    }

    public function listaPropostasPorPreContrato(Request $request)
    {
        $query = Proposta::query()
            ->where('id_pre_contrato', '=', $request->id_pre_contrato)
            ->orderBy('id', 'desc');

        $query->paginate(100);
        return $query->get();
    }

    public function salvarContrato(Request $request){

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        \DB::beginTransaction();

        $verificaContrato = Contrato::verificaStatusContrato($request->id_precontrato);

        if($verificaContrato)
        {
            return $verificaContrato;
        }

        $contrato = new Contrato();

        $contrato->pre_contrato_id = $request->id_precontrato;
        $contrato->criado_em = Carbon::now();
        $contrato->criado_por = $betaUser->id;

        $contrato->save();

        \DB::commit();

        return $contrato;
    }

    public function listaContratoPorPre(Request $request)
    {
        $query = Contrato::query()
            ->where('pre_contrato_id', '=', $request->pre_contrato_id)
            ->first();

        if($query){
            return $query;
        }else{
            return $msg = ['msg' => 'Não há contrato vinculado'];
        }
    }

    public function salvarItemContrato(Request $request)
    {

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        \DB::beginTransaction();

        $verificaItens = ContratoItens::verificaItens($request);

        if($verificaItens)
        {
            return $code = ['code'=>'999'];
        }

        $itens = new ContratoItens();

        $itens->contrato_id = $request->contrato_id;
        $itens->produto_id = $request->produto_id;
        $itens->nomenclatura_id = 0;
        $itens->quantidade = $request->quantidade;
        $itens->criado_em = Carbon::now();
        $itens->criado_por = $betaUser->id;

        $itens->save();

        \DB::commit();

        return $itens;
    }

    public function listaItensContrato(Request $request)
    {
        $query = ContratoItens::query()
            ->where('contrato_id', $request->contrato_id);

        if ($request->include) {
            foreach ((array)$request->include as $inc) {
                $query->with($inc);
            }
        }

        $query->paginate(100);
        return $query->get();
    }

    public function excluirAtividade(Request $request)
    {
        return ContratoItens::whereId($request->codigo_item)->delete();
    }

    public function finalizarContrato(Request $request)
    {
        $contrato = Contrato::verificaContrato($request->contrato_id);

        if (!$contrato) {
            return response('Contrato não encontrado', 421);
        }

        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();

        $contrato->status_contrato = '2';
        $contrato->data_inicio = $request->data_inicio;
        $contrato->data_fim = $request->data_fim;
        $contrato->alterado_em = Carbon::now();
        $contrato->alterado_por = $betaUser->id;

        \DB::beginTransaction();

        $contrato->save();

        \DB::commit();

        return $contrato;
    }
}