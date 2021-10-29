<template>
    <div class="ajusar-container">
        <iframe class="ajustar-email" src="https://illuminarti.com.br/webmail/"></iframe>
    </div>
</template>

<script>


    import Card from "./../Card";
    import GridEmpresas from "./../GridEmpresas";
    import SingleForm from "../SingleForm";

    export default {
        name: 'Webmail',
        components: {SingleForm, GridEmpresas, Card}
    };


</script>

<style>

    .ajustar-email {
        border: none;
        width: 100%;
        min-height: 90vh;
    }

    .ajusar-container {
        margin: -24px;
    }
</style>
