<template>
  <div>
    <b-row>
      <b-col>
        <b-form-group label="Tipo de Contrato">
          <b-form-select
              name="tp_contrato"
              v-model="preContrato.tp_contrato"
              :options="l_.map(tiposPreContratoOriginal, (i, k) => ({value: k, text: i}))"
              :state="l_.get(validationResponse,'tp_contrato') ? false : undefined"
              :disabled="tipo_contrato_on">
          </b-form-select>
          <b-form-invalid-feedback :state="l_.get(validationResponse,'tp_contrato') ? true:undefined">
            {{l_.get(validationResponse,'tp_contrato',[]).join('')}}
          </b-form-invalid-feedback>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group label="Nome do Contrato">
          <b-form-input
              id="nm_contrato"
              name="nm_contrato"
              v-model="preContrato.nm_contrato"
              :class="{'is-invalid': l_.get(validationResponse,'nome_contrato')}"
              type="text"
              :disabled="aprovadoOrReprovado"
              placeholder="Nome do Contrato"
          ></b-form-input>
          <small class="invalid-feedback"
                 v-if="l_.get(validationResponse,'nome_contrato')">
            {{l_.get(validationResponse,'nome_contrato').join(' ')}}
          </small>
        </b-form-group>
      </b-col>

      <!-- Contratantes -->
      <b-col>
        <div class="form-group" :class="{'has-error': l_.get(validationResponse,'contratante')}">
          <label>Contratante</label>
          <v-select name="contratante"
                    v-model="preContrato.contratante"
                    :options="listaContratantes">
            <span slot="no-options">Não há dados a serem exibidos!</span>
          </v-select>
          <small class="invalid-feedback" v-if="l_.get(validationResponse,'contratante')">
            {{ l_.get(validationResponse,'contratante') .join(' ') }}</small>
        </div>
        <!--                <label>Contratante</label>-->
        <!--                <BtnBuscarClientesContratante-->
        <!--                        @select="preContrato.contratante = $event"-->
        <!--                        :disabled="aprovadoOrReprovado">-->
        <!--                </BtnBuscarClientesContratante>-->
      </b-col>
      <!-- Fim contratantes -->

    </b-row>
    <b-row>
      <!-- Responsáveis -->
      <b-col>
        <div class="form-group" :class="{'has-error': l_.get(validationResponse,'responsavel')}">
          <label>Responsável</label>
          <v-select name="responsavel"
                    v-model="preContrato.responsavel"
                    :options="listaUsuariosSistema">
            <span slot="no-options">Não há dados a serem exibidos!</span>
          </v-select>
          <small class="invalid-feedback" v-if="l_.get(validationResponse,'responsavel')">
            {{ l_.get(validationResponse,'responsavel') .join(' ') }}</small>
        </div>
        <!--                <label>Responsável</label>-->
        <!--                <BtnBuscarUsuarios @select="preContrato.responsavel = $event" :disabled="aprovadoOrReprovado"></BtnBuscarUsuarios>-->
      </b-col>
      <!-- Fim responsáveis -->
      <b-col>
        <div :class="{'has-error': l_.get(validationResponse,'tipo_chamado')}">
          <b-form-group label="Status Pré-contrato">
            <b-form-select disabled v-model="preContrato.status" :options="l_.map(statusPreContrato, (i, k) => ({value: k, text: i}))"></b-form-select>
            <small class="invalid-feedback" v-if="l_.get(validationResponse,'tipo_chamado')">
              {{ l_.get(validationResponse,'tipo_chamado') .join(' ') }}
            </small>
          </b-form-group>
        </div>
      </b-col>

      <!-- Executante -->
      <b-col>
        <div class="form-group" :class="{'has-error': l_.get(validationResponse,'executante')}">
          <label>Executante</label>
          <v-select name="executante"
                    :options="listaContratantes"
                    v-model="preContrato.executante">
            <span slot="no-options">Não há dados a serem exibidos!</span>
          </v-select>
          <small class="invalid-feedback" v-if="l_.get(validationResponse,'executante')">
            {{ l_.get(validationResponse,'executante') .join(' ') }}</small>
        </div>
        <!--                <label>Executante</label>-->
        <!--                <BtnBuscarClientesContratante @select="preContrato.executante = $event" :disabled="aprovadoOrReprovado"></BtnBuscarClientesContratante>-->
      </b-col>
      <!-- Fim executante -->
    </b-row>

    <!-- FOMULÁRIO PARA LICITAÇÃO -->

    <template v-if="preContrato.tp_contrato == '5'" class="animate__animated animate__bounce">
      <b-row>
        <b-col>
          <b-form-group label="Status Licitação">
            <b-form-select
                name="statusLicitacao"
                v-model="licitacao.status_licitacao"
                :options="l_.map(statusLicitacao, (i, k) => ({value: k, text: i}))"
                :state="l_.get(validationResponse,'statusLicitacao') ? false : undefined">
            </b-form-select>
            <b-form-invalid-feedback :state="l_.get(validationResponse,'statusLicitacao') ? true:undefined">
              {{l_.get(validationResponse,'statusLicitacao',[]).join('')}}
            </b-form-invalid-feedback>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Modalidade">
            <b-form-select
                name="modalidadeLicitacao"
                v-model="licitacao.modalidade"
                :options="l_.map(modalidadeLicitacao, (i, k) => ({value: k, text: i}))"
                :state="l_.get(validationResponse,'modalidadeLicitacao') ? false : undefined">
            </b-form-select>
            <b-form-invalid-feedback :state="l_.get(validationResponse,'modalidadeLicitacao') ? true:undefined">
              {{l_.get(validationResponse,'modalidadeLicitacao',[]).join('')}}
            </b-form-invalid-feedback>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Tipo Licitação">
            <b-form-select
                name="tipoLicitacao"
                v-model="licitacao.tipo_licitacao"
                :options="l_.map(tipoLicitacao, (i, k) => ({value: k, text: i}))"
                :state="l_.get(validationResponse,'tipoLicitacao') ? false : undefined">
            </b-form-select>
            <b-form-invalid-feedback :state="l_.get(validationResponse,'tipoLicitacao') ? true:undefined">
              {{l_.get(validationResponse,'tipoLicitacao',[]).join('')}}
            </b-form-invalid-feedback>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group label="Regime">
            <b-form-select
                name="regimeLicitacao"
                v-model="licitacao.regime"
                :options="l_.map(regimeLicitacao, (i, k) => ({value: k, text: i}))"
                :state="l_.get(validationResponse,'regimeLicitacao') ? false : undefined">
            </b-form-select>
            <b-form-invalid-feedback :state="l_.get(validationResponse,'regimeLicitacao') ? true:undefined">
              {{l_.get(validationResponse,'regimeLicitacao',[]).join('')}}
            </b-form-invalid-feedback>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Nº Licitação">
            <b-form-input
                id="numeroLicitacao"
                v-model="licitacao.nr_licitacao"
                type="text"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Edital">
            <b-form-input
                id="numeroEdital"
                v-model="licitacao.nr_edital"
                type="text"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="6">
          <b-form-group label="Local">
            <b-form-input
                id="localLicitacao"
                v-model="licitacao.local_licitacao"
                type="text"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <label for="abertura">Data Abertura</label>
          <b-form-datepicker id="abertura" v-model="licitacao.data_abertura" class="mb-2"></b-form-datepicker>
        </b-col>
        <b-col>
          <b-form-group label="Valor">
            <b-form-input
                id="valorLicitacao"
                v-model="licitacao.valor_licitacao"
                type="text"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group label="Objeto">
            <b-form-textarea
                id="objeto"
                v-model="licitacao.objeto"
                placeholder="Entre com o objeto..."
                rows="3"
                max-rows="6"
            ></b-form-textarea>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group label="Histórico">
            <b-form-textarea
                id="historico"
                v-model="licitacao.historico"
                placeholder="Entre com o histórico..."
                rows="3"
                max-rows="6"
            ></b-form-textarea>
          </b-form-group>
        </b-col>
      </b-row>
    </template>
    <!-- FIM -->

    <b-row>
      <b-col>
        <b-form-group label="Descrição">
          <b-form-textarea
              id="textarea-rows"
              placeholder="Descrição pré-contrato"
              :disabled="aprovadoOrReprovado"
              v-model="preContrato.descricao"
              rows="4"
          ></b-form-textarea>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <div class="col-lg-6">
        <b-button variant="primary" @click="incluirAnexos">
          <i class="fa fa-paperclip"></i> Incluir Anexo <b-badge variant="light">{{lista_anexo.length}}</b-badge>
        </b-button>
        <!--        <BtnUpload v-model="uploadPreContratosEditais"></BtnUpload>-->
      </div>
      <div class="col-lg-6" style="text-align: right">
        <div class="btn-group">
          <button v-if="preContrato.id_contrato == ''"
                  type="button"
                  @click="salvarPreContrato"
                  :disabled="aprovadoOrReprovado"
                  class="btn btn-success"><i class="fa fa-save"></i> SALVAR
          </button>

          <button v-if="preContrato.id_contrato != ''"
                  type="button"
                  @click="salvarEdicaoPreContrato"
                  :disabled="aprovadoOrReprovado"
                  class="btn btn-success"><i class="fa fa-edit"></i> ALTERAR
          </button>

          <button type="button"
                  @click="limparPreContrato"
                  class="btn btn-info"><i class="fa fa-eraser"></i> LIMPAR
          </button>
        </div>
      </div>
    </b-row>

    <hr>

    <b-card class="shadow-lg" no-body>
      <template #header>
        <span><i class="fa fa-list"></i> LISTA DE PRÉ-CONTRATO</span>
      </template>

      <template #default>
        <b-overlay
            :show="ajaxStatus"
            rounded
            opacity="0.6"
            spinner-small
            spinner-variant="primary"
            class="d-inline-block">
          <div class="row">
            <div class="col-md-12 col-lg-12">
              <!-- BEGIN SAMPLE TABLE PORTLET-->
              <div class="portlet box primary">
                <div class="portlet-body">
                  <div class="table-scrollable table-responsive">
                    <table class="table table-hover table-striped precont" style="font-size: 13px">
                      <thead>
                      <tr>
                        <th>Código</th>
                        <th>Contrato</th>
                        <th>Responsável</th>
                        <th>Tipo</th>
                        <th style="text-align: center">Anexo</th>
                        <th style="text-align: center">Status</th>
                        <th >Ações</th>
                      </tr>
                      </thead>
                      <tbody>
                      <template v-if="preContratosLista.length > 0">
                        <tr v-for="v in preContratosLista">
                          <td v-if="v.status == '2'">
                              <span class="analise" v-if="v.status == '1'"
                                    data-toggle="tooltip"
                                    title="Contrato em Análise"
                                    v-on:click="detalhePreContrato(v.id)">
                                  <i class="fa fa-circle"></i> #{{v.codigo_pre_contrato}}
                              </span>
                            <span class="aprovado"
                                  v-if="v.status == '2'"
                                  data-toggle="tooltip"
                                  title="Contrato Aprovado"
                                  v-on:click="detalhePreContrato(v.id)">
                                <i class="fa fa-circle"></i> #{{v.codigo_pre_contrato}}
                            </span>
                            <span class="reprovado"
                                  v-if="v.status == '3'"
                                  data-toggle="tooltip"
                                  title="Contrato Reprovado"
                                  v-on:click="detalhePreContrato(v.id)">
                                <i class="fa fa-circle"></i> #{{v.codigo_pre_contrato}}
                            </span>
                          </td>

                          <td v-if="v.status != '2'">
                            <span class="analise" v-if="v.status == '1'"
                                  data-toggle="tooltip"
                                  title="Contrato em Análise">
                                <i class="fa fa-circle"></i> #{{v.codigo_pre_contrato}}
                            </span>
                            <span class="aprovado"
                                  v-if="v.status == '2'"
                                  data-toggle="tooltip"
                                  title="Contrato Aprovado">
                                <i class="fa fa-circle"></i> #{{v.codigo_pre_contrato}}
                            </span>
                            <span class="reprovado"
                                  v-if="v.status == '3'"
                                  data-toggle="tooltip"
                                  title="Contrato Reprovado">
                                <i class="fa fa-circle"></i> #{{v.codigo_pre_contrato}}
                            </span>
                          </td>

                          <td>{{v.nome_contrato}}</td>
                          <td>{{v.responsavel.nome}}</td>
                          <td>{{tiposPreContratoOriginal[v.tipo_contrato]}}</td>
                          <td style="text-align: center; cursor: pointer" v-if="v.anexos.length > 0">
                            <i class="far fa-list-alt fa-2x" @click="anexados(v.id)"></i>
                          </td>
                          <td style="text-align: center"  v-else>--</td>
                          <!--                          <td style="text-align: center">-->
                          <!--                            <a :href="v.token_url" target="_blank" v-if="v.token_url">-->
                          <!--                              <Ficone :mime="v.mimeType"></Ficone>-->
                          <!--                            </a>-->
                          <!--                            <a v-else>-->
                          <!--                              <span style="font-weight: bold">&#45;&#45;</span>-->
                          <!--                            </a>-->
                          <!--                          </td>-->
                          <td style="text-align: center" v-if="v.status == '1'"><b-badge href="#" variant="warning">{{statusPreContrato[v.status]}}</b-badge></td>
                          <td style="text-align: center" v-if="v.status == '2'"><b-badge href="#" variant="success">{{statusPreContrato[v.status]}}</b-badge></td>
                          <td style="text-align: center" v-if="v.status == '3'"><b-badge href="#" variant="danger">{{statusPreContrato[v.status]}}</b-badge></td>
                          <td>
                            <b-dropdown variant="outline-secondary" class="m-2" no-caret dropup >
                              <template v-slot:button-content>
                                <i class="fas fa-ellipsis-v"></i>
                              </template>
                              <b-dropdown-group header="Selecione" class="">
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item-button variant="primary" v-if="usuarioLogado == v.id_responsavel" @click="editarPreContrato(v)">
                                  <i class="fa fa-edit"></i> Editar
                                </b-dropdown-item-button>
                                <b-dropdown-item-button variant="danger" v-if="usuarioLogado == v.id_responsavel" @click="excluirPreContrato(v)">
                                  <i class="fa fa-trash-alt"></i> Excluir
                                </b-dropdown-item-button>
                                <b-dropdown-item-button variant="success" v-if="usuarioLogado == v.id_responsavel && v.status != '2'" @click="aprovarReprovarPreContrato(v, '2')">
                                  <i class="fa fa-thumbs-up"></i> Aprovar
                                </b-dropdown-item-button>
                                <b-dropdown-item-button variant="danger" v-if="usuarioLogado == v.id_responsavel && v.status != '3'" @click="aprovarReprovarPreContrato(v, '3')">
                                  <i class="fa fa-thumbs-down"></i> Reprovar
                                </b-dropdown-item-button>
                                <b-dropdown-item-button variant="dark" v-on:click="detalhePreContrato(v.id)">
                                  <i class="fa fa-thumbs-down"></i> Detalhar
                                </b-dropdown-item-button>
                              </b-dropdown-group>
                            </b-dropdown>

                            <!--                                                        <div class="btn-group" role="group" aria-label="Basic example">-->
                            <!--                                                          <a v-if="usuarioLogado == v.id_responsavel" href="#" class="btn btn-outline-primary mr-0" data-toggle="tooltip" title="Editar" @click="editarPreContrato(v)">-->
                            <!--                                                            <i class="fa fa-edit"></i></a>-->
                            <!--                                                          <a v-if="usuarioLogado == v.id_responsavel" href="#" class="btn btn-outline-secondary mr-0" data-toggle="tooltip" title="Excluir" @click="excluirPreContrato(v)">-->
                            <!--                                                            <i class="fa fa-trash-alt"></i>-->
                            <!--                                                          </a>-->
                            <!--                                                          <a href="#"-->
                            <!--                                                             class="btn btn-outline-success mr-0"-->
                            <!--                                                             data-toggle="tooltip"-->
                            <!--                                                             title="Aprovar"-->
                            <!--                                                             v-if="usuarioLogado == v.id_responsavel && v.status != '2'"-->
                            <!--                                                             @click="aprovarReprovarPreContrato(v, '2')">-->
                            <!--                                                            <i class="fa fa-thumbs-up"></i>-->
                            <!--                                                          </a>-->
                            <!--                                                          <a href="#"-->
                            <!--                                                             class="btn btn-outline-danger mr-0"-->
                            <!--                                                             data-toggle="tooltip"-->
                            <!--                                                             title="Reprovar"-->
                            <!--                                                             v-if="usuarioLogado == v.id_responsavel && v.status != '3'"-->
                            <!--                                                             @click="aprovarReprovarPreContrato(v, '3')">-->
                            <!--                                                            <i class="fa fa-thumbs-down"></i>-->
                            <!--                                                          </a>-->
                            <!--                                                          <a href="#"-->
                            <!--                                                             class="btn btn-outline-danger mr-0"-->
                            <!--                                                             data-toggle="tooltip"-->
                            <!--                                                             title="Reprovar"-->
                            <!--                                                             v-if="usuarioLogado != v.id_responsavel"-->
                            <!--                                                             @click="aprovarReprovarPreContrato(v, '3')">-->
                            <!--                                                            <i class="fa fa-thumbs-down"></i>-->
                            <!--                                                          </a>-->
                            <!--                                                          <a href="#"-->
                            <!--                                                             class="btn btn-outline-dark mr-0"-->
                            <!--                                                             data-toggle="tooltip"-->
                            <!--                                                             title="Proposta"-->
                            <!--                                                             @click="detalhePreContrato(v.id)">-->
                            <!--                                                            <i class="far fa-file-alt"></i>-->
                            <!--                                                          </a>-->
                            <!--                                                        </div>-->
                          </td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr style="text-align: center; font-weight: bold">
                          <td colspan="22"> NÃO HÁ REGISTROS PARA SER EXIBIDOS!</td>
                        </tr>
                      </template>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- END SAMPLE TABLE PORTLET-->
            </div>
          </div>
        </b-overlay>
      </template>
      <template #footer></template>
    </b-card>

    <!-- Modal Incluir Anexo -->
    <b-modal id="mIncluirAnexo"
             hide-footer
             title="INCLUIR ANEXO(s)"
             size="lg">
      <div class="row">
        <div class="col-md-12 form-group">
          <BtnUpload v-model="preContrato.upload_doc_uid"
                     v-if="!preContrato.uid_anexo"
                     style="display: inline-block;"
                     mimes="">
          </BtnUpload>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10 form-group">
          <b-form-input placeholder="Nome Anexo"
                        v-model="dados_lista_anexo.titulo_anexo"
                        :disabled="preContrato.upload_doc_uid == ''">
          </b-form-input>
        </div>
        <div class="col-md-2" style="text-align: right">
          <b-button variant="success"
                    @click="incluirListaAnexo"
                    :disabled="preContrato.upload_doc_uid == ''">
            <i class="fa fa-folder-plus"></i> Incluir</b-button>
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
                <td>{{i.titulo_anexo}}</td>
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
                  <b-button variant="danger" @click="removerItemListaAnexo(index)"><i class="fa fa-trash"></i></b-button>
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
          <b-button class="mt-3" variant="success"  @click="$bvModal.hide('mIncluirAnexo')"><i class="fa fa-check"></i> Concluir</b-button>
        </b-button-group>
      </div>

    </b-modal>
    <!-- End Modal Incluir Anexo -->

    <!-- Modal Anexos -->
    <b-modal id="mAnexos"
             hide-footer
             title="ANEXO(S)"
             size="lg">
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <!-- BEGIN SAMPLE TABLE PORTLET-->
          <div class="portlet box primary">
            <div class="portlet-body">
              <div class="table-scrollable table-responsive">
                <table class="table table-hover table-striped precont" style="font-size: 13px">
                  <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Anexo</th>
                  </tr>
                  </thead>
                  <tbody>
                  <template v-if="lista_anexados.length > 0">
                    <tr v-for="v in lista_anexados">
                      <td>{{v.nome_anexo}}</td>
                      <td>
                        <a :href="v.url" target="_blank" v-if="v.url">
                          <Ficone :mime="v.mime_type"></Ficone>
                        </a>
                      </td>
                    </tr>
                  </template>
                  <template v-else>
                    <tr style="text-align: center; font-weight: bold">
                      <td colspan="22"> NÃO HÁ REGISTROS PARA SER EXIBIDOS!</td>
                    </tr>
                  </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div style="text-align: right">
        <b-button-group>
          <b-button class="mt-3" variant="success"  @click="$bvModal.hide('mAnexos')"><i class="fa fa-check"></i> Fechar</b-button>
        </b-button-group>
      </div>

    </b-modal>
    <!-- End Modal Incluir Anexo -->
  </div>
</template>

<script>

import Card from "../geral/Card";
import SingleForm from "../geral/SingleForm";
import InputGeralClientes from "../geral/singleForm/InputGeralEmpresas";

import SelectTipoContrato from "./form/SelectTipoContrato";

import BtnBuscarClientesContratante from "../geral/BtnBuscarClientes";
import BtnBuscarClientesExecutante from "../geral/BtnBuscarClientes";
import TiposDeContrato from "./view/TiposDeContratos";
import BtnUpload from "../geral/BtnUpload";
import InputCep from "../geral/singleForm/InputCep";
import InputEstados from "../geral/singleForm/InputEstados";
import InputCidades from "../geral/singleForm/InputCidades";
import BtnBuscarUsuarios from "../geral/BtnBuscarUsuarios";
import Ficone from "../geral/Ficone";

export default {

  name: "FormPreContrato",

  mounted() {
    this.listarPreContratos();
    this.listaClientes();
    // this.listaUsuarios();

    this.usuarioLogado = this.$root.USER.id;

  },

  components: {
    InputCep,
    Ficone,
    BtnBuscarUsuarios,
    BtnBuscarClientesContratante, BtnUpload, SelectTipoContrato, TiposDeContrato, InputGeralClientes, SingleForm, InputCep, InputEstados, InputCidades},

  props: ['rastreador'],

  watch: {
    uploadPreContratosEditais: function (value) {

      this.doGet('geral/file-storage/buscar', {filtro:{uid:value}}).then(function (result) {

        console.log('res', result);

        this.preContrato.mimeType = result.data[0].metadata.mimeType;
        this.preContrato.token = result.data[0].uid;
        this.preContrato.img_thumb = result.data[0].thumb_url;
      });
    },

    'preContrato.contratante': function (value){
      this.doGet('geral/usuarios/buscar', {filtro:{vinculo_departamento: value.dados.id}, paginacao: false}).then(function (result) {
        console.log('pre', result);
        this.arrayUsuarios = result.data;
      });
    },

    'preContrato.upload_doc_uid': function (value) {

      this.doGet('geral/file-storage/buscar', {filtro:{uid:value}}).then(function (result) {

        console.log('resss', result);

        this.dados_lista_anexo.token = result.data[0].uid;
        this.dados_lista_anexo.img_thumb = result.data[0].thumb_url;
        this.dados_lista_anexo.url = result.data[0].url;
        this.dados_lista_anexo.original_name = result.data[0].metadata.originalName;
        this.dados_lista_anexo.mimeType = result.data[0].metadata.mimeType;
      });
    }
  },

  computed: {
    listaContratantes: function () {
      return _.map(this.arrayContratante, function (item) {
        return {
          label: item.nome,
          value: item.id,
          dados: item
        };
      });
    },

    listaUsuariosSistema: function () {
      return _.map(this.arrayUsuarios, function (item) {
        return {
          label: item.nome,
          value: item.id,
          dados: item
        };
      });
    },
  },

  methods: {

    salvarPreContrato: function () {

      var _this = this;

      if(_this.preContrato.tp_contrato != '5') {

        var params = {
          nome_contrato: _this.preContrato.nm_contrato,
          id_contratante: _this.preContrato.contratante.dados.id,
          id_responsavel: _this.preContrato.responsavel.dados.id,
          id_executante: _this.preContrato.executante.dados.id,
          id_licitacao: 0,
          tipo_contrato: _this.preContrato.tp_contrato,
          token: _this.preContrato.token,
          token_url: _this.preContrato.img_thumb,
          mimeType: _this.preContrato.mimeType,
          status: _this.preContrato.status,
          descricao_pre_contrato: _this.preContrato.descricao,
          uidAnexo: _this.lista_anexo,
        };

        this.doPost('contrato/contrato/salvarPreContrato', params).then(function (result) {
          _this.preContrato.nome_contrato = result.nome_contrato;
          _this.preContrato.codigoPreContrato = result.codigo_pre_contrato;

          _this.alertSucesso();
          _this.listarPreContratos();
          _this.limparPreContrato();

        })
      }else {

        var licita = {
          id_contratante: _this.preContrato.contratante.dados.id,
          status_licitacao: _this.licitacao.status_licitacao,
          modalidade: _this.licitacao.modalidade,
          tipo_licitacao: _this.licitacao.tipo_licitacao,
          regime: _this.licitacao.regime,
          numero_licitacao: _this.licitacao.nr_licitacao,
          numero_edital: _this.licitacao.nr_edital,
          local_licitacao: _this.licitacao.local_licitacao,
          data_abertura: _this.licitacao.data_abertura,
          valor_licitacao: _this.licitacao.valor_licitacao,
          objeto: _this.licitacao.objeto,
          historico: _this.licitacao.historico
        }

        this.doPost('licitacao/licitacao/salvarLicitacao', licita).then(function (result) {

          _this.licitacao.id_licitacao = result.id;

          var params = {
            nome_contrato: _this.preContrato.nm_contrato,
            id_contratante: _this.preContrato.contratante.dados.id,
            id_responsavel: _this.preContrato.responsavel.dados.id,
            id_executante: _this.preContrato.executante.dados.id,
            id_licitacao: _this.licitacao.id_licitacao,
            tipo_contrato: _this.preContrato.tp_contrato,
            token: _this.preContrato.token,
            token_url: _this.preContrato.img_thumb,
            mimeType: _this.preContrato.mimeType,
            status: _this.preContrato.status,
            descricao_pre_contrato: _this.preContrato.descricao
          };

          this.doPost('contrato/contrato/salvarPreContrato', params).then(function (result) {
            _this.preContrato.nome_contrato = result.nome_contrato;
            _this.preContrato.codigoPreContrato = result.codigo_pre_contrato;

            _this.alertSucesso();
            _this.listarPreContratos();
            _this.limparPreContrato();

          })
        });
      }
    },

    listaClientes: function(){

      var _this = this;

      this.doGet('geral/clientes/buscar').then(function (result) {
        _this.arrayContratante = result.data;
      })
    },

    // listaUsuarios: function(){
    //   var _this = this;
    //
    //   this.doGet('geral/usuarios/buscar').then(function (result) {
    //     _this.arrayUsuarios = result.data;
    //   })
    // },


    listarPreContratos: function () {

      var _this = this;

      this.doGet('contrato/contrato/listarPreContratos', {include: ['contratantes', 'responsavel', 'executante', 'licitacao', 'anexos']}).then(function (result) {
        _this.preContratosLista = result;
      })

      setTimeout(function () {
        $('[data-toggle="tooltip"]').tooltip();
      }, 2000);
    },

    editarPreContrato: function ($precontrato) {

      var _this = this;

      _this.preContrato.id_contrato = $precontrato.id;
      _this.preContrato.codigoPreContrato = $precontrato.codigo_pre_contrato;
      _this.preContrato.nm_contrato = $precontrato.nome_contrato;
      _this.preContrato.contratante = {label: $precontrato.contratantes.nome, value: $precontrato.contratantes.id, dados: $precontrato.contratantes};
      _this.preContrato.responsavel = {label: $precontrato.responsavel.nome, value: $precontrato.responsavel.id, dados: $precontrato.responsavel};
      _this.preContrato.executante = {label: $precontrato.executante.nome, value: $precontrato.executante.id, dados: $precontrato.executante};
      _this.preContrato.cep = $precontrato.cep;
      _this.preContrato.uf = $precontrato.uf;
      _this.preContrato.cidade = $precontrato.cidade;
      _this.preContrato.endereco = $precontrato.endereco;
      _this.preContrato.complemento = $precontrato.complemento;
      _this.preContrato.tp_contrato = $precontrato.tipo_contrato;
      _this.preContrato.status = $precontrato.status;
      _this.preContrato.descricao = $precontrato.descricao_pre_contrato;

      /* LICITACAO */
      _this.licitacao.id_licitacao = $precontrato.licitacao.id;
      _this.licitacao.status_licitacao = $precontrato.licitacao.status_licitacao;
      _this.licitacao.modalidade = $precontrato.licitacao.modalidade;
      _this.licitacao.tipo_licitacao = $precontrato.licitacao.tipo_licitacao;
      _this.licitacao.regime= $precontrato.licitacao.regime;
      _this.licitacao.nr_licitacao= $precontrato.licitacao.numero_licitacao;
      _this.licitacao.nr_edital= $precontrato.licitacao.numero_edital;
      _this.licitacao.local_licitacao= $precontrato.licitacao.local_licitacao;
      _this.licitacao.data_abertura= $precontrato.licitacao.data_abertura;
      _this.licitacao.valor_licitacao= $precontrato.licitacao.valor_licitacao;
      _this.licitacao.objeto= $precontrato.licitacao.objeto;
      _this.licitacao.historico = $precontrato.licitacao.historico;
      _this.tipo_contrato_on = true;


    },

    limpaBuscas: function ($busca){
      var _this = this;

      if ($busca == 'C'){
        _this.contratantes = [] ;
      }else if($busca == 'R'){
        _this.responsaveis = [];
      }else if($busca == 'E'){
        _this.executantes = [];
      }
    },

    limparPreContrato: function () {
      var _this = this;

      _this.preContrato.id_contrato = '';
      _this.preContrato.id_contrato = '';
      _this.preContrato.codigoPreContrato = '';
      _this.preContrato.nm_contrato = '';
      _this.preContrato.contratante = '';
      _this.preContrato.responsavel = '';
      _this.preContrato.executante = '';
      _this.preContrato.cep ='';
      _this.preContrato.uf = '';
      _this.preContrato.cidade = '';
      _this.preContrato.endereco = '';
      _this.preContrato.complemento = '';
      _this.preContrato.tp_contrato = '';
      // _this.preContrato.status = '';
      _this.preContrato.descricao = '';
      _this.contratantes = [];
      _this.responsaveis = [];
      _this.executantes = [];

      _this.aprovadoOrReprovado = false;

      _this.uploadPreContratosEditais = '';

      /* LIMPAR LICITAÇÃO*/

      _this.preContrato.contratante.dados.id = '';
      _this.licitacao.status_licitacao = '';
      _this.licitacao.modalidade = '';
      _this.licitacao.tipo_licitacao = '';
      _this.licitacao.regime = '';
      _this.licitacao.nr_licitacao = '';
      _this.licitacao.nr_edital = '';
      _this.licitacao.local_licitacao = '';
      _this.licitacao.data_abertura = '';
      _this.licitacao.valor_licitacao = '';
      _this.licitacao.objeto = '';
      _this.licitacao.historico = ''

    },

    salvarEdicaoPreContrato: function () {
      var _this = this;

      if(_this.preContrato.tp_contrato != '5') {

        var params = {
          nome_contrato: _this.preContrato.nm_contrato,
          id_contratante: _this.preContrato.contratante.dados.id,
          id_responsavel: _this.preContrato.responsavel.dados.id,
          id_executante: _this.preContrato.executante.dados.id,
          id_licitacao: 0,
          tipo_contrato: _this.preContrato.tp_contrato,
          token: _this.preContrato.token,
          token_url: _this.preContrato.img_thumb,
          mimeType: _this.preContrato.mimeType,
          status: _this.preContrato.status,
          descricao_pre_contrato: _this.preContrato.descricao
        };

        this.doPost('contrato/contrato/salvarEdicaoPreContrato', params).then(function (result) {
          _this.alertSucesso();
          _this.listarPreContratos();
        })
      }else {

        var licita = {
          id_licitacao: _this.licitacao.id_licitacao,
          id_contratante: _this.preContrato.contratante.dados.id,
          status_licitacao: _this.licitacao.status_licitacao,
          modalidade: _this.licitacao.modalidade,
          tipo_licitacao: _this.licitacao.tipo_licitacao,
          regime: _this.licitacao.regime,
          numero_licitacao: _this.licitacao.nr_licitacao,
          numero_edital: _this.licitacao.nr_edital,
          local_licitacao: _this.licitacao.local_licitacao,
          data_abertura: _this.licitacao.data_abertura,
          valor_licitacao: _this.licitacao.valor_licitacao,
          objeto: _this.licitacao.objeto,
          historico: _this.licitacao.historico
        }

        this.doPost('licitacao/licitacao/salvarEdicaoLicitacao', licita).then(function (result) {

          _this.licitacao.id_licitacao = result.id;

          var params = {
            id_precontrato: _this.preContrato.id_contrato,
            nome_contrato: _this.preContrato.nm_contrato,
            id_contratante: _this.preContrato.contratante.dados.id,
            id_responsavel: _this.preContrato.responsavel.dados.id,
            id_executante: _this.preContrato.executante.dados.id,
            id_licitacao: _this.licitacao.id_licitacao,
            tipo_contrato: _this.preContrato.tp_contrato,
            token: _this.preContrato.token,
            token_url: _this.preContrato.img_thumb,
            status: _this.preContrato.status,
            descricao_pre_contrato: _this.preContrato.descricao
          };

          this.doPost('contrato/contrato/salvarEdicaoPreContrato', params).then(function (result) {
            _this.alertSucesso();
            _this.listarPreContratos();
          })
        });
      }
    },

    /**
     * A exclusão do pré-contrato, somente será lógica, assim podendo criar um histórico.
     */

    excluirPreContrato: function ($precontrato) {

      var _this = this;

      var params = {
        id_precontrato: $precontrato.id
      };

      this.doPost('contrato/contrato/excluirPreContrato', params).then(function (result) {

        _this.alertSucesso();
        _this.listarPreContratos();
      })
    },

    detalhePreContrato: function ($id_precontrato) {

      var params = {
        id_precontrato: $id_precontrato,
        include: ['contratantes', 'responsavel', 'executante']
      };

      this.doGet('contrato/contrato/listarPreContratoPorId', params).then(function (result) {
        this.result = result;
        this.$emit('seleciona_precontrato', result);
      });
    },

    aprovarReprovarPreContrato: function ($precontrato, $statusPreContrato) {
      var _this = this;

      var params = {
        id_precontrato: $precontrato.id,
        status: $statusPreContrato
      };

      this.doPost('contrato/contrato/aprovarReprovarPreContrato', params).then(function (result) {
        _this.alertSucesso();
        _this.listarPreContratos();

        if(result.status == '2'){
          _this.$emit('contrato_gravado', true);
        }

        // _this.contratantes = $precontrato.contratantes;
        // _this.responsaveis = $precontrato.responsavel;
        // _this.executantes = $precontrato.executante;
        //
        // _this.preContrato.id_contrato = result.id;
        // _this.preContrato.codigoPreContrato = result.codigo_pre_contrato;
        // _this.preContrato.nm_contrato = result.nome_contrato;
        // _this.preContrato.contratante = result.contratantes;
        // _this.preContrato.responsavel = result.responsavel;
        // _this.preContrato.executante = result.executante;
        // _this.preContrato.cep = result.cep;
        // _this.preContrato.uf = result.uf;
        // _this.preContrato.cidade = result.cidade;
        // _this.preContrato.endereco = result.endereco;
        // _this.preContrato.complemento = result.complemento;
        // _this.preContrato.tp_contrato = result.tipo_contrato;
        // _this.preContrato.status = result.status;
        // _this.preContrato.descricao = result.descricao_pre_contrato;
        //
        // _this.aprovadoOrReprovado = true;

      })
    },

    incluirAnexos: function () {
      this.$bvModal.show('mIncluirAnexo');
    },

    anexados: function ($id_precontrato) {

      var _this = this;

      var params = {
        id_precontrato: $id_precontrato,
      };

      this.doGet('contrato/contrato/listaAnexosPreContrato', params).then(function (result) {

        this.lista_anexados = result;

        setTimeout(function (){
          _this.$bvModal.show('mAnexos');
        },500);
      });

    },

    incluirListaAnexo: function () {

      this.dados_lista_anexo.token = this.dados_lista_anexo.token;
      this.dados_lista_anexo.url = this.dados_lista_anexo.url;
      this.dados_lista_anexo.titulo_anexo = this.dados_lista_anexo.titulo_anexo ? this.dados_lista_anexo.titulo_anexo : this.dados_lista_anexo.original_name;
      this.dados_lista_anexo.original_name = this.dados_lista_anexo.original_name;
      this.dados_lista_anexo.mimeType = this.dados_lista_anexo.mimeType;

      this.lista_anexo.push(_.clone(this.dados_lista_anexo));

      this.dados_lista_anexo.titulo_anexo = '';
      this.preContrato.upload_doc_uid = '';

    },

    removerItemListaAnexo: function ($i) {
      this.lista_anexo.splice($i, 1);
    },

    limparListaAnexo: function () {
      this.lista_anexo = [];
      this.dados_lista_anexo.titulo_anexo = '';
      this.preContrato.upload_doc_uid = '';
    },

  },

  data() {
    return {

      tiposPreContratoOriginal: {
        '1': 'LOCAÇÃO',
        '2': 'INTERMITENTE',
        '3': 'MERCANTIS',
        '4': 'ADMINISTRATIVOS',
        '5': 'LICITATÓRIO',
        '6': 'PRÉ PAGO'
      },

      statusPreContrato: {
        '1': 'EM ANÁLISE',
        '2': 'APROVADO',
        '3': 'REPROVADO',
      },

      preContrato: {
        id_contrato: '',
        codigoPreContrato: '',
        nm_contrato: '',
        responsavel: '',
        executante: '',
        cep: '',
        uf: '',
        cidade: '',
        endereco: '',
        complemento: '',
        tp_contrato: '',
        status: '1',
        descricao: '',
        contratante: '',
        img_thumb: '',
        token: '',
        mimeType: '',
        upload_doc_uid: '',
        uid_anexo: ''
      },

      uploadPreContratosEditais: '',
      preContratosLista: [],
      contratantes: [],
      responsaveis: [],
      executantes: [],
      arrayContratante: [],
      arrayUsuarios: [],

      usuarioLogado: '',
      respAproRepro: '',
      aprovadoOrReprovado: false,

      modalidadeLicitacao: {
        '1': 'CONCORRÊNCIA',
        '2': 'CONCURSO',
        '3': 'CONVITE',
        '4': 'LEILÃO',
        '5': 'PREGÃO'
      },

      statusLicitacao: {
        '1': 'PUBLICADA',
        '2': 'ENCERRADA',
        '3': 'ANULADA',
        '4': 'REVOGADA',
        '5': 'CONVALIDADA'
      },

      tipoLicitacao: {
        '1': 'DISPENSA DE LICITAÇÃO',
        '2': 'MELHOR TÉCNICA',
        '3': 'MENOR PREÇO',
        '4': 'TÉCNICA E PREÇO'
      },

      regimeLicitacao: {
        '1': 'EMPREEITADA POR PREÇO GLOBAL',
        '2': 'EMPREEITADA POR PREÇO TOTAL'
      },

      licitacao: {
        id_licitacao: '',
        status_licitacao: '',
        contratante: '',
        modalidade: '',
        tipo_licitacao: '',
        regime: '',
        nr_licitacao: '',
        nr_edital: '',
        local_licitacao: '',
        data_abertura: '',
        valor_licitacao: '',
        objeto: '',
        historico: ''
      },

      arrayLicitacoes: [],

      tipo_contrato_on: false,

      dados_lista_anexo: {
        token: '',
        titulo_anexo: '',
        original_name: '',
        img_thumb: '',
        url: '',
        data_cricao: '',
        mimeType: ''
      },

      lista_anexo: [],

      lista_anexados: []
    };
  },
}
</script>

<style scoped>

.analise{
  color: #e05f13;
  cursor: pointer;
  border: 1px solid;
  padding: 5px;
  border-radius: 5px;
}

.aprovado{
  color: #13a86f;
  cursor: pointer;
  border: 1px solid;
  padding: 5px;
  border-radius: 5px;
  background: rgb(40,115,33);
  background: linear-gradient(90deg, rgba(40,115,33,0.18531162464985995) 0%, rgba(131,230,64,0.2777485994397759) 100%, rgba(0,212,255,1) 100%);
}

.reprovado{
  color: #ce000a;
  cursor: pointer;
  border: 1px solid;
  padding: 5px;
  border-radius: 5px;
}

.badge {
  display: inline-block;
  padding: 0.50em 0.9em;
  font-size: 90%;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.35rem;
  -webkit-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}

.table th, .table td{
  vertical-align: middle!important;
}

</style>
