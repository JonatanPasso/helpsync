<template>

    <Card :footer="false"
          :header="!modal"
          :nocard="modal">

        <template #header>
            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Usuários cadastrados</h6>
        </template>

        <template #default>

            <b-button v-show="!show_filtros"
                      @click="show_filtros=!show_filtros"><i class="fa fa-search"></i> FILTROS
            </b-button>

            <div v-show="show_filtros">

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

                            <button type="button"
                                    @click.prevent.stop="show_filtros=!show_filtros"
                                    class="btn btn-info"><i class="fa fa-minus"></i> Ocultar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>

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

                <div class="table-responsive table-with-scrool"
                     :style="{maxHeight: modal ? '30vh' : '60vh' }">

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
                            <td v-for="c in camposGrid" style="vertical-align: middle">
                                <span v-if="c.campo == 'foto'">
                                    <ImgProfile style="height: 50px" :usuario="d"></ImgProfile>
                                </span>
                                {{l_.get(d,c.campo)}}
                            </td>
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

    import Cidades from "./singleForm/InputCidades";
    import Estados from "./singleForm/InputEstados";
    import Card from "./Card";
    import Paginator from "./view/Paginator";
    import SingleForm from "./SingleForm";
    import ImgProfile from "@/components/geral/perfil/ImgProfile";

    export default {
        components: {ImgProfile, SingleForm, Paginator, Card, Estados, Cidades},

        name: 'GridUsuarios',

        props: {
            modal: {
                default: false,
            }
        },

        computed: {
            custonBind() {
                if (this.modal) {
                    return {
                        cardClass: 'nnn'
                    }
                }
                return {}
            }
        },
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
                    }
                },
                selectedRow: {},

                filtro: {
                    id: null,
                    cliente_id: '',
                },

                camposFiltro: [


                    {
                        name: 'id',
                        label: 'Codigo',
                        type: 'input-mask',
                        bclass: 'col-md-1',
                        attribs: {
                            mask: '######'
                        }
                    },
                    {
                        name: 'contem',
                        label: 'Nome',
                        type: 'input-text',
                        bclass: 'col-md-3',
                    },

                    {
                        name: 'cliente_id',
                        label: 'Cliente',
                        type: 'input-geral-empresas',
                        bclass: 'col-md-3',
                        bind: {id_only: true}
                    },

                    {
                        name: 'email',
                        label: 'Email',
                        type: 'input-email',
                        bclass: 'col-md-3 input-sm',
                    },
                    {
                        name: 'fone',
                        label: 'Fone',
                        type: 'input-fone',
                        bclass: 'col-md-2',
                    }

                ],
                camposGrid: [
                    {
                        'label': 'Código',
                        'campo': 'id'
                    },

                    {
                        'label': 'Foto',
                        'campo': 'foto',
                    },
                    {
                        'label': 'Nome',
                        'campo': 'nome',
                    },
                    {
                        label: 'Cliente',
                        'campo': 'clientes[0].nome',
                    },
                    {
                        'label': 'Email',
                        'campo': 'email',
                    },

                    {
                        'label': 'Fone',
                        'campo': 'fone',
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

                aux.filtro = _.clone(this.filtro);

                aux.filtro.vinculo_empresa_id = _.get(this.filtro, 'vinculo_empresa_id.id');
                aux.include = ['clientes'];

                self.doGet('geral/usuarios/buscar', aux)
                    .then(function (paginacaoResult) {
                        self.pageData = paginacaoResult;
                    });
            },
            selecionar: function (item) {

                this.selectedRow = item;

                this.$emit('select', _.clone(item));

                $('html,body').animate({scrollTop: 0});
            }
        }
    };


</script>

<style scoped>

</style>
