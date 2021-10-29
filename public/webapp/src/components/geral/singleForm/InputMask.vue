<template>
    <div>
        <the-mask
            :class="errors ? 'is-invalid':''"
            v-bind="l_.extend($attrs,l_.get(config,'attribs'))"
            v-on:input="$emit('input', $event)" class="form-control">
            <div class="invalid-feedback">
                <div v-for="e in errors">{{e}}</div>
            </div>
        </the-mask>
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        name: "InputMask",
        props: ['input', 'config', 'errors']
    }
</script>

<style scoped>

</style>
