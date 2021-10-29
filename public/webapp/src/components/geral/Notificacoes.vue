<template>

    <b-card class="shadow-lg" no-body>

        <template #header>
            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-exclamation-triangle"></i> Notificações</h6>
        </template>

        <br>
        <div class="d-none d-md-block" style="padding: 0px 10px">

            <b-row>
                <b-col>
                    <b-pagination
                        v-model="filtro.page"
                        :total-rows="pageData.total"
                        :per-page="pageData.per_page"
                        align="right"></b-pagination>
                    <!--            <div class="text-center  d-none d-md-block">-->
                    <!--                Total de Registros: {{pageData.total}} Registos por Página: {{pageData.per_page}}-->
                    <!--            </div>-->
                </b-col>
            </b-row>
            <b-overlay
                :show="ajaxStatus"
                rounded
                opacity="0.6"
                spinner-variant="primary">

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>TÍTULO</th>
                        <th>NOTIFICAÇÃO</th>
                        <th>NOTIFICANDO EM</th>
                        <th>VISUALIZADO</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="n in pageData.data">
                        <td>{{n.titulo}}</td>
                        <td>{{n.msg}}</td>
                        <td>{{n.gerada_em | dateTimeFormat('DD/MM/YYYY HH:mm') }}</td>
                        <td class="text-center">

                            <a href="#"
                               v-if="n.leitura_em == null"
                               @click.prevent.stop="visualizar(n)"
                               class="btn btn-secondary">

                                <i v-if="ajaxStatus && operacao == 'visualizar'+n.id"
                                   class="fas fa-spinner fa-spin"></i>

                                <i v-else class="fa fa-check-circle"></i>
                            </a>

                            <span v-else
                                  class="btn btn-success"><i class="fa fa-check-circle"></i></span>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </b-overlay>

        </div>

        <div class="d-md-none">

            <div class="col-xl-3 col-md-6 mb-4"
                 v-show="!qtdNaoVisualizadas">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        Sem mais notificações <i class="fa fa-thumbs-up"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4"
                 v-for="n in pageData.data"
                 v-show="n.leitura_em == null"
                 :key="n.id">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{n.titulo}}
                                </div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    {{n.msg}}
                                </div>
                            </div>
                            <div class="col-auto">

                                <a href="#"
                                   v-if="n.leitura_em == null"
                                   @click.prevent.stop="visualizar(n)"
                                   class="btn btn-secondary">

                                    <i v-if="ajaxStatus && operacao == 'visualizar'+n.id"
                                       class="fas fa-spinner fa-spin"></i>

                                    <i v-else class="fa fa-check-circle"></i>
                                </a>

                                <span v-else
                                      class="btn btn-success"><i class="fa fa-check-circle"></i></span>

                                <!--                                <i class="fas fa-calendar fa-2x text-gray-300"></i>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <template #footer>
            <div class="btn-group">
                <button type="button"
                        @click="visualizarTodas"
                        :disabled="!qtdNaoVisualizadas || ( ajaxStatus && operacao == 'visualizarTodas' )"
                        class="btn btn-success">MARCAR TODAS COMO VISUALIZADO <i
                    v-if="ajaxStatus && operacao == 'salvar'"
                    class="fas fa-spinner fa-spin"></i>
                </button>
            </div>
        </template>
    </b-card>

</template>

<script>


    export default {

        name: 'Notificacoes',

        data: function () {

            return {
                registro: {},
                pageData: {
                    data: [],
                },
                filtro: {
                    paginacao: true,
                    // id: 1
                }
            }

        },

        computed: {
            qtdNaoVisualizadas() {
                return _.reduce(this.pageData.data, (sum, i) => {
                    return sum + (i.leitura_em === null ? 1 : 0);
                }, 0);
            },
        },
        created: function () {
            this.buscarNotificacoes();
        },

        methods: {

            buscarNotificacoes: function () {
                this.doGet('geral/notificacoes/buscar', this.fitltro)
                    .then((r) => {
                        this.pageData = r;
                    });
            },

            visualizar: function (notif) {

                this.operacao = 'visualizar' + notif.id;
                this.doPost('geral/notificacoes/visualizar', notif)
                    .then(function () {
                        this.buscarNotificacoes();
                    })
            },

            visualizarTodas: function () {

                this.confirmar(function (seSim) {

                    if (seSim) {

                        var idNotrific = _.reduce(this.pageData.data, (r, i) => {
                            r.push(i.id);
                            return r;
                        }, []);

                        this.doPost('geral/notificacoes/visualizar',
                            {id: idNotrific})
                            .then(function () {
                                this.buscarNotificacoes();
                            });
                    }
                });

            }
        }
    }

</script>
