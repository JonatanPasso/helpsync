<template>

    <Card>
        <template #header>
            <i class="fa fa-mobile"></i> Cadastro de Rastreador
        </template>

        <template #default>

            <SingleForm :scheme="scheme"
                        :model="registro"
                        :errors="validationResponse"></SingleForm>


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

    export default {
        name: "FormRastreador",
        components: {InputGeralClientes, SingleForm, Card},

        props: ['rastreador'],

        data() {
            return {
                scheme: require('./RastreadorFields'),
                registro: {
                    operadora_gsm: null,
                    id: null,
                    esn: null,
                    status: null,
                    iccid: null,
                    fone_chip_gsm: null
                },
                operacao: null
            }
        },

        watch: {
            rastreador: function (rastreador) {

                if (this.rastreador) {
                    this.registro = rastreador;
                } else {
                    this.registro = {};
                }
            }
        },

        computed: {},
        methods: {

            salvar: function () {

                var self = this;

                this.operacao = 'salvar';

                this.doPost('frota/rastreadores/salvar', this.registro)
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
                        self.doPost('frota/rastreadores/excluir', self.registro)
                            .then(function () {
                                self.alertSucesso('Registro excluído com sucesso');

                                this.$emit('deleted', self.registro);

                            });
                    }

                    self.limpar();
                })

            },

            limpar: function () {

                this.registro = {};
                this.validationResponse = {};

            },

        }
    }
</script>

<style scoped>

</style>
