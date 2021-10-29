<template>

    <Card :footer="false">

        <template #header>
            <i class="fa fa-table"></i> Rastreadores cadastrados
        </template>

        <template #default>

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

            <b-pagination
                v-model="config.page"
                :total-rows="pageData.total"
                :per-page="pageData.per_page"
                align="center"></b-pagination>

            <div class="text-center">
                Registros: {{pageData.total}}
            </div>

            <b-overlay
                :show="ajaxStatus"
                rounded
                opacity="0.6"
                spinner-variant="primary">
                <div class="table-responsive" style="max-height: 60vh">

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

                            <td v-for="c in camposGrid">{{l_.get(d,c.name)}}</td>

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
    </Card>

</template>


<script>

    import Cidades from "./../geral/singleForm/InputCidades";
    import Estados from "./../geral/singleForm/InputEstados";
    import Card from "./../geral/Card";
    import Paginator from "./../geral/view/Paginator";
    import SingleForm from "./../geral/SingleForm";
    import InputModelosRastreador from "./singleForm/InputModelosRastreador";

    export default {
        components: {SingleForm, Paginator, Card, Estados, Cidades},

        name: 'GridRastreadores',

        data: function () {
            return {
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
                    include: ['veiculo'],
                    ignorar_cliente: true
                },
                selectedRow: {},

                filtro: {
                    contem: '',

                },
                camposFiltro: [
                    {
                        "type": "InputMask",
                        'label': 'Código',
                        'name': 'id',
                        'bclass': 'col-2',
                        'attribs': {mask: '######'},
                    },
                    {
                        "type": "InputMask",
                        'label': 'Esn',
                        'name': 'esn',
                        'attribs': {mask: '####################'}
                    },

                    {
                        "type": InputModelosRastreador,
                        'label': 'Modelo',
                        'name': 'modelo',
                    },
                    {
                        "type": "InputGeralEmpresas",
                        'label': 'Clientes',
                        'name': 'cliente_id'
                    },
                    {
                        "type": "InputVeiculos",
                        'label': 'Veículo',
                        'name': 'veiculo_id',
                        'bind': ['cliente_id']
                    },

                    {
                        "label": "Operadora Gsm",
                        "name": "operadora_gsm",
                        "desc": "Operadora Gsm",
                        "bclass": "col-md-2",
                        "type": "InputSelect",
                        "options": [
                            "CLARO",
                            "VIVO",
                            "OI",
                            "TIM",
                            "NEXTEL",
                            "ALGAR"
                        ]
                    },

                    {
                        'type': 'InputFone',
                        'label': 'Número Chip',
                        'name': 'fone_chip_gsm',
                    },

                    {
                        'label': 'Status',
                        'name': 'status',
                        "type": "InputSelect",
                        "options": [
                            "ATIVO",
                            "BLOQUEADO",
                            "INATIVO"
                        ]
                    }
                ],

                camposGrid: [

                    {
                        'label': 'Código',
                        'name': 'id'
                    },
                    {
                        'label': 'Esn',
                        'name': 'esn'
                    },

                    {
                        'label': 'Equipamento',
                        'name': 'modelo',
                    },
                    {
                        'label': 'Veículo',
                        'name': 'veiculo.placa',
                    },

                    {
                        'label': 'Operadora',
                        'name': 'operadora_gsm',
                    },

                    {
                        'label': 'Número Chip',
                        'name': 'fone_chip_gsm',
                    },

                    {
                        'label': 'Status',
                        'name': 'status',
                    }

                ]
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
            carregarGrid: function () {

                var self = this;

                self.doGet('frota/rastreadores/buscar', _.extend(this.config, this.filtro))
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
