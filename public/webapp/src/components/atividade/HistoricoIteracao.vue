<template>
  <div>
    <div class="row">
      <br>
      <div class="col-md-12 col-lg-6" id="topo-hist-chamado">
        <div class="form-group">
          <ul class="list-group list-group-flush">
            <li class="list-group-item active" style="font-size: 12px">
              <span style="font-weight: bold"><i class="fa fa-building"></i> EMPRESA:</span>
              <span class="nr_chamado_mod" style="padding-left: 20px; padding-right: 20px"
                    v-if="chamadoAux.empresa_chamado">{{ chamadoAux.empresa_chamado.nome }}</span>
              <span>&nbsp;&nbsp;</span>
              <span style="font-weight: bold"><i class="fa fa-briefcase"></i> DEPARTAMENTO:</span>
              <span class="nr_chamado_mod" style="padding-left: 20px; padding-right: 20px"
                    v-if="chamadoAux.departamento_chamado">{{ chamadoAux.departamento_chamado.nome }}</span>
            </li>
            <li class="list-group-item">
              <b-button variant="primary">
                Chamado
                <b-badge variant="light">#{{ chamadoAux.nr_chamado }}</b-badge>
              </b-button>
            </li>
            <li class="list-group-item"><span style="font-weight: bold">Avaliação: </span>
              <span class="avlia0" style="color: darkred; font-weight: bold"> {{ chamadoAvaliado }}</span>
              <span class="avalia1" style="color: #ffd042"> {{ chamadoAvaliado }}</span>
              <span class="avalia2" style="color: #ffd042"> {{ chamadoAvaliado }}</span>
              <span class="avalia3" style="color: #ffd042"> {{ chamadoAvaliado }}</span>
              <span class="avalia4" style="color: #ffd042"> {{ chamadoAvaliado }}</span>
              <span class="avalia5" style="color: #ffd042"> {{ chamadoAvaliado }}</span>
            </li>
            <li class="list-group-item"><span style="font-weight: bold">Título: </span> <span
                class="titulo_chamado_mod">{{ chamadoAux.titulo_chamado }}</span></li>
            <li class="list-group-item"><span style="font-weight: bold"> Descrição: </span> <span
                class="descricao_chamado_mod" v-html="chamadoAux.descricao_chamado"></span></li>
            <li class="list-group-item info" v-if="chamadoAux.anexo_chamado">
              <span style="font-weight: bold">Anexo(s) Principal(is): </span>
              <template v-for="an in chamadoAux.anexo_chamado">
                <ul v-if="an.iteracao_id == null">
                  <a :href="an.url+'&download=1'" target="_blank"
                     style="color: #000; font-size: 14px; font-weight: bold; text-decoration: underline!important;"
                     v-if="an.mime_type == 'text/plain'">
                    <i class="fa fa-paperclip"></i> {{ an.nome_anexo }}
                  </a>
                  <a :href="an.url" target="_blank"
                     style="color: #000; font-size: 14px; font-weight: bold; text-decoration: underline!important;"
                     v-else>
                    <i class="fa fa-paperclip"></i> {{ an.nome_anexo }}
                  </a>
                </ul>
              </template>
            </li>
            <!-- Anexo Interacoes -->
            <li class="list-group-item info" v-if="chamadoAux.anexo_chamado_por_iteracao">
              <span style="font-weight: bold">Anexo(s) Atualizações: </span>
              <template v-for="an in chamadoAux.anexo_chamado_por_iteracao">
                <ul>
                  <a :href="an.url+'&download=1'" target="_blank"
                     style="color: #000; font-size: 14px; font-weight: bold; text-decoration: underline!important;"
                     v-if="an.mime_type == 'text/plain'">
                    <i class="fa fa-paperclip"></i> {{ an.nome_anexo }}
                  </a>
                  <a :href="an.url" target="_blank"
                     style="color: #000; font-size: 14px; font-weight: bold; text-decoration: underline!important;"
                     v-else>
                    <i class="fa fa-paperclip"></i> {{ an.nome_anexo }}
                  </a>
                  <span> -- </span>
                  <b-badge variant="primary">{{ an.usuarios_iteracao.nome }}</b-badge>
                </ul>
              </template>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-md-12 col-lg-6">
        <div class="form-group">
          <ul class="list-group">
            <li class="list-group-item"><span style="font-weight: bold">Criado por: </span><span
                class="atribuido_mod"
                v-if="chamadoAux.atividade_chamado">{{ l_.get(chamado, 'usuario_criador.nome', 'Nao definido') }}</span>
            <li class="list-group-item"><span style="font-weight: bold">Atividade: </span><span
                class="atribuido_mod"
                v-if="chamadoAux.atividade_chamado">{{ chamadoAux.atividade_chamado.nm_atividade }}</span>

            <li class="list-group-item"><span style="font-weight: bold">Subatividade: </span><span
                class="atribuido_mod"
                v-if="chamadoAux.subatividade_chamado">{{ chamadoAux.subatividade_chamado.nm_subatividade }}</span>
            <li class="list-group-item"><span style="font-weight: bold">Atribuido: </span>
              <span class="atribuido_mod"
                    v-if="chamadoAux.usuario_executor">{{ chamadoAux.usuario_executor.nome }}</span>
            </li>
            <li class="list-group-item"
                v-if="l_.get(chamado, 'atividade_chamado') && l_.get(chamado, 'atividade_chamado.moderada') == 'S'"
                style="background-color: #cccccc26;">
                            <span style="font-weight: bold"><i class="fas fa-crown"
                                                               style="color: #fadd4d; font-weight: bold"></i> Moderador: </span>
              <span class="atribuido_mod"
                    v-if="chamadoAux.usuario_executor">{{ chamadoAux.usuario_executor.nome }}</span>
            </li>
            <li class="list-group-item"><span style="font-weight: bold">Tipo: </span> <span
                class="tipo_mod"><img
                :src="tiposChamado(chamadoAux.tipo_chamado)[0]"/>{{ tiposChamado(chamadoAux.tipo_chamado)[1] }}</span>
            </li>
            <li class="list-group-item"><span style="font-weight: bold">Prioridade: </span> <span
                class="prioridade_mod"><img :src="tiposPrioridades(chamadoAux.prioridade_chamado)[0]"
                                            style="width: 20px; height: 20px"/>{{ tiposPrioridades(chamadoAux.prioridade_chamado)[1] }}</span>
            </li>
            <li class="list-group-item"><span style="font-weight: bold">Situação: </span>
              <a v-bind:class="[{ concluido: chamadoAux.status_chamado == 'CO'},
												  {finalizado: chamadoAux.status_chamado == 'FN'},
												  {status_ce: chamadoAux.status_chamado != 'CO' && chamadoAux.status_chamado != 'FN'}]"
                 href="#">
                <span v-if="(chamadoAux.moderou_chamado == '1' || chamadoAux.moderou_chamado === '2')">{{ chamadoAux.status_chamado|statusChamado }}</span>
                <span v-if="chamadoAux.status_chamado != 'CO' && chamadoAux.status_chamado != 'FN' && chamadoAux.moderou_chamado === '0'">Em Moderação</span>
                <span v-if="chamadoAux.status_chamado == 'CO' && chamadoAux.moderou_chamado === '0'">{{ chamadoAux.status_chamado|statusChamado }}</span>
                <span v-if="chamadoAux.status_chamado == 'FN' && chamadoAux.moderou_chamado === '0'">{{ chamadoAux.status_chamado|statusChamado }}</span>
              </a>
            </li>
            <li class="list-group-item"><span style="font-weight: bold">Estimativa: </span>
              <b-form-datepicker id="example-datepicker"
                                 v-model="detalhe.estimativa"
                                 class="mb-2"
                                 locale="pt-BR"
                                 placeholder="Selecione a data"
                                 today-button
                                 reset-button
                                 label-today-button="DATA ATUAL"
                                 label-reset-button="REDEFINIR"
                                 :disabled="chamadoAux.estimativa_horas != null">
              </b-form-datepicker>
            </li>
            <li class="list-group-item" style="text-align: right">
              <b-form-group>
                <b-button-group>
                  <b-button variant="info"
                            data-toggle="tooltip"
                            title="Transferir de Executor ou Atividade"
                            v-if="transferencia"
                            @click="transferirAtividadeExecutor">
                    <i class="fas fa-random"></i> Transferir
                  </b-button>
                  <b-button variant="secondary" v-if="podeModerar" @click="moderarChamado"><i
                      class="fa fa-filter fa-lg"></i> Moderar Chamado
                  </b-button>
                  <b-button variant="success" v-if="podeIniciar"
                            @click="iniciarIteracaoChamado(chamadoAux.nr_chamado)"><i
                      class="fa fa-play-circle fa-lg"></i> Iniciar
                  </b-button>

                  <b-button variant="danger" v-if="podeFinalizar" @click="finalizarChamado"><i
                      class="fa fa-hourglass-end"></i> Finalizar
                  </b-button>
                </b-button-group>
              </b-form-group>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-2">
        <h6><i class="fa fa-redo-alt" style="color: #a3a3a3"></i>
          <span style="font-weight: bold; color: #a3a3a3"> INTERAÇÕES: </span>
          <span style="color: #a3a3a3">{{ countHistorico ? countHistorico : '0' }}</span>
        </h6>
      </div>
      <div class="col-sm-2" style="padding-left: 20px">
        <h6><i class="fa fa-paperclip" style="color: #a3a3a3"></i>
          <span style="font-weight: bold; color: #a3a3a3"> ANEXOS: </span>
          <span style="color: #a3a3a3">{{ countAnexo ? countAnexo : '0' }}</span>
        </h6>
      </div>
      <div class="col-sm-8 part" style="cursor: pointer;">
        <h6>
          <!--                    <i class="fa fa-users" style="color: #a3a3a3"></i>-->
          <div class="row">
            <div style="padding-right: 5px; margin-top: 2px;">
              <span style="font-weight: bold; color: #a3a3a3"> PARTICIPANTES: </span>
            </div>
            <template>
              <div>
                <b-avatar-group size="25px">
                  <b-avatar variant="warning"></b-avatar>
                  <b-avatar variant="danger"></b-avatar>
                  <b-avatar variant="primary">{{ arrayParticipantesAux.length }}</b-avatar>
                </b-avatar-group>
              </div>
            </template>
          </div>

          <template>
            <div class="participantes-hide" id="numb-part"
                 style="padding-top: 10px; position: absolute; z-index: 999999999">
              <b-list-group style="max-width: 400px;">
                <b-list-group-item class="d-flex align-items-center" v-for="d in arrayParticipantesAux">
                  <b-avatar size="25px"></b-avatar>
                  <span style="padding-left: 8px"> {{ d }}</span>
                </b-list-group-item>
              </b-list-group>
            </div>
          </template>
        </h6>
      </div>
    </div>
    <hr>
    <b-form-group label="Descrição Interação"
                  :class="l_.get(validationResponse,'justificativa_iteracao_chamado') ? 'is-invalid' : ''"
                  label-size="lg">
      <b-form-textarea
          id="justificativa_iteracao_chamado"
          :state="l_.get(validationResponse,'justificativa_iteracao_chamado') ? false : undefined"
          v-model="detalhe.justificativa_iteracao_chamado"
          name="justificativa_iteracao_chamado"
          placeholder="Entre com a descrição da interação"
          rows="6"
          max-rows="6">
      </b-form-textarea>
      <small class="invalid-feedback" v-if="l_.get(validationResponse,'justificativa_iteracao_chamado')">
        {{ l_.get(validationResponse, 'justificativa_iteracao_chamado').join(' ') }}</small>
    </b-form-group>
    <div class="row">
      <div class="col-md-3 col-lg-3">
        <label style="visibility: hidden">Salvar</label>
        <b-form-group>
          <div class="form-control">
            <b-form-checkbox v-model="checkCitarColaborador" :disabled="podeIterar">Citar Colaborador
            </b-form-checkbox>
          </div>
        </b-form-group>
      </div>
      <div class="col-md-4 col-lg-9" v-if="podeCitar">
        <b-form-group label="Citar Colaborador">
          <v-select name="interar"
                    multiple
                    id="id_usuario_chamado"
                    v-model="interacaoChamado"
                    :options="listaUsuariosComp">
          </v-select>
        </b-form-group>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-6">
        <label style="visibility: hidden">Salvar</label>
        <b-form-group>
          <b-button-group>
            <b-button style="background-color: rgb(65, 118, 172)"
                      v-if="!podeIterar"
                      v-on:click="salvarIteracao(chamadoAux.id)"><i
                class="fa fa-share-square"></i> Atualizar
            </b-button>
            <b-button variant="danger" v-if="podeConcluir" @click="concluirChamado(chamadoAux.nr_chamado)">
              <i
                  class="fa fa-hourglass-end"></i> Concluir
            </b-button>
            <b-button variant="primary" v-if="podeRevogar" @click="revogarConclusao(chamadoAux.nr_chamado)">
              <i
                  class="fa fa-exclamation-triangle"></i> Reabrir
            </b-button>
          </b-button-group>
        </b-form-group>
      </div>
      <div class="col-md-6 col-lg-6" style="text-align: right">
        <label style="visibility: hidden"> Salvar</label>
        <b-form-group>
          <b-button-group>
            <b-button variant="primary" @click="incluirAnexos" v-if="!podeIterar">
              <i class="fa fa-paperclip"></i> Anexo
              <b-badge variant="light">{{ lista_anexo.length }}</b-badge>
            </b-button>
          </b-button-group>
        </b-form-group>
      </div>
    </div>
    <hr>
    <div class="historicoChamado" style="background-color: rgb(245,246,249)">
      <div v-for="(dados, index) in arrayIteracoes">
        <div class="timeline-item" style="padding: 50px" :date-is="index|datas">
          <div id="corpo" v-for="(d, i) in dados" style="background-color: white">

            <h4><span
                style="font-weight: bold"> {{ l_.get(d, 'usuario_criador_iteracao.nome', 'Nao Definido') }}</span>
            </h4>

            <!--Chamados Compartilhados-->
            <hr v-if="d.chamados_compartilhados.length > 0">

            <h6 v-if="d.chamados_compartilhados.length > 0" style="color: #4176ac; ">
              <span style="font-weight: bold"><i class="fas fa-share-alt"></i> COMPARTILHOU COM</span>
            </h6>
            <ul class="list-group">
              <template v-for="us in d.chamados_compartilhados">
                <li class="list-group-item"
                    style="font-size: 0.9em; border-left: 4px solid rgb(65, 118, 172); background-color: #f8f9fc;"
                    v-if="(l_.get(us, 'usuario.id') != null) &&
                                         (l_.get(us, 'usuario.id') != (l_.get(chamado,'usuario_criador.id'))) &&
                                         (l_.get(chamado, 'usuario_executor.id') != (l_.get(us, 'usuario.id')))">
                  <i class="fa fa-user-tie"></i>
                  <!--                                    {{l_.get(us, 'usuario.nome')}}-->
                  <span style="font-weight: bold"
                        v-if="l_.get(us,'departamento_id') == l_.get(us, 'departamentos.id')">
                                        {{ l_.get(us, 'usuario.nome') }} <b-badge variant="info"
                                                                                  style="padding: 9px">{{ l_.get(us, 'departamentos.nome') }}</b-badge>
                                    </span>
                </li>
              </template>
            </ul>

            <!--Chamados Moderados-->
            <hr v-if="d.chamados_moderados">

            <h6 v-if="d.chamados_moderados" style="color: rgb(121, 85, 72); ">
                            <span style="font-weight: bold"> <i
                                class="fas fa-exchange-alt"></i> CHAMADO MODERADO PARA</span>
            </h6>

            <ul class="list-group" v-if="d.chamados_moderados">
              <li class="list-group-item"
                  style="font-size: 0.9em; border-left: 4px solid rgb(121, 85, 72);; background-color: rgba(121, 85, 72, 0.15);">
                <i class="fa fa-user-tie"></i> <span
                  style="font-weight: bold">{{ d.chamados_moderados.usuario_moderado.nome }}</span></li>
            </ul>

            <hr v-if="d.justificativa_iteracao_chamado != ''">
            <h5 style="margin-left: 10px;" v-html="d.justificativa_iteracao_chamado"></h5>

            <hr>

            <div class="row">
              <div class="col-md-6 col-sm-6" style="padding-left: 21px">
                <b-badge href="#" variant="warning" style="padding: 6px">
                  <a href="#" v-scroll-to="'#topo-hist-chamado'"
                     style="text-decoration: none; color: #000">#{{ chamadoAux.nr_chamado }}</a>
                </b-badge>
              </div>
              <div class="col-md-6 col-sm-6"
                   style="text-align: right; font-weight: bold; color: rgb(65, 118, 172)">
                <p><i class="fa fa-time"></i> {{ d.criado_em|datas('L LT') }}</p>
              </div>
            </div>

            <hr v-if="d.anexo_chamado_por_iteracao.length > 0">

            <div class="row" v-if="d.anexo_chamado_por_iteracao.length > 0">
              <div class="col-md-12">
                <template v-for="sd in d.anexo_chamado_por_iteracao">
                  <span style="font-weight: bold" v-if="sd.iteracao_id == d.id">Anexo: </span>
                  <a :href="sd.url" target="_blank"
                     style="color: #000; font-size: 14px; font-weight: bold; text-decoration: underline!important;">
                    <h5 v-if="sd.iteracao_id == d.id">
                      <b-badge variant="primary">{{ sd.nome_anexo }}</b-badge>
                    </h5>
                  </a>

                </template>
              </div>
            </div>
          </div>
          <hr>
        </div>
      </div>
    </div>
    <!-- Modal Moderador -->

    <b-modal id="modalModerador" ref="modalModerador" title="Moderar Chamado">
      <b-form-group label="Compartilhar Chamado" label-for="moderar">
        <v-select name="moderar"
                  id="moderar"
                  v-model="moderadoSelecionado"
                  :options="listaUsuariosComp">
        </v-select>
        <small class="help-block">{{ l_.get(validationResponse, 'moderar') }}</small>

      </b-form-group>

      <template #modal-footer>
        <b-button variant="info" v-on:click="salvarModeracao(chamadoAux.id, chamadoAux.nr_chamado)">
          <i class="fa fa-user"></i> Moderar
        </b-button>
        <b-button variant="danger" @click="$bvModal.hide('modalModerador')">
          <i class="fa fa-times"></i> Fechar
        </b-button>
      </template>
    </b-modal>

    <!-- Modal Avaliação -->
    <div class="modal fade" id="modalAvaliacao" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title-iteracao" id="exampleModalAvaliacaoTitle">Avaliar Chamado:
              #{{ chamadoAux.nr_chamado }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="margin-top: -35px;!important;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row" style="text-align:  center; font-size:  28px; font-weight: bold;">
              <p>A fim de avaliar o atendimento prestado, por favor pontue seu nível de satisfação.</p>
            </div>
            <div class="row" style="text-align: center">
              <div class="col-md-12 col-lg-12">
                <div class="vote">
                  <label>
                    <input type="radio" name="fb" value="1"/>
                    <i class="fa"></i>
                  </label>
                  <label>
                    <input type="radio" name="fb" value="2"/>
                    <i class="fa"></i>
                  </label>
                  <label>
                    <input type="radio" name="fb" value="3"/>
                    <i class="fa"></i>
                  </label>
                  <label>
                    <input type="radio" name="fb" value="4"/>
                    <i class="fa"></i>
                  </label>
                  <label>
                    <input type="radio" name="fb" value="5"/>
                    <i class="fa"></i>
                  </label>
                </div>
              </div>
            </div>
            <hr>
            <div class="row" style="text-align: center">
              <div id="voto"
                   style="font-weight: bold;font-size: 35px;height: 50%;color: green;margin: 0 auto;"></div>
            </div>

          </div>
          <div class="modal-footer">
            <button class="btn btn-success" type="button"
                    v-on:click="finalizaAvaliacao(chamadoAux.nr_chamado)">
              <i class="fa fa-check"></i> Avaliar
            </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
    <!--End Modal Avaliação-->

    <!-- Modal Incluir Anexo -->
    <b-modal id="mIncluirAnexoHistorico"
             hide-footer
             title="INCLUIR ANEXO(s)"
             size="lg">
      <div class="row">
        <div class="col-md-12 form-group">
          <BtnUpload v-model="detalhe.upload_det_uid"
                     v-if="!detalhe.uid_anexo"
                     style="display: inline-block;"
                     mimes="">
          </BtnUpload>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10 form-group">
          <b-form-input placeholder="Nome Anexo"
                        v-model="dados_detalhe_anexo.titulo_anexo"
                        :disabled="detalhe.upload_det_uid == ''">
          </b-form-input>
        </div>
        <div class="col-md-2" style="text-align: right">
          <b-button variant="success"
                    @click="incluirListaAnexo"
                    :disabled="detalhe.upload_det_uid == ''">
            <i class="fa fa-folder-plus"></i> Incluir
          </b-button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 ">
          <table class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
              <th>Título</th>
              <th style="text-align: center">Anexo</th>
              <th style="text-align: center">Ação</th>
            </tr>
            </thead>
            <tbody>
            <template v-if="lista_anexo.length > 0">
              <tr v-for="(i, index) in lista_anexo">
                <td>{{ i.titulo_anexo }}</td>
                <td style="text-align: center">
                  <a :href="i.url" target="_blank" v-if="i.url">
                    <!--                                        <span class="fa fa-file-pdf fa-2x" style="color: red"></span>-->
                    <Ficone :mime="i.mimeType"></Ficone>
                  </a>
                  <a href="" v-else>
                    <span style="font-weight: bold">--</span>
                  </a>
                </td>
                <td style="text-align: center">
                  <b-button variant="danger" @click="removerItemListaAnexo(index)"><i
                      class="fa fa-trash"></i></b-button>
                </td>
              </tr>
            </template>
            <template v-else>
              <tr>
                <td colspan="22">Não há dados para serem exibidos!</td>
              </tr>
            </template>
            </tbody>
          </table>
        </div>
      </div>

      <div style="text-align: right">
        <b-button-group>
          <b-button class="mt-3" @click="limparListaAnexo"><i class="fa fa-eraser"></i> Limpar</b-button>
          <b-button class="mt-3" variant="success" @click="$bvModal.hide('mIncluirAnexoHistorico')"><i
              class="fa fa-check"></i> Concluir
          </b-button>
        </b-button-group>
      </div>

    </b-modal>
    <!-- End Modal Incluir Anexo -->

    <!-- Modal Transferência de Atividade ou Executor -->
    <b-modal id="modalTransferencia"
             size="lg"
             ref="modalTransferencia"
             title="Transferir Atividade ou Executor">
      <div>
        <b-card-group deck>
          <b-card
              header="Dados Atuais"
              header-tag="header"
              footer=""
              footer-tag="footer">
            <b-card-text><span style="font-weight: bold">Atividade:</span> {{ l_.get(chamadoAux, 'atividade_chamado.nm_atividade')}}</b-card-text>
            <b-card-text><span style="font-weight: bold">Subatividade: </span>{{ l_.get(chamadoAux, 'subatividade_chamado.nm_subatividade')}}</b-card-text>
            <b-card-text><span style="font-weight: bold">Atribuido: </span>{{ l_.get(chamadoAux, 'usuario_executor.nome')}}</b-card-text>
            <b-card-text><span style="font-weight: bold">Empresa: </span>{{ l_.get(chamadoAux, 'empresa_chamado.nome')}}</b-card-text>
            <b-card-text><span style="font-weight: bold">Departamento: </span>{{ l_.get(chamadoAux, 'departamento_chamado.nome')}}</b-card-text>
          </b-card>

          <b-card header-tag="header" footer-tag="footer">
            <template v-slot:header>
              <h6 class="mb-0">Dados Novos</h6>
            </template>

            <div class="row">
              <div class="col-md-12 col-lg-12">
                <b-form-group>
                  <label>Compartilhar Chamado</label>
                  <div class="form-control">
                    <b-form-checkbox v-model="desejaAlter">Deseja alterar: </b-form-checkbox>
                  </div>
                </b-form-group>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 col-lg-12">
                <b-form-group>
                  <div class="form-control">
                    <b-form-radio-group :disabled="disableAlter">
                      <b-form-radio v-model="selecionaAtividade" name="some-radios" value="A">Atividade</b-form-radio>
                      <b-form-radio v-model="selecionaAtividade" name="some-radios" value="E">Executor</b-form-radio>
                    </b-form-radio-group>
                  </div>
                </b-form-group>
              </div>
            </div>

            <div class="form-group">
              <label>Atividade</label>
              <v-select name="atividades"
                        v-model="atividades"
                        :options="atividadesComputed"
                        :disabled="disabledAtiSub">
                <span slot="no-options">Não há opções para esta empresa!</span>
              </v-select>
            </div>

            <div class="form-group">
              <label>Subatividade</label>
              <v-select name="atividades"
                        v-model="subatividade"
                        :options="subAtividadesComputed"
                        :disabled="disabledAtiSub">
                <span slot="no-options">Não há opções para esta empresa!</span>
              </v-select>
            </div>

            <div class="form-group">
              <label>Executor</label>
              <v-select name="executor"
                        v-model="executor"
                        :options="usuariosComputed"
                        :disabled="disabledExec">
                <span slot="no-options">Não há opções para esta empresa!</span>
              </v-select>
            </div>

          </b-card>
        </b-card-group>
      </div>
      <template #modal-footer>
        <b-button variant="info" @click="salvarTransferencia">
          <i class="fas fa-save"></i> Salvar Transferência
        </b-button>
        <b-button variant="danger" @click="$bvModal.hide('modalTransferencia')">
          <i class="fa fa-times"></i> Fechar
        </b-button>
      </template>
    </b-modal>
    <!-- End Modal Transferência -->

  </div>
</template>


<script>

import BtnUpload from "../geral/BtnUpload";
import InputText from "../geral/singleForm/InputText";
import Swal from 'sweetalert2';

//Tipo de Chamado
import Bugs from "./../../assets/fluxo/bug.svg";
import Enhancement from "./../../assets/fluxo/enhancement.svg";
import Proposal from "./../../assets/fluxo/proposal.svg";
import Task from "./../../assets/fluxo/task.svg";

//Prioridade
import Trivial from "./../../assets/fluxo/trivial.svg";
import Minor from "./../../assets/fluxo/minor.svg";
import Major from "./../../assets/fluxo/major.svg";
import Critical from "./../../assets/fluxo/critical.svg";
import Blocker from "./../../assets/fluxo/blocker.svg";

import FundoMsg from "./../../assets/fluxo/wps.png";
import Ficone from "../geral/Ficone";

function filtroChamado(value) {
  switch (value) {
    case 'CE':
      return 'CAIXA DE ENTRADA'
      break;
    case 'IN':
      return 'INICIADO'
      break;
    case 'PS':
      return 'PAUSADO'
      break;
    case 'RT':
      return 'RETORNADO'
      break;
    case 'CA':
      return 'CANCELADO'
      break;
    case 'FN':
      return 'FINALIZADO'
      break;
    case 'TR':
      return 'TRANSFERIDO'
      break;
    case 'CO':
      return 'CONCLUÍDO'
      break;
    default:
      return '--'
  }
};


export default {

  name: 'HistoricoIteracao',
  components: {Ficone, InputText, BtnUpload},
  mounted: function () {

    this.ancoraTopo();
    this.usuarioLogado = this.$root.USER.id;

    setTimeout(function () {
      $('[data-toggle="tooltip"]').tooltip();
    },300);


    //  this.validationResponse  = {id_usuario_chamado:[' Esalfaslfsadfasdf']};


  },

  watch: {

    /**
     * Faz copia do chamdo para ser editavel locamente (props somente leitura)
     */
    chamado: {
      deep: true,
      handler(chamdo) {
        this.chamadoAux = chamdo
      }
    },

    'chamadoAux.estimativa_horas': function (value) {
      if (value != null) {
        this.detalhe.estimativa = value;
      }
    },

    'chamadoAux.id': function (value) {

      //Limpa histórico de iteração ao mudar de chamadoAux.
      this.arrayIteracoes = [];
      this.podeModerar = false;
      this.podeConcluir = false;
      this.podeFinalizar = false;
      this.podeRevogar = false;
      this.podeIterar = true;

      var idUsuariothis = this.$root.USER.id;

      this.listarHistorico(this.chamadoAux.id);
      this.iterarChamado();

      // Verifica se o usuário logado é o mesmo do executor do chamado
      var idUsuarioExecutor = this.chamadoAux.id_usuario_executor;
      var idUsuarioCriador = this.chamadoAux.id_usuario_criador;

      var params = {
        departamento_id: this.chamadoAux.id_departamento,
        usuario_id: idUsuariothis,
        opcao: 'gestor'
      };

      //      this.$api.atividade.consultas.usuariosPorDepartamento(params)
      this.doGet('geral/vaga-departamento/buscar', params).then(function (result) {

        for (var i in result) {

          if (result[i].vaga_departamento_pai == null) {

            var params = {
              nr_chamado: this.chamadoAux.nr_chamado
            };

            /**
             * @todo verificar estea logia. Esta chamando varias vezes a mesma api
             * com mesmos parametros ????
             * */
            // this.$api.atividade.consultas.listaChamadoPorNr(params)
            this.doGet('atividades/atividades/listarChamadosPorNr', params).then(function (resultChamados) {

              if ((parseInt(idUsuariothis) == parseInt(resultChamados.id_usuario_executor)) &&
                  (this.chamadoAux.status_chamado != 'IN') &&
                  (this.chamadoAux.status_chamado != 'FN') &&
                  (this.chamadoAux.status_chamado != 'CO')) {

                this.podeModerar = true;
              }
            });
          }
        }
      });

      if ((idUsuariothis == idUsuarioExecutor && this.chamadoAux.status_chamado == 'CE')) {
        this.podeIniciar = true;
      }

      if (this.chamadoAux.status_chamado == 'FN' || this.chamadoAux.status_chamado == 'IN') {
        this.podeIniciar = false;
      }

      if ((parseInt(idUsuariothis) == parseInt(idUsuarioExecutor) || parseInt(idUsuariothis) == parseInt(idUsuarioCriador)) &&
          // this.chamadoAux.status_chamado != 'CE' && this.chamadoAux.status_chamado != 'FN' && this.chamadoAux.status_chamado != 'CO') {
          this.chamadoAux.status_chamado != 'CE' && this.chamadoAux.status_chamado != 'FN') {

        this.podeIterar = false;
      }

      if (parseInt(idUsuariothis) == parseInt(idUsuarioExecutor) && this.chamadoAux.status_chamado == 'IN') {
        this.podeConcluir = true;
      } else {
        this.podeConcluir = false;
      }

      if ((parseInt(idUsuariothis) == parseInt(idUsuarioCriador)) && this.chamadoAux.status_chamado == 'CO') {
        this.podeFinalizar = true;
        this.podeRevogar = true;
      }

      var params = {
        id_chamado: value,
        id_usuario_chamado: idUsuariothis
      };

      // this.$api.atividade.consultas.atendimentos(params)
      this.doGet('atividades/atividades/consultaAtendimento', params).then(function (result) {

        var _this = this;

        var usuarioAtual = this.$root.USER.id;
        $.each(result, function (index, element) {
          $.each(element.chamados_compartilhados_atualizar, function (ind, ele) {
            if ((ele.id_usuario_compartilhado == usuarioAtual) && (_this.chamadoAux.status_chamado != 'FN') && (_this.chamadoAux.status_chamado != 'CE')) {
              _this.podeIterar = false;
            }
          });

          // if(element.id_usuario_chamado == usuarioAtual && _this.chamadoAux.status_chamado != 'CE' && _this.chamadoAux.status_chamado != 'FN' && _this.chamadoAux.status_chamado != 'CO'){
          if (element.id_usuario_chamado == usuarioAtual && _this.chamadoAux.status_chamado != 'CE' && _this.chamadoAux.status_chamado != 'FN') {
            _this.podeIterar = false;
          }
        });
      }).fail(function (erro) {
        // console.log(erro);
        // this.podeIniciar = true;
        return erro;
      });
    },

    'detalhe.upload_det_uid': function (value) {

      this.doGet('geral/file-storage/buscar', {filtro: {uid: value}}).then(function (result) {
        this.dados_detalhe_anexo.token = result.data[0].uid;
        this.dados_detalhe_anexo.img_thumb = result.data[0].thumb_url;
        this.dados_detalhe_anexo.url = result.data[0].url;
        this.dados_detalhe_anexo.original_name = result.data[0].metadata.originalName;
        this.dados_detalhe_anexo.mimeType = result.data[0].metadata.mimeType;
      });
    },

    'checkCitarColaborador': function (value) {

      if (value == true) {
        if (this.podeRevogar == true &&
            _.get(this, 'chamadoAux.status_chamado') == 'CO' &&
            (_.get(this, 'chamadoAux.usuario_criador.id') == this.$root.USER.id)) {
          this.podeCitar = true;
          this.podeRevogar = false;
        }

        if (this.podeConcluir == true &&
            _.get(this, 'chamadoAux.status_chamado') == 'IN' &&
            _.get(this, 'chamadoAux.id_usuario_executor') == this.$root.USER.id) {
          this.podeCitar = true;
          this.podeConcluir = false;
        }

        this.podeCitar = true;
      } else {

        this.interacaoChamado = [];

        if (this.podeRevogar != true &&
            _.get(this, 'chamadoAux.status_chamado') == 'CO' &&
            (_.get(this, 'chamadoAux.usuario_criador.id') == this.$root.USER.id)) {
          this.podeCitar = false;
          this.podeRevogar = true;
        }

        if (this.podeConcluir != true &&
            _.get(this, 'chamadoAux.status_chamado') == 'IN' &&
            _.get(this, 'chamadoAux.id_usuario_executor') == this.$root.USER.id) {
          this.podeCitar = false;
          this.podeConcluir = true;
        }

        this.podeCitar = false;
      }
    },

    'desejaAlter': function (value){

      if(value == true){
        this.disableAlter = false;
      }else{
        this.disableAlter = true;
        this.selecionaAtividade = '';
        this.selecionaExecutor = '';
        this.atividades = '';
        this.subatividade = '';
        this.executor = '';
      }

    },

    'selecionaAtividade': function (value){
      if(value == 'A'){
        this.disabledAtiSub = false;
        this.disabledExec = true;
        this.atividades = '';
        this.subatividade = '';
        this.executor = '';
      }else{
        this.disabledAtiSub = true;
        this.disabledExec = false;
        this.atividades = '';
        this.subatividade = '';
        this.executor = '';
        this.getUsuariosPorDepartamento(this.chamadoAux.departamento_chamado.id);
      }
    },

    'atividades': function (valor) {
      if (_.get(valor, 'dados.id')) {
        this.subatividade = '';
        this.getSubatividadesPorIdAtividade(valor.dados.id);
      }
    },
  },

  computed: {

    listaUsuariosComp: function () {
      return _.map(this.arrayAuxUsuarios, function (item) {
        return {
          label: item.nome,
          value: item.id,
          dados: item
        };
      });
    },

    listaUsuariosCompartilhados: function () {

      var _this = this;

      return _this.arrayAuxUsuarios;
    },

    chamadoAvaliado: function () {

      var _this = this;

      var avaliacao = _this.chamadoAux.avaliacao_chamado;

      if (avaliacao == '' || avaliacao == null || avaliacao == undefined) {
        $('.avlia0').html('Aguardando avaliação!');
      }

      switch (parseInt(avaliacao)) {
        case 1:
          $('.avlia0').html('');
          $('.avalia1').addClass("fa fa-star");
          break;
        case 2:
          $('.avlia0').html('');
          $('.avalia1').addClass("fa fa-star");
          $('.avalia2').addClass("fa fa-star");
          break;
        case 3:
          $('.avlia0').html('');
          $('.avalia1').addClass("fa fa-star");
          $('.avalia2').addClass("fa fa-star");
          $('.avalia3').addClass("fa fa-star");
          break;
        case 4:
          $('.avlia0').html('');
          $('.avalia1').addClass("fa fa-star");
          $('.avalia2').addClass("fa fa-star");
          $('.avalia3').addClass("fa fa-star");
          $('.avalia4').addClass("fa fa-star");
          break;
        case 5:
          $('.avlia0').html('');
          $('.avalia1').addClass("fa fa-star");
          $('.avalia2').addClass("fa fa-star");
          $('.avalia3').addClass("fa fa-star");
          $('.avalia4').addClass("fa fa-star");
          $('.avalia5').addClass("fa fa-star");
          break;
        default:
          $('.avalia1').removeClass("fa fa-star");
          $('.avalia2').removeClass("fa fa-star");
          $('.avalia3').removeClass("fa fa-star");
          $('.avalia4').removeClass("fa fa-star");
          $('.avalia5').removeClass("fa fa-star");
      }
    },

    transferencia: function () {

      if(this.chamadoAux.status_chamado == 'CE' &&
          this.arrayNomesInteracoesAux.length <= 1 &&
          this.chamadoAux.usuario_criador.id == this.$root.USER.id &&
          (this.chamadoAux.moderou_chamado === '0' ||
          this.chamadoAux.moderou_chamado === '2'))
      {
        return true;
      }
      return false;
    },

    atividadesComputed: function () {
      return _.map(this.arrayAtividades, function (item) {
        return {
          label: item.nm_atividade,
          value: item.id,
          dados: item
        }
      })
    },

    subAtividadesComputed: function () {
      return _.map(this.arraySubAtividade, function (item) {
        return {
          label: item.nm_subatividade,
          value: item.id,
          dados: item
        }
      })
    },

    usuariosComputed: function () {
      return _.map(this.arrayUsuarios, function (item) {
        return {
          label: _.get(item, 'usuario.nome'),
          value: _.get(item, 'usuario.id'),
          dados: _.get(item, 'usuario')
        }
      })
    },
  },

  filters: {
    statusChamado: filtroChamado,

    datas: function (value, args) {
      if (value) {
        return moment(String(value)).format(args ? args : 'DD/MM/YYYY');
      }
    },
  },

  methods: {

    tiposChamado: function (value) {
      switch (value) {
        case '1':
          return [Bugs, 'Bug']
          break;
        case '2':
          return [Enhancement, 'Aprimoramento']
          break;
        case '3':
          return [Proposal, 'Proposta']
          break;
        case '4':
          return [Task, 'Tarefa']
          break;
        default:
          return ['', 'Não atribuido']
      }
    },

    tiposPrioridades: function (value) {
      switch (value) {
        case '1':
          return [Trivial, 'Trivial']
          break;
        case '2':
          return [Minor, 'Baixa']
          break;
        case '3':
          return [Major, 'Alta']
          break;
        case '4':
          return [Critical, 'Crítica']
          break;
        case '5':
          return [Blocker, 'Bloqueio']
          break;
        default:
          return 'Não atribuido'
      }
    },

    iterarChamado: function () {

      var _this = this;

      this.doGet('geral/usuarios/buscar', {paginate: 'false'}).then(function (result) {

        _this.arrayAuxUsuarios = result;

        $.each(_this.arrayAuxUsuarios, function (index, value) {
          if (_this.$root.USER.id == value.id) {
            _this.arrayAuxUsuarios.splice(index, 1);
          }
        });

        var params = {
          id_chamado: _this.chamadoAux.id
        };

        this.doGet('atividades/atividades/listaUsuariosCompartilhados', params).then(function (resultAtend) {
          _this.arrayAuxCompartilha = resultAtend;
        });
      });

      this.tempo_executado = '';
      this.justificativa_iteracao_chamado = '';
    },

    moderarChamado: function () {

      var _this = this;

      var opcoes = {
        departamento_id: this.chamadoAux.id_departamento,
        paginate: 'false',
        ignorar_id: this.$root.USER.id
      };

      this.doGet('geral/vaga-departamento/buscar', opcoes).then(function (result) {

        var array = [];
        for (var i in result) {
          if (result[i].usuario.id !== this.$root.USER.id) {
            array.push(result[i].usuario);
          }
        }
        _this.arrayAuxUsuarios = array;

        return;
      });

      this.tempo_executado = '';
      this.justificativa_iteracao_chamado = '';
      this.$refs['modalModerador'].show();
    },

    iniciarIteracaoChamado: function ($chamado) {

      //Atualiza estatus chamadoAux.
      var params = {
        nr_chamado: $chamado,
        estimativa_horas: this.detalhe.estimativa,
        status_chamado: 'IN'
      };

      this.doPost('atividades/atividades/iniciarChamado', params, false).then(function (result) {

        this.chamadoAux = result;
        this.podeIniciar = false;
        this.podeModerar = false;
        this.podeConcluir = true;
        this.podeIterar = false;

        this.detalhe.estimativa = result.estimativa_horas;

        this.detalhe.justificativa_aux = 'Interação de chamado iniciado com' + ' estimativa para: ' + moment(this.detalhe.estimativa).format('DD/MM/YYYY');

        var params = {
          id_chamado: result.id,
          tempo_executado: '00:00:00',
          justificativa_iteracao_chamado: this.detalhe.justificativa_iteracao_chamado ? this.detalhe.justificativa_aux + '<br>' + '<hr>' + '<br>' + this.detalhe.justificativa_iteracao_chamado : this.detalhe.justificativa_aux
        };

        this.doPost('atividades/atividades/incluirIteracao', params, false).then(function (result) {
          this.alertSucesso();
          this.listarHistorico(result.id_chamado);
        });

        var paramsNotificacao = {};

      });
    },

    revogarConclusao: function ($chamado) {

      var _this = this;

      _this.confirmar(function (sim) {
        if (sim) {
          // Atualiza estatus chamado e avalia.
          var params = {
            nr_chamado: $chamado,
            status_chamado: 'IN',
            avaliacao_chamado: _this.nivelAvaliacao
          };

          _this.doPost('atividades/atividades/finalizaIteracaoChamado', params).then(function (result) {

            var params = {
              id_chamado: result.id,
              justificativa_iteracao_chamado: 'Chamado reaberto. Isso ocorreu por que houve alguma inconsistência na execução do mesmo.',
            };

            this.doPost('atividades/atividades/incluirIteracao', params).then(function (result) {
              this.alertSucesso("Chamado reaberto com sucesso!.")
              this.listarHistorico(result.id);
              _this.podeRevogar = false;
              _this.podeFinalizar = false;

              if(this.chamadoAux.usuario_executor.id == this.$root.USER.id) {
                _this.podeConcluir = true;
              }
            });
          });
        }
      }, "Deseja reabrir este chamado?");
    },

    finalizarChamado: function () {

      this.carragaAvaliacao();

      $('#modalAvaliacao').modal('show');

    },

    finalizaAvaliacao: function ($chamado) {

      // Atualiza estatus chamado e avalia.
      var params = {
        nr_chamado: $chamado,
        status_chamado: 'FN',
        avaliacao_chamado: this.nivelAvaliacao,
        justificativa_iteracao_chamado: 'Chamado concluído.',
      };

      this.doPost('atividades/atividades/finalizaIteracaoChamado', params).then(function (result) {
        this.salvarIteracao(_.get(this, 'chamadoAux.id'), 'Chamado Finalizado');
        // this.alertSucesso('Chamado finalizado!');

        this.podeIniciar = false;
        this.podeIterar = true;
        this.podeRevogar = false;
        this.podeFinalizar = false;

        this.chamadoAux = result;

        $('#modalAvaliacao').modal('hide');

      }).fail(function (erro) {
        this.alertErro(erro);
      });
    },

    concluirChamado: function ($chamado) {
      // Atualiza estatus chamado e avalia.

      var params = {
        nr_chamado: $chamado,
        status_chamado: 'CO',
        avaliacao_chamado: this.nivelAvaliacao,
        justificativa_iteracao_chamado: 'Chamado concluído.',
      }

      // this.$api.atividade.atualiza.finalizaIteracaoChamado(params)
      this.doPost('atividades/atividades/finalizaIteracaoChamado', params).then(function (result) {

        if (this.detalhe.justificativa_iteracao_chamado != '') {
          this.detalhe.justificativa_aux = 'Chamado concluído pelo executor' + '<br>' + '<hr>' + '<br>' + this.detalhe.justificativa_iteracao_chamado
        }else{
          this.detalhe.justificativa_aux = 'Chamado concluído pelo executor';
        }

        this.salvarIteracao(_.get(this, 'chamadoAux.id'), this.detalhe.justificativa_aux);
        this.listarHistorico(result.id_chamado)
        // this.alertSucesso('Chamado concluído!');
        this.podeConcluir = false;
        this.podeIterar = false;

        if(this.chamadoAux.usuario_criador.id == this.$root.USER.id){
          this.podeFinalizar = true;
          this.podeRevogar = true;
        }
        this.chamadoAux = result;

      });
    },

    listarHistorico: function ($idChamado) {

      var params = {
        id_chamado: $idChamado
      };

      this.doGet('atividades/atividades/listarHistorico', params).then(function (result) {

        var intCount = 0;
        var intAnexoCount = 0;
        var participantesCout = 0;
        var arrayNomesInteracoes = [];
        var arrayNomesCompartilhados = [];

        this.arrayIteracoes = result;

        $.each(this.arrayIteracoes, function (index, value) {
          $.each(value, function (i, v) {

            if (v.chamados_compartilhados.length > 0) {
              $.each(v.chamados_compartilhados, function (s, w) {
                if (arrayNomesCompartilhados.includes(w.usuario.nome)) {
                  // console.log('compart', w.usuario);
                } else {
                  arrayNomesCompartilhados.push(w.usuario.nome);
                }
              })
            }
            if (arrayNomesInteracoes.includes(v.usuario_criador_iteracao.nome)) {
              // console.log('true');
            } else {
              arrayNomesInteracoes.push(v.usuario_criador_iteracao.nome);
            }
          })
        })

        this.arrayNomesInteracoesAux = arrayNomesInteracoes;
        this.arrayNomesCompartilhadossAux = arrayNomesCompartilhados;

        this.arrayParticipantesAux = _.union(this.arrayNomesInteracoesAux, this.arrayNomesCompartilhadossAux);

        $.each(result, function (index, element) {
          participantesCout = element[0].chamados_atendimento.length;
          intCount = (element.length);
          intAnexoCount = (element[0].anexo_chamado_por_iteracao.length + element[0].anexo_por_chamado.length);
        });

        this.countParticipantes = participantesCout;
        this.countHistorico = intCount;
        this.countAnexo = intAnexoCount;

        // $("html, body").delay(500).animate({
        //     scrollTop: $('#divIteracoes').offset().top
        // }, 1200);
      });
    },

    salvarIteracao: function ($idChamado, $just) {

      var idUsuariothis = this.$root.USER.id;

      var idUsuarioInteracao = [];

      for (var i in this.interacaoChamado) {

        idUsuarioInteracao.push(this.interacaoChamado[i].value);
      }

      var $justificativa = '';

      if ($just != undefined) {
        $justificativa = $just;
      }else{
        $justificativa = this.detalhe.justificativa_iteracao_chamado;
      }

      var params = {
        id_chamado: $idChamado,
        id_usuario_chamado: idUsuarioInteracao,
        uidAnexo: this.lista_anexo,
        justificativa_iteracao_chamado: $justificativa
      };

      //Atualiza estatus chamadoAux.
      this.doPost('atividades/atividades/incluirIteracao', params).then(function (result) {
        this.listarHistorico(result.id_chamado);
        this.limparFormIteracao();
        this.limparListaAnexo();
        this.alertSucesso();
      }).fail(function (erro) {
        //  this.alertErro(erro);
      });
    },

    limparFormIteracao: function () {

      $('#selectId').val('').trigger('change');
      this.interacaoChamado = '';
      this.detalhe.justificativa_iteracao_chamado = '';
    },

    salvarModeracao: function ($idChamado, $nrChamado) {

      var _this = this;

      var idUsuariothis = this.$root.USER.id;

      // this.usuariosCompAux = {
      //     usuarios: this.moderadoSelecionado.dados.id
      // };

      var params = {
        nr_chamado: $nrChamado,
        id_usuario_executor: this.moderadoSelecionado.dados.id
      };

      //Atualiza usuario executor.
      this.doPost('atividades/atividades/atualizaUsuarioExecutor', params).then(function (result) {

        this.podeModerar = false;
        this.podeIniciar = false;
        this.podeIterar = false;

        //Incluir usuário atendimento
        var paramAtendimento = {
          id_chamado: $idChamado,
          id_usuario_chamado: this.moderadoSelecionado.dados.id
        };

        this.doPost('atividades/atividades/criarListaAtendimentoChamado', paramAtendimento).then(function (resultLista) {
          // console.log(resultLista);
        }).fail(function (erro) {
          this.alertErro(erro);
        });

        // Criar Interação / Histórico.
        var params = {
          id_chamado: $idChamado,
          id_usuario_chamado: this.moderadoSelecionado.dados.id,
          tempo_executado: '00:00:00',
          uidAnexo: '',
          justificativa_iteracao_chamado: 'Chamado moderado com sucesso!'
        };

        this.doPost('atividades/atividades/incluirIteracao', params).then(function (resultIteracao) {

          // Incluir Histórico de Usuários Moderados
          var paramsModerado = {
            id_chamado: $idChamado,
            id_usuario_moderado: this.moderadoSelecionado.dados.id,
            id_iteracao: resultIteracao.id
          };

          this.doPost('atividades/atividades/salvarChamadoModerado', paramsModerado).then(function (result) {
            // console.log('result', result);
          });
        })

        _this.listarHistorico(result.id_chamado);

        this.alertSucesso();

        this.$refs['modalModerador'].hide();

      }).fail(function (erro) {
        this.alertErro(erro);
      });
    },

    verificaLado: function ($num) {

      var n = $num;

      if (n % 2 == 0) {
        return 'par'
      } else {
        return 'impar'
      }

    },

    carregaChosenUsuarios: function () {
      $(".compartilhar").chosen({
        no_results_text: "Oops, nenhum resultado encontrado!",
        allow_single_deselect: true, max_shown_results: 5,
        width: "100%",
        search_contains: true,
        case_sensitive_search: true,
      }).change(function () {
        // _this.cd_atleta = $('.sel_pessoa').val();
        $('.compartilhar').trigger('chosen:updated');
      });

      $(".moderar").chosen({
        no_results_text: "Oops, nenhum resultado encontrado!",
        allow_single_deselect: true, max_shown_results: 5,
        width: "100%",
        search_contains: true,
        case_sensitive_search: true,
      }).change(function () {
        // _this.cd_atleta = $('.sel_pessoa').val();
        $('.moderar').trigger('chosen:updated');
      });
    },

    carragaAvaliacao: function () {

      var _this = this;

      $('.vote label i.fa').on('click mouseover', function () {
        // remove classe ativa de todas as estrelas
        $('.vote label i.fa').removeClass('active');

        // pegar o valor do input da estrela clicada
        var val = $(this).prev('input').val();

        //percorrer todas as estrelas
        $('.vote label i.fa').each(function () {
          /* checar de o valor clicado é menor ou igual do input atual
          *  se sim, adicionar classe active
          */
          var $input = $(this).prev('input');
          if ($input.val() <= val) {
            $(this).addClass('active');
          }
        });


        var textoAvaliacao = '';
        var colorText = '';

        switch (parseInt(val)) {
          case 1:
            textoAvaliacao = 'Ruim'
            colorText = 'FireBrick'
            break;
          case 2:
            textoAvaliacao = 'Regular'
            colorText = 'IndianRed'
            break;
          case 3:
            textoAvaliacao = 'Bom'
            colorText = 'GreenYellow'
            break;
          case 4:
            textoAvaliacao = 'Muito Bom'
            colorText = 'ForestGreen'
            break;
          case 5:
            textoAvaliacao = 'Ótimo'
            colorText = 'Green'
            break;
          default:
            textoAvaliacao = ' '
        }

        $("#voto").html(textoAvaliacao);
        $("#voto").css('color', colorText);

      });
      //Ao sair da div vote
      $('.vote').mouseleave(function () {
        //pegar o valor clicado
        var val = $(this).find('input:checked').val();
        //se nenhum foi clicado remover classe de todos
        if (val == undefined) {
          $('.vote label i.fa').removeClass('active');
        } else {
          //percorrer todas as estrelas
          $('.vote label i.fa').each(function () {
            /* Testar o input atual do laço com o valor clicado
            *  se maior, remover classe, senão adicionar classe
            */
            var $input = $(this).prev('input');
            if ($input.val() > val) {
              $(this).removeClass('active');
            } else {
              $(this).addClass('active');
            }
          });
        }

        var textoAvaliacao = '';
        var colorText = '';

        switch (parseInt(val)) {
          case 1:
            textoAvaliacao = 'Ruim'
            colorText = 'FireBrick'
            break;
          case 2:
            textoAvaliacao = 'Regular'
            colorText = 'IndianRed'
            break;
          case 3:
            textoAvaliacao = 'Bom'
            colorText = 'GreenYellow'
            break;
          case 4:
            textoAvaliacao = 'Muito Bom'
            colorText = 'ForestGreen'
            break;
          case 5:
            textoAvaliacao = 'Ótimo'
            colorText = 'Green'
            break;
          default:
            textoAvaliacao = ' '
        }

        $("#voto").html(textoAvaliacao);
        $("#voto").css('color', colorText);

        _this.nivelAvaliacao = val;
      });
    },

    incluirAnexos: function () {
      this.$bvModal.show('mIncluirAnexoHistorico');
    },

    incluirListaAnexo: function () {

      this.dados_detalhe_anexo.token = this.dados_detalhe_anexo.token;
      this.dados_detalhe_anexo.url = this.dados_detalhe_anexo.url;
      this.dados_detalhe_anexo.titulo_anexo = this.dados_detalhe_anexo.titulo_anexo ? this.dados_detalhe_anexo.titulo_anexo : this.dados_detalhe_anexo.original_name;
      this.dados_detalhe_anexo.original_name = this.dados_detalhe_anexo.original_name;
      this.dados_detalhe_anexo.mimeType = this.dados_detalhe_anexo.mimeType;

      this.lista_anexo.push(_.clone(this.dados_detalhe_anexo));

      this.dados_detalhe_anexo.titulo_anexo = '';
      this.detalhe.upload_det_uid = '';

    },

    removerItemListaAnexo: function ($i) {
      this.lista_anexo.splice($i, 1);
    },

    limparListaAnexo: function () {
      this.lista_anexo = [];
      this.dados_detalhe_anexo.titulo_anexo = '';
      this.detalhe.upload_det_uid = '';
    },

    transferirAtividadeExecutor: function () {
      this.getAtividadesPorDepartamento(this.chamadoAux.departamento_chamado.id);
    },

    getAtividadesPorDepartamento: function (value) {
      this.doGet('atividades/atividades/listarAtividadesCadastradas', {idDepartamento: value}).then(function (result) {
        this.arrayAtividades = result;
        this.$refs['modalTransferencia'].show();
      });
    },

    getSubatividadesPorIdAtividade: function (value) {

      var params = {
        idAtividade: value
      };

      this.doGet('atividades/atividades/listarSubAtividadePorAtividade', params).then(function (result) {
        this.arraySubAtividade = result;
      });
    },

    getUsuariosPorDepartamento: function (value) {

      var arrayUsuariosAux = [];

      var params = {
        departamento_id: value,
        opcao: 'usuarios'
      };

      this.doGet('geral/vaga-departamento/listaVagaDepartamento', params).then(function (result) {

        var _this = this;

        $.each(result, function (index, value) {
          $.each(_.get(value, 'usuario.vaga_departamentos'), function (indice, val) {
            if (_.get(val, 'vaga_departamento_pai', 1) == null) {
              _this.isGestor = val.usuario_id;
            }
          });
        });

        $.each(result, function (s, w) {
          if(w.usuario_id != _this.$root.USER.id){
            arrayUsuariosAux.push(w)
          }
        })

        this.arrayUsuarios = arrayUsuariosAux;
      });
    },

    salvarTransferencia: function (){

      var _this = this;

      var params = {
        nr_chamado: _this.chamadoAux.nr_chamado,
        id_atividade: _this.atividades.value,
        id_subatividade: _this.subatividade.value,
        id_usuario_executor: _this.executor.value,
        tipo_alteracao: _this.selecionaAtividade
      };

      //Atualiza estatus chamadoAux.
      this.doPost('atividades/atividades/tranferirAtividadeExecutor', params).then(function (result) {
        this.$refs['modalTransferencia'].hide();
        this.alertSucesso();

        _this.detalheChamado(result.nr_chamado);


      })
    },

    detalheChamado: function ($nr_chamado) {

      var params = {
        nr_chamado: $nr_chamado
      };

      this.doGet('atividades/atividades/listarChamadosPorNr', params).then(function (result) {
        this.result = result;
        this.chamado = result;
      });
    },
  },

  props: ['chamado'],

  data: function () {
    return {
      detalhe: {
        estimativa: moment().format('YYYY-MM-DD'),
        tempo_executado: '',
        tempo_restante_estimado: '',
        justificativa_iteracao_chamado: '',
        justificativa_aux: '',
        upload_det_uid: '',
        mime: ''
      },

      arrayAuxCompartilha: [],
      arrayAuxUsuarios: [],
      arrayIteracoes: [],
      arrayNomesInteracoesAux: [],
      arrayNomesCompartilhadossAux: [],
      arrayParticipantesAux: [],
      initStatus: false,
      initStatus: false,
      podeIniciar: false,
      podeConcluir: false,
      podeFinalizar: false,
      podeRevogar: false,
      podeModerar: false,
      podeIterar: false,
      podeCitar: false,
      nivelAvaliacao: '',
      moderadoSelecionado: '',
      interacaoChamado: [],
      dataEstimativa: '',
      countHistorico: '',
      countParticipantes: '',
      dataVerify: '',
      dataVerifyAtual: '',
      FundoMsg: FundoMsg,
      idIteracao: '',
      checkCitarColaborador: false,
      countAnexo: '',
      usuarioLogado: '',
      arrayAtividades: [],
      arraySubAtividade: [],
      arrayUsuarios: [],
      atividades: '',
      subatividade: '',
      executor: '',
      disableAlter: true,
      disabledAtiSub: true,
      disabledExec: true,
      desejaAlter: '',
      selecionaAtividade: '',
      selecionaExecutor: '',

      dados_detalhe_anexo: {
        token: '',
        titulo_anexo: '',
        original_name: '',
        img_thumb: '',
        url: '',
        data_cricao: '',
        mimeType: ''
      },

      lista_anexo: [],

      chamadoAux: {},
    };
  }

}
</script>

<style scoped>

.participantes-hide {
  display: none;
}

.part:hover .participantes-hide {
  display: block;
}

.modal-title-iteracao {
  font-family: inherit !important;
  font-weight: bold !important;
  color: #0B2C5F !important;
}

.titulo-chamado-form-iteracao {
  color: #0B2C5F;
  border: 1px solid;
  border-radius: 3px;
  padding: 3px;
}

.open-interation {
  text-align: center;
  -webkit-box-shadow: 0px 10px 38px 1px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 10px 38px 1px rgba(0, 0, 0, 0.75);
  box-shadow: 0px 10px 20px -7px rgba(0, 0, 0, 0.75);
  padding-bottom: 10px;
  font-weight: bold;
  font-size: 16px;
  color: #111192;
  cursor: pointer;
}

.historicoChamados {
  border: solid 1px;
  padding: 5px;
  border-color: #ccc;
  -webkit-box-shadow: 0px 10px 38px 1px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 10px 38px 1px rgba(0, 0, 0, 0.75);
  box-shadow: 0px 10px 20px -7px rgba(0, 0, 0, 0.75);
}

#subirTopo {
  text-decoration: none;
  background: rgba(30, 156, 161, .9);
  bottom: 30px;
  right: 30px;
  color: #fff;
  text-align: center;
  cursor: pointer;
  padding: 15px;
  font-size: 15px;
  font-weight: bold;
  text-transform: uppercase;
  position: fixed;
  border: 0;
  font-family: sans-serif;
  opacity: .8;
}

#subirTopo:hover {
  opacity: 1;
}

.smoothscroll-top {
  position: fixed;
  opacity: 0;
  visibility: hidden;
  overflow: hidden;
  text-align: center;
  z-index: 99;
  background-color: #2ba6e1;
  color: #fff;
  width: 47px;
  height: 44px;
  line-height: 44px;
  right: 25px;
  bottom: -25px;
  padding-top: 2px;
  border-radius: 5px;
  transition: all .5s ease-in-out;
  transition-delay: .2s;
}

.smoothscroll-top:hover {
  background-color: #3eb2ea;
  color: #fff;
  transition: all .2s ease-in-out;
  transition-delay: 0;
}

.smoothscroll-top.show {
  visibility: visible;
  cursor: pointer;
  opacity: 1;
  bottom: 25px;
}

.smoothscroll-top i.fa {
  line-height: inherit;
}

.usuarios-compartilhados {
  border: 1px solid;
  padding: 2px 9px 3px 9px;
  background-color: #cccccc61;
  border-radius: 3px;
}

/*CSS Votação de Chamado*/
/********************************************************************/
.vote label {
  cursor: pointer;
}

.vote label input {
  display: none;
}

.vote label i {
  /*font-family: FontAwesome;*/
  font-size: 40px;
  -webkit-transition-property: color, text;
  -webkit-transition-duration: .2s, .2s;
  -webkit-transition-timing-function: linear, ease-in;
  -moz-transition-property: color, text;
  -moz-transition-duration: .2s;
  -moz-transition-timing-function: linear, ease-in;
  -o-transition-property: color, text;
  -o-transition-duration: .2s;
  -o-transition-timing-function: linear, ease-in;
}

.vote label i:before {
  content: '\f005';
}

.vote label i.active {
  color: gold;
}

/*End css votação*/
/*************************************************************************/


.pagination {
  margin: 0 !important;
}

.sub-header {
  padding-bottom: 10px;
  border-bottom: 1px solid #EEE;
}


.pagination {
  margin: 0;
}

.pagination > li > a,
.pagination > li > span {
  padding: 4px 10px;
}

.table-condensed > tbody > tr > td.stack,
.table-condensed > tfoot > tr > td.stack,
.table-condensed > thead > tr > td.stack {
  padding: 0;
  border-top: none;
  background-color: #F6F6F6;
  border-top: 1px solid #D1D1D1;
  max-width: 0;
  overflow-x: auto;
}

.table-condensed > tbody > tr > td > p {
  margin: 0;
}

.stack-content {
  padding: 8px;
  color: #AE0E0E;
  font-family: consolas, Menlo, Courier, monospace;
  font-size: 12px;
  font-weight: 400;
  white-space: pre-line;
}

.info-box.level {
  display: block;
  padding: 0;
  margin-bottom: 15px;
  /*min-height: 70px;*/
  background: #fff;
  width: 100%;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
  border-radius: 2px;
}

.info-box.level .info-box-text,
.info-box.level .info-box-number,
.info-box.level .info-box-icon > i {
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
}

.info-box.level .info-box-text {
  display: block;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.info-box.level .info-box-content {
  /*padding: 5px 10px;*/
  margin-left: 70px;
  padding: 5px 12px 5px 2px;
}

.info-box.level .info-box-number {
  display: block;
  font-weight: bold;
  font-size: 14px;
}

.info-box.level .info-box-icon {
  border-radius: 2px 0 0 2px;
  display: block;
  float: left;
  height: 62px;
  width: 60px;
  text-align: center;
  font-size: 30px;
  line-height: 70px;
  background: rgba(0, 0, 0, 0.2);
  padding-top: 0;
  padding-bottom: 45px;
}

.info-box.level .progress {
  background: rgba(0, 0, 0, 0.2);
  margin: 5px -10px 5px -10px;
  height: 2px;
}

.info-box.level .progress .progress-bar {
  background: #fff;
}

.info-box.level-empty {
  opacity: .6;
  -webkit-filter: grayscale(1);
  -moz-filter: grayscale(1);
  -ms-filter: grayscale(1);
  filter: grayscale(1);
  -webkit-transition: all 0.2s ease-in-out;
  -moz-transition: all 0.2s ease-in-out;
  -o-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
  -webkit-transition-property: -webkit-filter, opacity;
  -moz-transition-property: -moz-filter, opacity;
  -o-transition-property: filter, opacity;
  transition-property: -webkit-filter, -moz-filter, -o-filter, filter, opacity;
}

.info-box.level-empty:hover {
  opacity: 1;
  -webkit-filter: grayscale(0);
  -moz-filter: grayscale(0);
  -ms-filter: grayscale(0);
  filter: grayscale(0);
}

.level {
  padding: 2px 6px;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
  border-radius: 2px;
  font-size: .9em;
  font-weight: 600;
}

.badge.level-all,
.badge.level-emergency,
.badge.level-alert,
.badge.level-critical,
.badge.level-error,
.badge.level-warning,
.badge.level-notice,
.badge.level-info,
.badge.level-debug,
.level, .level i,
.info-box.level-all,
.info-box.level-emergency,
.info-box.level-alert,
.info-box.level-critical,
.info-box.level-error,
.info-box.level-warning,
.info-box.level-notice,
.info-box.level-info,
.info-box.level-debug {
  color: #FFF;
}

.label-env {
  font-size: .85em;
}

.badge.level-all, .level.level-all, .info-box.level-all {
  background-color: #1DA1F2;
}

.badge.level-emergency, .level.level-emergency, .info-box.level-emergency {
  background-color: #B71C1C;
}

.badge.level-alert, .level.level-alert, .info-box.level-alert {
  background-color: #D32F2F;
}

.badge.level-critical, .level.level-critical, .info-box.level-critical {
  background-color: #F44336;
}

.badge.level-error, .level.level-error, .info-box.level-error {
  background-color: #EF6F6C;
}

.badge.level-warning, .level.level-warning, .info-box.level-warning {
  background-color: #F89A14;
}

.badge.level-notice, .level.level-notice, .info-box.level-notice {
  background-color: #418bca;
}

.badge.level-info, .level.level-info, .info-box.level-info {
  background-color: #67C5DF;
}

.badge.level-debug, .level.level-debug, .info-box.level-debug {
  background-color: #90CAF9;
}

.badge.level-empty, .level.level-empty {
  background-color: #D1D1D1;
}

.badge.label-env, .label.label-env {
  background-color: #BD237A;
}

.m-r-5 {
  margin-right: 5px;
}

.panel-heading > span {
  margin-top: 5px !important;
}

.table > tbody > tr > td {
  vertical-align: middle !important;
}

td span, th span, tr td.text-right {
  display: inline-flex;
}

/* Chart.js */
@-webkit-keyframes chartjs-render-animation {
  from {
    opacity: 0.99
  }
  to {
    opacity: 1
  }
}

@keyframes chartjs-render-animation {
  from {
    opacity: 0.99
  }
  to {
    opacity: 1
  }
}

.chartjs-render-monitor {
  -webkit-animation: chartjs-render-animation 0.001s;
  animation: chartjs-render-animation 0.001s;
}

/*time line*/

.timeline-item {
  padding: 3em 2em 2em;
  position: relative;
  color: rgba(0, 0, 0, 0.7);
  border-left: 2px solid #36464e;
}

.timeline-item p {
  font-size: 1rem;
}

.timeline-item::before {
  content: attr(date-is);
  position: absolute;
  left: 1em;
  font-weight: bold;
  top: 1em;
  display: block;
  /*font-family: 'Roboto', sans-serif;*/
  font-weight: 700;
  font-size: 1.2rem;
  border: 1px solid #e1f2fb;
  background-color: #e1f2fb;
  color: #7b8185;
  padding: 5px;
  border-radius: 5px;
}

.timeline-item::after {
  width: 15px;
  height: 15px;
  display: block;
  top: 2em;
  position: absolute;
  left: -8px;
  border-radius: 10px;
  content: '';
  border: 2px solid rgb(65, 118, 172);
  background: rgb(65, 118, 172);
}

.timeline-item:last-child {
  border-color: rgb(65, 118, 172);
  /*-o-border-image: linear-gradient(to bottom, rgb(65, 118, 172) 60%, transparent) 1 100%;*/
  /*border-image: -webkit-linear-gradient(top, rgb(65, 118, 172) 60%, transparent) 1 100%;*/
  /*border-image: linear-gradient(to bottom, rgb(65, 118, 172) 60%, transparent) 1 100%;*/
}

#corpo {
  border: 1px solid #ccc;
  margin-top: 20px;
  border-radius: 0px 10px 10px 10px;
  padding: 10px;
  margin-bottom: 10px;
}

#justificativa_iteracao_chamado {
  background-color: rgb(248, 249, 252);
}

/*.historicoChamado{*/
/*background-image: url("./../../assets/fluxo/wps.png");*/
/*}*/


/**
forcar validacao
 */
.is-invalid .v-select {
  border: 1px solid red !important;
}

.is-invalid .invalid-feedback {
  display: block !important;
}

.concluido {
  border: 1px solid #ccc;
  border-radius: 3px;
  color: #333;
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  line-height: 99%;
  margin: 0;
  padding: 5px 30px;
  text-align: center;
  text-decoration: none;
  text-transform: uppercase;
  white-space: nowrap;
  background-color: rgba(110, 0, 0, 0.62);
  color: #fff;
  cursor: pointer;
  width: 166.63px;
  height: 22px;
}

.finalizado {
  border: 1px solid #ccc;
  border-radius: 3px;
  color: #333;
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  line-height: 99%;
  margin: 0;
  padding: 5px 30px;
  text-align: center;
  text-decoration: none;
  text-transform: uppercase;
  white-space: nowrap;
  background-color: rgba(6, 79, 7, 0.62);
  color: #fff;
  cursor: pointer;
  width: 166.63px;
  height: 22px;
}

.status_ce {
  border: 1px solid #ccc;
  border-radius: 3px;
  color: #333;
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  line-height: 99%;
  margin: 0;
  padding: 5px 30px;
  text-align: center;
  text-decoration: none !important;
  text-transform: uppercase;
  white-space: nowrap;
  background-color: #cccccc21;
  cursor: pointer;
  width: 166.63px;
  height: 22px;
}

</style>
