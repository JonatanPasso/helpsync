<template>
<span>
    <i class="fa fa-2x" :class="icone"></i>
</span>
</template>

<script>

    /**
     * Componente padrao pra icone de arquivo
     * <f-icone mime="meime"></f-icone>
     * @type {{mime: string}}
     */

    var mapaIconeMime = {
        'application/pdf': 'fa-file-pdf',
        'application/vnd.ms-excel': 'fa-file-excel',
        'application/excel': 'fa-file-excel',
        'application/x-excel': 'fa-file-excel',
        'application/x-msexcel': 'fa-file-excel',
        'application/vnd.ms-excel': 'fa-file-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fa-file-excel',
        'application/msword': 'fa-file-word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fa-file-word',
        'application/vnd.ms-powerpoint': 'fa-file-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'fa-file-powerpoint',
        'text/plain': 'fa-file-csv',
        'image/png': 'fa-file-image',
        'image/jpeg': 'fa-file-image',
        'application/vnd.rar': 'fa-file-archive',
        'application/zip': 'fa-file-archive',
        'application/x-7z-compressed': 'fa-file-archive',

    };

    export default {
        name: "Ficone",
        props: ['mime'],
        computed: {
            icone() {
                return _.get(mapaIconeMime, this.mime, 'fa-paperclip');
            }
        }
    }
</script>

<style scoped>

</style>
