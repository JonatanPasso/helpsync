<template>

    <div>

        <select v-if="ajaxStatus"
                class="form-control">
            <option>Carregando...</option>
        </select>

        <v-select v-else
                  v-model="aux_selecao"
                  :options="registrosCmpt"></v-select>

        <div class="invalid-feedback" :style="errors ? 'display: block':''">
            <div v-for="e in errors">{{e}}</div>
        </div>

    </div>

</template>

<script>

    export default {
        name: 'InputVeiculos',
        created: function () {

            this.carregarVeiculos();
        },
        data: function () {
            return {
                registros: [],
                aux_selecao: null,
                clienteId: null
            }
        },
        props: {
            value: {
                default: ''
            },
            cliente_id: {
                default: ''
            },
            errors: {
                default: null
            }
        },

        computed: {
            registrosCmpt: function () {

                var aux = [{
                    label: 'Nenhum',
                    value: null
                }];

                for (var i in this.registros) {
                    aux.push({
                        label: this.registros[i].placa,
                        value: this.registros[i].id
                    });
                }

                return aux;

            }
        },

        watch: {
            aux_selecao: function (sel) {
                if (sel)
                    this.$emit('input', sel ? sel.value : null);
            },
            value: function (valor) {

                for (var i in this.registrosCmpt) {
                    if (this.registrosCmpt[i].value == valor) {
                        this.aux_selecao = this.registrosCmpt[i];
                        return;
                    }
                }

                this.aux_selecao = null
            },
            cliente_id(value) {

                if (_.isObject(value)) {
                    this.clienteId = value.id;
                } else {
                    this.clienteId = value
                }

                this.carregarVeiculos();
            }
        },

        methods: {
            carregarVeiculos() {

                this.doGet('frota/veiculos/buscar', {
                    paginacao: false,
                    last_log: 'nao',
                    filtro: {
                        cliente_id: this.clienteId
                    }
                }).dthen('registros');
            }
        }
    }
</script>
