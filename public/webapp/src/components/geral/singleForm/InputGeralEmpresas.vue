<template>
    <div>

        <select type="text"
                v-bind="$attrs"
                :class="errors ? 'is-invalid':''"
                v-model="selValue"
                :disabled="disabled"
                class="form-control">
            <option value="">{{ ajaxStatus ? 'Carregando...' : 'Selecionar Empresa'}}</option>
            <option v-for="c in clientes" :value="c">{{c.nome}}</option>
        </select>
        <div class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        name: "InputGeralClientes",
        props: ['input', 'config', 'errors', 'value', 'id_only', 'disabled'],
        data() {
            return {
                clientes: [],
                selValue: ""
            }
        },
        created() {

            this.doGet('geral/clientes/buscar', {paginacao: false})
                .dthen('clientes');
        },
        watch: {
            selValue() {
                if (this.id_only) {
                    this.$emit('input', _.get(this, 'selValue.id', ''));
                } else {
                    this.$emit('input', this.selValue);
                }
            },
            value(valor) {

                if (this.id_only) {

                    var aux = _.find(this.clientes, {id: valor});

                    this.selValue = aux ? aux : '';

                } else {
                    this.selValue = valor ? valor : '';
                }
            }
        }
    }
</script>

<style scoped>

</style>
