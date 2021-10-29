<template>
    <b-row>

        <b-col>
            <b-card class="shadow">

                <template #header>
                    <i class="fa fa-shield-alt"></i> Permissões
                </template>

                <template>

                    <b-tabs content-class="mt-3" v-model="tab_id">

                        <b-tab title="Por Usuário" active>

                            <BtnBuscarUsuarios variant="success"
                                               @select="usuario=$event">
                                <i class="fa fa-search"></i> Buscar usuário
                            </BtnBuscarUsuarios>


                            <div v-if="usuario">

                                <br>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>NOME:</td>
                                        <td>{{usuario.nome}}</td>
                                    </tr>

                                    <tr>
                                        <td>EMAIL:</td>
                                        <td>{{usuario.email}}</td>
                                    </tr>

                                    <tr>
                                        <td>STATUS:</td>
                                        <td>{{usuario.status}}</td>
                                    </tr>

<!--                                    <tr>-->
<!--                                        <td>CLIENTES</td>-->
<!--                                        <td>-->
<!--                                            <div v-for="c in usuario.clientes">-->
<!--                                                {{c.cpf_cnpj | mask( c.cpf_cnpj.length > 11 ? '##.###.###/####-##':-->
<!--                                                '###.###.###-##') }} {{c.nome}}-->
<!--                                            </div>-->
<!--                                        </td>-->
<!--                                    </tr>-->

                                </table>

                                <hr>

                                <div>
                                    <span>EMPRESAS VINCULADAS</span>
                                    <select class="form-control" v-model="empresa_id">
                                        <option value="">SELECIONAR EMPRESA</option>
                                        <option v-for="e in usuario.clientes"
                                                :value="e.id">{{e.nome}}
                                        </option>
                                    </select>
                                </div>

                            </div>

                        </b-tab>

                        <!--Tab 2-->
                        <b-tab title="Por Grupo">


                            <BtnBuscarGruposUsuarios variant="success"
                                                     @select="grupo=$event">
                                <i class="fa fa-user-friends"></i> Buscar grupos de usuários
                            </BtnBuscarGruposUsuarios>

                            <hr>

                            <table v-if="grupo"
                                   class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>GRUPO:</td>
                                    <td>{{grupo.nome}}</td>
                                </tr>
                                <tr>
                                    <td>CLIENTE:</td>
                                    <td>{{l_.get(grupo,'cliente.nome','NENHUM')}}</td>
                                </tr>
                                </tbody>
                            </table>

                            <div v-else>
                                <h4><i class="fa fa-hand-point-up"></i> Selecione um grupo para configurar as
                                    permissões
                                </h4>
                            </div>

                        </b-tab>

                    </b-tabs>

                </template>

                <template #footer>

                    <div class="btn-group">

                        <button type="button"
                                @click="limpar"
                                class="btn btn-secondary"><i class="fa fa-recycle"></i> LIMPAR
                        </button>

                    </div>
                </template>
            </b-card>
        </b-col>

        <b-col v-if="tab_id == 0">
            <b-card class="shadow">

                <template #header>Permissões</template>

                <h4 v-if="!acessos || !empresa_id"><i class="fa fa-hand-point-left"></i> Selecione um usuário para
                    configurar as
                    permissões
                </h4>

                <b-overlay v-else
                           :show="ajaxStatus"
                           rounded
                           opacity="0.6"
                           spinner-variant="primary">

                    <PermisaoTreeview @sel="selRecurso"
                                      @selAll="selRecursoAll"
                                      :acessos="acessos"></PermisaoTreeview>
                </b-overlay>

            </b-card>
        </b-col>

        <b-col v-if="tab_id == 1">

            <b-card class="shadow">
                <template #header><i class="fa fa-shield-alt"></i> Permissões</template>
                <b-card-sub-title>
                    Permissões do Grupo <b>{{l_.get(grupo,'nome','SELECIONAR')}}</b>

                </b-card-sub-title>

                <br>
                <h4 v-if="!acessos || !grupo"><i class="fa fa-hand-point-left"></i> Selecione um GRUPO para
                    configurar as
                    permissões
                </h4>

                <PermisaoTreeview v-else @sel="selRecursoGrupo"
                                  :acessos="acessos"></PermisaoTreeview>
            </b-card>

        </b-col>

    </b-row>

</template>

<script>

    import Card from "../Card";
    import SingleForm from "../SingleForm";
    import PermisaoTreeview from "../PermisaoTreeview";
    import GridUsuarios from "../GridUsuarios";
    import InputGeralClientes from "../singleForm/InputGeralEmpresas";
    import BtnBuscarUsuarios from "../BtnBuscarUsuarios";
    import BtnBuscarGruposUsuarios from "../BtnBuscarGruposUsuarios";

    export default {
        name: "Permissoes",

        components: {
            BtnBuscarGruposUsuarios,
            BtnBuscarUsuarios, InputGeralClientes, GridUsuarios, PermisaoTreeview, SingleForm, Card
        },

        props: {},

        data: function () {
            return {
                tab_id: 0,
                acessos: null,
                usuario: null,
                usuarioModal: null,
                empresa_id: '',
                grupo: null
            }
        },

        created: function () {
        },

        watch: {
            usuario: function () {

                this.usuario && this.carregarAcessos();
            },
            'empresa_id'() {

                this.usuario && this.carregarAcessos();
            },
            'grupo.id'(id) {
                id && this.carregarAcessos('grupo');
            },
            tab_id() {
                this.empresa_id = '';
                this.grupo = null;
                this.acessos = null;
            }
        },

        methods: {

            carregarAcessos: function (tipo) {

                let params = null;

                if (tipo == 'grupo') {
                    params = {
                        grupo_id: this.grupo.id
                    }
                    this.empresa_id = null;
                } else {
                    params = {
                        empresa_id: this.empresa_id,
                        usuario_id: this.usuario.id
                    };
                }

                this.doGet('geral/controle-acessos/buscar',
                    params)
                    .then(function (e) {
                        this.acessos = e;
                    })
            },

            salvar: function (recursoId, acesso, tipo) {

                let options = {
                    recurso_id: recursoId,
                    acesso: acesso ? 'Y' : 'N'
                }

                if (tipo == 'grupo') {
                    options.grupos_usuarios_id = this.grupo.id;
                } else {
                    options.usuario_id = this.usuario.id;
                    options.empresa_id = this.empresa_id;
                }


                this.doPost('geral/controle-acessos/salvar', options)
                    .then((resp) => {

                        function find(acessos) {

                            if (_.get(acessos, 'id')) {

                                if (acessos.id == resp.recurso_id) {
                                    acessos.acesso = resp.acesso;
                                }

                            } else {
                                for (var i in acessos) {
                                    find(acessos[i])
                                }
                            }
                        }

                        find(this.acessos);

                        clearTimeout(this.timeout);

                        this.timeout = setTimeout(() => {

                            this.$bvToast.hide();

                            this.$bvToast.toast('Permissão salva com sucesso!', {
                                title: 'Controle de Acesso',
                                variant: 'success',
                                solid: true
                            });

                        }, 500);

                    });
            },

            selRecurso(selecao) {

                this.salvar(selecao.recurso.id,
                    selecao.status);
            },

            selRecursoAll(selecao) {
                this.salvarRecursivo(selecao.recurso, selecao.status);
            },

            salvarRecursivo(recursos, status) {

                for (var i in recursos) {

                    if (_.get(recursos[i], 'id')) {

                        this.salvar(recursos[i].id, status);

                    } else {
                        this.salvarRecursivo(recursos[i], status);
                    }
                }
            },

            selRecursoGrupo(selecao) {

                this.salvar(selecao.recurso.id,
                    selecao.status, 'grupo');
            },

            selUsuario() {

                if (this.usuarioModal) {
                    this.usuario = this.usuarioModal
                }
            },
            limpar() {
                this.usuario = null;
                this.usuarioModal = null;
                this.acessos = null;
                this.empresa_id = '';
            }
        }
    }
</script>

<style scoped>

    .box-permissoes {
        background-color: #cccccc40;
        padding: 10px 0px;
        border: 1px solid #d1d3e2;
        margin: 5px 0px;
        border-radius: 4px;
    }
</style>
