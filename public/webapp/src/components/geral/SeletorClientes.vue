<template>
    <div>

        <a class="nav-link dropdown-toggle"
           href="#" id="messagesDropdown" role="button"
           :title="l_.get(CLIENTE,'nome','')"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            <ImgProfile class="mr-1" :cliente="CLIENTE"></ImgProfile>
            {{ l_.get(CLIENTE, 'nome', '') | wfirst }}

        </a>
        <!-- Dropdown - Messages -->
        <div style="z-index: 3000"
             class="jt-empresas-dropdown dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
             aria-labelledby="messagesDropdown">

            <h6 class="dropdown-header">
                Clientes Vinculados
            </h6>


            <div v-if="USER.is_root == 'Y'" class="input-group campo-busca-cliente-topo">

                <input type="text" v-model.trim="filtro.filtro.contem" class="form-control"
                       placeholder="NOME, CPF, CNPJ , TELEFONE">
                <span class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        </span>
            </div>


            <a v-for="cli in clientes"
               class="dropdown-item d-flex align-items-center"
               href="#"
               @click.prevent.stop="selCliente(cli)">
                <div class="dropdown-list-image mr-3">

                    <ImgProfile :cliente="cli"></ImgProfile>
                    <!--                    <img class="rounded-circle"-->
                    <!--                         src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcToKoRXUfgabDYnUW5cDfMr1xu57tZ1Rdwnkrr76vrVZ_ZTdOY5"-->
                    <!--                         alt="">-->
                </div>
                <div class="font-weight-light">
                    <div class="text-truncate"> {{ cli.nome }}</div>
                    <div class="small text-gray-500">{{ cli.cpf_cnpj }}</div>
                </div>
            </a>

            <a v-if="!clientes.length" class="dropdown-item text-center small text-gray-900"
               href="#">Sem
                Resultados</a>
        </div>
    </div>
</template>

<script>
import ImgProfile from "@/components/geral/cliente/ImgProfile";

export default {

    name: "SeletorClientes",
    components: {ImgProfile},
    data: function () {

        return {
            clientes: [],
            filtro: {
                paginacao: true,
                items_por_pagina: 10,
                filtro: {contem: ''}
            }
        }

    },

    created: function () {

        this.buscarClientes();

        this.debouncedBuscarClientes = _.debounce(this.buscarClientes, 500);
    },

    watch: {
        'filtro.filtro.contem': function (busca) {
            this.debouncedBuscarClientes();
        }
    },

    computed: {
        CLIENTE() {
            return this.$root.CLIENTE;
        },
        USER() {
            return this.$root.USER;
        }
    },

    methods: {

        buscarClientes: function () {

            this.doGet('geral/clientes/buscar', this.filtro)
                .then(function (paginacao) {
                    this.clientes = paginacao.data;
                });

        },

        selCliente(cliente) {
            // this.$root.CLIENTE = cliente;
            $('.jt-empresas-dropdown').removeClass('show');

            this.$bvModal.msgBoxConfirm('Dados não salvos serão limpos', {

                title: "Confirmar Troca de Sessão?",
                okTitle: 'Trocar',
                cancelTitle: 'Não',
                headerBgVariant: 'info',
                headerBorderVariant: 'info'
            }).then(opt => {
                if (opt) {
                    this.$root.CLIENTE = cliente;
                }
            });
        }

    }
}
</script>

<style scoped>

</style>
