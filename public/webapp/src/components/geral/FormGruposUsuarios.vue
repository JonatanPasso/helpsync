<template>

    <Card>
        <template #header>
            <i class="fa fa-users"></i> Grupos de Usuários
        </template>

        <template #default>

            <div class="row">
                <div class="form-group"
                     :class="c.bclass ? c.bclass : 'col-md-4'"
                     v-for="c in scheme">

                    <label>{{c.label}}</label>

                    <component
                        :is="c.type"
                        :config="c"
                        :errors="l_.get(validationResponse,c.name)"
                        v-bind="getBindParams(c.name)"
                        :id_only="true"
                        v-model="registro[c.name]"></component>

                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <b-card no-body :class="l_.get(validationResponse,'usuarios') ? 'border-danger': ''">
                        <template #header>
                            Usuários do Grupo
                            <div style="float: right">
                                <b-button-group>
                                    <b-button variant="danger"
                                              title="Excluir Todos"
                                              @click="excluirTodosUsuarios"
                                              :disabled="!registro.id"
                                              size="sm"><i class="fa fa-user-minus"></i></b-button>

                                    <b-button variant="success"
                                              title="Adicionar Todos"
                                              @click="addTodosUsuarios"
                                              :disabled="!registro.id"
                                              size="sm"><i class="fa fa-users"></i></b-button>
                                    <BtnBuscarUsuarios variant="info"
                                                       title="Adicionar Usuários"
                                                       :disabled="!registro.id"
                                                       @select="addUsuario"
                                                       size="sm">
                                        <i class="fas fa-user-plus"></i>
                                    </BtnBuscarUsuarios>
                                </b-button-group>
                            </div>
                        </template>

                        <b-overlay
                            :show="ajaxStatus"
                            rounded
                            opacity="0.6"
                            spinner-variant="primary">
                            <div style="max-height: 30vh;overflow: auto">
                                <table class="table">
                                    <tr v-if="!usuarios.length">
                                        <td>Nenhum usuário adicionado</td>
                                    </tr>
                                    <tr v-for="(u,index) in usuarios">

                                        <td>{{index+1}}</td>
                                        <td>{{u.nome}}</td>
                                        <td>
                                            <a href="#"
                                               class="text-danger"
                                               @click.prevent.stop="removerUsuario(u)"><i
                                                class="fa fa-times"></i></a>
                                        </td>

                                    </tr>
                                </table>
                            </div>
                        </b-overlay>
                    </b-card>

                    <div class="invalid-feedback" style="display: block">
                        <div v-for="e in l_.get(validationResponse,'usuarios')">{{e}}</div>
                    </div>

                </div>
            </div>

        </template>

        <template #footer>
            <div class="btn-group">

                <button type="button"
                        @click="salvar"
                        :disabled="ajaxStatus && operacao == 'salvar'"
                        class="btn btn-success">SALVAR <i v-if="ajaxStatus && operacao=='salvar' "
                                                          class="fas fa-spinner fa-spin"></i>
                </button>

                <button type="button"
                        :disabled="( ajaxStatus && operacao == 'excluir' ) || !registro.id"
                        @click="excluir"
                        class="btn btn-danger">EXCLUIR <i v-if="ajaxStatus && operacao=='excluir' "
                                                          class="fas fa-spinner fa-spin"></i>
                </button>

                <button type="button"
                        @click="limpar"
                        class="btn btn-info">LIMPAR
                </button>
            </div>
        </template>
    </Card>


</template>

<script>
    import Card from "../geral/Card";
    import SingleForm from "../geral/SingleForm";
    import InputGeralClientes from "../geral/singleForm/InputGeralEmpresas";
    import InputText from "./singleForm/InputText";
    import InputSelect from "./singleForm/InputSelect";
    import BtnBuscarUsuarios from "./BtnBuscarUsuarios";

    export default {
        name: "FormGruposUsuarios",
        components: {BtnBuscarUsuarios, SingleForm, Card},

        props: ['grupo'],

        data() {
            return {
                scheme: [
                    {
                        "label": "Código",
                        "name": "id",
                        "desc": "Código do Rastreador",
                        "bclass": "col-md-2",
                        "type": InputText,
                        "attribs": {
                            "readonly": "readonly"
                        }
                    },

                    {
                        "label": "Nome",
                        "name": "nome",
                        "desc": "Nome do Grupo",
                        "bclass": "col-md-4",
                        "type": InputText
                    },

                    {
                        "label": "Tipo",
                        "name": "tipo",
                        "desc": "Tipo de Grupo",
                        "bclass": "col-md-3",
                        "type": InputSelect,
                        "options": ['GLOBAL', 'POR CLIENTE']
                    },

                    {
                        "label": "Cliente",
                        "name": "cliente_id",
                        "desc": "Cliente",
                        "bclass": "col-md-3",
                        "type": InputGeralClientes,

                    },


                ],

                registro: {id: '', nome: '', cliente_id: '', tipo: '', criado_em: ''},

                operacao: null,

                usuarios: [],

            }
        },

        watch: {
            'registro.id': function (id) {


                if (id) {
                    this.registro.tipo = this.registro.cliente_id ? 'POR CLIENTE' : 'GLOBAL';
                    this.carregarUsuariosGrupo();

                }


            },
            'validationResponse.usuarios': function (msg) {
                msg && this.alertErro(msg)
            },
        },

        computed: {},
        methods: {

            getBindParams(campo) {

                if (campo == 'cliente_id') {
                    return {
                        disabled: this.registro.tipo == 'GLOBAL'
                    }
                }
            },

            addUsuario(u) {

                this.doPost('geral/grupos-usuarios/adicionar-usuario', {
                    grupos_usuarios_id: this.registro.id,
                    usuario_id: u.id
                }).then(() => {
                    this.carregarUsuariosGrupo();
                });
            },
            addTodosUsuarios() {

                this.confirmar(function (sim) {
                    if (sim) {

                        this.doPost('geral/grupos-usuarios/adicionar-usuario', {
                            grupos_usuarios_id: this.registro.id,
                            modo: 'incluir_todos'
                        }).then(() => {
                            this.carregarUsuariosGrupo();
                        });
                    }
                }, 'Todos os usuários do sistema serão incluídos nesse grupo. Confirmar ?');

            },

            excluirTodosUsuarios() {

                this.confirmar(function (sim) {
                    if (sim) {
                        this.doPost('geral/grupos-usuarios/excluir-usuario', {
                            grupos_usuarios_id: this.registro.id,
                            modo: 'excluir_todos'
                        }).then(() => {
                            this.carregarUsuariosGrupo();
                        });
                    }
                },'Todos os usuários serão removidos do grupo. Confirmar ?');
            },

            carregarUsuariosGrupo() {

                this.doGet('geral/grupos-usuarios/buscar-usuarios', {
                    grupos_usuarios_id: this.registro.id,
                    paginacao: false
                }).then((result) => {
                    this.usuarios = result;
                })
            },


            salvar: function () {

                var self = this;

                this.operacao = 'salvar';

                this.doPost('geral/grupos-usuarios/salvar', this.registro)
                    .then(function (response) {

                        self.validationResponse = {};
                        self.registro = response;
                        self.alertSucesso();

                        this.$emit('saved', self.registro);

                    });

            },


            excluir: function () {

                let self = this;

                this.operacao = 'excluir';

                this.confirmar(function (sim) {
                    if (sim) {
                        self.doPost('geral/grupos-usuarios/excluir', self.registro)
                            .then(function () {
                                self.alertSucesso('Registro excluído com sucesso');

                                this.$emit('deleted', self.registro);

                            });
                    }

                    self.limpar();
                })

            },

            limpar: function () {

                this.registro = this.$options.data().registro;

                this.usuarios = [];

                this.validationResponse = {};

            },

            removerUsuario(u) {

                this.doPost('geral/grupos-usuarios/excluir-usuario', {
                    grupos_usuarios_id: this.registro.id,
                    usuario_id: u.id
                }).then(() => {
                    this.carregarUsuariosGrupo();
                });
            }

        }
    }
</script>

<style scoped>

</style>
