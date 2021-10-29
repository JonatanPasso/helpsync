<template>
    <div>
        <select type="text"
                :class="errors ? 'is-invalid':''"
                v-bind="l_.extend($attrs,config.attribs)"
                v-on:input="$emit('input', $event.target.value)" class="form-control">
            <option value="">Selecionar</option>
            <option v-for="c in config.options">{{c}}</option>
        </select>
        <div class="invalid-feedback">
            <div v-for="e in errors">{{e}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InputText",
        props: ['input', 'config', 'errors']
    }
</script>

<style scoped>

</style>
