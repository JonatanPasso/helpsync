<template>
    <v-select v-model="aux_selecao" :options="registrosCmpt"></v-select>
</template>

<script>
    export default {
        name: 'InputTipoVeiculos',
        created: function () {

            this.doGet('frota/tipo-veiculos/buscar', {paginacao: false})
                .dthen('registros');
        },
        data: function () {
            return {
                registros: [],
                aux_selecao: null
            }
        },
        props: {
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
                        label: this.registros[i].descricao,
                        value: this.registros[i].id
                    });
                }

                return aux;

            }
        },

        watch: {
            aux_selecao: function (sel) {
                if (sel) this.$emit('input', sel ? sel.value : null);
            },
            value: function () {
                for (var i in this.registrosCmpt) {
                    if (this.registrosCmpt[i].value == this.value) {
                        this.aux_selecao = this.registrosCmpt[i];
                        return;
                    }
                }

                this.aux_selecao = null
            }
        }
    };

</script>
