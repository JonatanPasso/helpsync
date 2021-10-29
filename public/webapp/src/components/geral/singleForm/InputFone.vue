<template>
    <div class="input-group">
        <the-mask class="form-control"
                  v-bind="l_.extend($attrs,config.attribs)"
                  :class="errors ? 'is-invalid':''"
                  v-model="rawValue"
                  :mask="['(##)#####-####','(##)####-####']">
        </the-mask>
        <span class="input-group-append"><span class="input-group-text"><i class="fa fa-phone"></i> </span></span>

        <div v-if="errors" class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>

    </div>

</template>

<script>
    export default {
        name: "InputFone",
        props: ['input', 'config','errors','value'],
        data() {
            return {
                rawValue: ''
            }
        },
        watch: {
            rawValue() {
                this.$emit('input', this.rawValue);
            },
            value: function (valor) {
                this.rawValue = valor;
            }
        }
    }
</script>

<style scoped>

</style>
