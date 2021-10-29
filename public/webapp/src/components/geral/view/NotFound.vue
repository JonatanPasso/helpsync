<template>
    <Card :footer="false">
        <template #header>
           <i class="fa fa-fire-alt"></i> Ops! Algo deu errado.
        </template>
        <template #default>
            <h1 class="text-danger">
                <i class="fa fa-exclamation-triangle fa-2x"></i>
                Página não encontrada
            </h1>
        </template>
    </Card>
</template>

<script>


    import Card from "./../Card";
    import GridEmpresas from "./../GridEmpresas";
    import SingleForm from "../SingleForm";

    export default {
        name: 'NotFound',
        components: {SingleForm, GridEmpresas, Card}
    };


</script>
