<template>
    <div>
        <select v-model="selectedValue"
                v-bind="l_.extend($attrs,l_.get(config,'attribs'))"
                class="form-control"
                :class="errors ? ' ':''">
            <option value="">Selecionar</option>
            <option v-for="c in cidades">{{c}}</option>
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
        name: 'InputCidades',
        template: '',
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
                default() {
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
        computed: {
            cidades: function () {
                if (this.estado) {
                    for (var i in this.dados) {
                        if (this.dados[i].sigla.toLowerCase() == this.estado.toLowerCase() ||
                            this.dados[i].nome.toLowerCase() == this.estado.toLowerCase()) {
                            return this.dados[i].cidades;
                        }
                    }
                }
                return []
            }
        },
        watch: {
            selectedValue: function (new_, old) {
                this.$emit('input', this.selectedValue);
            },
            estado: function (value) {

                // console.log(value)
                // this.selectedValue = '';
            },
            value: function (cidade) {

                this.selectedValue = cidade;
            }
        }
    }
</script>
