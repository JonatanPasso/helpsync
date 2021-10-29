<template>
    <div>

        <select :class="errors ? 'is-invalid':''"
                v-bind="l_.extend($attrs,l_.get(config,'attribs'))"
                v-model="aux_selecao" class="form-control">
            <option value="">Selecionar</option>
            <option v-for="i in modelos">{{i}}</option>
        </select>
        <div class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>
    </div>
</template>

<script>

    export default {
        inheritAttrs: false,
        name: 'InputModelosRastreador',
        created: function () {

        },
        data: function () {
            return {
                modelos: require('./../ModelosRastreador'),
                aux_selecao: null
            }
        },
        props: {
            errors: {
                default: null
            },
            config: {
                default: {}
            },
            value: {
                default: ''
            }
        },

        watch: {
            aux_selecao: function (sel) {

                if (sel) {
                    this.$emit('input', sel);
                }
            },
            value(v) {
                this.aux_selecao = v;
            }
        },

        methods: {}
    }
</script>

