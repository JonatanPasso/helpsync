<template>
    <div>
        <b-overlay
            :show="block"
            rounded
            opacity="0.6"
            spinner-variant="primary">

            <b-button-group v-if="!enviado">
                <b-button
                    :disabled="block || disabled"
                    :data-accept="mimes"
                    v-file-click="upload"
                    class="btn btn-secondary"
                    v-b-tooltip.hover title="Upload de documentos">
            <span class="btn-label pull-left">

                <i class="fas fa-cloud-upload-alt"></i></span>
                    <!--                    <span class="label-text">&nbsp;{{label}}</span>-->
                    <span class="label-text">&nbsp;{{label}}</span>
                </b-button>

                <b-button v-b-modal="modalId"
                          v-if="historico"
                          variant="info"
                          v-b-tooltip.hover title="Histórico de documentos">
                    <i class="fa fa-history"></i>
                </b-button>

            </b-button-group>

        </b-overlay>

        <b-btn-group v-if="enviado">
            <b-btn tag="a"
                   :title="result.original_name"
                   variant="primary"
                   target="_blank"
                   :href="`${result.url}?authorization=${token}`">
                <i class="fa fa-file"></i> {{ result.original_name | limite }}
            </b-btn>
            <b-button>
                <i class="fa fa-times" title="Limpar" @click.stop.prevent="limpar"></i>
            </b-button>
        </b-btn-group>

        <b-modal @show="carregarHistorico"
                 size="lg"
                 ref="modal"
                 ok-only
                 ok-title="FECHAR"
                 title="Histórico de Envios"
                 :id="modalId">

            <div class="row" v-if="dhistorico.total > 1">

                <div class="col-12 text-right">

                    {{ dhistorico.from }} ate {{ dhistorico.to }} de {{ dhistorico.total }}

                    <div class="ajust-padding-paginacao">
                        <b-pagination
                            v-model="dhistorico.page"
                            :total-rows="dhistorico.total"
                            :per-page="dhistorico.per_page"
                            align="right"></b-pagination>
                    </div>

                </div>

            </div>

            <h4 v-if="!dhistorico.data.length">
                Nenhum arquivo no histórico
            </h4>

            <b-overlay
                :show="ajaxStatus"
                rounded
                opacity="0.6"
                spinner-variant="primary">

                <b-row>
                    <b-col md="3" lg="4"
                           v-for="f in dhistorico.data" :key="f.id">
                        <b-card class="overflow-hidden text-center" no-body>

                            <b-link @click="selFile(f)">
                                <b-img-lazy thumbnail
                                            class="m-2"
                                            style="height: 20vh"
                                            :src="`https://illuminarti.com.br/api/geral/file-storage/thumb/${f.uid}?authorization=${token}`"></b-img-lazy>
                            </b-link>

                            <template #footer>

                                <b-link tag="a"
                                        style="font-size: 14px"
                                        target="_blank"
                                        class="text-nowrap"
                                        :title="f.original_name"
                                        variant="primary"
                                        :href="`${f.url}?authorization=${token}`">
                                    <i class="fa fa-file"></i> {{ f.original_name | limite }}
                                </b-link>

                            </template>

                        </b-card>
                    </b-col>
                </b-row>

            </b-overlay>


        </b-modal>


    </div>
</template>

<script>

import Util from '@/util';
import FileClick from "../../directives/FileClick";

export default {
    name: "BtnUpload",
    components: {},
    data: function () {
        var uid = Util.uid();
        return {
            enviado: false,
            result: {},
            block: false,
            modalId: uid,
            dhistorico: {
                data: []
            }
        }
    },

    computed: {
        token() {
            return TOKEN
        }
    },

    directives: {FileClick},

    methods: {

        upload: function (files) {


            var promise = this.doUpload(files);

            this.block = true;
            promise.then(function (result) {

                for (var i in result) {
                    this.result = result[i];
                    this.enviado = true;
                    this.$emit('sucesso', this.result);
                }

                this.block = false;

            }.bind(this))
                .fail(function (erro) {
                    this.alertErro('Ocorreu erro inesperado:' + erro.responseText);
                    this.block = false;
                }.bind(this));

        },

        limpar: function () {
            this.enviado = false;
            this.result = {};
        },

        carregarHistorico() {

            this.doGet('geral/file-storage/buscar')
                .then(r => {
                    this.dhistorico = r;
                });
        },

        selFile(file) {
            this.$refs.modal.hide();
            this.$emit('sucesso', file);

            this.result = file;
            this.enviado = true;
        }
    },

    watch: {
        result: function () {
            this.$emit('input', this.fullResult ? this.result : this.result.uid);
        },
        value: function () {
            if (!this.value) {
                this.limpar();
            }
        }
    },
    props: {
        mimes: {
            type: String,
            default: ''
        },

        value: {
            type: [Object, String],
            default() {
                return ''
            },
        },
        label: {
            type: String,
            default: null
        },
        disabled: {
            default: false
        },
        /**
         * se true o model recebe todo o objeto do result do upload
         */
        fullResult: {
            type: Boolean,
            default: false
        },
        /**
         * mostrar Botao historico. Default true
         */
        historico: {
            type: Boolean,
            default: true
        }
    },
    filters: {
        limite(value) {
            if (value.length > 40) {
                return value.substr(0, 40) + '...'
            }
            return value;
        }
    }
}
</script>

<style scoped>

</style>
