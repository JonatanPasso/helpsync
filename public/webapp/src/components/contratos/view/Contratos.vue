<template>
  <b-card no-body>

    <template #header>
      <span><i class="fa fa-tasks"></i> GESTÃO DE CONTRATOS</span>
    </template>

    <b-tabs card v-model="tab_contract">
      <b-tab title="Pré Contrato" :active="statusTab">
        <FormPreContratos
            ref="preContrato"
            v-on:seleciona_precontrato="buscaContrato"
            v-on:aba="tab_contract=$event">
        </FormPreContratos>
      </b-tab>

      <b-tab title="Proposta" :disabled="tabPro">
        <FormPropostas
            :pre-contrato="preContrato"
            v-on:seleciona_proposta="buscaProposta"
            v-on:aba="tab_contract=$event">
        </FormPropostas>
      </b-tab>
      <b-tab title="Contrato" :disabled="tabCont">
        <FormContratos :proposta="preContrato"></FormContratos>
      </b-tab>
    </b-tabs>
  </b-card>
</template>

<script>

import FormPreContratos from "../FormPreContratos";
import FormPropostas from "../FormPropostas";
import FormContratos from "../FormContratos";

export default {
  name: "Contratos",
  components: {FormPreContratos, FormPropostas, FormContratos},

  comments: {},

  watch: {},

  computed: {
    statusTab: function (){
      if(this.tab_contract == 0){
        this.tabPro = true;
        this.tabCont = true;
      }
    }
  },

  methods: {

    buscaContrato: function (preContrato) {

      this.preContrato = preContrato;

      this.tabPro = false;

      this.$nextTick(() => {
        this.tab_contract = 1;
      })

    },

    buscaProposta: function (preContrato) {
      this.preContrato = preContrato;

      this.tabCont = false;

      this.$nextTick(() => {
        this.tab_contract = 2;
      })
    },

  },

  data: function () {
    return {

      preContrato: {
        id_contrato: '',
        codigoPreContrato: '',
        nm_contrato: '',
        // responsavel: '',
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
        responsavel: ''
      },

      tab_contract: 0,
      preContratoOk: false,
      propostaOk: false,
      precontrato: '',

      tabPro: true,
      tabCont: true
    };
  }
};
</script>

<style scoped>

</style>
