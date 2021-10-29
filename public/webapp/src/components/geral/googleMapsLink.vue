<template>
    <a target="_blank"
       :href="`https://maps.google.com/?q=${lat},${lng}`">
        <slot></slot>
    </a>
</template>

<script>
    export default {
        name: "googleMapsLink",
        props: ['lat', 'lng']
    }
</script>

<style scoped>

</style>
