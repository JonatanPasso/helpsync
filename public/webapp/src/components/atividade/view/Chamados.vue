<template>
    <b-card no-body>

        <template #header>
            <span><i class="fa fa-tasks"></i> LISTA DE CHAMADOS / INCLUSÃO</span>
        </template>

        <b-tabs card v-model="tab_index">
            <b-tab title="Lista de Chamados">

                <Lista v-on:seleciona_chamado="buscaChamado"
                       ref="lista"
                       v-on:aba="tab_index=$event"></Lista>
            </b-tab>
            <b-tab title="Chamado">
                <CriarChamado
                    @lista_chamado="atualizarLista"
                    :chamado="chamado"></CriarChamado>
            </b-tab>
            <b-tab title="Interação" :disabled="!chamado.id">
                <HistoricoIteracao :chamado="chamado"></HistoricoIteracao>
            </b-tab>
        </b-tabs>

        <template #footer>
            <div @click="$bvModal.show('modalLegendaChamado')" style="cursor: pointer">
                <span class="legend-chamado fa fa-th-list"></span> Legenda
            </div>
        </template>
    </b-card>
</template>

<style>
.destaque-title {
    /* background-color: #01BC8C; */
    border-color: #379cd5;
    background-color: #379cd51f;
    border-color: #379cd5 !important;
    /*margin: 20px 0;*/
    padding: 5px;
    border-left: 3px solid #eee;
    box-sizing: border-box;
}

.line-div {
    border-top: 1px dashed #8c8b8b;
}

.btn:not(:disabled):not(.disabled) {
    cursor: pointer;
}

.table .btn {
    margin-top: 0;
    margin-left: 0;
    margin-right: 5px;
}

.btn.default {
    color: #333333;
    background-color: #e5e5e5 !important;
}

.btn.blue-stripe {
    border-left: 3px solid #418BCA;
}

.btn.orange-stripe {
    border-left: 3px solid #ca630a;
}

.btn.red-stripe {
    border-left: 3px solid #9e0a27;
}

.btn-sm, .btn-xs {
    padding: 4px 10px 5px 10px;
    font-size: 13px;
    line-height: 1.5;
}

/*Checkboxes styles*/

/**
@todo Colocar clase antes (regra de css ta gobal )
*/
/*input[type="checkbox"] { display: none; }*/

/*input[type="checkbox"] + label {*/
/*    display: block;*/
/*    position: relative;*/
/*    padding-left: 35px;*/
/*    margin-bottom: 20px;*/
/*    font: 14px/20px 'Open Sans', Arial, sans-serif;*/
/*    color: #5d5d5d;*/
/*    cursor: pointer;*/
/*    -webkit-user-select: none;*/
/*    -moz-user-select: none;*/
/*    -ms-user-select: none;*/
/*}*/

/*input[type="checkbox"] + label:last-child { margin-bottom: 0; }*/

/*input[type="checkbox"] + label:before {*/
/*    content: '';*/
/*    display: block;*/
/*    width: 20px;*/
/*    height: 20px;*/
/*    border: 1px solid #418bca;*/
/*    position: absolute;*/
/*    left: 0;*/
/*    top: 0;*/
/*    opacity: .6;*/
/*    -webkit-transition: all .12s, border-color .08s;*/
/*    transition: all .12s, border-color .08s;*/
/*}*/

/*input[type="checkbox"]:checked + label:before {*/
/*    width: 10px;*/
/*    top: -5px;*/
/*    left: 5px;*/
/*    border-radius: 0;*/
/*    opacity: 1;*/
/*    border-top-color: transparent;*/
/*    border-left-color: transparent;*/
/*    -webkit-transform: rotate(45deg);*/
/*    transform: rotate(45deg);*/
/*}*/

/*.privado{*/
/*    position: absolute;*/
/*    margin-top: -37px;*/
/*    !*z-index: 9934;*!*/
/*    margin-left: 166px;*/
/*}*/


</style>

<script>

// Vue.loadComponent('Lista');
// Vue.loadComponent('CriarChamado');
// Vue.loadComponent('HistoricoIteracao');

import Card from "../../geral/Card";
import Lista from './../Lista';
import HistoricoIteracao from './../HistoricoIteracao';
import CriarChamado from './../CriarChamado';

export default {

    mounted: function () {

        // this.carregaCkDescricaoChamado();

    },

    components: {Lista, HistoricoIteracao, CriarChamado, Card},

    watch: {
        'chamado.id_chamado': function (idChamado) {
            if (idChamado) {
                this.aba_ativa = 'criar_chamado';
            }
        }
    },

    methods: {

        showModalLegenda: function () {
            $('#modalLegendaChamado').modal('show');
        },


        incluir: function () {
            this.$api.atividade.cadastro.incluir(this.atividade).then(function (result) {
            }).fail(function (erro) {
                this.alertErro(erro);
            })
        },

        buscaChamado: function (chamado) {
            this.chamado = chamado;
            this.$nextTick(() => {
                this.tab_index = 2;
            });

        },

        atualizarLista() {
            this.tab_index = 0;
            this.$refs.lista.listaChamados();
        }

    },

    data: function () {
        return {
            chamado: {
                id_chamado: '',
                titulo_chamado: '',
                tipo_chamado: '',
                prioridade_chamado: '',
                estimativa: '',
                atribuicao: '',
                status_chamado: '',
            },

            tab_index: 0
        }
    }
}
</script>
