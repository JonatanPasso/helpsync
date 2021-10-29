<template>
    <select v-bind="$attrs"
            v-model="sel"
            @change="$emit('input',sel)">
        <option value="">Selecionar</option>
        <option v-for="v in opcoes" :value="v">{{v}}</option>
    </select>
</template>

<script>
    export default {
        inheritAttrs: false,
        name: "SelectTipoContrato",
        props: ['value'],
        data() {
            return {
                opcoes: [
                    'Locação',
                    'Intermitente',
                    'Mercantis',
                    'Administrativos',
                    'Licitatório',
                    'Consumo',
                    'Pré Pago',
                ],
                sel: ''
            }
        },
        watch: {
            value() {
                this.sel = this.value;
            }
        }
    }
</script>

<style scoped>

</style>
