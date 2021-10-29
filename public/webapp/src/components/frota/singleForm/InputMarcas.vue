<template>

    <b-select v-model="aux_selecao"
              :options="registrosCmpt"></b-select>

</template>

<script>


    export default {
        name: 'inputMarcas',
        created: function () {

            this.doGet('frota/marcas/buscar', {paginacao: false})
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
                    text: 'Nenhum',
                    value: null
                }];

                for (var i in this.registros) {
                    aux.push({
                        text: this.registros[i].nome,
                        value: this.registros[i].id
                    });
                }

                return aux;

            }
        },

        watch: {
            aux_selecao: function (sel) {

                if (sel)
                    this.$emit('input', sel ? sel : null);
            },
            value: function (valor) {

                // for (var i in this.registrosCmpt) {
                //     if (this.registrosCmpt[i].value == valor) {
                //         this.aux_selecao = this.registrosCmpt[i];
                //         return;
                //     }
                // }

                this.aux_selecao = valor
            }
        }
    };

</script>
