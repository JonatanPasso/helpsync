<template>
    <v-select v-model="aux_selecao" :options="registrosCmpt"></v-select>
</template>

<script>

    export default {
        name: 'InputModelos',
        created: function () {

        },
        data: function () {
            return {
                registros: [],
                aux_selecao: null
            }
        },
        props: {
            marca_id: {
                default: '',
            },
            value: {
                default: ''
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
                        label: this.registros[i].nome,
                        value: this.registros[i].id
                    });
                }

                return aux;

            }
        },

        watch: {
            aux_selecao: function (sel) {
                if (sel) {
                    this.$emit('input', sel ? sel.value : '');
                }
            },
            marca_id: function (valor) {
                if (valor)
                    this.doGet('frota/modelos/buscar', {paginacao: false, marca_id: valor})
                        .then(function (reg) {
                            this.registros = reg;
                            this.selelionar();
                        });
                else this.registros = [];

                this.aux_selecao = '';
            },

            value: function (valor) {
// console.log('value',valor);
// this.selelionar();
            }
        },

        methods: {
            selelionar: function () {
                for (var i in this.registrosCmpt) {
                    if (this.registrosCmpt[i].value == this.value) {
                        this.aux_selecao = this.registrosCmpt[i];
                        return;
                    }
                }
                this.aux_selecao = this.registrosCmpt[0];
            }
        }
    }
</script>

