<template>
    <b-row>
        <b-col v-for="c in form"
               v-bind="c.size"
               :key="c.name">

            <!--                {{c}}-->
            <b-form-group :label="c.label"
                          :key="c.name">
                <component
                    :is="c.type"
                    :disabled="disabled"
                    v-model="registro[c.name]"
                    v-bind="c.attr"></component>
                <b-form-invalid-feedback :state="c.state">
                    {{ c.erros }}
                </b-form-invalid-feedback>
            </b-form-group>
        </b-col>
    </b-row>
</template>

<script>

function defaultData() {
    return {
        estoque_id: '',
        armazem_id: '',
        cliente_id: '',
    }
}

export default {
    name: "SubFormArmazem",

    data() {
        return {
            registro: defaultData(),

            armazens: [],
            estoques: [],
            clientes: [],
        }
    },

    props: {
        value: {
            type: Object,
            default() {
                return defaultData();
            }
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    watch: {

        registro: {
            deep: true,
            handler(dados) {
                this.$emit('input', dados)
            }
        },

        value(value) {
            this.registro = value;
        },

        'registro.cliente_id'() {
            this.carregarEstoques();
        },
        'registro.estoque_id'() {
            this.carregarArmazens();
        }
    },

    computed: {
        form() {
            return [

                {
                    name: 'cliente_id',
                    label: 'Empresa',
                    type: 'b-form-select',
                    size: {md: 6},
                    attr: {
                        options: this.clientes.map(i => ({text: i.nome, value: i.id}))
                    }
                },

                {
                    name: 'estoque_id',
                    label: 'Estoque',
                    type: 'b-form-select',
                    size: {md: 6},
                    attr: {
                        options: this.estoques.map(i => ({text: i.nome, value: i.id}))
                    }
                },

                {
                    name: 'armazem_id',
                    label: 'Armazem',
                    type: 'b-form-select',
                    size: {md: 12},
                    attr: {
                        options: this.armazens.map(i => ({text: i.nome, value: i.id}))
                    }
                },

            ].map(i => {
                i.attr.state = this.validationResponse[i.name] ? false : undefined
                i.erros = this.validationResponse[i.name] ? this.validationResponse[i.name].join('') : ''
                return i;
            })
        },
    },

    methods: {
        /**
         * Carregar fabricantes
         */
        carregarEmpresas() {

            this.doGet('geral/clientes/buscar', {
                paginacao: false,
                filtro: {}
            }).then(r => {
                this.clientes = r;
            });
        },

        /**
         * Carregar Estoques
         */
        carregarEstoques() {

            this.doGet('estoque/estoque/buscar', {
                paginacao: false,
                filtro: {
                    cliente_id: this.registro.cliente_id
                }
            }).then(r => {
                this.estoques = r;
            });
        },

        /**
         * Carregar Armazens
         */
        carregarArmazens() {

            this.doGet('estoque/armazem/buscar', {
                paginacao: false,
                filtro: {
                    estoque_id: this.registro.estoque_id
                }
            }).then(r => {
                this.armazens = r;
            });
        },
    },

    created() {
        this.registro = this.value;
        this.carregarEmpresas();
    }
}
</script>

<style scoped>

</style>
