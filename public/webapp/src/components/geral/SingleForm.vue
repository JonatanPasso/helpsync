<template>

    <div :class="verticalForm ? '' : 'row'">

        <div class="form-group"
             :class="c.bclass ? c.bclass : 'col-md-4'"
             v-for="c in schemaCmpt">

            <label>{{c.label}}</label>

            <component
                    :is="c.type ? c.type : 'input-text' "
                    :config="c"
                    :errors="l_.get(errors,c.name)"
                    v-bind="configProps(c)"
                    v-model="dataModel[c.name]"></component>

        </div>

    </div>


</template>

<script>


    import InputTextarea from "./singleForm/InputTextarea";
    import InputText from "./singleForm/InputText";
    import InputSelect from "./singleForm/InputSelect";
    import InputCpfCnpj from "./singleForm/InputCpfCnpj";
    import InputCep from "./singleForm/InputCep";
    import InputCidades from "./singleForm/InputCidades";
    import InputEstados from "./singleForm/InputEstados";
    import InputDate from "./singleForm/InputDate";
    import InputFone from "./singleForm/InputFone";
    import InputEmail from "./singleForm/InputEmail";
    import InputPassword from "./singleForm/InputPassword";
    import InputGeralEmpresas from "./singleForm/InputGeralEmpresas";
    import InputMarcas from "../frota/singleForm/InputMarcas";
    import InputModelos from "../frota/singleForm/InputModelos";
    import InputRastreador from "../frota/singleForm/InputRastreador";
    import InputTipoVeiculos from "../frota/singleForm/InputTipoVeiculos";
    import InputVeiculos from "../frota/singleForm/InputVeiculos";
    import InputMask from "./singleForm/InputMask";
    import InputModelosRastreador from "../frota/singleForm/InputModelosRastreador";

    export default {

        name: "SingleForm",
        components: {
            InputTextarea,
            InputEmail,
            InputFone,
            InputDate,
            InputText,
            InputSelect,
            InputCpfCnpj,
            InputCep,
            InputCidades,
            InputEstados,
            InputPassword,
            InputGeralEmpresas,
            InputMarcas,
            InputModelos,
            InputRastreador,
            InputTipoVeiculos,
            InputVeiculos,
            InputMask,
            InputModelosRastreador

        },

        props: ['scheme', 'model', 'errors', 'verticalForm'],

        computed: {
            schemaCmpt: function () {
                return this.scheme
            }
        },

        created() {
            this.dataModel = this.model;
        },

        watch: {
            model: {
                deep: true,
                handler() {
                    this.dataModel = this.model;
                }
            },
            dataModel: {
                deep: true,
                handler() {
                    this.$emit('updateModel', this.dataModel)
                }
            }
        },
        data() {
            return {
                dataModel: {}
            }

        },
        methods: {
            configProps(c) {
                if (c.bind) {
                    var aux = {};

                    if (_.isArray(c.bind)) {
                        for (var i in c.bind) {
                            var keyBind = c.bind[i];
                            aux[keyBind] = _.get(this, `dataModel.${keyBind}`);
                        }
                    } else {
                        for (var i in c.bind) {
                            aux[i] = c.bind[i];
                        }
                    }
                    return aux;
                }
            },
            getClass(c) {

                switch (c.type) {

                    case 'input-date':
                    case 'input-fone':
                    case 'input-email':
                        return '';
                    default:
                        return 'form-control';
                }
            }
        }
    }
</script>

<style scoped>

</style>
