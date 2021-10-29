<template>
  <div>
    <div class="container-fluid">

      <!-- Page Heading -->
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashaboard - Fluxo de Atividades</h1>
<!--        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dash" @click="filtrosRelatorio" v-if="filter == false">-->
<!--          <i class="fas fa-filter fa-sm text-white-50"></i> Filtros-->
<!--        </button>-->
<!--        <button class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" @click="filterDash" v-if="filter == true">-->
<!--          <i class="fas fa-chart-line fa-sm text-white-50"></i> Dashboard-->
<!--        </button>-->
      </div>

      <transition name="fade">
        <div v-if="showDash">
          <!-- Content Row -->
          <!--          <div class="row">-->
          <!--            <template v-for="st in arrayChamadosStatus">-->
          <!--              &lt;!&ndash; Earnings (Monthly) Card Example &ndash;&gt;-->
          <!--              <div class="col-xl-3 col-md-6 mb-4" v-if="st.status_chamado == 'CE'">-->
          <!--                <div class="card border-left-primary shadow h-100 py-2">-->
          <!--                  <div class="card-body">-->
          <!--                    <div class="row no-gutters align-items-center">-->
          <!--                      <div class="col mr-2">-->
          <!--                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Caixa de Entrada</div>-->
          <!--                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ st.qtde_status }}</div>-->
          <!--                      </div>-->
          <!--                      <div class="col-auto">-->
          <!--                        <i class="fas fa-inbox fa-2x text-primary"></i>-->
          <!--                      </div>-->
          <!--                    </div>-->
          <!--                  </div>-->
          <!--                </div>-->
          <!--              </div>-->

          <!--              &lt;!&ndash; Earnings (Monthly) Card Example &ndash;&gt;-->
          <!--              <div class="col-xl-3 col-md-6 mb-4" v-if="st.status_chamado == 'IN'">-->
          <!--                <div class="card border-left-success shadow h-100 py-2">-->
          <!--                  <div class="card-body">-->
          <!--                    <div class="row no-gutters align-items-center">-->
          <!--                      <div class="col mr-2">-->
          <!--                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Iniciadas</div>-->
          <!--                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ st.qtde_status }}</div>-->
          <!--                      </div>-->
          <!--                      <div class="col-auto">-->
          <!--                        <i class="fas fa-hourglass-start fa-2x text-success"></i>-->
          <!--                      </div>-->
          <!--                    </div>-->
          <!--                  </div>-->
          <!--                </div>-->
          <!--              </div>-->

          <!--              &lt;!&ndash; Earnings (Monthly) Card Example &ndash;&gt;-->
          <!--              <div class="col-xl-3 col-md-6 mb-4" v-if="st.status_chamado == 'CO'">-->
          <!--                <div class="card border-left-warning shadow h-100 py-2">-->
          <!--                  <div class="card-body">-->
          <!--                    <div class="row no-gutters align-items-center">-->
          <!--                      <div class="col mr-2">-->
          <!--                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Conluídas</div>-->
          <!--                        <div class="row no-gutters align-items-center">-->
          <!--                          <div class="col-auto">-->
          <!--                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ st.qtde_status }}</div>-->
          <!--                          </div>-->
          <!--                        </div>-->
          <!--                      </div>-->
          <!--                      <div class="col-auto">-->
          <!--                        <i class="far fa-check-square fa-2x text-warning"></i>-->
          <!--                      </div>-->
          <!--                    </div>-->
          <!--                  </div>-->
          <!--                </div>-->
          <!--              </div>-->

          <!--              &lt;!&ndash; Pending Requests Card Example &ndash;&gt;-->
          <!--              <div class="col-xl-3 col-md-6 mb-4" v-if="st.status_chamado == 'CO'">-->
          <!--                <div class="card border-left-danger shadow h-100 py-2">-->
          <!--                  <div class="card-body">-->
          <!--                    <div class="row no-gutters align-items-center">-->
          <!--                      <div class="col mr-2">-->
          <!--                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Finalizadas</div>-->
          <!--                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ st.qtde_status }}</div>-->
          <!--                      </div>-->
          <!--                      <div class="col-auto">-->
          <!--                        <i class="far fa-clock fa-2x text-danger"></i>-->
          <!--                      </div>-->
          <!--                    </div>-->
          <!--                  </div>-->
          <!--                </div>-->
          <!--              </div>-->
          <!--            </template>-->
          <!--          </div>-->


          <div class="row">
            <div class="col-md-4">
              <QuantidadeChamados :dados="arrayChamadosStatus" :height="200"></QuantidadeChamados>
            </div>
            <div class="col-md-4">
              <PercentualChamados :dados="arrayChamadosStatus" :height="200"></PercentualChamados>
            </div>
            <div class="col-md-4">
              <PercentualAtividades :dados="arrayTotalAtividades" :height="200"></PercentualAtividades>
            </div>
          </div>
          <div class="row">
            <hr>
          </div>

          <!-- Content Row -->

          <div class="row">

            <div class="col-xl-12 col-md-12">
              <b-card class="shadow-lg mb-4">
                <template #header>
                  <span><i class="fa fa-tasks"></i> Totalizador de Chamados Por Departamento</span>
                </template>

                <template #default>
                  <div>
                    <table id="chamadosListados"
                           class="table tablesorter display responsive no-wrap listaChamadosDt lista-clientes table-striped">
                      <thead>
                      <tr>
                        <th>EMPRESA</th>
                        <th>DEPARTAMENTO</th>
                        <th>QTDE</th>
                      </tr>
                      </thead>
                      <tbody>
                      <template v-if="arrayChamadosDepartamento.length > 0">
                        <tr v-for="v in arrayChamadosDepartamento">
                          <td>{{v.empresa}}</td>
                          <td>{{v.departamento}}</td>
                          <td><b-badge variant="primary" style="padding: 6px;width: 40px;">{{v.qtde}}</b-badge></td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr>
                          <td colspan="22"><h5 style="font-weight: bold">Não há dados para serem exibidos!</h5></td>
                        </tr>
                      </template>
                      </tbody>
                    </table>
                  </div>
                </template>
                <template #footer></template>
              </b-card>
            </div>

          </div>
        </div>
        <div v-if="!showDash">
          <b-card class="shadow-lg mb-4">
            <template #header>
              <span><i class="fa fa-tasks"></i>  FILTROS</span>
            </template>

            <template #default>
              <div class="row">
                <div class="col-md-12 col-lg-6">
                  <div class="form-group">
                    <label>Empresa</label>
                    <v-select name="empresa" v-model="filtros.empresas" :options="empresasComputed">
                      <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                  </div>
                </div>
                <div class="col-md-12 col-lg-6">
                  <div class="form-group" >
                    <label>Departamento</label>
                    <v-select name="departamento" v-model="filtros.departamentos" :options="departamentosComputed">
                      <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-lg-6">
                  <div class="form-group">
                    <label>Atividade</label>
                    <v-select name="atividades" v-model="filtros.atividades" :options="atividadesComputed">
                      <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                  </div>
                </div>
                <div class="col-md-12 col-lg-6">
                  <div class="form-group">
                    <label>Subatividade</label>
                    <v-select name="subatividades" v-model="filtros.subAtividades" :options="subAtividadesComputed">
                      <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-lg-6">
                  <div class="form-group">
                    <label>Criador</label>
                    <v-select name="criador" v-model="filtros.criador" :options="usuariosComputed">
                      <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                  </div>
                </div>
                <div class="col-md-12 col-lg-6">
                  <div class="form-group">
                    <label>Executor</label>
                    <v-select name="executor" v-model="filtros.executor" :options="usuariosComputed">
                      <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 col-lg-3">
                  <div class="form-group">
                    <label>Tipo</label>
                    <b-form-select v-model="filtros.tipo_chamado">
                      <b-form-select-option v-for="t in tipos"
                                            :key="t.value"
                                            :value="t.value">{{t.text}}
                      </b-form-select-option>
                    </b-form-select>
                  </div>
                </div>
                <div class="col-md-3 col-lg-3">
                  <div class="form-group" :class="{'has-error': l_.get(validationResponse,'id_subatividade')}">
                    <label>Prioridade</label>
                    <b-form-select v-model="filtros.prioridade_chamado">
                      <b-form-select-option v-for="p in prioridades"
                                            :key="p.value"
                                            :value="p.value">{{p.text}}
                      </b-form-select-option>
                    </b-form-select>
                    <small class="invalid-feedback"
                           v-if="l_.get(validationResponse,'id_subatividade')">{{
                        l_.get(validationResponse,'id_subatividade').join(' ') }}</small>
                  </div>
                </div>
                <div class="col-md-3 col-lg-3">
                  <div class="form-group">
                    <label>Data Início</label>
                    <InputDate type="text"
                               :config="{}"
                               v-model="filtros.data_inicio"
                               id="data_inicio"
                               name="data_inicio">
                    </InputDate>
                  </div>
                </div>
                <div class="col-md-3 col-lg-3">
                  <div class="form-group">
                    <label>Data Fim</label>
                    <InputDate type="text"
                               :config="{}"
                               v-model="filtros.data_fim"
                               id="data_fim"
                               name="data_fim">
                    </InputDate>
                  </div>
                </div>
              </div>
            </template>

            <template #footer>
              <div>
                <div class="mt-3" style="float: right">
                  <b-button-group>
                    <b-button variant="success" @click="filtrarRelatorio"><i class="fas fa-filter"></i> Filtrar</b-button>
                    <b-button variant="secondary" @click="limparFiltros"><i class="fas fa-eraser"></i> Limpar</b-button>
                  </b-button-group>
                </div>
              </div>
            </template>
          </b-card>

          <b-card class="shadow-lg">
            <template #header>
              <span><i class="fa fa-list"></i> LISTA DE CHAMADOS</span>
            </template>

            <template #default>
              <table
                  class="table tablesorter display responsive no-wrap table-striped">
                <thead>
                <tr>
                  <th>CHAMADO</th>
                  <th>TITULO</th>
                  <th>AÇÕES</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="resultadoDetalhado.length > 0">
                  <tr v-for="is in resultadoDetalhado">
                    <td>{{is.nr_chamado}}</td>
                    <td>{{is.titulo_chamado}}</td>
                    <td>
                      <b-button-group>
                        <b-button variant="primary" @click="detalheChamado(is.nr_chamado)"><i class="fas fa-info-circle"></i> detalhar</b-button>
                      </b-button-group>
                    </td>
                  </tr>
                </template>
                <template v-else>
                  <tr style="text-align: center">
                    <td colspan="22"><h5 style="font-weight: bold">Não há dados para serem exibidos!</h5></td>
                  </tr>
                </template>
                </tbody>
              </table>
            </template>

            <template #footer>

            </template>
          </b-card>
        </div>
      </transition>
    </div>

    <!-- Modal Detalhe do Chamado -->
    <b-modal ref="my-modal" size="xl" hide-footer title="Relatório Chamados">
      <div id="printMe">
        <div class="row">
          <br>
          <div class="col-md-12 col-lg-6" id="topo-hist-chamado">
            <div class="form-group">
              <ul class="list-group list-group-flush">
                <li class="list-group-item active" style="font-size: 12px">
                  <span style="font-weight: bold"><i class="fa fa-building"></i> EMPRESA:</span>
                  <span class="nr_chamado_mod" style="padding-left: 20px; padding-right: 20px"
                        v-if="resultadoDetalhado.empresa_chamado">{{ resultadoDetalhado.empresa_chamado.nome }}</span>
                  <span>&nbsp;&nbsp;</span>
                  <span style="font-weight: bold"><i class="fa fa-briefcase"></i> DEPARTAMENTO:</span>
                  <span class="nr_chamado_mod" style="padding-left: 20px; padding-right: 20px"
                        v-if="resultadoDetalhado.departamento_chamado">{{ resultadoDetalhado.departamento_chamado.nome }}</span>
                </li>
                <li class="list-group-item">
                  <b-button variant="primary">
                    Chamado
                    <b-badge variant="light">#{{ resultadoDetalhado.nr_chamado }}</b-badge>
                  </b-button>
                </li>
                <!--              <li class="list-group-item"><span style="font-weight: bold">Avaliação: </span>-->
                <!--                <span class="avlia0" style="color: darkred; font-weight: bold"> {{ chamadoAvaliado }}</span>-->
                <!--                <span class="avalia1" style="color: #ffd042"> {{ chamadoAvaliado }}</span>-->
                <!--                <span class="avalia2" style="color: #ffd042"> {{ chamadoAvaliado }}</span>-->
                <!--                <span class="avalia3" style="color: #ffd042"> {{ chamadoAvaliado }}</span>-->
                <!--                <span class="avalia4" style="color: #ffd042"> {{ chamadoAvaliado }}</span>-->
                <!--                <span class="avalia5" style="color: #ffd042"> {{ chamadoAvaliado }}</span>-->
                <!--              </li>-->
                <li class="list-group-item"><span style="font-weight: bold">Título: </span> <span
                    class="titulo_chamado_mod">{{ resultadoDetalhado.titulo_chamado }}</span></li>
                <li class="list-group-item"><span style="font-weight: bold"> Descrição: </span> <span
                    class="descricao_chamado_mod" v-html="resultadoDetalhado.descricao_chamado"></span></li>

              </ul>
            </div>
          </div>
          <div class="col-md-12 col-lg-6">
            <div class="form-group">
              <ul class="list-group">
                <li class="list-group-item"><span style="font-weight: bold">Criado por: </span><span
                    class="atribuido_mod"
                    v-if="resultadoDetalhado.atividade_chamado">{{ l_.get(resultadoDetalhado, 'usuario_criador.nome', 'Nao definido') }}</span>
                <li class="list-group-item"><span style="font-weight: bold">Atividade: </span><span
                    class="atribuido_mod"
                    v-if="resultadoDetalhado.atividade_chamado">{{ resultadoDetalhado.atividade_chamado.nm_atividade }}</span>

                <li class="list-group-item"><span style="font-weight: bold">Subatividade: </span><span
                    class="atribuido_mod"
                    v-if="resultadoDetalhado.subatividade_chamado">{{ resultadoDetalhado.subatividade_chamado.nm_subatividade }}</span>
                <li class="list-group-item"><span style="font-weight: bold">Atribuido: </span>
                  <span class="atribuido_mod"
                        v-if="resultadoDetalhado.usuario_executor">{{ resultadoDetalhado.usuario_executor.nome }}</span>
                </li>
                <li class="list-group-item"
                    v-if="l_.get(resultadoDetalhado, 'atividade_chamado') && l_.get(resultadoDetalhado, 'atividade_chamado.moderada') == 'S'"
                    style="background-color: #cccccc26;">
                            <span style="font-weight: bold"><i class="fas fa-crown"
                                                               style="color: #fadd4d; font-weight: bold"></i> Moderador: </span>
                  <span class="atribuido_mod"
                        v-if="resultadoDetalhado.usuario_executor">{{ resultadoDetalhado.usuario_executor.nome }}</span>
                </li>
                <li class="list-group-item"><span style="font-weight: bold">Tipo: </span> <span
                    class="tipo_mod">
                <img :src="tiposChamado(resultadoDetalhado.tipo_chamado)[0]"/>{{ tiposChamado(resultadoDetalhado.tipo_chamado)[1] }}
</span>
                </li>
                <li class="list-group-item"><span style="font-weight: bold">Prioridade: </span> <span
                    class="prioridade_mod">
                <img :src="tiposPrioridades(resultadoDetalhado.prioridade_chamado)[0]"
                     style="width: 20px; height: 20px"/>
                                {{ tiposPrioridades(resultadoDetalhado.prioridade_chamado)[1] }}
              </span>
                </li>
                <li class="list-group-item"><span style="font-weight: bold">Situação: </span>
                  <a v-bind:class="[{ concluido: resultadoDetalhado.status_chamado == 'CO'},
												  {finalizado: resultadoDetalhado.status_chamado == 'FN'},
												  {status_ce: resultadoDetalhado.status_chamado != 'CO' && resultadoDetalhado.status_chamado != 'FN'}]"
                     href="#">
                    <span v-if="(resultadoDetalhado.moderou_chamado == '1' || resultadoDetalhado.moderou_chamado === '2')">{{ resultadoDetalhado.status_chamado|statusChamado }}</span>
                    <span v-if="resultadoDetalhado.status_chamado != 'CO' && resultadoDetalhado.status_chamado != 'FN' && resultadoDetalhado.moderou_chamado === '0'">Em Moderação</span>
                    <span v-if="resultadoDetalhado.status_chamado == 'CO' && resultadoDetalhado.moderou_chamado === '0'">{{ resultadoDetalhado.status_chamado|statusChamado }}</span>
                    <span v-if="resultadoDetalhado.status_chamado == 'FN' && resultadoDetalhado.moderou_chamado === '0'">{{ resultadoDetalhado.status_chamado|statusChamado }}</span>
                  </a>
                </li>
                <li class="list-group-item"><span style="font-weight: bold">Estimativa: </span>
                  <h5>{{resultadoDetalhado.estimativa_horas|datas}}</h5>
                </li>
                <li class="list-group-item" style="text-align: right">
                  <button class="btn btn-primary" @click="print"><i class="fa fa-print"></i> Imprimir</button>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <hr>

        <div class="historicoChamado" style="background-color: rgb(245,246,249)">
          <div v-for="(dados, index) in arrayIteracoes">
            <div class="timeline-item" style="padding: 50px" :date-is="index|datas">
              <div id="corpo" v-for="(d, i) in dados" style="background-color: white">

                <h4><span
                    style="font-weight: bold"> {{ l_.get(d, 'usuario_criador_iteracao.nome', 'Nao Definido') }}</span>
                </h4>

                <!--Chamados Compartilhados-->
                <hr v-if="d.chamados_compartilhados.length > 0">

                <h6 v-if="d.chamados_compartilhados.length > 0" style="color: #4176ac; ">
                  <span style="font-weight: bold"><i class="fas fa-share-alt"></i> COMPARTILHOU COM</span>
                </h6>
                <ul class="list-group">
                  <template v-for="us in d.chamados_compartilhados">
                    <li class="list-group-item"
                        style="font-size: 0.9em; border-left: 4px solid rgb(65, 118, 172); background-color: #f8f9fc;"
                        v-if="(l_.get(us, 'usuario.id') != null) &&
                                               (l_.get(us, 'usuario.id') != (l_.get(resultadoDetalhado,'usuario_criador.id'))) &&
                                               (l_.get(resultadoDetalhado, 'usuario_executor.id') != (l_.get(us, 'usuario.id')))">
                      <i class="fa fa-user-tie"></i>
                      <!--                                    {{l_.get(us, 'usuario.nome')}}-->
                      <span style="font-weight: bold"
                            v-if="l_.get(us,'departamento_id') == l_.get(us, 'departamentos.id')">
                                              {{ l_.get(us, 'usuario.nome') }} <b-badge variant="info"
                                                                                        style="padding: 9px">{{ l_.get(us, 'departamentos.nome') }}</b-badge>
                                          </span>
                    </li>
                  </template>
                </ul>

                <!--Chamados Moderados-->
                <hr v-if="d.chamados_moderados">

                <h6 v-if="d.chamados_moderados" style="color: rgb(121, 85, 72); ">
                                  <span style="font-weight: bold"> <i
                                      class="fas fa-exchange-alt"></i> CHAMADO MODERADO PARA</span>
                </h6>

                <ul class="list-group" v-if="d.chamados_moderados">
                  <li class="list-group-item"
                      style="font-size: 0.9em; border-left: 4px solid rgb(121, 85, 72);; background-color: rgba(121, 85, 72, 0.15);">
                    <i class="fa fa-user-tie"></i> <span
                      style="font-weight: bold">{{ d.chamados_moderados.usuario_moderado.nome }}</span></li>
                </ul>

                <hr v-if="d.justificativa_iteracao_chamado != ''">
                <h5 style="margin-left: 10px;" v-html="d.justificativa_iteracao_chamado"></h5>

                <hr>

                <div class="row">
                  <div class="col-md-12 col-sm-12"
                       style="text-align: right; font-weight: bold; color: rgb(65, 118, 172)">
                    <p><i class="fa fa-time"></i> {{ d.criado_em|datas('L LT') }}</p>
                  </div>
                </div>

                <hr v-if="d.anexo_chamado_por_iteracao.length > 0">

                <div class="row" v-if="d.anexo_chamado_por_iteracao.length > 0">
                  <div class="col-md-12">
                    <template v-for="sd in d.anexo_chamado_por_iteracao">
                      <span style="font-weight: bold" v-if="sd.iteracao_id == d.id">Anexo: </span>
                      <a :href="sd.url" target="_blank"
                         style="color: #000; font-size: 14px; font-weight: bold; text-decoration: underline!important;">
                        <h5 v-if="sd.iteracao_id == d.id">
                          <b-badge variant="primary">{{ sd.nome_anexo }}</b-badge>
                        </h5>
                      </a>

                    </template>
                  </div>
                </div>
              </div>
              <hr>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
    <!-- End Modal Detalhe -->

  </div>
</template>

<script>

import QuantidadeChamados from "@/components/atividade/QuantidadeChamados";
import PercentualChamados from "@/components/atividade/PercentualChamados";
import PercentualAtividades from "@/components/atividade/PercentualAtividades";
import InputDate from "../../geral/singleForm/InputDate";
import Lista from "@/components/atividade/Lista";
import HistoricoIteracao from "@/components/atividade/HistoricoIteracao";
import CriarChamado from "@/components/atividade/CriarChamado";
import Card from "@/components/geral/Card";
import Bugs from "@/assets/fluxo/bug.svg";
import Enhancement from "@/assets/fluxo/enhancement.svg";
import Proposal from "@/assets/fluxo/proposal.svg";
import Task from "@/assets/fluxo/task.svg";
import Trivial from "@/assets/fluxo/trivial.svg";
import Minor from "@/assets/fluxo/minor.svg";
import Major from "@/assets/fluxo/major.svg";
import Critical from "@/assets/fluxo/critical.svg";
import Blocker from "@/assets/fluxo/blocker.svg";

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
  name: "DashboardRelatorios",

  components: {PercentualAtividades, QuantidadeChamados,InputDate,PercentualChamados, PercentualAtividades},

  mounted: function () {

    this.listarTotalChamadosPorDepartamento();
    this.listarTotalChamadosPorStatus();
    this.listarTotalAtividades();

    this.getEmpresas();

  },

  filters: {
    statusChamado: filtroChamado,

    datas: function (value, args) {
      if (value) {
        return moment(String(value)).format(args ? args : 'DD/MM/YYYY');
      }
    },
  },

  methods: {

    print () {
      // Pass the element id here
      this.$htmlToPaper('printMe');
    },

    tiposChamado: function (value) {
      switch (value) {
        case '1':
          return [Bugs, 'Bug']
          break;
        case '2':
          return [Enhancement, 'Aprimoramento']
          break;
        case '3':
          return [Proposal, 'Proposta']
          break;
        case '4':
          return [Task, 'Tarefa']
          break;
        default:
          return ['', 'Não atribuido']
      }
    },

    tiposPrioridades: function (value) {
      switch (value) {
        case '1':
          return [Trivial, 'Trivial']
          break;
        case '2':
          return [Minor, 'Baixa']
          break;
        case '3':
          return [Major, 'Alta']
          break;
        case '4':
          return [Critical, 'Crítica']
          break;
        case '5':
          return [Blocker, 'Bloqueio']
          break;
        default:
          return 'Não atribuido'
      }
    },

    listarTotalChamadosPorDepartamento: function (){
      this.doGet('atividades/atividades/listarTotalChamadosPorDepartamento', {paginate: 'false'}).then(function (result) {
        this.arrayChamadosDepartamento = result;
      });
    },

    listarTotalChamadosPorStatus: function (){
      this.doGet('atividades/atividades/listarTotalChamadosPorStatus', {paginate: 'false'}).then(function (result) {
        this.arrayChamadosStatus = result;
      });
    },

    listarTotalAtividades: function (){
      this.doGet('atividades/atividades/listarTotalAtividades', {paginate: 'false'}).then(function (result) {
        this.arrayTotalAtividades = result;
      });
    },

    getEmpresas: function () {

      this.doGet('geral/clientes/listarTodasEmpresas').then(function (result) {
        this.arrayEmpresas = result;
      });
    },

    getDepartamentos: function (value) {

      var params = {
        idEmpresa: value
      };

      this.doGet('geral/departamentos/listarDepartamentosPorEmpresa', params).then(function (result) {
        this.arrayDepartamentos = result;
      });
    },

    getAtividadesPorDepartamento: function (value) {
      this.doGet('atividades/atividades/listarAtividadesCadastradas', {idDepartamento: value}).then(function (result) {
        this.arrayAtividades = result;
      });
    },

    getUsuariosPorDepartamento: function (value) {

      var params = {
        departamento_id: value,
        opcao: 'usuarios'
      };

      this.doGet('geral/vaga-departamento/listaVagaDepartamento', params).then(function (result) {

        var _this = this;

        $.each(result, function (index, value) {
          $.each(_.get(value, 'usuario.vaga_departamentos'), function (indice, val) {
            if (_.get(val, 'vaga_departamento_pai', 1) == null) {
              _this.isGestor = val.usuario_id;
            }
          });
        });

        this.arrayUsuarios = result;
      });
    },

    getSubatividadesPorIdAtividade: function (value) {

      var params = {
        idAtividade: value
      };

      this.doGet('atividades/atividades/listarSubAtividadePorAtividade', params).then(function (result) {
        this.arraySubAtividade = result;
      });
    },

    filtrosRelatorio: function (){

      var _this = this;

      _this.showDash = false;
      _this.filter = true;
    },

    filterDash: function (){
      var _this = this;

      _this.showDash = true;
      _this.filter = false;

    },

    filtrarRelatorio: function (){

      var _this = this;

      var params = {
        empresa_id: _this.filtros.empresas.value,
        departamento_id: _this.filtros.departamentos.value,
        atividade_id: _this.filtros.atividades.value,
        subatividades_id: _this.filtros.subAtividades.value,
        criador_id: _this.filtros.criador.value,
        executor_id: _this.filtros.executor.value,
        tipo: _this.filtros.tipo_chamado,
        prioridade: _this.filtros.prioridade_chamado,
        data_inicio: _this.filtros.data_inicio,
        data_fim: _this.filtros.data_fim
      };

      this.doGet('atividades/atividades/filtrosRelatorioChamados', params).then(function (result) {
        _this.resultadoDetalhado = result;
      });
    },

    detalheChamado: function ($nr_chamado) {

      var params = {
        nr_chamado: $nr_chamado
      };

      this.doGet('atividades/atividades/listarChamadosPorNr', params).then(function (result) {

        this.resultadoDetalhado = result;
        this.result = result;

        this.listarHistorico(this.resultadoDetalhado.id);

        this.$refs['my-modal'].show();
      });
    },

    listarHistorico: function ($idChamado) {

      var params = {
        id_chamado: $idChamado
      };

      this.doGet('atividades/atividades/listarHistorico', params).then(function (result) {

        var arrayNomesInteracoes = [];
        var arrayNomesCompartilhados = [];

        this.arrayIteracoes = result;

        $.each(this.arrayIteracoes, function (index, value) {
          $.each(value, function (i, v) {

            if (v.chamados_compartilhados.length > 0) {
              $.each(v.chamados_compartilhados, function (s, w) {
                if (arrayNomesCompartilhados.includes(w.usuario.nome)) {
                  // console.log('compart', w.usuario);
                } else {
                  arrayNomesCompartilhados.push(w.usuario.nome);
                }
              });
            }
            if (arrayNomesInteracoes.includes(v.usuario_criador_iteracao.nome)) {
              // console.log('true');
            } else {
              arrayNomesInteracoes.push(v.usuario_criador_iteracao.nome);
            }
          });
        });

        this.arrayNomesInteracoesAux = arrayNomesInteracoes;
        this.arrayNomesCompartilhadossAux = arrayNomesCompartilhados;

        this.arrayParticipantesAux = _.union(this.arrayNomesInteracoesAux, this.arrayNomesCompartilhadossAux);

      });
    },

    limparFiltros: function (){
      this.filtros.empresas= '';
      this.filtros.departamentos= '';
      this.filtros.atividades= '';
      this.filtros.subAtividades= '';
      this.filtros.criador= '';
      this.filtros.executor= '';
      this.filtros.tipo_chamado= '';
      this.filtros.prioridade_chamado= '';
      this.filtros.data_inicio= '';
      this.filtros.data_fim= '';
      this.resultadoDetalhado = []
    }
  },

  watch: {

    'filtros.empresas': function (valor) {
      if (_.get(valor, 'dados.id')) {
        this.getDepartamentos(valor.dados.id);
      }
    },

    'filtros.departamentos': function (valor) {
      if (_.get(valor, 'dados.id')) {
        this.getAtividadesPorDepartamento(valor.dados.id);
        this.getUsuariosPorDepartamento(valor.dados.id);
      }
    },

    'filtros.atividades': function (valor) {
      if (_.get(valor, 'dados.id')) {
        this.getSubatividadesPorIdAtividade(valor.dados.id);
      }
    },
  },

  computed:{

    empresasComputed: function () {
      return _.map(this.arrayEmpresas, function (item) {
        return {
          label: item.nome,
          value: item.id,
          dados: item
        };
      });
    },

    departamentosComputed: function () {
      return _.map(this.arrayDepartamentos, function (item) {
        return {
          label: item.nome,
          value: item.id,
          dados: item
        };
      });
    },

    atividadesComputed: function () {
      return _.map(this.arrayAtividades, function (item) {
        return {
          label: item.nm_atividade,
          value: item.id,
          dados: item
        };
      });
    },

    subAtividadesComputed: function () {
      return _.map(this.arraySubAtividade, function (item) {
        return {
          label: item.nm_subatividade,
          value: item.id,
          dados: item
        };
      });
    },

    usuariosComputed: function () {
      return _.map(this.arrayUsuarios, function (item) {

        return {
          label: _.get(item, 'usuario.nome'),
          value: _.get(item, 'usuario.id'),
          dados: _.get(item, 'usuario')
        };
      });
    },

  },

  data: function () {
    return {
      arrayChamadosDepartamento: [],
      arrayChamadosStatus: [],
      arrayTotalAtividades: [],
      showDash: true,
      filter: false,
      arrayEmpresas: [],
      arrayDepartamentos: [],
      arrayAtividades: [],
      arrayUsuarios: [],
      arraySubAtividade: [],
      resultadoDetalhado: [],
      arrayIteracoes: [],
      objTestes: {},

      filtros: {
        empresas: '',
        departamentos: '',
        atividades: '',
        subAtividades: '',
        criador: '',
        executor: '',
        tipo_chamado: '',
        prioridade_chamado: '',
        data_inicio: '',
        data_fim: ''
      },

      tipos: [
        {value: '', text: 'Selecione o tipo'},
        {value: '1', text: 'ERRO'},
        {value: '2', text: 'APRIMORAMENTO'},
        {value: '3', text: 'PROPOSTA'},
        {value: '4', text: 'TAREFA'},
      ],

      prioridades: [
        {value: '', text: 'Selecione a prioridade'},
        {value: '1', text: 'TRIVIAL'},
        {value: '2', text: 'BAIXA'},
        {value: '3', text: 'ALTA'},
        {value: '4', text: 'CRÍTICA'},
        {value: '5', text: 'BLOQUEIO'},
      ],

    };
  }

};

</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .8s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active em versões anteriores a 2.1.8 */ {
  opacity: 0;
}

.concluido {
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
  background-color: rgba(110, 0, 0, 0.62);
  color: #fff;
  cursor: pointer;
  width: 166.63px;
  height: 22px;
}

.finalizado {
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
  background-color: rgba(6, 79, 7, 0.62);
  color: #fff;
  cursor: pointer;
  width: 166.63px;
  height: 22px;
}

.status_ce {
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
  text-decoration: none !important;
  text-transform: uppercase;
  white-space: nowrap;
  background-color: #cccccc21;
  cursor: pointer;
  width: 166.63px;
  height: 22px;
}

/*time line*/

.timeline-item {
  padding: 3em 2em 2em;
  position: relative;
  color: rgba(0, 0, 0, 0.7);
  border-left: 2px solid #36464e;
}

.timeline-item p {
  font-size: 1rem;
}

.timeline-item::before {
  content: attr(date-is);
  position: absolute;
  left: 1em;
  font-weight: bold;
  top: 1em;
  display: block;
  /*font-family: 'Roboto', sans-serif;*/
  font-weight: 700;
  font-size: 1.2rem;
  border: 1px solid #e1f2fb;
  background-color: #e1f2fb;
  color: #7b8185;
  padding: 5px;
  border-radius: 5px;
}

.timeline-item::after {
  width: 15px;
  height: 15px;
  display: block;
  top: 2em;
  position: absolute;
  left: -8px;
  border-radius: 10px;
  content: '';
  border: 2px solid rgb(65, 118, 172);
  background: rgb(65, 118, 172);
}

.timeline-item:last-child {
  border-color: rgb(65, 118, 172);
  /*-o-border-image: linear-gradient(to bottom, rgb(65, 118, 172) 60%, transparent) 1 100%;*/
  /*border-image: -webkit-linear-gradient(top, rgb(65, 118, 172) 60%, transparent) 1 100%;*/
  /*border-image: linear-gradient(to bottom, rgb(65, 118, 172) 60%, transparent) 1 100%;*/
}

#corpo {
  border: 1px solid #ccc;
  margin-top: 20px;
  border-radius: 0px 10px 10px 10px;
  padding: 10px;
  margin-bottom: 10px;
}

#justificativa_iteracao_chamado {
  background-color: rgb(248, 249, 252);
}

</style>