<template>

    <b-card class="shadow-lg">

        <template #header>
            <i class="fa fa-table"></i> Contratos cadastrados
            <b-button variant="secondary"
                      title="Pesquisar"
                      :pressed="showFiltro"
                      @click="showFiltro=!showFiltro"
                      class="float-right"
                      size="sm"><i class="fa fa-search"></i></b-button>
        </template>

        <template #default>

            <div v-show="showFiltro">
                <b-row>
                    <template v-for="c in camposFiltro">

                        <b-col :class="`col-${c[2]}`">

                            <b-form-group
                                :label="c[1]">

                                <component
                                    :is="c[3]"
                                    :placeholder="c[1]"
                                    v-bind="config(c[0])"
                                    :title="c[1]"
                                    @select
                                    v-model="filtro[c[0]]">


                                    <span v-if="c[0] == 'executante_id' || c[0] == 'solicitante_id'"><i
                                        class="fa fa-search"></i> SELECIONAR</span>


                                </component>


                            </b-form-group>
                        </b-col>
                    </template>
                </b-row>

                <div class="row">
                    <div class="col-md-12" style="margin-top: 10px;margin-bottom: 10px">
                        <div class="btn-group">

                            <button type="button"
                                    @click.prevent.stop="carregarGrid"
                                    class="btn btn-success"><i class="fa fa-search"></i> Filtrar
                            </button>
                            <button type="button"
                                    @click.prevent.stop="limpar"
                                    class="btn btn-secondary"><i class="fa fa-recycle"></i> Limpar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <b-row>
                <b-col align="right">
                    Registros: {{pageData.total}}
                </b-col>
                <b-col>
                    <b-pagination
                        v-model="config.page"
                        :total-rows="pageData.total"
                        :per-page="pageData.per_page"
                        align="right"></b-pagination>
                </b-col>
            </b-row>

            <b-overlay
                :show="ajaxStatus"
                rounded
                opacity="0.6"
                spinner-variant="primary">
                <div class="table-responsive" style="max-height: 60vh">

                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td v-for="c in camposGrid">{{c[1]}}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="pageData.data.length  == 0">
                            <td colspan="99">Nehum registro cadastrado</td>
                        </tr>
                        <tr v-for="d in pageData.data"
                            :class="{'table-success':d == selectedRow}"
                            style="cursor:pointer;"
                            @click="selecionar(d)">

                            <td v-for="c in camposGrid">{{l_.get(d,c[0])}}</td>

                        </tr>
                        </tbody>
                    </table>

                </div>
            </b-overlay>

            <b-pagination
                v-model="config.page"
                :total-rows="pageData.total"
                :per-page="pageData.per_page"
                align="center"></b-pagination>

        </template>
    </b-card>

</template>


<script>

    import Cidades from "../geral/singleForm/InputCidades";
    import Estados from "../geral/singleForm/InputEstados";
    import SelectTipoContrato from "./form/SelectTipoContrato";
    import BtnBuscarClientes from "../geral/BtnBuscarClientes";

    export default {


        name: 'GridContratos',

        data() {
            return {
                pageData: {
                    data: [],
                    current_page: 1,
                    from: null,
                    per_page: null,
                    to: null
                },
                selectedRow: {},

                showFiltro: false,

                filtro: {
                    contem: '',
                    estado: '',
                    cidade: ''

                },
                camposFiltro: [
                    ['contem', 'Buscar', 'md-12', 'b-form-input'],
                    ['estado', 'Estado', 'md-2', Estados],
                    ['cidade', 'Cidade', 'md-2', Cidades],
                    ['tipo', 'Tipo de Contrato', 'md-2', SelectTipoContrato],
                    ['executante_id', 'Executante', 'md-3', BtnBuscarClientes],
                    ['solicitante_id', 'Solicitante', 'md-3', BtnBuscarClientes],
                ],

                camposGrid: [
                    ['nome', 'Nome'],
                    ['nome', 'Contratante'],
                    ['nome', 'Executante'],
                    ['nome', 'Estado'],
                    ['nome', 'Cidade'],
                    ['nome', 'Status'],
                ],
                executante: null,
                solicitante: null
            }
        },
        created: function () {
            this.carregarGrid();
        },

        watch: {
            config: {
                deep: true,
                handler: function () {
                    this.carregarGrid();
                }
            }
        },
        methods: {

            config(campo) {

                switch (campo) {
                    case 'cidade':
                        return {
                            estado: this.filtro.estado
                        };
                        break;
                    case 'tipo':
                        return {
                            class: 'form-control'
                        }
                        break;
                }
            },

            carregarGrid: function () {

                var self = this;
                return;
                /**
                 * @todo Implementar api de contrato
                 */
                self.doGet('contratos/contratos/buscar', _.extend(this.config, this.filtro))
                    .then(function (paginacaoResult) {
                        self.pageData = paginacaoResult;
                    });
            },
            selecionar: function (item) {

                this.selectedRow = item;
                this.$emit('select', _.clone(item));

                $('html,body').animate({scrollTop: 0}, 500);
            },

            limpar: function () {
                this.config = this.$options.data().config;
                this.filtro = this.$options.data().filtro;
                this.pageData = this.$options.data().pageData;
                this.carregarGrid();
            }
        }
    };


</script>

<style scoped>

</style>
