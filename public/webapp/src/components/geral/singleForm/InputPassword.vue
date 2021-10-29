<template>

    <div class="input-group">

        <span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-key"></i> </span></span>

        <input type="password"
               :class="errors ? 'is-invalid':''"
               v-bind="l_.extend($attrs,config.attribs)"
               v-on:input="$emit('input', $event.target.value)" class="form-control">

        <div v-if="errors" class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>
    </div>

</template>

<script>
    export default {
        inheritAttrs: false,
        name: "InputPassword",
        props: ['input', 'config', 'errors']
    }
</script>

<style scoped>

</style>
