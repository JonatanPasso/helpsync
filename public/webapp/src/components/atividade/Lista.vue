<template>
    <div style="min-height: 300px">
        <br>
        <div class="row">
            <div class="col-lg-6">
                <b-input-group>
                    <b-input class="input-search" alt="lista-clientes" placeholder="Pesquisar..."></b-input>
                    <b-input-group-append>
                        <b-btn disabled><i class="fa fa-search"></i></b-btn>
                    </b-input-group-append>
                </b-input-group>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <b-button-group>
                    <b-dropdown right text="Gerencial" variant="primary">
                        <b-dropdown-item>
                            <i class="fa fa-plus-circle"></i>
                            <router-link to="/atividade/view/atividades"> Incluir Atividade</router-link>
                        </b-dropdown-item>

                        <b-dropdown-divider></b-dropdown-divider>
                        <b-dropdown-item
                            @click.prevent.stop="$emit('aba',1)"><i class="fa fa-tasks"></i> Criar Chamado
                        </b-dropdown-item>

                        <b-dropdown-divider></b-dropdown-divider>
                        <b-dropdown-item @click="$bvModal.show('modalFiltros')"><i class="fa fa-filter"></i> Gerenciar
                            Filtros
                        </b-dropdown-item>
                    </b-dropdown>

                    <b-dropdown right text="Painel" variant="danger">
                        <b-dropdown-item @click="showModalColunas"><i class="fa fa-columns"></i> Gerenciar
                            Colunas do Painel
                        </b-dropdown-item>
                    </b-dropdown>
                    <b-button variant="warning" @click="listaChamados('m')"><i class="fa fa-retweet"></i> Atualizar
                        Lista
                    </b-button>
                </b-button-group>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <br>

                <template v-for="(filter, i) in arrayFiltros">


                    <div class="col-md-2 hvr-overline-from-center"
                         v-if="filter.titulo_filtro != 'Todos' || acesso('atividades:VerTudo')"
                         :class="{ 'bg_filtros' :controleFiltro == filter.parametro, 'box-filters': i == 0, 'box-filters-left': i != 0}"
                         v-on:click="listaChamados(filter.parametro)" data-toggle="tooltip"
                         :title="filter.descricao_filtro">
                        <i :class="filter.icon"></i> &nbsp; {{ filter.titulo_filtro }}
                    </div>

                </template>

                <br>
                <div class="row">
                    <hr>
                </div>
                <!--                <div class="row" v-scroll="{height:'400px'}">-->
                <b-overlay
                    :show="ajaxStatus"
                    rounded
                    opacity="0.6"
                    spinner-variant="primary">
                    <div class="row">
                        <table id="chamadosListados"
                               class="table tablesorter display responsive no-wrap listaChamadosDt lista-clientes table-striped"
                               style="font-size: 12px!important;">
                            <thead>
                            <tr>
                                <th data-sort-default>Chamado</th>
                                <th>Título</th>
                                <th style="text-align: center"><span data-toggle="tooltip" title="Criado por"
                                                                     style="cursor: pointer">C</span></th>
                                <th style="text-align: center"><span data-toggle="tooltip" title="Tipo"
                                                                     style="cursor: pointer">T</span></th>
                                <th style="text-align: center"><span data-toggle="tooltip" title="Prioridade"
                                                                     style="cursor: pointer">P</span></th>
                                <!--                            <th style="text-align: center">Situação</th>-->
                                <th>Departamento</th>
                                <th>Criado Por</th>
                                <th>Atribuido</th>
                                <th>Criado</th>
                                <th>Atualizado</th>
                                <!--<th style="text-align: center">Obs</th>-->
                                <th style="text-align: center">Anexo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="ch in arrayChamados">
                                <tr :class="ch.leitura_em ? '':'negritude'">
                                    <td style=" vertical-align: middle; font-size: 13px; letter-spacing: 1px; text-shadow: 1px 1px 1px 1px #000000">

                                        <span class="lk_nr_chamado" v-on:click="detalheChamado(ch.nr_chamado)"
                                              style="padding-left: 8px;white-space: nowrap">
                                        <span class="concluido" v-if="ch.status_chamado == 'CO'" data-toggle="tooltip"
                                              title="Chamado Concluído"><i
                                            class="fa fa-circle"></i> #{{ ch.nr_chamado }}</span>
                                        <span class="finalizado" v-if=" ch.status_chamado == 'FN'" data-toggle="tooltip"
                                              title="Chamado Finalizado"><i
                                            class="fa fa-circle"></i> #{{ ch.nr_chamado }}</span>
                                        <span class="status_ce"
                                              v-if="ch.status_chamado != 'CO' && ch.status_chamado != 'FN' && (ch.moderou_chamado == '1' || ch.moderou_chamado === '2')"
                                              data-toggle="tooltip" title="Caixa de Entrada"><i
                                            class="fa fa-circle"></i> #{{ ch.nr_chamado }}</span>
                                        <span class="status_ce"
                                              v-if="ch.status_chamado != 'CO' && ch.status_chamado != 'FN' && ch.moderou_chamado === '0'"
                                              data-toggle="tooltip" title="Em Moderação" variant="danger"><i
                                            class="fa fa-circle"></i> #{{ ch.nr_chamado }}</span>
                                    </span>
                                    </td>
                                    <td style=""><span data-toggle="tooltip"
                                                       :title="ch.titulo_chamado">{{
                                            ch.titulo_chamado|reduzTitulo
                                        }}</span>
                                    </td>
                                    <td style="text-align: center"><span class="fa fa-user fa-1x"
                                                                         data-toggle="tooltip"
                                                                         :title="l_.get(ch,'usuario_criador.nome')"
                                                                         style="cursor: pointer"></span></td>
                                    <td style="text-align: center"><a href="#" data-toggle="tooltip"
                                                                      :title="tiposChamado(ch.tipo_chamado)[1]"
                                                                      class="icon icon-enhancement"><img width="20px"
                                                                                                         height="20px"
                                                                                                         :src="ch.tipo_chamado|tipoChamado"></a>
                                    </td>
                                    <td style="text-align: center"><a href="#" data-toggle="tooltip"
                                                                      :title="tiposPrioridades(ch.prioridade_chamado)[1]"
                                                                      class="icon icon-major"><img width="20px"
                                                                                                   height="20px"
                                                                                                   :src="ch.prioridade_chamado|prioridadeLista">
                                    </a></td>
                                    <!--                                <td style="text-align: center">-->
                                    <!--                                    <i class="fa fa-circle fa-2x concluido shadow-lg" v-if="ch.status_chamado == 'CO'" data-toggle="tooltip" title="Chamado Concluído"></i>-->
                                    <!--                                    <i class="fa fa-circle fa-2x finalizado shadow-lg" v-if=" ch.status_chamado == 'FN'" data-toggle="tooltip" title="Chamado Finalizado"></i>-->
                                    <!--                                    <i class="fa fa-circle fa-2x status_ce shadow-lg" v-if="ch.status_chamado != 'CO' && ch.status_chamado != 'FN'" data-toggle="tooltip" title="Caixa de Entrada"></i>-->
                                    <!--                                    <a v-bind:class="[{ concluido: ch.status_chamado == 'CO'},-->
                                    <!--												  {finalizado: ch.status_chamado == 'FN'},-->
                                    <!--												  {status_ce: ch.status_chamado != 'CO' && ch.status_chamado != 'FN'}]"-->
                                    <!--                                           href="#" class="btn-circle">-->
                                    <!--                                    {{ch.status_chamado|statusChamado}}-->
                                    <!--                                </a>-->
                                    <!--                                </td>-->
                                    <td style="">{{ l_.get(ch, 'departamento_chamado.nome') }}</td>
                                    <td style="white-space: nowrap" :title="l_.get(ch, 'usuario_criador.nome')">
                                        <ImgProfile style="height: 30px;display: inline-block"
                                                    class=""
                                                    :usuario="ch.usuario_criador"></ImgProfile>
                                        {{ l_.get(ch, 'usuario_criador.nome') | wfirst }}
                                    </td>
                                    <td style="white-space: nowrap" :title=" l_.get(ch, 'usuario_executor.nome')">
                                        <ImgProfile style="height: 30px;display: inline-block"
                                                    :usuario="ch.usuario_executor"></ImgProfile>
                                        {{ l_.get(ch, 'usuario_executor.nome') |wfirst }}
                                    </td>
                                    <td>{{ ch.criado_em | datas('DD/MM/YY LT') }}</td>
                                    <td>{{ ch.alterado_em | datas('DD/MM/YY LT') }}</td>
                                    <!--<td style="text-align: center">-->
                                    <!--<a v-show="ch.id_usuario_executor == observador" href="#" data-template="issue-watch-template" data-type="issue" class="follow following" title="Parar de Acompanhar">-->
                                    <!--<img width="20px" height="20px" src="/beta/img/watching.svg">-->
                                    <!--</a>-->
                                    <!--<a v-show="ch.id_usuario_executor != observador" href="#" data-template="issue-watch-template" data-type="issue" class="follow following" title="Acompanhar Evolução">-->
                                    <!--<img width="20px" height="20px" src="/beta/img/not-watching.svg">-->
                                    <!--</a>-->
                                    <!--</td>-->
                                    <td style="text-align: center" v-if="ch.anexo_chamado">
                                        <h5 v-if="ch.anexo_chamado.length > 0"
                                            v-on:click="detalheChamado(ch.nr_chamado)" style="cursor: pointer">
                                            <b-badge variant="info"><i class="fa fa-paperclip"></i>
                                                {{ ch.anexo_chamado.length }}
                                            </b-badge>
                                        </h5>
                                        <!--                                        <h5 v-if="ch.anexo_chamado.length == 0"><b-badge variant="secondary" disabled="true"><i class="fa fa-paperclip"></i> {{ch.anexo_chamado.length}}</b-badge></h5>-->

                                    </td>
                                    <td style="text-align: center" v-else>
                                        <i class="fa fa-ellipsis-h"></i>
                                    </td>
                                </tr>
                            </template>
                            <tr v-if="arrayChamados.length <= 0">
                                <td colspan="99" style="text-align: center; font-weight: bold"> Não há registros para
                                    serem
                                    exibidos.
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <!--                        <td colspan="12"-->
                            <!--                            style="text-align: right; background-color: rgba(204,204,204,0.24); cursor: pointer"-->
                            <!--                            v-on:click="showModalLegenda">-->
                            <!--                            <span class="legend-chamado fa fa-th-list"></span> Legenda-->
                            <!--                        </td>-->
                            </tfoot>
                        </table>
                    </div>

                </b-overlay>

            </div>
        </div>

        <!-- Modal Legenda -->

        <b-modal id="modalLegendaChamado" size="lg" hide-footer>
            <template v-slot:modal-title>
                Legenda de Tipo e Prioridade do chamado
            </template>
            <div class="d-block text-center">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>T = Tipo</th>
                        <th>P = Prioridade</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><img src="./../../assets/fluxo/bug.svg"> - Erro</td>
                        <td><img style="width: 20px; height: 20px" src="./../../assets/fluxo/trivial.svg"> - Trivial
                        </td>
                    </tr>
                    <tr>
                        <td><img src="./../../assets/fluxo/enhancement.svg"> - Aprimoramento</td>
                        <td><img style="width: 20px; height: 20px" src="./../../assets/fluxo/minor.svg"> - Baixa</td>
                    </tr>
                    <tr>
                        <td><img src="./../../assets/fluxo/proposal.svg"> - Proposta</td>
                        <td><img style="width: 20px; height: 20px" src="./../../assets/fluxo/major.svg"> - Alta</td>
                    </tr>
                    <tr>
                        <td><img src="./../../assets/fluxo/task.svg"> - Tarefa</td>
                        <td><img style="width: 20px; height: 20px" src="./../../assets/fluxo/critical.svg"> - Crítica
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><img style="width: 20px; height: 20px" src="./../../assets/fluxo/blocker.svg"> - Bloqueio
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>C = Criado Por</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span class="fa fa-user fa-2x"></span> - Usuário que criou o Chamado</td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Filtros</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span class="status_ce">Todos</span></td>
                        <td><span> Filtra todos chamados, de qualquer departamento independente do status</span>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <b-button class="mt-3" block @click="$bvModal.hide('modalLegendaChamado')">Fechar</b-button>
        </b-modal>

        <!-- Modal Filtros -->
        <b-modal id="modalFiltros" @show="showModalFilters" size="lg" hide-footer>
            <template v-slot:modal-title>
                Gerenciamento de Filtros
            </template>
            <div class="d-block text-center">
                <p class="alert-info" style="padding: 5px; text-align: center"><i class="fa fa-info"></i> &nbsp;-
                    Selecione o filtro desejado para que possa ser exibido na lista de chamados.</p>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Filtro</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-1" v-model="filtroTodos">
                                <label for="box-1">Todos</label>
                            </div>
                        </td>
                        <td>
                            <p>Filtra todos os chamados meus e de terceiros.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-2" v-model="filtroMeus">
                                <label for="box-2">Meus Chamados</label>
                            </div>
                        </td>
                        <td>
                            <p>Filtra somente os meus e os que estou envolvido, exemplo os que eu abri ou os que
                                foram abertos para mim ou compartilhados.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-3" v-model="filtroAbertos">
                                <label for="box-3">Abertos</label>
                            </div>
                        </td>
                        <td>
                            <p>Filtra meus chamados em aberto.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-4" v-model="filtroIniciados">
                                <label for="box-4">Iniciados</label>
                            </div>
                        </td>
                        <td>
                            <p>Filtra meus chamados iniciados.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-5" v-model="filtroConcluidos">
                                <label for="box-5">Concluídos</label>
                            </div>
                        </td>
                        <td>
                            <p>Filtra meus chamados que já conclui.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-6" v-model="filtroAvaliacao">
                                <label for="box-6">Minhas Avaliações</label>
                            </div>
                        </td>
                        <td>
                            <p>Filtra chamados que foram concluídos e ainda não foram finalizados.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-7" v-model="filtroFinalizado">
                                <label for="box-7">Finalizados</label>
                            </div>
                        </td>
                        <td>
                            <p>Filtra chamados já finalizados.</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <b-button class="mt-3" block @click="$bvModal.hide('modalFiltros')">Fechar</b-button>
        </b-modal>

        <!-- Modal Colunas -->
        <b-modal id="modalColunas" size="lg" hide-footer>
            <template v-slot:modal-title>
                Gerenciamento de Colunas - Painel Chamado
            </template>
            <div class="d-block text-center">
                <p class="alert-info" style="padding: 5px; text-align: center"><i class="fa fa-info"></i> &nbsp;-
                    Selecione a coluna que você gostaria que fosse exibida no painel de chamados.</p>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50%">Coluna</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr style="background-color: #402f2fe6; color: #fff;">
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-8" v-model="colunaNrChamado" disabled>
                                <label for="box-8" style="color: #fff">Nº Chamado</label>
                            </div>
                        </td>
                        <td>
                            <p>Número do chamado (Coluna Padrão, não se altera) </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-9" v-model="colunaTitulo">
                                <label for="box-9">Título</label>
                            </div>
                        </td>
                        <td>
                            <p>Título do chamado</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">

                                <input type="checkbox" id="box-10" v-model="colunaCriadoIcon">
                                <label for="box-10">Criado por ( Somente ícone )</label>
                            </div>
                        </td>
                        <td>
                            <p>Ícone de criado por, para exibir quem criou basta passar o mouse sobre o
                                ícone.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-11" v-model="colunaTipoIcon">
                                <label for="box-11">Tipo ( Somente ícone )</label>
                            </div>
                        </td>
                        <td>
                            <p>Tipo do Chamado, para exibir a descrição do tipo basta passar o mouse sobre o
                                ícone.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-12" v-model="colunaPrioridadeIcon">
                                <label for="box-12">Prioridade ( Somente ícone )</label>
                            </div>
                        </td>
                        <td>
                            <p>Prioridade do chamado, para exibir a descrição da prioridade basta passar o mouse
                                sobre o ícone.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-13" v-model="colunaSituacao">
                                <label for="box-13">Situação do Chamado</label>
                            </div>
                        </td>
                        <td>
                            <p>Indica em que etapa o chamado se encontra.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-14" v-model="colunaDepartamento">
                                <label for="box-14">Departamento</label>
                            </div>
                        </td>
                        <td>
                            <p>Indica em que etapa o chamado se encontra.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-15" v-model="colunaCriadoPor">
                                <label for="box-15">Criado por</label>
                            </div>
                        </td>
                        <td>
                            <p>Nome da pessoa que criou o chamado.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-16" v-model="colunaAtribuidoPara">
                                <label for="box-16">Atribuido para</label>
                            </div>
                        </td>
                        <td>
                            <p>Nome da pessoa que irá executar o chamado.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-17" v-model="colunaCriadoEm">
                                <label for="box-17">Criado em</label>
                            </div>
                        </td>
                        <td>
                            <p>Quando foi criado o chamado.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-18" v-model="colunaAtualizadoEm">
                                <label for="box-18">Atualizado em</label>
                            </div>
                        </td>
                        <td>
                            <p>Quando foi atualizado o chamado.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="boxes">
                                <input type="checkbox" id="box-19" v-model="colunaAnexo">
                                <label for="box-19">Anexo principal</label>
                            </div>
                        </td>
                        <td>
                            <p>Anexo que foi incorporado ao criar o chamado.</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <b-button class="mt-3" block @click="$bvModal.hide('modalColunas')">Fechar</b-button>
        </b-modal>
    </div>
</template>

<style scoped>

.negritude {
    font-weight: bolder;
}

a {
    text-decoration: none;
}

#custom-search-input {
    padding: 3px;
    border: solid 1px #E4E4E4;
    border-radius: 6px;
    background-color: #fff;
}

#custom-search-input input {
    border: 0;
    box-shadow: none;
}

#custom-search-input button {
    margin: 2px 0 0 0;
    background: none;
    box-shadow: none;
    border: 0;
    color: #666666;
    padding: 0 8px 0 10px;
    border-left: solid 1px #ccc;
}

#custom-search-input button:hover {
    border: 0;
    box-shadow: none;
    border-left: solid 1px #ccc;
}

#custom-search-input .glyphicon-search {
    font-size: 23px;
}

th[role=columnheader]:not(.no-sort) {
    cursor: pointer;
}

th[role=columnheader]:not(.no-sort):after {
    content: '';
    float: right;
    margin-top: 7px;
    border-width: 0 4px 4px;
    border-style: solid;
    border-color: #404040 transparent;
    visibility: hidden;
    opacity: 0;
    -ms-user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
}

th[aria-sort=ascending]:not(.no-sort):after {
    border-bottom: none;
    border-width: 4px 4px 0;
}

th[aria-sort]:not(.no-sort):after {
    visibility: visible;
    opacity: 0.4;
}

th[role=columnheader]:not(.no-sort):hover:after {
    visibility: visible;
    opacity: 1;
}

.drop-atividades ul.dropdown-menu {
    left: -85px !important;
}

.hvr-overline-from-center {
    display: inline-block;
    vertical-align: middle;
    /* -webkit-transform: perspective(1px) translateZ(0); */
    transform: perspective(1px) translateZ(0);
    box-shadow: 0 0 1px rgba(0, 0, 0, 0);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

[class^="hvr-"] {
    margin: .4em;
    padding: 1em;
    cursor: pointer;
    background: #e1e1e1;
    text-decoration: none;
    color: #666;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.hvr-overline-from-center:hover:before, .hvr-overline-from-center:focus:before, .hvr-overline-from-center:active:before {
    left: 0;
    right: 0;
}

.hvr-overline-from-center:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 51%;
    right: 51%;
    top: 0;
    background: #2098D1;
    height: 4px;
    -webkit-transition-property: left, right;
    transition-property: left, right;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
}

.btn-drop-atividades {
    border: 1px solid rgb(204, 204, 204);
    border-radius: 16px 0px 5px 16px;
    background-color: rgb(50, 113, 192);
    color: #fff;
}

.btn-drop-atividades:hover {
    box-shadow: 0 0 20px rgb(50, 113, 192);
    color: #fff;
}


.btn-drop-columns {
    border: 1px solid rgb(204, 204, 204);
    border-radius: 0px 16px 16px 0px;
    background-color: rgba(96, 103, 114, 0.69);
    color: #fff;
}

.btn-drop-columns:hover {
    box-shadow: 0 0 20px rgba(96, 103, 114, 0.69);
    color: #fff;
}

.app-header--heading {
    font-size: 1.71428571em;
    font-style: inherit;
    font-weight: 500;
    letter-spacing: -.01em;
    line-height: 1.16666667;
    margin-top: 28px;
    color: #172B4D;
}

.situacao {
    /*border-color: #a5b3c2;*/
    /*background: #ccc;*/
    border: 1px solid #ccc;
    border-radius: 3px;
    color: #333;
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    line-height: 99%;
    margin: 0;
    padding: 5px 30px;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    white-space: nowrap;
}

.title-filtros {
    font-size: 16px;
    font-weight: bold;
    color: #337ab7;
    padding-right: 5px;
}

.active-filtro {
    background: #337ab7;
    border: 1px solid #101233;
    color: #fff;
    padding: 4px;
    border-radius: 5px;
    cursor: pointer;
}

.filtros-sec {
    background: #a9b8c5c2;
    border: 1px solid #9d9eb3;
    color: #fff;
    padding: 4px;
    border-radius: 5px;
    cursor: pointer;
}

.filtros-sec:hover {
    background: #337ab7;
    border: 1px solid #101233;
    color: #fff;
    padding: 4px;
    border-radius: 5px;
    cursor: pointer;
}

.legend-chamado {
    text-align: right;
    background-color: rgba(204, 204, 204, 0.51);
    font-weight: bold;
}

.nr_chamado_mod {
    border: 1px solid;
    padding: 3px;
    border-radius: 3px;
    background-color: #418bca12;
    font-weight: bold;
}

.situacao_mod {
    border: 1px solid;
    padding: 3px;
    border-radius: 4px;
    font-weight: bold;
}

.concluido {
    /*border: 1px solid #ccc;*/
    /*border-radius: 3px;*/
    /*color: #333;*/
    /*display: inline-block;*/
    /*font-size: 11px;*/
    /*font-weight: 700;*/
    /*line-height: 99%;*/
    /*margin: 0;*/
    /*padding: 5px 30px;*/
    /*text-align: center;*/
    /*text-decoration: none;*/
    /*text-transform: uppercase;*/
    /*white-space: nowrap;*/
    color: rgba(110, 0, 0, 0.62);
    /*color: #fff;*/
    /*cursor: pointer;*/
    /*width: 166.63px;*/
    /*height: 22px;*/
}

.status_ce {
    /*border: 1px solid #ccc;*/
    /*border-radius: 3px;*/
    /*color: #333;*/
    /*display: inline-block;*/
    /*font-size: 11px;*/
    /*font-weight: 700;*/
    /*line-height: 99%;*/
    /*margin: 0;*/
    /*padding: 5px 30px;*/
    /*text-align: center;*/
    /*text-decoration: none !important;*/
    /*text-transform: uppercase;*/
    /*white-space: nowrap;*/
    color: rgba(11, 46, 134, 0.79);
    /*cursor: pointer;*/
    /*width: 166.63px;*/
    /*height: 22px;*/
}

.finalizado {
    /*border: 1px solid #ccc;*/
    /*border-radius: 3px;*/
    /*color: #333;*/
    /*display: inline-block;*/
    /*font-size: 11px;*/
    /*font-weight: 700;*/
    /*line-height: 99%;*/
    /*margin: 0;*/
    /*padding: 5px 30px;*/
    /*text-align: center;*/
    /*text-decoration: none;*/
    /*text-transform: uppercase;*/
    /*white-space: nowrap;*/
    color: rgba(6, 79, 7, 0.62);
    /*color: #fff;*/
    /*cursor: pointer;*/
    /*width: 166.63px;*/
    /*height: 22px;*/
}

.lk_nr_chamado {
    cursor: pointer;
    color: #3271c0;
    /*font-weight: bold*/
}

.lk_chamado_concluido {
    cursor: pointer;
    color: #fff;
    font-weight: bold
}

.box-filters {
    border: 1px solid;
    font-size: 12px;
    border-radius: 1px;
    border-color: #ccc;
    background-color: #cccccc1a;
    /* text-align: center; */
    padding: 8px 8px 8px 15px;
}

.box-filters-left {
    border-top: 1px solid;
    border-right: 1px solid;
    border-bottom: 1px solid;
    font-size: 12px;
    border-radius: 1px;
    border-color: #ccc;
    background-color: #cccccc1a;
    /* text-align: center; */
    padding: 8px 8px 8px 15px;
}

.bg_filtros {
    /*background-color: #0f6848;*/
    border-top: 2px solid #2098D1;
}


/*@media screen and (min-width: 768px) {*/
/*.custom-class {*/
/*width: 80%; !* either % (e.g. 60%) or px (400px) *!*/
/*}*/
/*}*/

</style>


<script>

import '@/assets/tema/fluxo/atividades/modo_noturno_lista_chamdados.scss'

import Scroll from "../../directives/Scroll";
import Tablesort from 'tablesort/dist/tablesort.min'

//Tipo de Chamado
import Bugs from "./../../assets/fluxo/bug.svg";
import Enhancement from "./../../assets/fluxo/enhancement.svg";
import Proposal from "./../../assets/fluxo/proposal.svg";
import Task from "./../../assets/fluxo/task.svg";

//Prioridade
import Trivial from "./../../assets/fluxo/trivial.svg";
import Minor from "./../../assets/fluxo/minor.svg";
import Major from "./../../assets/fluxo/major.svg";
import Critical from "./../../assets/fluxo/critical.svg";
import Blocker from "./../../assets/fluxo/blocker.svg";
import ImgProfile from "@/components/geral/perfil/ImgProfile";

function filtroChamado(value) {
    switch (value) {
        case 'CE':
            return 'CAIXA DE ENTRADA'
            break;
        case 'IN':
            return 'INICIADO'
            break;
        case 'PS':
            return 'PAUSADO'
            break;
        case 'RT':
            return 'RETORNADO'
            break;
        case 'CA':
            return 'CANCELADO'
            break;
        case 'FN':
            return 'FINALIZADO'
            break;
        case 'TR':
            return 'TRANSFERIDO'
            break;
        case 'CO':
            return 'CONCLUÍDO'
            break;
        default:
            return '--'
    }
};


export default {

    name: 'Lista',
    components: {ImgProfile},
    directives: {Scroll},

    mounted: function () {

        var _this = this;

        _this.listaChamados();
        _this.listaVagaDepartamento();

        this.$root.$watch('USER.id', function (idUsuario) {
            _this.listaChamados();
            _this.listaVagaDepartamento();
        });

        this.activeFiltrosChamado();

        setTimeout(function () {
            // new Tablesort(document.getElementById('chamadosListados'));

            $(".input-search").keyup(function () {
                //pega o css da tabela
                var tabela = $(this).attr('alt');
                if ($(this).val() != "") {
                    $("." + tabela + " tbody>tr").hide();
                    $("." + tabela + " td:contains-ci('" + $(this).val() + "')").parent("tr").show();
                } else {
                    $("." + tabela + " tbody>tr").show();
                }
            });

            $.extend($.expr[":"], {
                "contains-ci": function (elem, i, match, array) {
                    return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                }
            });
        }, 5000);

    },

    computed: {
        observador: function () {
            return this.$root.USER.id;
        },
        token() {
            return TOKEN;
        }
    },

    filters: {
        tipoChamado: function (value) {

            switch (value) {
                case '1':
                    return Bugs
                    break;
                case '2':
                    return Enhancement
                    break;
                case '3':
                    return Proposal
                    break;
                case '4':
                    return Task
                    break;
                default:
                    return '--'
            }
        },

        datas: function (value, args) {
            if (value) {
                return moment(String(value)).format(args ? args : 'DD/MM/YYYY');
            }
        },

        reduzTitulo: function (value) {

            if (value.length >= 35) {
                var str = value;

                var newStr = str.substring(0, 35) + '...';

                return newStr;
            }

            return value;

        },

        prioridadeLista: function (value) {

            switch (value) {
                case '1':
                    return Trivial
                    break;
                case '2':
                    return Minor
                    break;
                case '3':
                    return Major
                    break;
                case '4':
                    return Critical
                    break;
                case '5':
                    return Blocker
                    break;
                default:
                    return '--'
            }
        },

        statusChamado: filtroChamado
    },

    props: ['aba_ativa'],

    watch: {

        aba_ativa: function () {
            this.listaChamados('m');
        },

        filtroTodos: function (value) {
            this.setaFiltro(this.idFiltroTodos, value);
        },
        filtroMeus: function (value) {
            this.setaFiltro(this.idFiltroMeus, value);
        },
        filtroAbertos: function (value) {
            this.setaFiltro(this.idFiltroAbertos, value);
        },
        filtroIniciados: function (value) {
            this.setaFiltro(this.idFiltroIniciados, value);
        },
        filtroConcluidos: function (value) {
            this.setaFiltro(this.idFiltroConcluidos, value);
        },
        filtroAvaliacao: function (value) {
            this.setaFiltro(this.idFiltroAvaliacao, value);
        },
        filtroFinalizado: function (value) {
            this.setaFiltro(this.idFiltroFinalizado, value)
        },


        colunaNrChamado: function (value) {
            this.setColuna(this.idColunaChamado, value)
        },
        colunaTitulo: function (value) {
            this.setColuna(this.idColunaTitulo, value)
        },
        colunaCriadoIcon: function (value) {
            this.setColuna(this.idColunaCriadoIcon, value)
        },
        colunaTipoIcon: function (value) {
            this.setColuna(this.idColunaTipoIcon, value)
        },
        colunaPrioridadeIcon: function (value) {
            this.setColuna(this.idColunaPrioridadeIcon, value)
        },
        colunaSituacao: function (value) {
            this.setColuna(this.idColunaSituacao, value)
        },
        colunaDepartamento: function (value) {
            this.setColuna(this.idColunaDepartamento, value)
        },
        colunaCriadoPor: function (value) {
            this.setColuna(this.idColunaCriadoPor, value)
        },
        colunaAtribuidoPara: function (value) {
            this.setColuna(this.idColunaAtribuidoPara, value)
        },
        colunaCriadoEm: function (value) {
            this.setColuna(this.idColunaCriadoEm, value)
        },
        colunaAtualizadoEm: function (value) {
            this.setColuna(this.idColunaAtualizadoEm, value)
        },
        colunaAnexo: function (value) {
            this.setColuna(this.idColunaAnexo, value)
        }
    },

    methods: {

        // showModalLegenda: function () {
        //     $('#modalLegendaChamado').modal('show');
        // },

        showModalColunas: function () {

            var idUsuarioLogado = this.$root.USER.id;

            var params = {
                idUsuario: idUsuarioLogado
            }

            // Lista de colunas do painel de chamados
            this.doGet('atividades/atividades/listarColunas', params).then(function (resultColunas) {

                var _this = this;

                _this.idColunaChamado = 1;
                _this.idColunaTitulo = 2;
                _this.idColunaCriadoIcon = 3;
                _this.idColunaTipoIcon = 4;
                _this.idColunaPrioridadeIcon = 5;
                _this.idColunaSituacao = 6;
                _this.idColunaDepartamento = 7;
                _this.idColunaCriadoPor = 8;
                _this.idColunaAtribuidoPara = 9;
                _this.idColunaCriadoEm = 10;
                _this.idColunaAtualizadoEm = 11;
                _this.idColunaAnexo = 12;


                $.each(resultColunas, function (index, value) {

                    if (value.posicao_coluna == '1' && value.status == 'A') {
                        _this.colunaNrChamado = true;
                    }
                    if (value.posicao_coluna == '2' && value.status == 'A') {
                        _this.colunaTitulo = true;
                    }
                    if (value.posicao_coluna == '3' && value.status == 'A') {
                        _this.colunaCriadoIcon = true;
                    }
                    if (value.posicao_coluna == '4' && value.status == 'A') {
                        _this.colunaTipoIcon = true;
                    }
                    if (value.posicao_coluna == '5' && value.status == 'A') {
                        _this.colunaPrioridadeIcon = true;
                    }
                    if (value.posicao_coluna == '6' && value.status == 'A') {
                        _this.colunaSituacao = true;
                    }
                    if (value.posicao_coluna == '7' && value.status == 'A') {
                        _this.colunaDepartamento = true;
                    }
                    if (value.posicao_coluna == '8' && value.status == 'A') {
                        _this.colunaCriadoPor = true;
                    }
                    if (value.posicao_coluna == '9' && value.status == 'A') {
                        _this.colunaAtribuidoPara = true;
                    }
                    if (value.posicao_coluna == '10' && value.status == 'A') {
                        _this.colunaCriadoEm = true;
                    }
                    if (value.posicao_coluna == '11' && value.status == 'A') {
                        _this.colunaAtualizadoEm = true;
                    }
                    if (value.posicao_coluna == '12' && value.status == 'A') {
                        _this.colunaAnexo = true;
                    }
                })

            });

            // $('#modalColunas').modal('show');
            this.$bvModal.show('modalColunas')
        },

        showModalFilters: function () {

            var idUsuarioLogado = this.$root.USER.id;

            var params = {
                idUsuario: idUsuarioLogado
            };

            this.doGet('atividades/atividades/listarFiltros', params).then(function (result) {

                var _this = this;

                _this.idFiltroTodos = 1;
                _this.idFiltroMeus = 2;
                _this.idFiltroAbertos = 3;
                _this.idFiltroIniciados = 4;
                _this.idFiltroConcluidos = 5;
                _this.idFiltroAvaliacao = 6;
                _this.idFiltroFinalizado = 7;

                $.each(result, function (index, value) {

                    if (value.parametro == 't' && value.status_filtro == 'A') {
                        _this.filtroTodos = true;
                    }

                    if (value.parametro == 'm' && value.status_filtro == 'A') {
                        _this.filtroMeus = true;
                    }

                    if (value.parametro == 'a' && value.status_filtro == 'A') {
                        _this.filtroAbertos = true;
                    }

                    if (value.parametro == 'i' && value.status_filtro == 'A') {
                        _this.filtroIniciados = true;
                    }

                    if (value.parametro == 'c' && value.status_filtro == 'A') {
                        _this.filtroConcluidos = true;
                    }

                    if (value.parametro == 'v' && value.status_filtro == 'A') {
                        _this.filtroAvaliacao = true;
                    }

                    if (value.parametro == 'f' && value.status_filtro == 'A') {
                        _this.filtroFinalizado = true;
                    }
                });

            });
        },

        listaChamados: function (controle, page) {

            var _this = this;

            var idUsuarioLogado = this.$root.USER.id;

            if (controle == '' || controle == null) {
                _this.controleFiltro = 'm';
            } else {
                _this.controleFiltro = controle;
            }

            var params = {
                paramControle: _this.controleFiltro,
                idUsuario: idUsuarioLogado,
                page: page
            };

            this.doGet('atividades/atividades/listarChamados', params).then(function (result) {


                this.arrayChamados = result;

                var paramsFiltros = {
                    idUsuario: _this.$root.USER.id
                };

                // Lista de chamados
                this.doGet('atividades/atividades/listarFiltros', paramsFiltros).then(function (resultFiltros) {

                    var arrayAux = [];
                    var count = 1;

                    $.each(resultFiltros, function (index, value) {

                        if (value.status_filtro == 'A') {
                            if (count <= 6) {
                                arrayAux.push(value);
                            }
                            count++;
                        }
                    })

                    _this.arrayFiltros = arrayAux

                });

                // Lista de colunas do painel de chamados
                this.doGet('atividades/atividades/listarColunas', paramsFiltros).then(function (resultColunas) {

                    if (resultColunas.length == 0) {
                        $('#chamadosListados th:nth-child(7)').hide();
                        $('#chamadosListados td:nth-child(7)').hide();

                        $('#chamadosListados th:nth-child(8)').hide();
                        $('#chamadosListados td:nth-child(8)').hide();
                    } else {
                        $.each(resultColunas, function (index, value) {

                            var posicao = value.posicao_coluna;

                            if (value.status == 'I') {
                                $('#chamadosListados th:nth-child(' + posicao + ')').hide();
                                $('#chamadosListados td:nth-child(' + posicao + ')').hide();
                            } else {
                                $('#chamadosListados th:nth-child(' + posicao + ')').show();
                                $('#chamadosListados td:nth-child(' + posicao + ')').show();
                            }
                        });
                    }
                });

                setTimeout(function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }, 3000);
            });
        },

        detalheChamado: function ($nr_chamado) {

            var params = {
                nr_chamado: $nr_chamado,
                gravar_leitura: 'sim'
            };

            this.doGet('atividades/atividades/listarChamadosPorNr', params).then(function (result) {
                this.result = result;
                this.$emit('seleciona_chamado', result);
            });
        },

        tiposChamado: function (value) {
            switch (value) {
                case '1':
                    return ['/beta/img/bug.svg', 'Bug']
                    break;
                case '2':
                    return ['/beta/img/enhancement.svg', 'Aprimoramento']
                    break;
                case '3':
                    return ['/beta/img/proposal.svg', 'Proposta']
                    break;
                case '4':
                    return ['/beta/img/task.svg', 'Tarefa']
                    break;
                default:
                    return ['', 'Não atribuido']
            }
        },

        tiposPrioridades: function (value) {
            switch (value) {
                case '1':
                    return ['/beta/img/trivial.svg', 'Trivial']
                    break;
                case '2':
                    return ['/beta/img/minor.svg', 'Baixa']
                    break;
                case '3':
                    return ['/beta/img/major.svg', 'Alta']
                    break;
                case '4':
                    return ['/beta/img/critical.svg', 'Crítica']
                    break;
                case '5':
                    return ['/beta/img/blocker.svg', 'Bloqueio']
                    break;
                default:
                    return 'Não atribuido'
            }
        },

        activeFiltrosChamado: function () {
            $('.btnfilter').click(function () {
                if (!$(this).hasClass('active-filtro')) {
                    $('.btnfilter').removeClass('active-filtro')
                    $('.btnfilter').removeClass('filtros-sec')
                    $('.btnfilter').addClass('filtros-sec')

                    $(this).removeClass('filtros-sec')
                    $(this).addClass('active-filtro')
                }
            });
        },

        setaFiltro: function ($id, $status) {

            var _this = this;

            var idUsuarioLogado = this.$root.USER.id;

            var params = {
                idFiltro: $id,
                idUsuario: idUsuarioLogado,
                status: $status
            };

            this.doPost('atividades/atividades/atualizaStatusFiltros', params).then(function (result) {
                _this.listaChamados('m');
            });
        },

        setColuna: function ($id, $status) {

            var _this = this;

            var idUsuarioLogado = this.$root.USER.id;

            var params = {
                idColuna: $id,
                idUsuario: idUsuarioLogado,
                status: $status
            }

            //this.$api.atividade.atualiza.statusColuna(
            this.doPost('atividades/atividades/atualizaStatusColunas', params).then(function (result) {
                _this.listaChamados('m');
            });
        },

        listaVagaDepartamento: function () {

            var params = {
                usuario_id: this.$root.USER.id,
                opcao: 'usuarios'
            };

            //this.$api.atividade.consultas.usuariosPorDepartamento(
            this.doGet('geral/vaga-departamento/listaVagaDepartamento', params).then(function (result) {
                for (var i in result) {
                    if (result[i].vaga_departamento_pai == null) {
                        this.gestor = true;
                    }
                }
            });
        }
    },

    data: function () {
        return {

            idFiltroPrincipal: '',
            result: {},
            arrayChamados: [],
            arrayFiltros: [],
            arrayColunas: [],
            gestor: false,
            idFiltroTodos: '',
            filtroTodos: false,
            idFiltroMeus: '',
            filtroMeus: false,
            idFiltroAbertos: '',
            filtroAbertos: false,
            idFiltroIniciados: '',
            filtroIniciados: false,
            idFiltroConcluidos: '',
            filtroConcluidos: false,
            idFiltroAvaliacao: '',
            filtroAvaliacao: false,
            idFiltroFinalizado: '',
            filtroFinalizado: false,
            countFilter: 0,
            controleFiltro: 'm',

            idColunaChamado: '',
            idColunaTitulo: '',
            idColunaCriadoIcon: '',
            idColunaTipoIcon: '',
            idColunaPrioridadeIcon: '',
            idColunaSituacao: '',
            idColunaDepartamento: '',
            idColunaCriadoPor: '',
            idColunaAtribuidoPor: '',
            idColunaCriadoEm: '',
            idColunaAtualizadoEm: '',
            idColunaAnexo: '',

            colunaNrChamado: true,
            colunaTitulo: false,
            colunaCriadoIcon: false,
            colunaTipoIcon: false,
            colunaPrioridadeIcon: false,
            colunaSituacao: false,
            colunaDepartamento: false,
            colunaCriadoPor: false,
            colunaAtribuidoPara: false,
            colunaCriadoEm: false,
            colunaAtualizadoEm: false,
            colunaAnexo: false,

            ativar_aba: false,

            perPage: 3,
            currentPage: 1,

        }
    }
};
</script>
