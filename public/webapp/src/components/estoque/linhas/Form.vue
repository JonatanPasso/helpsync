<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-edit"></i> Cadastro de Linhas de Produtos</span>
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

import methods from "@/components/estoque/Estoque";

export default {
    name: "Form",
    props: ['linha'],

    extends: {
        methods: methods
    },

    data() {
        return {
            registro: {
                id: '',
                nome: '',
                marca_id: null
            },
            marcas: []
        }
    },
    computed: {
        form() {
            return _.mapValues({
                id: {
                    is: 'b-form-input',
                    label: 'Código',
                    placeholder: 'auto',
                    disabled: true, cols: 2
                },
                marca_id: {
                    is: 'b-form-select',
                    label: 'Marca',
                    cols: 4,
                    options: this.marcas.map(i => ({text: i.nome, value: i.id}))
                },
                nome: {is: 'b-form-input', label: 'Linha', cols: 6},
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
        linha: {
            deep: true,
            handler(value) {
                if (value) {
                    this.registro = this.linha;
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
        this.getMarcas().then(r => this.marcas = r);
    },
    methods: {


        salvar() {
            this.doPost('estoque/linhas/salvar', this.registro)
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
                    this.doPost('estoque/linhas/excluir', this.registro)
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
            this.validationResponse = {};
            this.registro = this.$options.data().registro;
        }
    }
}
</script>

<style scoped>

</style>
