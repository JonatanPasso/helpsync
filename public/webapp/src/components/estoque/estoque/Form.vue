<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-edit"></i> Cadastro de Estoques</span>
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

import Vue from 'vue';

export default {
    name: "Form",
    props: ['estoque'],

    data() {
        return {
            registro: {
                id: '',
                cliente_id: '',
                nome: '',
                descricao: '',
                criado_por: '',
                criado_em: '',
            },
            clientes: [{
                text: 'Nenhum',
                value: null,
            }]
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
                    size: {md: 4},
                    attr: {
                        disabled: this.registro.id != '',
                        options: this.clientes.map(i => ({
                            value: i.id,
                            text: `${i.nome}`
                        }))
                    }
                },

                {
                    name: 'nome',
                    label: 'Nome',
                    type: 'b-form-input',
                    size: {md: 6},
                    attr: {
                        placeholder: 'Nome do Estoque'
                    }
                },

                {
                    name: 'descricao',
                    label: 'Descrição',
                    type: 'b-form-textarea',
                    size: {md: 12},
                    attr: {
                        placeholder: 'Descrição do estoque'
                    }
                },
            ].map(i => {
                i.attr.state = this.validationResponse[i.name] ? false : undefined;
                i.erros = this.validationResponse[i.name] ? this.validationResponse[i.name].join('') : ''
                return i;
            })
        },
    },

    watch: {
        estoque: {
            deep: true,
            handler(value) {
                if (value) {
                    this.registro = value;
                }
            }
        },
    },

    created() {

        this.carregarClientes();
    },
    methods: {

        /**
         * Carregar fabricantes
         */
        carregarClientes() {

            console.log('000')
            this.doGet('geral/clientes/buscar', {
                paginacao: false,
                filtro: {
                    usuario_id: this.$root.USER.id
                }
            }).then(r => this.clientes = r);

        },
        salvar() {
            this.doPost('estoque/estoque/salvar', this.registro)
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
                    this.doPost('estoque/estoque/excluir', this.registro)
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
