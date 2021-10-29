<template>

    <div>
        <the-mask class="form-control"
                  v-bind="l_.extend($attrs,config.attribs)"
                  v-model="rawValue"
                  :class="errors ? 'is-invalid':''"
                  :mask="['###.###.###-##', '##.###.###/####-##']">
        </the-mask>

        <div v-if="errors" class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InputCpfCnpj",
        props: ['input', 'config', 'errors', 'value'],
        data() {
            return {
                rawValue: ''
            }
        },

        watch: {
            rawValue() {
                this.$emit('input', this.rawValue);
            },
            value(valor) {
                this.rawValue = valor;
            }
        }
    }
</script>

<style scoped>

</style>
