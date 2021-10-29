<template>

    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center"
           href="">
            <img src="./../assets/login/verttice-negativo-oficial.svg" style="height: 50px" class="d-md-block d-none">
            <img src="./../assets/login/verttice-negativo-oficial.svg" style="height: 39px" class="d-md-none">
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item active">
            <router-link class="nav-link" to="/">
                <div @click="closeMenu">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></div>
            </router-link>
        </li>

        <template v-if="menu">

            <template v-for="(mLevel1,modulo) in menu" v-if="has_p(mLevel1)">

                <hr class="sidebar-divider my-0">

                <div class="sidebar-heading">
                    {{modulo}}
                </div>

                <li v-for="(mLevel2,grupo) in mLevel1"
                    v-if="has_p(mLevel2)"
                    class="nav-item active">


                    <a class="nav-link collapsed"
                       href="#"
                       data-toggle="collapse"
                       :data-target="'#collapseTwo'+hash(grupo)"
                       aria-expanded="true"
                       aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>{{grupo}}</span>
                    </a>

                    <div :id="'collapseTwo'+hash(grupo)" class="collapse" aria-labelledby="headingTwo"
                         data-parent="#accordionSidebar" style="">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <!--<h6 class="collapse-header">Custom Components:</h6>-->
                            <router-link
                                v-for="mLevel3 in mLevel2"
                                :to="mLevel3.uid"
                                class="collapse-item"
                                @click="clickMenu"
                                :title="mLevel3.descricao"
                                :key="mLevel3.id"
                                v-if="mLevel3.has_p == 'Y'"
                                :href="'#'+mLevel3.uid">
                                <div @click="closeMenu">{{mLevel3.nome}}</div>
                            </router-link>
                        </div>
                    </div>

                </li>
            </template>
        </template>

    </ul>

</template>

<script>

    import crypto from 'crypto';

    export default {
        name: 'Menu',
        components: {},
        props: {
            msg: String
        },
        computed: {
            menu() {
                return this.$root.menu;
            }
        },

        methods: {
            hash: function (nome) {
                return crypto.createHmac('md5', '')
                    .update(nome)
                    .digest('hex');
            },
            has_p(p) {
                for (var i in p) {

                    if (_.get(p[i], 'id') === undefined && this.has_p(p[i])) {
                        return true;
                    }

                    if (_.get(p[i], 'has_p') == 'Y') {
                        return true;
                    }
                }
                return false;
            },

            closeMenu() {
                if ($(window).width() <= 768) {
                    $('#sidebarToggleTop').trigger('click');
                }
            },
            clickMenu(){
                alert('aaa')
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    h3 {
        margin: 40px 0 0;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        /*margin: 0 10px;*/
    }

    a {
        color: #42b983;
    }

    #accordionSidebar {
        background-color: #36464E;
        background-image: none;
    }
</style>
