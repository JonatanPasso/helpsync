<template>
    <div>

        <Card>

            <template #header>
                <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-users"></i> Usuários</h6>
            </template>

            <template #default>

                <b-row>
                    <b-col md="8">
                        <b-alert show="true" class="h5"><i class="fa fa-info"></i> O campos login, email, cpf e Fone
                            podem ser utilizados para login!
                        </b-alert>
                    </b-col>
                </b-row>

                <div class="row">

                    <div class="col-md-8">

                        <b-card header="Dados do usuário">
                            <SingleForm :scheme="campos"
                                        :model="registro"
                                        @update-model="registro"
                                        :errors="validationResponse"></SingleForm>
                        </b-card>
                    </div>

                    <div class="col-md-4">

                        <b-card no-body>
                            <template #header>
                                Empresas vinculadas ao usuário
                            </template>

                            <table class="table table-condensed">

                                <tr v-for="(c,i) in registro.clientes">
                                    <td>{{ i+1 }}</td>
                                    <td>{{ c.nome }}</td>
                                    <td><a href="#" @click.prevent="delCliente(c)"><i class="fa fa-trash"></i> </a></td>
                                </tr>

                                <tr>
                                    <td>{{registro.clientes.length+1}}</td>

                                    <td colspan="2">

                                        <div style="white-space: nowrap">

                                            <InputGeralClientes v-model="aux_sel_cliente"></InputGeralClientes>

                                        </div>

                                    </td>
                                </tr>

                            </table>

                            <template #footer>
                                <b-button variant="info"
                                          @click.prevent="addCliente"><i class="fa fa-plus"></i> Adicionar Empresa
                                </b-button>
                            </template>

                        </b-card>


                    </div>
                </div>


            </template>

            <template #footer>

                <div class="btn-group">

                    <button type="button"
                            @click="salvar"
                            :disabled="ajaxStatus && operacao == 'salvar'"
                            class="btn btn-success">SALVAR <i v-if="ajaxStatus && operacao == 'salvar'"
                                                              class="fas fa-spinner fa-spin"></i>
                    </button>

                    <button type="button"
                            :disabled="( ajaxStatus && operacao == 'excluir') || !registro.id"
                            @click="excluir"
                            class="btn btn-danger">EXCLUIR <i v-if="ajaxStatus && operacao == 'excluir'"
                                                              class="fas fa-spinner fa-spin"></i>
                    </button>

                    <button type="button"
                            @click="limpar"
                            class="btn btn-info">LIMPAR
                    </button>

                </div>
            </template>
        </Card>

        <GridUsuarios ref="grid"
                      @select="registro=$event"></GridUsuarios>

    </div>

</template>

<script>

    import Card from "../Card";
    import SingleForm from "../SingleForm";
    import InputGeralClientes from "../singleForm/InputGeralEmpresas";
    import GridUsuarios from "../GridUsuarios";

    export default {
        name: "Usuarios",
        components: {GridUsuarios, InputGeralClientes, SingleForm, Card},

        data() {
            return {
                campos: [
                    {
                        'label': 'Código',
                        'name': 'id',
                        'desc': 'Código do Usuário',
                        'bclass': 'col-md-2',
                        'type': 'input-text',
                        'attribs': {'readonly': 'readonly'},
                    },

                    {
                        'label': 'Nome',
                        'name': 'nome',
                        'desc': 'Nome do usuário',
                        'bclass': 'col-md-10',
                        'type': 'input-text'
                    },

                    {
                        'label': 'Login',
                        'name': 'usuario',
                        'desc': 'Login de acesso',
                        'bclass': 'col-md-6',
                        'type': 'input-text'
                    },

                    {
                        'label': 'Email',
                        'name': 'email',
                        'desc': 'Endereço de Email',
                        'bclass': 'col-md-6',
                        'type': 'input-email'
                    },

                    {
                        'label': 'CPF/CNPJ',
                        'name': 'cpf',
                        'desc': 'Cpf do Usuário',
                        'bclass': 'col-md-5',
                        'type': 'input-cpf-cnpj',
                    },


                    {
                        'label': 'Fone',
                        'name': 'fone',
                        'desc': 'Celular',
                        'bclass': 'col-md-5',
                        'type': 'input-fone'
                    },

                    {
                        'label': 'Senha',
                        'name': 'senha',
                        'desc': 'Senha',
                        'bclass': 'col-md-5',
                        'type': 'input-password'
                    }],
                registro: {
                    clientes: []
                },

                clientes: [],
                aux_sel_cliente: null,

                operacao: null
            }
        },

        created: function () {


        },

        methods: {
            salvar: function () {

                var self = this;

                this.operacao = 'salvar';

                this.doPost('geral/usuarios/salvar', this.registro)
                    .then(function (response) {

                        self.validationResponse = {};
                        self.registro.id = response.id;
                        self.alertSucesso();

                        this.$refs.grid.carregarGrid();
                    });

            },


            excluir: function () {

                let self = this;

                this.operacao = 'excluir';

                this.confirmar(function (sim) {
                    if (sim) {
                        self.doPost('geral/usuarios/excluir', self.registro)
                            .then(function () {
                                self.alertSucesso('Registro excluído com sucesso');
                                self.limpar();
                                this.$refs.grid.carregarGrid();
                            });
                    }
                })

            },

            limpar: function () {

                this.registro = this.$options.data().registro;

                this.validationResponse = {};
                this.aux_sel_cliente = null;

            },

            addCliente: function () {

                if (!this.aux_sel_cliente) return;

                if (!this.registro.clientes) {
                    this.registro.clientes = [];
                }

                if (_.find(this.registro.clientes, {id: this.aux_sel_cliente.id})) {
                    this.alertErro("Empresa já vinculada");
                    return;
                }

                this.registro.clientes.push(this.aux_sel_cliente);
                this.aux_sel_cliente = null;
            },
            delCliente: function (cliente) {
                this.registro.clientes = _.difference(this.registro.clientes, [cliente]);
            }
        }
    }
</script>

<style scoped>

</style>
