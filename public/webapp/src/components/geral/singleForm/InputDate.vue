<template>

    <div class="input-group">

        <date-picker :bootstrap-styling="true"
                     :config="getDatePickerConfig"
                     v-mask="'##/##/####'"
                     v-model="modelValue"
                     :class="errors ? 'is-invalid':''"
                     v-bind="l_.extend($attrs,config.attribs)"></date-picker>
        <span class="input-group-append"
              onclick="$(this).prev().focus();">
            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
        </span>

        <div class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>

    </div>

</template>

<script>
    export default {
        name: "InputDate",
        props: ['input', 'config', 'value', 'errors'],
        data() {
            return {
                modelValue: null,

                /**
                 * Default options for datepicker
                 */
                defaultDateTimeConfig: {
                    format: 'DD/MM/YYYY',
                    // maxDate:moment(),
                    // minDate:moment()
                }
            }
        },
        created() {
            this.modelValue = this.value;
        },
        watch: {
            modelValue() {
                this.$emit('input', this.modelValue);
            },
            value() {
                this.modelValue = this.value;
            }
        },
        computed: {

            /**
             * Join default config with custom config
             * @returns {Object}
             */
            getDatePickerConfig() {

                var aux = _.extend(this.defaultDateTimeConfig, _.get(this, 'config.attribs.datePickerConfig'));

                return _.clone(aux)
            }
        }
    }
</script>

<style scoped>
    .input-group-append {
        cursor: pointer;
    }
</style>
