<template>
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Search -->
        <form
            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <!--            <div class="input-group">-->
            <!--                <input type="text" class="form-control bg-light border-0 small"-->
            <!--                       placeholder="Buscar Veículo"-->
            <!--                       aria-label="Search" aria-describedby="basic-addon2">-->
            <!--                <div class="input-group-append">-->
            <!--                    <button class="btn btn-primary" type="button">-->
            <!--                        <i class="fas fa-search fa-sm"></i>-->
            <!--                    </button>-->
            <!--                </div>-->
            <!--            </div>-->
        </form>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">

                <!--                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"-->
                <!--                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                <!--                    <i class="fas fa-search fa-fw"></i>-->
                <!--                </a>-->

                <!-- Dropdown - Messages -->
                <!--                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated&#45;&#45;grow-in"-->
                <!--                     aria-labelledby="searchDropdown">-->
                <!--                    <form class="form-inline mr-auto w-100 navbar-search">-->
                <!--                        <div class="input-group">-->
                <!--                            <input type="text" class="form-control bg-light border-0 small"-->
                <!--                                   placeholder="Search for..." aria-label="Search"-->
                <!--                                   aria-describedby="basic-addon2">-->
                <!--                            <div class="input-group-append">-->
                <!--                                <button class="btn btn-primary" type="button">-->
                <!--                                    <i class="fas fa-search fa-sm"></i>-->
                <!--                                </button>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </form>-->
                <!--                </div>-->
            </li>

            <!-- Nav Item - Alerts -->
            <li id="appGeralAlerts" class="nav-item dropdown no-arrow mx-1" v-cloak>

                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span class="badge badge-danger badge-counter"
                          v-if="notificacoesCmpt.length">{{ notificacoesCmpt.length }}</span>
                </a>

                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     style="z-index: 2000"
                     aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Notificações
                    </h6>

                    <a v-if="!notificacoesCmpt.length"
                       class="dropdown-item d-flex align-items-center"
                       href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-exclamation-circle text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">Sem Notificações</div>
                        </div>
                    </a>


                    <a href="#"
                       v-for="(n,index) in notificacoesCmpt"
                       v-if="index < 5"
                       @click.prevent.stop="visualizar(n)"
                       class="dropdown-item d-flex align-items-center"
                       :key="n.id">
                        <div class="mr-3">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{ n.titulo }}</div>
                            {{ n.msg }}
                        </div>
                    </a>

                    <router-link class="dropdown-item text-center small text-gray-500"
                                 to="/geral/view/notificacoes">Ver Todas
                    </router-link>
                </div>
            </li>

            <!-- Nav Item - Clientes -->
            <li id="appGeralClientes" class="nav-item dropdown no-arrow mx-1" v-cloak>

                <SeletorClientes></SeletorClientes>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">

                <a class="nav-link dropdown-toggle"
                   href="#"
                   id="userDropdown"
                   role="button"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ USER.nome | wfirst }}</span>
                    <ImgProfile :usuario="USER"></ImgProfile>
                </a>

                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="userDropdown" style="z-index: 2000">
                    <router-link class="dropdown-item" to="/geral/view/perfil">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Perfil
                    </router-link>

                    <router-link class="dropdown-item" to="/geral/view/webmail">
                        <i class="fa fa-envelope fa-sm fa-fw mr-2 text-gray-400"></i>
                        Webmail
                    </router-link>

                    <!--                    <a class="dropdown-item" href="/view/configuracoes">-->
                    <!--                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>-->
                    <!--                        Configurações-->
                    <!--                    </a>-->
                    <!--                    <a class="dropdown-item" href="/view/logs">-->
                    <!--                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>-->
                    <!--                        Logs de Acesso-->
                    <!--                    </a>-->
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item"
                       @click="showSair=true"
                       href="#">
                        <i class="fa fa-power-off fa-sm fa-fw mr-2 text-gray-400"></i>
                        SAIR
                    </a>
                </div>
            </li>

        </ul>


        <!-- Logout Modal-->
        <b-modal v-model="showSair" title="Confirmar saída?">
            <h4>Clique no botão <b>sair</b> para finalizar a sessão neste dispositivo.</h4>
            <template #modal-footer>
                <button class="btn btn-secondary"
                        type="button" @click="showSair=false">CONTINUAR LOGADO
                </button>
                <a class="btn btn-danger" href="/sair">FAZER LOGOFF <i class="fa fa-power-off"></i></a>
            </template>
        </b-modal>

        <!--        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"-->
        <!--             aria-hidden="true">-->
        <!--            <div class="modal-dialog" role="document">-->
        <!--                <div class="modal-content">-->
        <!--                    <div class="modal-header">-->
        <!--                        <h5 class="modal-title" id="exampleModalLabel">Confirmar saída?</h5>-->
        <!--                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">-->
        <!--                            <span aria-hidden="true">×</span>-->
        <!--                        </button>-->
        <!--                    </div>-->
        <!--                    <div class="modal-body">Clique no botão sair para finalizar a sessão neste computador.</div>-->
        <!--                    <div class="modal-footer">-->
        <!--                        <button class="btn btn-secondary" type="button" data-dismiss="modal">CANCELAR</button>-->
        <!--                        <a class="btn btn-primary" href="/sair">SAIR</a>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

    </nav>
</template>

<script>

import Util from "@/util";
import UrlFoto from "@/assets/tema/fluxo/usuario-unisex.png"


Util.getRandomColor();

import SeletorClientes from "./geral/SeletorClientes";
import ImgProfile from "@/components/geral/perfil/ImgProfile";

var audio = new Audio();

let audioSrc = null;

if (audio.canPlayType('audio/mp3') != "") {

    audioSrc = require('./../assets/me-too.mp3');

} else {

    // audioSrc = require('./../assets/me-too.m4r');

}

audio.muted = true;
audio.src = audioSrc;


export default {
    name: "BarraSuperior",
    components: {
        ImgProfile,
        SeletorClientes
    },
    data() {
        return {
            notificacoes: [],
            filtro: {
                filtro: ''
            },
            showSair: false,
            audioReady: false
        }
    },

    created() {
        this.loopCall();

        audio.addEventListener('canplay', (event) => {
            $('body').one('click.audionotify', () => {
                this.audioReady = true;
                audio.muted = false;
                //   console.log(audio)
            })
        });
    },

    computed: {
        USER() {
            return this.$root.USER;
        },

        notificacoesCmpt() {
            return _.filter(this.notificacoes, i => {
                return i.leitura_em === null;
            })
        },

    },

    methods: {

        loopCall() {

            this.verificarNotificacoes()
                .then(() => {
                    setTimeout(() => {
                        this.loopCall();
                    }, 1000 * 5);
                }).fail(() => {
                console.log('falha. Aguardando 1 minuto');
                // this.alertErro("Falha ao conectar com o servidor.");

                setTimeout(() => {
                    this.loopCall();
                }, 1000 * 60);
            });

        },

        verificarNotificacoes() {
            return this.doGet('geral/notificacoes/buscar', {}, false)
                .then(r => {

                    if (r.data.length) {

                        // console.log(this.aaa, r.data[0].id)

                        if (this.aaa === undefined || this.aaa < r.data[0].id) {
                            this.aaa = r.data[0].id;
                            this.audioReady && audio.play();
                        }

                    }

                    var aux = this.notificacoes.concat(r.data);
                    this.notificacoes = _.uniqBy(aux, 'id');

                });
        },

        visualizar: function (notif) {

            notif.leitura_em = 'hode';

            this.doPost('geral/notificacoes/visualizar', notif)
                .then(function () {
                    // this.buscarNotificacoes();
                })
        },
    }
}
</script>

<style scoped>

</style>
