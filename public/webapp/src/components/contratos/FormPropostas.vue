<template>
  <div>
    <b-row>
      <b-col>
        <b-form-group>
          <b-card header="Dados do Pré-contrato"
                  header-text-variant="white"
                  header-tag="header"
                  header-bg-variant="primary">
            <b-col md="6" style="float: left">
              <b-list-group-item href="#">Código Pré-contrato: <span><b-badge variant="primary" style="font-size: 15px">{{preContrato.codigo_pre_contrato}}</b-badge></span></b-list-group-item>
              <b-list-group-item href="#">Nome Pré-contrato: <span style="font-weight: bold">{{preContrato.nome_contrato}}</span></b-list-group-item>
              <b-list-group-item href="#">Contratante: <span style="font-weight: bold">{{l_.get(preContrato, 'contratantes.nome')}}</span></b-list-group-item>
              <b-list-group-item href="#">Responsável: <span style="font-weight: bold">{{l_.get(preContrato, 'responsavel.nome')}}</span></b-list-group-item>
            </b-col>
            <b-col md="6" style="float: right">
              <b-list-group-item href="#">Tipo Contrato: <span style="font-weight: bold">{{preContrato.tipo_contrato | tipo_pre}}</span></b-list-group-item>
              <b-list-group-item href="#" v-if="preContrato.status == '1'">Status: <b-badge variant="warning" style="font-size: 15px"><span style="font-weight: bold">{{preContrato.status | status_pre}}</span></b-badge></b-list-group-item>
              <b-list-group-item href="#" v-if="preContrato.status == '2'">Status: <b-badge variant="success" style="font-size: 15px"><span style="font-weight: bold">{{preContrato.status | status_pre}}</span></b-badge></b-list-group-item>
              <b-list-group-item href="#" v-if="preContrato.status == '3'">Status: <b-badge variant="danger" style="font-size: 15px"><span style="font-weight: bold">{{preContrato.status | status_pre}}</span></b-badge></b-list-group-item>
              <b-list-group-item href="#">Executante:  <span style="font-weight: bold">{{preContrato.executante.nome}}</span></b-list-group-item>
              <b-list-group-item href="#">Data de inclusão:  <span style="font-weight: bold">{{preContrato.criado_em | dateTimeFormat('DD/MM/YYYY HH:mm:ss')}}</span></b-list-group-item>
            </b-col>
          </b-card>
        </b-form-group>
      </b-col>
    </b-row>
    <hr>
    <b-row>
      <b-col md="8">
        <b-form-group label="Descrição Proposta">
          <b-form-input v-model="propostas.descricao_proposta"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col md="2">
        <b-form-group label="Data Início">
          <b-form-datepicker
              locale="pt-BR"
              :date-format-options="{}"
              placeholder="Selecionar"
              v-model="propostas.data_inicio"></b-form-datepicker>
        </b-form-group>
      </b-col>

      <b-col md="2">
        <b-form-group label="Data Fim">
          <b-form-datepicker
              locale="pt-BR"
              :date-format-options="{}"
              placeholder="Selecionar"
              v-model="propostas.data_fim"></b-form-datepicker>
        </b-form-group>
      </b-col>
    </b-row>

    <b-card>
      <template #header>
        <i class='fa fa-file'></i> Documentos
      </template>
      <b-row>
        <b-col md="4">
          <b-form-group label="Nome Documento">
            <b-form-input v-model="propostas.nome_documento"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col align-self="end" md="4">
          <b-form-group label="Documento">
            <btn-upload v-model="uploadData"></btn-upload>
          </b-form-group>
        </b-col>

        <b-col style="text-align: right">
          <label style="visibility: hidden">Salvar</label>
          <b-form-group>
            <b-button-group>
              <b-button type="button"
                        :disabled="!camposObrigatorios"
                        @click="salvar"
                        variant="success">
                <i class="fa fa-save"></i> SALVAR
              </b-button>
              <b-button type="button"
                        @click="detalheProposta(preContrato.id)">
                <i class="fas fa-file-signature"></i> Finalizar Proposta
              </b-button>
            </b-button-group>
          </b-form-group>
        </b-col>

      </b-row>

      <table class="table table-bordered table-condensed responsive table-striped" style="font-size: 13px">
        <thead>
        <th>#Código Proposta</th>
        <th>Descrição</th>
        <th>Documento</th>
        <th style="text-align: center">Status</th>
        <th style="text-align: center">Ação</th>
        </thead>
        <tbody>
        <template v-if="propostasGravadas.length > 0">
          <tr v-for="v in propostasGravadas">
            <td style="width: 15%">
              <span v-if="v.status_proposta == 1" class="analise">
                <i class="fa fa-circle"></i> #{{v.codigo_proposta}}
              </span>
              <span v-if="v.status_proposta == 2" class="aprovado">
                <i class="fa fa-circle"></i> #{{v.codigo_proposta}}
              </span>
              <span v-if="v.status_proposta == 3" class="reprovado">
                <i class="fa fa-circle"></i> #{{v.codigo_proposta}}
              </span>
            </td>
            <td>{{v.descricao_proposta}}</td>
            <td><a :href="v.img_thumb" target="_blank">{{v.nome_documento}}</a></td>
            <td v-if="v.status_proposta == '1'" style="text-align: center; width: 10%;">
              <b-badge href="#" variant="warning" data-toggle="tooltip" title="Proposta em Análise">{{statusPreContrato[v.status_proposta]}}</b-badge>
            </td>
            <td v-if="v.status_proposta == '2'" style="text-align: center; width: 10%;">
              <b-badge href="#" variant="success" data-toggle="tooltip" title="Proposta Aprovada">{{statusPreContrato[v.status_proposta]}}</b-badge>
            </td>
            <td v-if="v.status_proposta == '3'" style="text-align: center; width: 10%;">
              <b-badge href="#" variant="danger" data-toggle="tooltip" title="Proposta Reprovada">{{statusPreContrato[v.status_proposta]}}</b-badge>
            </td>
            <td style="width: 10%; text-align: center">
              <div class="btn-group">
                <button type="button"
                        v-if="v.status_proposta != '2'"
                        @click="aprovarReprovarProposta(v, '2')"
                        class="btn btn-success"
                        data-toggle="tooltip" title="Aprovar"
                        id="apr"><i class="far fa-thumbs-up"></i>
                </button>
                <button type="button"
                        v-if="v.status_proposta != '3'"
                        @click="aprovarReprovarProposta(v, '3')"
                        data-toggle="tooltip" title="Reprovar"
                        class="btn btn-danger"><i class="far fa-thumbs-down"></i>
                </button>
              </div>
            </td>
          </tr>
        </template>
        <template v-else>
          <tr>
            <td colspan="22" style="text-align: center; "><b>Nenhum documento adicionado</b></td>
          </tr>
        </template>
        </tbody>
      </table>
    </b-card>
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

export default {

  name: "FormPropostas",

  mounted() {
    this.listaPropostasPorPreContrato();
  },

  components: {BtnUpload, SelectTipoContrato, TiposDeContrato, InputGeralClientes, SingleForm},

  props: ['preContrato'],

  data() {
    return {

      statusPreContrato: {
        '0': 'Selecione o status',
        '1': 'EM ANÁLISE',
        '2': 'APROVADO',
        '3': 'REPROVADO',
      },

      podeIncluir: false,

      uploadData: {},

      propostas: {
        nome_documento: '',
        descricao_proposta: '',
        data_inicio: '',
        data_fim: '',
        img_thumb: '',
        uid: ''
      },

      podeFinalizar: 0,

      propostasGravadas: []

    };
  },

  watch: {

    preContrato: function(preContrato){
      this.listaPropostasPorPreContrato();
    },

    uploadData: function (uploadData) {

      this.doGet('geral/file-storage/buscar', {filtro:{uid:uploadData}}).then(function (result) {

        this.propostas.nome_documento = result.data[0].original_name;
        this.propostas.img_thumb = result.data[0].thumb_url;
        this.propostas.uid = result.data[0].uid;

        this.listaPropostasPorPreContrato();
      });
    }
  },

  computed: {
    camposObrigatorios: function (){
      if(this.propostas.descricao_proposta != '' &&
          this.propostas.data_inicio != '' &&
          this.propostas.data_fim != ''){
        return true;
      }
    }
  },

  filters: {
    tipo_pre: function (value) {
      if(value == '1'){
        return 'LOCAÇÃO';
      }else if('2'){
        return 'INTERMITENTE';
      }else if('3'){
        return 'MERCANTIS';
      }else if('4'){
        return 'ADMINISTRATIVOS';
      }else if('5'){
        return 'LICITATÓRIOS';
      }else if('6'){
        return 'PRÉ PAGO';
      }
    },

    status_pre: function (value) {
      if(value == '1'){
        return 'EM ANÁLISE';
      }else if('2'){
        return 'APROVADO';
      }else if('3'){
        return 'REPROVADO';
      }
    },
  },

  methods: {

    salvar: function () {

      var _this = this;

      var params = {
        id_pre_contrato: this.preContrato.id,
        descricao_proposta: this.propostas.descricao_proposta,
        data_inicio_proposta: this.propostas.data_inicio,
        data_fim_proposta: this.propostas.data_fim,
        nome_documento: this.propostas.nome_documento,
        img_thumb: this.propostas.img_thumb,
        uid: this.propostas.uid
      }

      this.doPost('contrato/contrato/salvarPropostas', params).then(function (response) {
        this.alertSucesso();
        this.listaPropostasPorPreContrato();

        this.propostas.descricao_proposta = '';
        this.propostas.data_inicio = '';
        this.propostas.data_fim = '';
        this.uploadData = ''
        this.propostas.nome_documento = '';
      });

    },

    listaPropostasPorPreContrato: function () {
      var _this = this;

      var params = {
        id_pre_contrato: _this.preContrato.id
      }

      this.doGet('contrato/contrato/listaPropostasPorPreContrato', params).then(function (result) {
        _this.propostasGravadas = result;
      })

      setTimeout(function () {
        $('[data-toggle="tooltip"]').tooltip();
      }, 2000);
    },

    aprovarReprovarProposta: function ($proposta, $statusProposta) {
      var _this = this;

      var params = {
        id_proposta: $proposta.id,
        status_proposta: $statusProposta
      };

      this.doPost('contrato/contrato/aprovarReprovarProposta', params).then(function (result) {
        _this.alertSucesso();
        _this.listaPropostasPorPreContrato();

        // if(result.status == '2'){
        //     _this.$emit('contrato_gravado', true);
        // }

      })
    },

    detalheProposta: function ($idPreContrato) {

      var params = {
        id_precontrato: $idPreContrato,
        include: ['contratantes', 'responsavel', 'executante', 'proposta']
      };

      this.doPost('contrato/contrato/salvarContrato', params).then(function (resultContrato) {

        this.doGet('contrato/contrato/listarPreContratoPorId', params).then(function (result) {
          this.result = result;
          this.$emit('seleciona_proposta', result);
        });

      });
    },
  }
}
</script>

<style scoped>

.analise{
  color: #e05f13;
}

.aprovado{
  color: #13a86f;
  cursor: pointer;
}

.reprovado{
  color: #ce000a;
}
.table .btn{
  margin-right: 0px!important;
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
</style>
