<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-edit"></i> Cadastro de Unidade de Medida</span>
        </template>

        <b-row>
            <b-col v-for="(c,id) in form"
                   :cols="c.cols"
                   :key="id">
                <!--                {{c}}-->
                <b-form-group :label="c.label" :key="id">
                    <component
                        v-model="registro[id]"
                        v-bind="c"></component>
                    <b-form-invalid-feedback :state="c.state">
                        {{ c.erros }}
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-col>
        </b-row>

        <template #footer>
            <b-button-group>
                <b-button
                    @click="salvar"
                    variant="success"><i class="fa fa-save"></i> SALVAR
                </b-button>
                <b-button
                    :disabled="!registro.id"
                    @click="excluir"
                    variant="danger"><i class="fa fa-times"></i> EXCLUIR
                </b-button>
                <b-button
                    @click="limpar"
                    variant="primary"><i class="fa fa-recycle"></i> LIMPAR
                </b-button>
            </b-button-group>
        </template>
    </b-card>
</template>

<script>

export default {
    name: "Form",
    props: ['unidade'],

    data() {
        return {
            registro: {
                id: '',
                descricao: '',
                unidade: ''
            }
        }
    },
    computed: {
        form() {
            return _.mapValues({
                id:{is: 'b-form-input', label: 'Código', disabled: true, cols: 2, placeholder: 'auto'},
                unidade: {is: 'b-form-input', label: 'Unidade', cols: 3},
                descricao: {is: 'b-form-input', label: 'Descrição', cols: 7},
            }, (item, key, object) => {
                let erros = _.get(this.validationResponse, key);
                if (erros) {
                    item.state = false;
                    item.erros = erros.join();
                }
                return item;
            });
        },
    },

    watch: {
        unidade: {
            deep: true,
            handler(value) {
                if (value) {
                    this.registro = value;
                } else {
                    this.registro = this.$options.data().registro;
                }
            }
        }
    },

    created() {

        /**
         * Carregar fabricantes
         */

    },
    methods: {


        salvar() {
            this.doPost('estoque/unidades/salvar', this.registro)
                .then(r => {
                    this.$bvToast.toast("Registro salvo",
                        {variant: 'success'});
                    this.$emit('update', _.clone(r));
                    this.registro = r;
                })
        },
        excluir() {
            this.confirmar(opt => {
                if (opt) {
                    this.doPost('estoque/unidades/excluir', this.registro)
                        .then(() => {
                            this.$bvToast.toast("Registro excluído",
                                {variant: 'success'});
                            this.limpar();
                            this.$emit('update');
                        })
                }
            }, 'Confirmar exclusão de registro?')
        },
        limpar() {
            this.registro = this.$options.data().registro;
        }
    }
}
</script>

<style scoped>

</style>
