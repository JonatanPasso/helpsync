<template>
    <div>
        <Form :categoria="registro"></Form>
        <Grid @select="registro=$event"></Grid>
    </div>
</template>

<script>

    import Form from './Form';
    import Grid from './Grid';

    export default {
        name: "Tela",
        components: {Form, Grid},
        data() {
            return {
                registro: null
            }
        }
    }
</script>

<style scoped>

</style>
