<template>

    <Card :footer="false" :header="!modal" :nocard="modal">
        <template #header>
            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Grupos de Usuários</h6>
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

            <!--            <Paginator :paginate="pageData" @change-page="config.page=$event"></Paginator>-->

            <div class="row">
                <div class="col-12 text-right">

                    {{pageData.from}} ate {{pageData.to}} de {{pageData.total}}

                    <div class="ajust-padding-paginacao">
                        <b-pagination
                                v-model="config.page"
                                :total-rows="pageData.total"
                                :per-page="pageData.per_page"
                                align="right"></b-pagination>
                    </div>
                </div>
            </div>

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
                            <td v-for="c in camposGrid">{{l_.get(d,c.campo)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </b-overlay>

            <div class="row">
                <div class="col-12 text-right">

                    {{pageData.from}} ate {{pageData.to}} de {{pageData.total}}

                    <div class="ajust-padding-paginacao">
                        <b-pagination
                                v-model="config.page"
                                :total-rows="pageData.total"
                                :per-page="pageData.per_page"
                                align="right"></b-pagination>
                    </div>
                </div>
            </div>

        </template>
    </Card>

</template>


<script>


    import Card from "./Card";
    import Paginator from "./view/Paginator";
    import SingleForm from "./SingleForm";
    import InputGeralEmpresas from "./singleForm/InputGeralEmpresas";

    export default {
        name: 'GridGruposUsuarios',
        components: {SingleForm, Paginator, Card},

        props: {
            modal: {
                default: false
            }
        },
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
                    include: []
                },
                selectedRow: {},

                filtro: {
                    id: null,
                    contem: null,
                },

                camposFiltro: [

                    {
                        name: 'id',
                        label: 'CODIGO',
                        type: 'inputMask',
                        bclass: 'col-md-2',
                        attribs: {mask: '########'}
                    },
                    {
                        name: 'contem',
                        label: 'NOME/CPF/CNPJ',
                        type: 'input-text',
                        bclass: 'col-md-4',
                    },

                    {
                        name: 'cliente_id',
                        label: 'Cliente',
                        type: InputGeralEmpresas,
                        bclass: 'col-md-3',
                    }

                ],
                camposGrid: [
                    {
                        'label': 'Código',
                        'campo': 'id'
                    },

                    {
                        'label': 'Nome',
                        'campo': 'nome',
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

            limpar: function () {
                this.config = this.$options.data().config;
                this.filtro = this.$options.data().filtro;
            },

            carregarGrid: function () {

                var self = this;

                var aux = _.clone(this.config);
                aux.filtro = this.filtro;

                self.doGet('geral/grupos-usuarios/buscar', aux)
                    .then(function (paginacaoResult) {
                        self.pageData = paginacaoResult;
                    });
            },
            selecionar: function (item) {

                this.selectedRow = item;

                this.$emit('select', _.clone(item));

                $('html,body').animate({scrollTop: 0}, 500);
            }
        }
    };


</script>

<style scoped>

</style>
