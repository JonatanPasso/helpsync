<template>
    <img class="img-profile rounded-circle"
         :src="urlFoto">
</template>

<script>
import UrlFoto from "@/assets/tema/fluxo/Logo-Empresa-sem-imagem.png";

export default {
    name: "ImgProfile",
    props: {
        cliente: {
            type: Object,
            default() {
                return {}
            }
        }
    },
    computed: {
        urlFoto() {

            let url = _.get(this, 'cliente.url_foto');

            return _.isEmpty(url) ?
                UrlFoto : this.cliente.url_foto;
        }
    }
}
</script>

<style scoped>

</style>
