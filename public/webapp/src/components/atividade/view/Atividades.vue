<template>
    <div>
        <b-card class="shadow-lg mb-4">
            <template #header>
                <span><i class="fa fa-tasks"></i> ATIVIDADES / SUBATIVIDADES</span>
            </template>

            <template #default>
                <div class="row">
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            <label>Atividade</label>
                            <input type="text" class="form-control" id="nm_atividade" v-model="atividade.nm_atividade"
                                   style="text-transform: uppercase"/>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            <label>Departamento</label>
                            <select class="form-control nm_departamento" id="aaaaid_departamento"
                                    v-model="atividade.id_departamento">

                                <option value="">Selecione...</option>

                                <option v-for="d in departamentos"
                                        :value="d.id">{{d.nome}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-2">
                        <label>Atividade Moderado</label>
                        <select class="form-control mod" id="moderado" v-model="atividade.moderada">
                            <option value="">Selecione...</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </div>
                    <div class="col-md-12 col-lg-2">
                        <div class="form-group">
                            <label>Atividade Privada</label>
                            <select class="form-control priv" id="privada" v-model="atividade.privada" readonly="readonly" disabled>
                                <option value="">Selecione...</option>
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </div>
                    </div>
                </div>
            </template>

            <template #footer>
                <div class="btn-group">
                    <button type="button"
                            class="btn btn-success"
                            style="width: 100px;"
                            :disabled="atividade.id != ''"
                            @click="incluir">
                        <i class="fa fa-save"></i> Incluir
                    </button>
                    <button type="button"
                            class="btn btn-primary"
                            style="width: 100px;"
                            @click="salvarEdicaoAtividade"
                            v-bind:disabled="!atividade.id">
                        <i class="fa fa-edit"></i> Alterar
                    </button>
                    <button type="button"
                            class="btn btn-secondary"
                            style="width: 100px;"
                            @click="limparAtividade">
                        <i class="fa fa-eraser"></i> Limpar
                    </button>
                </div>
            </template>
        </b-card>

        <b-card class="shadow-lg" no-body>
            <template #header>
                <span><i class="fa fa-list"></i> Lista de chamados</span>
            </template>
            <template #default>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!-- BEGIN SAMPLE TABLE PORTLET-->
                        <div class="portlet box primary">
                            <div class="portlet-body">
                                <div class="table-scrollable table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th >#</th>
                                            <th >Atividade</th>
                                            <th >Departamento</th>
                                            <th data-toggle="tooltip" title="Atividade Moderada">M</th>
                                            <th data-toggle="tooltip" title="Atividade Privada">P</th>
                                            <th data-toggle="tooltip" title="Quantidade de Subatividades">S</th>
                                            <th >Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <template v-if="dados.length > 0">
                                            <tr v-for="d in dados">
                                                <td>{{d.id}}</td>
                                                <td>{{d.nm_atividade}}</td>
                                                <td>{{l_.get(d,'departamento.nome')}}</td>
                                                <td v-if="d.moderada == 'S'"><span><i class="fa fa-eye fa-sm" style="color: #336bff" aria-hidden="true" data-toggle="tooltip" title="Moderado"></i></span></td>
                                                <td v-if="d.moderada == 'N'"><span><i class="fa fa-eye-slash fa-sm" style="color: darkred" aria-hidden="true" data-toggle="tooltip" title="Não moderado"></i></span></td>
                                                <td v-if="d.privada == 'S'" ><span><i class="fa fa-lock fa-sm" style="color: #336bff" aria-hidden="true" data-toggle="tooltip" title="Privado"></i></span></td>
                                                <td v-if="d.privada == 'N'" ><span><i class="fa fa-unlock-alt fa-sm" style="color: darkred" aria-hidden="true" data-toggle="tooltip" title="Público"></i></span></td>
                                                <td >
                                                    <span class="badge badge-primary" data-toggle="tooltip" :title="'Qtde Subatividade: '+d.subatividades.length">
                                                        <span v-if="d.subatividades.length">{{d.subatividades.length}}</span>
                                                        <span v-else>Nenhuma</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a href="#" class="btn btn-outline-primary mr-0"  data-toggle="tooltip" title="Subatividades"
                                                           @click.prevent.stop="modalSubAtividades(d.id)"><i class="fa fa-tasks"></i></a>
                                                        <a href="#" class="btn btn-outline-secondary mr-0" data-toggle="tooltip" title="Editar"
                                                           @click.prevent.stop="editarAtividade(d.id)"><i class="fa fa-edit"></i></a>
                                                        <a href="#" class="btn btn-outline-danger mr-0" data-toggle="tooltip" title="Excluir"
                                                           @click.prevent.stop="excluirAtividade(d.id)"><i class="fa fa-trash-alt"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-if="dados.length == 0">
                                            <tr>
                                                <td colspan="9"
                                                    style="text-align: center; font-weight: bold; color: #0B2C5F"><h4>
                                                    Não há dados para serem exibidos!</h4></td>
                                            </tr>
                                        </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END SAMPLE TABLE PORTLET-->
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="modalSubAtividades" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title-atividade" id="exampleModalLongTitle"
                                    style="font-weight: bold; color: #0B2C5F"></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        style="margin-top: -35px;!important;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Subatividade</label>
                                            <input type="text" class="form-control" id="nm_sub_atividade"
                                                   v-model="subatividade.nm_subatividade"
                                                   style="text-transform: uppercase"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12" style="text-align: right">
                                        <div class="btn-group btn-group-md">
                                            <button type="button" class="btn btn-success"
                                                    @click="incluirSubatividade"
                                                    v-bind:disabled="subatividade.id_subatividade !== ''">
                                                <i class="fa fa-save"></i> Incluir
                                            </button>
                                            <button type="button" class="btn btn-primary"
                                                    @click="salvarEdicaoSubatividade(subatividade.id_subatividade)"
                                                    v-bind:disabled="subatividade.id_subatividade == ''">
                                                <i class="fa fa-check-circle"></i> Alterar
                                            </button>
                                            <button type="button" class="btn btn-secondary"
                                                    @click="limparFormularioSub">
                                                <i class="fa fa-recycle"></i> Limpar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="destaque-title" style="border-bottom: 1px solid">
                                            <h1 class="page-header" id="btn-dropdowns-sub"
                                                style="margin: 0px 0px 0px 0px!important; padding-bottom: 0px!important; font-size: 17px;">
                                                <a class="anchorjs-link "
                                                   aria-label="Anchor link for: btn dropdowns"
                                                   data-anchorjs-icon=""
                                                   style="font-family: anchorjs-icons;
                                                           font-style: normal;
                                                           font-variant: normal;
                                                           font-weight: normal;
                                                           line-height: inherit;
                                                           position: absolute;
                                                           margin-left: -1em;
                                                           padding-right: 0.5em;">
                                                </a>Subatividades Cadastradas
                                            </h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th style="width: 10%; text-align: center">#</th>
                                                <th style="width: 70%">Subatividade</th>
                                                <th style="width: 20%; text-align: center;">Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="data in dadosSub">
                                                <td style="text-align: center; font-weight: bold">{{data.id}}</td>
                                                <td>{{data.nm_subatividade}}</td>
                                                <td style="text-align: center">
                                                    <div class="btn-group" role="group" aria-label="...">
                                                        <button type="button" class="btn btn-outline-danger"
                                                                aria-label="Left Align"
                                                                style="margin-left: 0px;!important;"
                                                                @click="excluirSubAtividade(data.id, data.id_atividade)">
                                                            <span class="fa fa-trash" aria-hidden="true"></span>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary"
                                                                aria-label="Justify"
                                                                @click="editarSubAtividade(data.id)">
                                                            <span class="fa fa-edit" aria-hidden="true"></span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer></template>0
        </b-card>
    </div>
</template>

<script>
    import Card from "../../geral/Card";

    export default {
        name: "viewAtividades",
        components: {Card},

        mounted() {

            var self = this;

            // this.carregaDepartamentos();

            setTimeout(function () {
                $('[data-toggle="tooltip"]').tooltip();
            }, 500);

        },

        created() {

            var self = this;
            // self.getAtividades();
            this.carregarAtividades();

            /**
             * Recarregar dados caso usario mude de empresa
             * */
            this.$root.$watch('CLIENTE.id', function (idUsuario) {
                // idUsuario && self.getAtividades();
                self.carregarDepartamentos();
                self.carregarAtividades();
            });

            self.carregarDepartamentos();

        },

        methods: {

            incluir: function () {
                this.doPost('atividades/atividades/incluir', this.atividade).then(function (result) {
                    this.atividade.id = result.id;
                    this.carregarAtividades();
                    this.alertSucesso();
                });
            },

            editarAtividade: function ($idAtividade) {

                var params = {
                    id_atividade: $idAtividade
                };

                this.doGet('atividades/atividades/hasMovement', params).then(function (result) {
                    this.doGet('atividades/atividades/consultaAtividade', params).then(function (resultAtividade) {
                        this.atividade.id = resultAtividade.id;
                        this.atividade.nm_atividade = resultAtividade.nm_atividade;
                        this.atividade.id_departamento = resultAtividade.id_departamento;
                        this.atividade.moderada = resultAtividade.moderada;
                        this.atividade.privada = resultAtividade.privada;
                    });
                });
            },

            salvarEdicaoAtividade: function ($idAtividade) {

                var params = {
                    id_atividade: this.atividade.id,
                    id_departamento: this.atividade.id_departamento,
                    nm_atividade: this.atividade.nm_atividade,
                    moderada: this.atividade.moderada,
                    privada: this.atividade.privada
                };

                this.doPost('atividades/atividades/atualizaAtividade', params).then(function (result) {
                    this.alertSucesso();
                    // this.getAtividades();
                    this.carregarAtividades();
                    this.limparAtividade();
                });
            },

            excluirAtividade: function ($idAtividade) {

                var params = {
                    id_atividade: $idAtividade
                };

                this.confirmar(r => {
                    if (r) this.doGet('atividades/atividades/hasMovement', params).then(function (result) {
                        this.doPost('atividades/atividades/excluirAtividade', params).then(function (resultExclusao) {
                            this.alertSucesso();
                            // this.getAtividades();
                            this.carregarAtividades();
                            this.limparAtividade();
                        })
                    })
                });
            },

            limparAtividade: function () {
                this.atividade.id = '';
                this.atividade.nm_atividade = '';
                this.atividade.id_departamento = '';
                this.atividade.moderada = '';
                this.atividade.privada = '';
            },

            modalSubAtividades: function (idAtividade) {

                var _this = this;
                var param = {
                    id: idAtividade
                }
                this.subatividade.id_atividade = idAtividade;

                this.doGet('atividades/atividades/buscarAtividades', param).then(function (result) {

                    $('.modal-title-atividade').html('Atividade: ' + result.nm_atividade);
                    $('#modalSubAtividades').modal('show');

                    setTimeout(function () {
                        _this.limparFormularioSub();
                    }, 50);

                    setTimeout(function () {
                        _this.limparFormularioSub();
                        _this.getSubatividadesPorAtividade(idAtividade);
                    }, 100);

                })

            },

            incluirSubatividade: function () {

                var _this = this;

                this.doPost('atividades/atividades/incluirSubAtividade', this.subatividade).then(function (result) {
                    _this.getSubatividadesPorAtividade(result.id_atividade);
                    _this.limparFormularioSub();
                    // _this.getAtividades();
                    _this.carregarAtividades();
                    this.alertSucesso();
                });
            },

            // getAtividades: function () {
            //
            //
            //     var paramBusca = {
            //         id: this.$root.USER.id,
            //         include: ['vagaDepartamentos', 'clientes'],
            //         departamentos: true
            //     }
            //
            //     this.doGet('geral/usuarios/buscar', paramBusca).then(function (usuarios) {
            //
            //         // console.log('usuarios', usuarios.departamentos);
            //         var parametros = {
            //             ids: [],
            //         };
            //
            //         usuarios.departamentos.forEach(function (item) {
            //
            //             /**
            //              * @todo corrigir api trazendo dados incorretos
            //              */
            //             if (item == null) return;
            //
            //             parametros.ids.push(item.id);
            //
            //             $('.nm_departamento').append('<option value="' + item.id + '">' + item.nome + '</option>');
            //
            //         });
            //
            //         this.doGet('atividades/atividades/buscarAtividades', parametros).then(function (result) {
            //             this.dados = result;
            //         });
            //     })
            //
            //     // this.$api.everday.usuarios.listar({id: idUsuariothis}).then(function (usuarios) {
            //     //
            //     //     var parametros = {
            //     //         ids: [],
            //     //     }
            //     //
            //     //     usuarios.departamentos.forEach(function (item) {
            //     //         parametros.ids.push(item.id);
            //     //     });
            //     //
            //     //     this.$api.atividade.atividadesCadastradas.getAtividadesCadastradas(parametros).then(function (result) {
            //     //         this.dados = result;
            //     //     })
            //     // })
            // },
            //
            getSubatividadesPorAtividade: function ($idAtividade) {

                var params = {
                    id_atividade: $idAtividade
                };

                this.doGet('atividades/atividades/buscarSubAtividades', params).then(function (result) {
                    this.dadosSub = result;
                })
            },

            limparFormularioSub: function () {
                var _this = this;
                _this.subatividade.id_subatividade = '';
                _this.subatividade.nm_subatividade = '';
            },

            editarSubAtividade: function ($idSub) {
                var params = {
                    id_subatividade: $idSub
                };

                this.doGet('atividades/atividades/hasMovementSubactivity', params).then(function (result) {
                    this.doGet('atividades/atividades/buscarSubAtividades', params).then(function (resultSub) {
                        this.subatividade.id_subatividade = resultSub.id;
                        this.subatividade.nm_subatividade = resultSub.nm_subatividade;
                    });
                });
            },

            excluirSubAtividade: function ($idSubAtividade, $idAtividade) {
                var params = {
                    id_subatividade: $idSubAtividade
                };

                this.doGet('atividades/atividades/hasMovementSubactivity', params).then(function (result) {
                    this.doPost('atividades/atividades/excluirSubAtividade', params).then(function (resultExclusao) {
                        this.alertSucesso();
                        this.getSubatividadesPorAtividade($idAtividade);
                    });
                });
            },

            salvarEdicaoSubatividade: function ($idSub) {

                var params = {
                    id_subatividade: $idSub,
                    nm_subatividade: this.subatividade.nm_subatividade
                };

                this.doPost('atividades/atividades/alterarSubatividade', params).then(function (result) {
                    this.alertSucesso();
                    this.getSubatividadesPorAtividade(result.id_atividade);
                    this.limparFormularioSub();
                });
            },

            // listaSubatividades: function ($id) {
            //     var params = {
            //         id_atividade: $id
            //     }
            //
            //     this.$api.atividade.subatividadesCadastradas.getSubatividadesCadastradas(params).then(function (result) {
            //
            //         gravaSub = '';
            //         $.each(result, function (i, item) {
            //             gravaSub += '['+result[i].nm_subatividade+']'+'<br>';
            //         })
            //
            //         var myModal = new jBox('Modal');
            //         myModal.setTitle('Subatividades Cadastradas');
            //         myModal.setContent(gravaSub);
            //         myModal.open();
            //     })
            // },
            //
            // carregaDepartamentos: function () {
            //
            //     var idUsuariothis = this.$root.USER.id;
            //
            //     this.$api.everday.usuarios.listar({id: idUsuariothis}).then(function (usuarios) {
            //         usuarios.departamentos.forEach(function (item) {
            //             $('.nm_departamento').append('<option value="'+item.id+'">' + item.nome + '</option>');
            //         });
            //     })
            // }

            carregarDepartamentos() {
                this.doGet('geral/departamentos/buscar', {empresa_id: this.$root.CLIENTE.id})
                    .then(departamentos => {
                        this.departamentos = departamentos;
                    });
            },
            carregarAtividades() {

                this.doGet('atividades/atividades/buscar', {
                    empresa_id: this.$root.CLIENTE.id,
                    include: ['departamento', 'subatividades']
                }).then(atividades => {
                    this.dados = atividades;
                    setTimeout(function () {
                        $('[data-toggle="tooltip"]').tooltip();
                    }, 500);
                });
            }

        },

        data: function () {
            return {
                atividade: {
                    id: '',
                    id_departamento: '',
                    nm_atividade: '',
                    moderada: '',
                    privada: 'N',
                },

                subatividade: {
                    id_subatividade: '',
                    id_atividade: '',
                    nm_subatividade: ''
                },

                dados: [],
                dadosSub: [],
                sub: [],

                departamentos: []
            }
        }
    }
</script>

<style scoped>

</style>
