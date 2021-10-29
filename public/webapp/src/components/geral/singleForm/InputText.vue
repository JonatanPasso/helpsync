<template>
    <div>
        <input type="text"
               :class="errors ? 'is-invalid':''"
               v-bind="l_.extend($attrs,l_.get(config,'attribs'))"
               v-on:input="$emit('input', $event.target.value)" class="form-control">
        <div class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        name: "InputText",
        props: ['input', 'config', 'errors']
    }
</script>

<style scoped>

</style>
