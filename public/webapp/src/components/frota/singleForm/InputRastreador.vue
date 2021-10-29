<template>
    <v-select v-model="aux_sel_rastreador" v-on:input="teste" :options="rastreadoresCmpt"></v-select>
</template>

<script>

    export default {

        name: 'inputRastreador',

        created: function () {

            this.doGet('frota/rastreadores/buscar', {paginacao: false})
                .dthen('rastreadores');
        },
        data: function () {
            return {
                rastreadores: [],
                aux_sel_rastreador: null
            }
        },
        props: {
            estado: {
                default: '',
            },
            value: {
                default: ''
            }
        },

        computed: {

            rastreadoresCmpt: function () {

                var aux = [{
                    label: 'Nenhum Rastreador',
                    value: null
                }];

                for (var i in this.rastreadores) {
                    aux.push({
                        label: this.rastreadores[i].modelo + ' - ' + this.rastreadores[i].esn,
                        value: this.rastreadores[i].esn
                    });
                }

                return aux;

            }
        },

        watch: {
            aux_sel_rastreador: function (sel) {

            },
            value: function (valor) {
                this.selelionar();
            }
        },

        methods: {

            teste: function (sel) {
                this.$emit('input', sel ? sel.value : null);
            },
            selelionar: function () {

                for (var i in this.rastreadoresCmpt) {

                    if (this.rastreadoresCmpt[i].value == this.value) {
                        this.aux_sel_rastreador = this.rastreadoresCmpt[i].value;
                        return
                    }
                }

                this.aux_sel_rastreador = this.rastreadoresCmpt[0];

            }
        }
    }

</script>
