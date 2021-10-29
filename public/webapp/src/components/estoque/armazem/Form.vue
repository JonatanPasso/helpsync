<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-edit"></i> Cadastro de Armazem</span>
        </template>

        <b-row>
            <b-col v-for="c in form"
                   v-bind="c.size"
                   :key="c.name">
                <!--                {{c}}-->
                <b-form-group :label="c.label"
                              :key="c.name">
                    <component
                        :is="c.type"
                        v-model="registro[c.name]"
                        v-bind="c.attr"></component>
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
        <!--        {{registro}}-->
    </b-card>
</template>

<script>
export default {
    name: "Form",
    props: ['armazem'],

    data() {
        return {
            registro: {
                id: '',
                estoque_id: '',
                nome: '',
                criado_por: '',
                criado_em: '',

                /**
                 * Auxiliar fieds
                 * */
                cliente_id: ''
            },

            estoques: [],
            clientes: []
        }
    },
    computed: {
        form() {
            return [
                {
                    name: 'id',
                    label: 'Código',
                    type: 'b-form-input',
                    size: {md: 2},
                    attr: {
                        disabled: true,
                        placeholder: 'Auto'
                    }
                },

                {
                    name: 'cliente_id',
                    label: 'Empresa',
                    type: 'b-form-select',
                    size: {md: 5},
                    attr: {
                        disabled: this.registro.id != '',
                        options: this.clientes.map(i => ({text: i.nome, value: i.id}))
                    }
                },

                {
                    name: 'estoque_id',
                    label: 'Estoque',
                    type: 'b-form-select',
                    size: {md: 5},
                    attr: {
                        disabled: this.registro.id != '',
                        options: this.estoques.map(i => ({text: i.nome, value: i.id}))
                    }
                },

                {
                    name: 'nome',
                    label: 'Nome',
                    type: 'b-form-input',
                    size: {md: 12},
                    attr: {
                        options: []
                    }
                },
            ].map(i => {
                i.attr.state = this.validationResponse[i.name] ? false : undefined
                i.erros = this.validationResponse[i.name] ? this.validationResponse[i.name].join('') : ''
                return i;
            })
        },
    },

    watch: {
        armazem: {
            deep: true,
            handler(value) {
                if (value) {
                    this.registro = value;
                    this.registro.cliente_id = value.estoque.cliente_id;
                } else {
                    this.registro = this.$options.data().registro;
                }
            }
        },
        'registro.cliente_id'() {
            this.carregarEstoques();
        },
    },

    created() {

        this.carregarEmpresas();

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
                console.log(r);
                this.estoques = r;
            });
        },

        salvar() {
            this.doPost('estoque/armazem/salvar', this.registro)
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
                    this.doPost('estoque/armazem/excluir', this.registro)
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
