<template>
    <b-card class="shadow-lg">
        <template #header>
            <i class="fa fa-file-archive"></i> Meus Arquivos

        </template>

        <b-modal id="teste"
                 ok-only
                 :title="l_.get(file,'metadata.originalName')">

            <div v-if="file">

                <b-card :img-src="file.thumb_url" img-width="200px" no-body
                        img-alt="Card image" img-left class="mb-3">

                    <b-card-text>
                        <table class="table table-borderless table-sm text-sm-right">
                            <tr>
                                <td>NOME</td>
                                <td>{{file.metadata.originalName}}</td>
                            </tr>

                            <tr>
                                <td>TIPO</td>
                                <td>{{file.metadata.mimeType}}</td>
                            </tr>

                            <tr>
                                <td>TAMANHO</td>
                                <td>{{file.metadata.size | prettyBytes}}</td>
                            </tr>

                            <tr>
                                <td>CRIADO EM</td>
                                <td>{{file.metadata.created_at | dateTimeFormat('DD/MM/YYYYY HH:mm')}}</td>
                            </tr>
                            <tr>
                                <td>ENVIADO POR</td>
                                <td>{{file.created_by.nome}}</td>
                            </tr>

                        </table>
                    </b-card-text>
                </b-card>


            </div>

        </b-modal>


        <b-row>
            <b-col md="3" lg="4"
                   v-for="f in arquivos.data" :key="f.id">
                <b-card class="overflow-hidden text-center" no-body>

                    <b-link @click="modalInfo(f)">
                        <b-img-lazy thumbnail
                                    class="m-2"
                                    style="height: 20vh"
                                    :src="`${f.thumb_url}`"></b-img-lazy>
                    </b-link>

                    <template #footer>

                        <b-link tag="a"
                                style="font-size: 14px"
                                target="_blank"
                                class="text-nowrap"
                                :title="f.original_name"
                                variant="primary"
                                :href="`${f.url}`">
                            <i class="fa fa-file"></i> {{f.original_name | limite }}
                        </b-link>

                    </template>

                </b-card>
            </b-col>
        </b-row>


    </b-card>
</template>

<script>


    export default {
        name: "MeusArquivos",

        data() {
            return {
                arquivos: [],
                file: null
            }
        },
        methods: {
            buscarArquivos() {

                this.doGet('geral/file-storage/buscar')
                    .then(list => this.arquivos = list)
            },
            modalInfo(file) {
                this.file = file;
                this.$bvModal.show('teste')
            }
        },

        watch: {},

        created() {
            this.buscarArquivos();
        },

        filters: {
            limite(value) {
                if (value.length > 40) {
                    return value.substr(0, 40) + '...'
                }
                return value;
            }
        }
    }
</script>

<style scoped>

</style>
