<template>
    <img class="img-profile rounded-circle"
         :src="urlFoto">
</template>

<script>
import UrlFoto from "@/assets/tema/fluxo/usuario-unisex.png";

export default {
    name: "ImgProfile",
    props: {
        usuario: {
            type: Object,
            default() {
                return {}
            }
        }
    },
    computed: {
        urlFoto() {
            let url = _.get(this, 'usuario.url_foto');

            return _.isEmpty(url) ?
                UrlFoto : this.usuario.url_foto;
        }
    }
}
</script>

<style scoped>

</style>
