<template>
    <div>
        <select v-model="selectedValue"
                v-bind="l_.extend($attrs,l_.get(config,'attribs'))"
                class="form-control"
                :class="errors ? 'is-invalid':''">
            <option value="">Selecionar</option>
            <option v-for="c in dados" :value="c.nome">{{c.nome}}</option>
        </select>
        <div class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>
    </div>
</template>
<script>

    import CidadeEstadosJson from './../../../assets/cidades-estados-brasil'

    export default {
        inheritAttrs: false,
        name: 'InputEstados',
        created: function () {
            this.selectedValue = this.value;
        },
        data: function () {
            return {
                dados: CidadeEstadosJson.estados,
                selectedValue: ''
            }
        },
        props: {
            config: {
                default: function(){
                    return {}
                }
            },
            estado: {
                default: '',
            },
            value: {
                default: ''
            },
            errors: {}
        },
        watch: {
            selectedValue: function (new_, old) {
                this.$emit('input', this.selectedValue);
            },
            estado: function () {
                this.selectedValue = '';
            },
            value: function (estado) {
                this.selectedValue = estado;
            }
        }
    }

</script>

