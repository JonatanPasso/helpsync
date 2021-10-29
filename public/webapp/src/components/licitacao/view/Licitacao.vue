<template>
  <div id="topo">
    <b-card class="shadow-lg mb-4">

      <template #header>
        <span><i class="fa fa-tasks"></i> GESTÃO DE LICITAÇÕES</span>
      </template>

      <template #default>

        <!-- FORM PRÉ-CONTRATO -->
        <b-row>
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

          </b-col>
          <!-- Fim contratantes -->

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

        </b-row>
        <b-row>
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
        </b-row>
        <!-- ################# -->


        <b-row>
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
      <template #footer>
        <b-row>
          <div class="col-lg-6">
            <BtnUpload v-model="uploadPreContratosEditais"></BtnUpload>
          </div>
          <div class="col-lg-6" style="text-align: right">
            <div class="btn-group">
              <button type="button"
                      @click="salvarLicitacao"
                      class="btn btn-success"
                      style="width: 100px;"
                      v-if="licitacao.id_licitacao == ''">
                <i class="fa fa-save"></i> Incluir
              </button>
              <button type="button"
                      class="btn btn-primary"
                      style="width: 100px;"
                      v-if="licitacao.id_licitacao != ''"
                      @click="salvarEdicaoLicitacao">
                <i class="fa fa-edit"></i> Alterar
              </button>
              <button type="button"
                      class="btn btn-secondary"
                      style="width: 100px;"
                      @click="limparLicitacao">
                <i class="fa fa-eraser"></i> Limpar
              </button>
            </div>
          </div>
        </b-row>
      </template>
    </b-card>

    <b-card class="shadow-lg">
      <template #header>
        <span><i class="fa fa-list"></i> LISTA DE LICITAÇÕES</span>
      </template>
      <template #default>
        <b-row>
          <b-col>
            <b-form-group label="Código Licitação">
              <b-form-input
                  id="cd_licitacao"
                  name="codigo"
                  v-model="preContrato.nm_contrato"
                  :class="{'is-invalid': l_.get(validationResponse,'codigo_licitacao')}"
                  type="text"
                  placeholder="Código Licitação"
              ></b-form-input>
              <small class="invalid-feedback"
                     v-if="l_.get(validationResponse,'codigo_licitacao')">
                {{l_.get(validationResponse,'codigo_licitacao').join(' ')}}
              </small>
            </b-form-group>
          </b-col>
          <b-col>
            <label for="dt_abertura">Data Início</label>
            <b-form-datepicker id="dt_abertura" class="mb-2"></b-form-datepicker>
          </b-col>
          <b-col>
            <label for="dt_fim_abertura">Data Fim</label>
            <b-form-datepicker id="dt_fim_abertura" class="mb-2"></b-form-datepicker>
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
            <label style="visibility: hidden">Salvar</label>
            <b-form-group>
              <button type="button"
                      class="btn btn-primary"
                      style="width: 100px;">
                <i class="fa fa-filter"></i> Filtrar
              </button>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <hr>
        </b-row>
        <b-overlay
            :show="ajaxStatus"
            rounded
            opacity="0.6"
            spinner-small
            spinner-variant="primary">
          <b-row>
            <div class="col-md-12 col-lg-12">
              <!-- BEGIN SAMPLE TABLE PORTLET-->
              <div class="portlet box primary">
                <div class="portlet-body">
                  <div class="table-scrollable table-responsive">
                    <table class="table table-hover table-striped" style="font-size: 13px">
                      <thead>
                      <tr>
                        <th>Licitação</th>
                        <th>Status</th>
                        <th>Abertura</th>
                        <th>Modalidade</th>
                        <th>Tipo</th>
                        <th>Subtotal</th>
                        <th>Pago</th>
                        <th>Anexo</th>
                        <th>Ações</th>
                      </tr>
                      </thead>
                      <tbody>
                      <template v-if="arrayLicitacoes.length > 0">
                        <tr v-for="v in arrayLicitacoes">
                          <td>#{{v.codigo_licitacao}}</td>
                          <td>{{statusLicitacao[v.status_licitacao]}}</td>
                          <td>{{v.data_abertura}}</td>
                          <td>{{modalidadeLicitacao[v.modalidade]}}</td>
                          <td>{{tipoLicitacao[v.tipo_licitacao]}}</td>
                          <td>--</td>
                          <td>--</td>
                          <td style="text-align: center">
                            <a :href="v.precontratos.token_url" target="_blank" v-if="v.precontratos">
                              <span class="fa fa-file-pdf fa-2x" style="color: red"></span>
                            </a>
                            <a href="" v-else>
                              <span style="font-weight: bold">--</span>
                            </a>
                          </td>
                          <td>
                            <b-button-group>
                              <b-button variant="primary" @click="showModalLotes(v)"><i class="fas fa-layer-group"></i></b-button>
                              <b-button variant="success"><i class="fas fa-calendar-check"></i></b-button>
                              <b-button variant="info" @click="editarLicitacao(v)" v-scroll-to="'#topo'"><i class="fas fa-edit"></i></b-button>
                            </b-button-group>
                          </td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr style="text-align: center; font-weight: bold">
                          <td colspan="22">NÃO HÁ DADOS</td>
                        </tr>
                      </template>

                      <b-modal :id="id_modal_lotes" size="xl"
                               no-close-on-esc
                               no-close-on-backdrop
                               hide-footer
                               ok-disabled
                               cancel-disabled
                               :title="nr_licitacao_modal">
                        <div class="col-md-12">
                          <b-row>
                            <b-col>
                              <div class="form-group" :class="{'has-error': l_.get(validationResponse,'executante')}">
                                <label>Fornecedor</label>
                                <v-select name="executante"
                                          :options="listaContratantes"
                                          v-model="lotes.fornecedor">
                                  <span slot="no-options">Não há dados a serem exibidos!</span>
                                </v-select>
                                <small class="invalid-feedback" v-if="l_.get(validationResponse,'executante')">
                                  {{ l_.get(validationResponse,'executante') .join(' ') }}</small>
                              </div>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col>
                              <b-form-group label="Status">
                                <b-form-select
                                    name="fornecedor"
                                    :options="l_.map(statusLote, (i, k) => ({value: k, text: i}))"
                                    v-model="lotes.status"
                                    :state="l_.get(validationResponse,'tipoLicitacao') ? false : undefined">
                                </b-form-select>
                                <b-form-invalid-feedback :state="l_.get(validationResponse,'tipoLicitacao') ? true:undefined">
                                  {{l_.get(validationResponse,'tipoLicitacao',[]).join('')}}
                                </b-form-invalid-feedback>
                              </b-form-group>
                            </b-col>
                            <b-col>
                              <b-form-group label="Vencedor">
                                <b-form-select
                                    name="fornecedor"
                                    v-model="lotes.vencedor"
                                    :options="l_.map(vencedor, (i, k) => ({value: k, text: i}))"
                                    :state="l_.get(validationResponse,'tipoLicitacao') ? false : undefined">
                                </b-form-select>
                                <b-form-invalid-feedback :state="l_.get(validationResponse,'tipoLicitacao') ? true:undefined">
                                  {{l_.get(validationResponse,'tipoLicitacao',[]).join('')}}
                                </b-form-invalid-feedback>
                              </b-form-group>
                            </b-col>
                            <b-form-group
                                label="Prazo (em dias)"
                                label-for="name-input"
                                invalid-feedback="Name is required"
                            >
                              <b-form-input
                                  id="name-input"
                                  required
                              ></b-form-input>
                            </b-form-group>
                          </b-row>
                          <b-row>
                            <b-col style="text-align: right">
                              <b-form-group>
                                <b-button-group>
                                  <b-button variant="success"><i class="fas fa-save"></i> Incluir</b-button>
                                  <b-button variant="primary"><i class="fas fa-edit"></i> Alterar</b-button>
                                </b-button-group>
                              </b-form-group>
                            </b-col>
                          </b-row>
                        </div>


                        <b-row>
                          <div class="col-md-12">
                            <b-card class="shadow-lg" no-body>
                              <template #header>
                                <span><i class="fa fa-list"></i> LISTA DE LOTES</span>
                              </template>
                              <template #default>
                                <b-overlay
                                    :show="ajaxStatus"
                                    rounded
                                    opacity="0.6"
                                    spinner-small
                                    spinner-variant="primary"
                                    class="d-inline-block">
                                  <b-row>
                                    <div class="col-md-12 col-lg-12">
                                      <!-- BEGIN SAMPLE TABLE PORTLET-->
                                      <div class="portlet box primary">
                                        <div class="portlet-body">
                                          <div class="table-scrollable table-responsive">
                                            <table class="table table-hover table-striped" style="font-size: 13px">
                                              <thead>
                                              <tr>
                                                <th>
                                                  <b-form-checkbox
                                                      id="checkbox-1"
                                                      v-model="status"
                                                      name="checkbox-1"
                                                      value="accepted"
                                                      unchecked-value="not_accepted">
                                                  </b-form-checkbox>
                                                </th>
                                                <th>Fornecedor</th>
                                                <th>SubTotal</th>
                                                <th>Pago</th>
                                                <th>Prazo (em dias)</th>
                                                <th>Vencedor</th>
                                                <th>Ações</th>
                                              </tr>
                                              </thead>
                                              <tbody>
                                              <tr>
                                                <td colspan="22" style="text-align: center; font-weight: bold">SEM DADOS PARA EXIBIR!</td>
                                              </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </b-row>
                                </b-overlay>
                              </template>
                              <template #footer>

                              </template>
                            </b-card>
                          </div>
                        </b-row>

                      </b-modal>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </b-row>
        </b-overlay>
      </template>
      <template #footer></template>
    </b-card>
  </div>
</template>

<script>
import InputCep from "../../geral/singleForm/InputCep";
import BtnBuscarUsuarios from "../../geral/BtnBuscarUsuarios";
import BtnBuscarClientesContratante from "../../geral/BtnBuscarClientes";
import SelectTipoContrato from "../../contratos/form/SelectTipoContrato";
import TiposDeContrato from "../../contratos/view/TiposDeContratos";
import InputGeralClientes from "../../geral/singleForm/InputGeralEmpresas";
import SingleForm from "../../geral/SingleForm";
import InputEstados from "../../geral/singleForm/InputEstados";
import InputCidades from "../../geral/singleForm/InputCidades";
import BtnUpload from "../../geral/BtnUpload";


export default {
  name: "Licitacao",
  components: {BtnUpload},
  mounted() {
    this.listaClientes();
    this.listarLicitacoes();
    this.listaUsuarios();
  },

  props: [],

  watch: {
    uploadPreContratosEditais: function (value) {

      this.doGet('geral/file-storage/buscar', {filtro:{uid:value}}).then(function (result) {
        this.preContrato.token = result.data[0].uid;
        this.preContrato.img_thumb = result.data[0].thumb_url;
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

    listaClientes: function () {

      var _this = this;

      this.doGet('geral/clientes/buscar').then(function (result) {
        _this.arrayContratante = result.data;
      });
    },

    salvarLicitacao: function () {

      var _this = this;

      var params = {
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
      };

      this.doPost('licitacao/licitacao/salvarLicitacao', params).then(function (result) {

        var params = {
          nome_contrato: _this.preContrato.nm_contrato,
          id_contratante: _this.preContrato.contratante.dados.id,
          id_responsavel: _this.preContrato.responsavel.dados.id,
          id_executante: _this.preContrato.executante.dados.id,
          id_licitacao: result.id,
          tipo_contrato: '5',
          token: _this.preContrato.token,
          token_url: _this.preContrato.img_thumb,
          status: _this.preContrato.status,
          descricao_pre_contrato: _this.preContrato.descricao
        };

        this.doPost('contrato/contrato/salvarPreContrato', params).then(function (result) {
          _this.alertSucesso();
          _this.listarLicitacoes();
        });

      });

    },

    listarLicitacoes: function () {

      var _this = this;

      this.doGet('licitacao/licitacao/listarLicitacoes', {include: ['contratantes', 'precontratos.responsavel', 'precontratos.executante']}).then(function (result) {
        _this.arrayLicitacoes = result;
      });

      setTimeout(function () {
        $('[data-toggle="tooltip"]').tooltip();
      }, 2000);
    },

    listaUsuarios: function () {
      var _this = this;

      this.doGet('geral/usuarios/buscar').then(function (result) {
        _this.arrayUsuarios = result.data;
      });
    },

    editarLicitacao: function ($v) {
      var _this = this;

      _this.licitacao.id_licitacao = $v.id;
      _this.preContrato.id_contrato = $v.precontratos.id;
      _this.preContrato.nm_contrato = $v.precontratos.nome_contrato;
      _this.preContrato.contratante = {
        label: $v.contratantes.nome,
        value: $v.contratantes.id,
        dados: $v.contratantes
      };
      _this.preContrato.responsavel = {
        label: $v.precontratos.responsavel.nome,
        value: $v.precontratos.responsavel.id,
        dados: $v.precontratos.responsavel
      };
      _this.preContrato.executante = {
        label: $v.precontratos.executante.nome,
        value: $v.precontratos.executante.id,
        dados: $v.precontratos.executante
      };
      _this.licitacao.status_licitacao = $v.status_licitacao;
      _this.licitacao.modalidade = $v.modalidade;
      _this.licitacao.tipo_licitacao = $v.tipo_licitacao;
      _this.licitacao.regime = $v.regime;
      _this.licitacao.nr_licitacao = $v.numero_licitacao;
      _this.licitacao.nr_edital = $v.numero_edital;
      _this.licitacao.local_licitacao = $v.local_licitacao;
      _this.licitacao.data_abertura = $v.data_abertura;
      _this.licitacao.valor_licitacao = $v.valor_licitacao;
      _this.licitacao.objeto = $v.objeto;
      _this.licitacao.historico = $v.historico;

    },

    limparLicitacao: function () {

      var _this = this;

      _this.licitacao.id_licitacao = '';
      _this.preContrato.nm_contrato = '';
      _this.preContrato.contratante = '';
      _this.preContrato.responsavel = '';
      _this.preContrato.executante = '';
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
      _this.licitacao.historico = '';
    },

    salvarEdicaoLicitacao: function () {
      var _this = this;

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
      };

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
          _this.listarLicitacoes();
        })
      });
    },

    showModalLotes: function ($v) {

      var _this = this;

      _this.nr_licitacao_modal = 'Licitação: '+ '( #LC'+ $v.numero_licitacao+"-"+$v.digito_controle + ' )';

      this.$bvModal.show(this.id_modal_lotes);
    },

    /**
     * Lotes da licitação
     */
    incluirItemLoteLicitacao: function () {

      var _this = this;

      var params = {
        cliente_id: _this.preContrato.contratante.dados.id,
        licitacao_id: _this.licitacao.status_licitacao,
        subtotal: _this.licitacao.modalidade,
        vlr_pago: _this.licitacao.tipo_licitacao,
        prazo: _this.licitacao.regime,
        vencedor: _this.licitacao.nr_licitacao,
      };

      this.doPost('licitacao/licitacao/salvarLicitacao', params).then(function (result) {

        var params = {
          nome_contrato: _this.preContrato.nm_contrato,
          id_contratante: _this.preContrato.contratante.dados.id,
          id_responsavel: _this.preContrato.responsavel.dados.id,
          id_executante: _this.preContrato.executante.dados.id,
          id_licitacao: result.id,
          tipo_contrato: '5',
          token: _this.preContrato.token,
          token_url: _this.preContrato.img_thumb,
          status: _this.preContrato.status,
          descricao_pre_contrato: _this.preContrato.descricao
        };

        this.doPost('contrato/contrato/salvarPreContrato', params).then(function (result) {
          _this.alertSucesso();
          _this.listarLicitacoes();
        });

      });

    },
  },

  created() {
    var _this = this;

    const idx = Math.floor(Math.random() * 10);

    _this.id_modal_lotes = idx;
  },

  data() {
    return {

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

      vencedor: {
        '0': 'NÃO DEFINIDO',
        '1': 'SIM',
        '2': 'NÃO'
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

      lotes: {
        vencedor: '0',
        status: '0',
        fornecedor: ''
      },

      uploadPreContratosEditais: '',

      arrayContratante: [],
      arrayLicitacoes: [],
      arrayUsuarios: [],

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

      statusLote: {
        '0': 'EM EDIÇÃO',
        '1': 'ENVIADO',
        '2': 'EM ORÇAMENTO COM FORNECEDOR',
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
        responsavel: '',
        img_thumb: '',
        token: ''
      },

      tipo_contrato_on: false,
      aprovadoOrReprovado: false,

      id_modal_lotes: '',

      nr_licitacao_modal: ''
    };
  },
}
</script>

<style scoped>

.table .btn {
  margin-top: 0;
  margin-left: 0;
  margin-right: 0;
}

</style>