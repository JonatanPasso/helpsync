<template>

    <b-card class="shadow-lg" no-body>

        <template #header>
            <i class="fa fa-car"></i> Cadastro de Veículos
        </template>

        <template #default>

            <b-tabs card>

                <!--  Tab cadastro de veículos-->
                <b-tab title="Cadastro">

                    <SingleForm :scheme="scheme"
                                :model="registro"
                                :errors="validationResponse"></SingleForm>

                    <div class="row">

                        <div class="col-md-8 col-lg-8">
                            <label>Vincular Cliente</label>

                            <table class="table table-condensed">

                                <tr v-for="(c,i) in registro.clientes">
                                    <td style="vertical-align: middle">{{ i+1 }}</td>
                                    <td>{{ c.nome }}</td>
                                    <td><a href="#" @click.prevent="delCliente(c)"><i class="fa fa-trash"></i> </a></td>
                                </tr>

                                <tr>

                                    <td style="vertical-align: middle">{{registro.clientes.length+1}}</td>

                                    <td colspan="2">
                                        <input-geral-clientes v-model="aux_sel_cliente"></input-geral-clientes>
                                    </td>

                                    <td>
                                <span class="input-group-text">
                                    <a href="#" @click.prevent="addCliente">Vincular Cliente</a></span>
                                    </td>

                                </tr>

                            </table>
                        </div>
                    </div>

                </b-tab>

                <!--  Tab eventos de veículos-->
                <b-tab title="Eventos/Alertas"
                       :disabled="!registro.id">

                    <FormEventos v-if="registro.id"
                                 :veiculo="registro"></FormEventos>


                </b-tab>

            </b-tabs>

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
    </b-card>


</template>

<script>
    import Card from "../geral/Card";
    import SingleForm from "../geral/SingleForm";
    import InputGeralClientes from "../geral/singleForm/InputGeralEmpresas";
    import FormEventos from "./FormEventos";

    /**
     *
     * @event update teste
     * */

    export default {
        name: "FormVeiculo",
        components: {FormEventos, InputGeralClientes, SingleForm, Card},

        props: ['veiculo'],

        data() {
            return {
                scheme: require('./VeiculoFields'),
                registro: {

                    clientes: [],
                },
                operacao: null,

                clientes: [],
                aux_sel_cliente: null,


            }
        },

        watch: {
            veiculo: function (veiculo) {

                if (this.veiculo) {
                    this.registro = veiculo;
                } else {
                    this.registro = {};
                }
            }
        },

        computed: {
            clientesCmpt: function () {
                var self = this;
                return _.filter(this.clientes, function (item) {
                    for (var i in self.registro.clientes) {
                        if (item.id == self.registro.clientes[i].id) {
                            return false;
                        }
                    }
                    return true;
                });
            },

        },
        methods: {

            salvar: function () {

                var self = this;

                this.operacao = 'salvar';

                this.doPost('frota/veiculos/salvar', this.registro)
                    .then(function (response) {

                        self.validationResponse = {};
                        self.registro = response;
                        self.alertSucesso();

                        /**
                         *  Update registros
                         *  @event Update registros
                         *
                         */
                        this.$emit('update', response);

                    });

            },


            excluir: function () {

                let self = this;

                this.operacao = 'excluir';

                this.confirmar(function (sim) {
                    if (sim) {
                        self.doPost('frota/veiculos/excluir', self.registro)
                            .then(function () {
                                self.alertSucesso('Registro excluído com sucesso');

                                this.$emit('delete');

                                self.limpar();

                            });
                    }
                })

            },

            limpar: function () {

                this.registro = {
                    clientes: []
                };
                this.validationResponse = {};

            },


            addCliente: function () {
                if (!this.aux_sel_cliente) return;
                if (!this.registro.clientes) {
                    this.registro.clientes = [];
                }


                if (_.find(this.registro.clientes, {id: this.aux_sel_cliente.id})) {
                    this.alertErro('Cliente já vinculado');
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
