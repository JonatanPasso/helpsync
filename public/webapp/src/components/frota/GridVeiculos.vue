<template>

    <b-card class="shadow-lg"
            :class="{ modo_modal:modal }">

        <template #header>
            <i class="fa fa-table"></i> Veículos cadastrados
        </template>

        <template #default>


            <b-row>
                <b-col>

                    <b-button @click="show_filtros=!show_filtros"
                              title="Filtros"
                              size="sm"
                              :pressed="show_filtros">
                        <span v-if="show_filtros">
                            Ocultrar Filtros
                        </span>
                        <span v-else>
                        <i class="fa fa-search"></i> Mostrar Filtros
                        </span>
                    </b-button>

                </b-col>
            </b-row>

            <div v-show="show_filtros"
                 style="border-bottom: 1px #ccc dashed;border-top: 1px #ccc dashed;margin: 10px 0">

                <SingleForm :scheme="camposFiltro"
                            @update="filtro=$event"
                            :model="filtro"></SingleForm>

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

                <b-col class="text-right">

                    <div class="text-center d-inline-block">
                        Registros: {{pageData.total}}&nbsp;
                    </div>
                    <div class="d-inline-block">
                        <b-pagination
                            size="sm"
                            v-model="config.page"
                            :total-rows="pageData.total"
                            :per-page="pageData.per_page"
                            align="center"></b-pagination>
                    </div>

                </b-col>
            </b-row>


            <b-overlay
                :show="ajaxStatus"
                rounded
                opacity="0.6"
                spinner-variant="primary">


                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td v-for="c in camposGrid">{{c.label}}</td>
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
                            <td v-for="c in camposGrid">{{l_.get(d,c.name,c.default)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </b-overlay>

            <b-row>
                <b-col class="text-right">

                    <div class="text-center d-inline-block">
                        Registros: {{pageData.total}}
                    </div>
                    <div class="d-inline-block">
                        <b-pagination
                            size="sm"
                            v-model="config.page"
                            :total-rows="pageData.total"
                            :per-page="pageData.per_page"
                            align="center"></b-pagination>
                    </div>
                </b-col>
            </b-row>

        </template>
    </b-card>

</template>


<script>

    import Cidades from "./../geral/singleForm/InputCidades";
    import Estados from "./../geral/singleForm/InputEstados";
    import Card from "./../geral/Card";
    import Paginator from "./../geral/view/Paginator";
    import SingleForm from "./../geral/SingleForm";
    import TiposVeiculos from "./../frota/singleForm/InputTipoVeiculos";

    export default {
        components: {SingleForm, Paginator, Estados, Cidades, TiposVeiculos},

        name: 'GridVeiculos',

        data: function () {
            return {

                show_filtros: false,

                pageData: {
                    data: [],
                    current_page: 1,
                    from: null,
                    per_page: null,
                    to: null
                },
                config: {
                    page: 1,
                    order: {
                        id: 'asc'
                    },
                    include: ['tipo', 'clientes', 'marca', 'modelo'],
                    todos: 'sim'
                },
                selectedRow: {},

                filtro: {
                    contem: '',

                },

                camposFiltro: [
                    {
                        label: 'Código',
                        name: 'id',
                        bclass: 'col-md-2'
                    },

                    {
                        label: 'PLACA, CHASSI, RENAVAN',
                        name: 'contem',
                        bclass: 'col-md-4'
                    },
                    {
                        label: 'Tipo',
                        name: 'tipo_id',
                        bclass: 'col-4',
                        type: TiposVeiculos
                    },

                    {
                        label: 'Cliente',
                        name: 'cliente_id',
                        bclass: 'col-md-4',
                        type: 'InputGeralEmpresas',
                        bind: {'id_only': true}
                    },

                    {
                        label: 'Marca',
                        name: 'marca_id',
                        bclass: 'col-md-4',
                        type: 'InputMarcas'
                    },


                    {
                        label: 'Modelo',
                        name: 'modelo_id',
                        bclass: 'col-md-4',
                        type: 'InputModelos',
                        attribs: {},
                        bind: ['marca_id']
                    },

                    {
                        name: "rastreador_esn",
                        label: "Rastreador",
                        type: "InputRastreador",
                        bclass: "col-4"
                    }
                ],

                camposGrid: [


                    {
                        'label': 'Código',
                        'name': 'id',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {}
                    },

                    {
                        'label': 'Cliente',
                        'name': 'clientes.0.nome',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {},
                        default: 'Sem Cliente'
                    },

                    {
                        'label': 'Nº Frota',
                        'name': 'numero_frota',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {}
                    },
                    {
                        'label': 'Placa/Série',
                        'name': 'placa',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {}
                    },

                    {
                        'label': 'Tipo',
                        'name': 'tipo.descricao',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {}
                    },
                    {
                        'label': 'Marca',
                        'name': 'marca.nome',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {}
                    },
                    {
                        'label': 'Modelo',
                        'name': 'modelo.nome',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {}
                    },

                    {
                        'label': 'Ano Modelo',
                        'name': 'ano_modelo',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {}
                    },
                    {
                        'label': 'Rastreador',
                        'name': 'rastreador_esn',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {}
                    },

                    {
                        'label': 'Status',
                        'name': 'status',
                        type: 'input-text',
                        bclass: 'col-md-2',
                        attribs: {},
                    }

                ]
            }
        },
        props: ['modal'],
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

            limpar: function () {
                this.pageData = this.$options.data().pageData;
                this.filtro = this.$options.data().filtro;
                this.config = this.$options.data().config;
            },

            carregarGrid: function () {

                var self = this;

                var aux = _.clone(this.config);
                aux.filtro = this.filtro;
                aux.last_log = 'nao';

                self.doGet('frota/veiculos/buscar', aux)
                    .then(function (paginacaoResult) {
                        self.pageData = paginacaoResult;
                    });
            },
            selecionar: function (item) {

                this.selectedRow = item;

                this.$emit('select', _.clone(item));
            }
        },
        filters: {
            cliente: function (valor) {
                var aux = '';
                for (var i in valor) {
                    aux += valor[i].nome + "\n"
                }
                return aux;
            }
        }
    };


</script>

<style scoped>

    .modo_modal .card-header {
        display: none;
    }

    .table-responsive {
        border: 1px solid #ccc;
    }

    .modo_modal .table-responsive {
        max-height: 50vh;
        overflow: auto;
        border: 1px solid #ccc;
    }

</style>
