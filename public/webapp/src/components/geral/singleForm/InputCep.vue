<template>

    <div>

        <input class="form-control"
               type="number"
               v-bind="l_.extend($attrs,l_.get(config,'attribs'))"
               :class="errors ? 'is-invalid':''"
               v-model="rawValue">
        <div class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "InputCep",
        props: ['input', 'config', 'errors', 'value'],
        data() {
            return {
                rawValue: ''
            }
        },
        created() {
            this.rawValue = this.value;
        },
        watch: {
            rawValue() {
                this.$emit('input', this.rawValue);
            },
            value() {
                this.rawValue = this.value;
            }
        }
    }
</script>

<style scoped>

</style>
