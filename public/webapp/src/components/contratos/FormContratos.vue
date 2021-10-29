<template>
  <div>
    <b-row>
      <b-col md="6">
        <b-form-group>
          <b-card header="DADOS DA PROPOSTA"
                  header-text-variant="white"
                  header-tag="header"
                  header-bg-variant="primary">
            <b-col md="12" style="float: left">
              <b-list-group-item style="background-color: #cccccc2e">Propostas Aprovadas:
                <span v-for="pr in arrayAnexoPropostas">
                  <span v-if="pr.status_proposta == '2'"> | </span>
                  <b-badge variant="primary" style="font-size: 15px" v-if="pr.status_proposta == '2'"> {{pr.codigo_proposta}}</b-badge>
                </span>
              </b-list-group-item>
            </b-col>
          </b-card>
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group>
          <b-card header="DADOS DO CONTRATO"
                  header-text-variant="white"
                  header-tag="header"
                  header-bg-variant="secondary">
            <b-col md="12" style="float: left">
              <b-list-group-item style="background-color: #cccccc2e">Contrato: <span style="font-weight: bold"><b-badge variant="warning" style="font-size: 15px"> {{contrato.codigo_contrato}} </b-badge></span></b-list-group-item>
              <b-list-group-item style="background-color: #cccccc2e">
                <span style="font-weight: bold">
                  <b-row>
                    <b-col>
                      <b-form-group label="Data Início">
                        <b-form-datepicker
                            locale="pt-BR"
                            :date-format-options="{}"
                            :disabled="contrato.status == '2'"
                            placeholder="Selecionar"
                            v-model="contrato.data_inicio">
                        </b-form-datepicker>
                      </b-form-group>
                    </b-col>
                    <b-col>
                      <b-form-group label="Data Fim">
                        <b-form-datepicker
                            locale="pt-BR"
                            :date-format-options="{}"
                            :disabled="contrato.status == '2'"
                            placeholder="Selecionar"
                            v-model="contrato.data_fim">
                        </b-form-datepicker>
                      </b-form-group>
                    </b-col>
                  </b-row>
                </span>
              </b-list-group-item>
              <b-list-group-item style="background-color: #cccccc2e">Descrição Contrato: <span style="font-weight: bold">{{proposta.descricao_pre_contrato}}</span></b-list-group-item>
              <b-list-group-item style="background-color: #cccccc2e">Status Contrato:
                <span style="font-weight: bold">
                  <b-badge variant="info" style="font-size: 15px" v-if="contrato.status != '2'"> EM DIGITAÇÃO </b-badge>
                  <b-badge variant="success" style="font-size: 15px" v-if="contrato.status == '2'"> FINALIZADO </b-badge>
                </span>
              </b-list-group-item>
            </b-col>
          </b-card>
        </b-form-group>
      </b-col>
    </b-row>

    <hr>
    <b-row>
      <b-col style="text-align: right">
        <b-button-group>
          <b-button variant="outline-primary"
                    @click="finalizarContrato(contrato.contrato_id)" v-if="contrato.status != '2'"
                    :disabled="!verificaDadas">
            <i class="fas fa-file-alt"></i> Finalizar Contrato
          </b-button>

        </b-button-group>
      </b-col>
    </b-row>
    <hr>

    <b-row>
      <b-col md="12">
        <b-card border-variant="primary"
                header-bg-variant="primary"
                header-text-variant="white">
          <template #header>
            <i class='fa fa-file'></i> DOCUMENTOS
          </template>
          <table class="table table-bordered table-condensed">
            <thead>
            <th>Descrição Anexo</th>
            </thead>
            <tbody>
            <template v-if="arrayAnexoPropostas.length > 0">
              <tr v-for="ap in arrayAnexoPropostas">
                <td v-if="ap.status_proposta == '2' && ap.nome_documento != ''"><a :href="ap.img_thumb" target="_blank">{{ap.nome_documento}}</a></td>
              </tr>
            </template>
            <template v-else>
              <tr>
                <td colspan="22">Nenhum documento adicionado</td>
              </tr>
            </template>
            </tbody>
          </table>

        </b-card>
      </b-col>
    </b-row>

    <!--        <br>-->

    <!--        <b-row>-->
    <!--            <b-col md="12">-->
    <!--                <b-card header="DADOS FINANCEIROS"-->
    <!--                        header-tag="header"-->
    <!--                        border-variant="primary"-->
    <!--                        header-bg-variant="primary"-->
    <!--                        header-text-variant="white">-->
    <!--                    <template>-->
    <!--                        <div>-->
    <!--                            <b-row>-->
    <!--                                <b-col>-->
    <!--                                    <b-form-group-->
    <!--                                            id="valorPeriodo"-->
    <!--                                            label="Período (R$)"-->
    <!--                                            label-for="valorPeriodo">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col>-->
    <!--                                    <b-form-group-->
    <!--                                            id="valorTotal"-->
    <!--                                            label="Valor Total (R$)"-->
    <!--                                            label-for="valorTotal">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col>-->
    <!--                                    <b-form-group-->
    <!--                                            id="nrParcelas"-->
    <!--                                            label="Número de Parcelas"-->
    <!--                                            label-for="nrParcelas">-->
    <!--                                        <b-form-spinbutton id="nrParcelas" v-model="value" min="1" max="100"></b-form-spinbutton>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col>-->
    <!--                                    <b-form-group-->
    <!--                                            id="intervalo"-->
    <!--                                            label="Intervalo"-->
    <!--                                            label-for="intervalo">-->
    <!--                                        <b-form-select></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col>-->
    <!--                                    <b-form-group-->
    <!--                                            id="entrada"-->
    <!--                                            label="Entrada (R$)"-->
    <!--                                            label-for="entrada">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col>-->
    <!--                                    <b-form-group>-->
    <!--                                        <label style="visibility: hidden">Salvar</label>-->
    <!--                                        <b-button variant="outline-primary"-->
    <!--                                                  class="d-block"><i class="fa fa-cogs"></i> Duplicatas</b-button>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                            <b-row>-->
    <!--                                <b-col>-->
    <!--                                    <b-card no-body class="shadow-lg">-->

    <!--                                        <template #header>-->
    <!--                                            <i class="fa fa-mobile"></i> Duplicatas Geradas-->
    <!--                                        </template>-->

    <!--                                        <template>-->
    <!--                                            <b-form-group>-->
    <!--                                                <table class="table table-striped">-->
    <!--                                                    <thead>-->
    <!--                                                    <tr>-->
    <!--                                                        <th scope="col">#Duplicata</th>-->
    <!--                                                        <th scope="col">Data Vencimento </th>-->
    <!--                                                        <th scope="col">Valor</th>-->
    <!--                                                        <th scope="col" style="text-align: center">Ação</th>-->
    <!--                                                    </tr>-->
    <!--                                                    </thead>-->
    <!--                                                    <tbody>-->
    <!--                                                    <tr>-->
    <!--                                                        <th style="width: 20%">20200601</th>-->
    <!--                                                        <td style="width: 20%">10/07/2020</td>-->
    <!--                                                        <td style="width: 20%">R$ 75.000,00</td>-->
    <!--                                                        <td style="width: 40%; text-align: right">-->
    <!--                                                            <div class="btn-group" role="group" aria-label="Basic example">-->
    <!--                                                                <button type="button" class="btn btn-success"><i class="fa fa-check-circle"></i> Baixar</button>-->
    <!--                                                                <button type="button" class="btn btn-info"><i class="fa fa-print"></i> Imprimir</button>-->
    <!--                                                                <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Excluir</button>-->
    <!--                                                            </div>-->
    <!--                                                        </td>-->
    <!--                                                    </tr>-->
    <!--                                                    <tr>-->
    <!--                                                        <th style="width: 20%">20200601</th>-->
    <!--                                                        <td style="width: 20%">10/07/2020</td>-->
    <!--                                                        <td style="width: 20%">R$ 75.000,00</td>-->
    <!--                                                        <td style="width: 40%; text-align: right">-->
    <!--                                                            <div class="btn-group" role="group" aria-label="Basic example">-->
    <!--                                                                <button type="button" class="btn btn-success"><i class="fa fa-check-circle"></i> Baixar</button>-->
    <!--                                                                <button type="button" class="btn btn-info"><i class="fa fa-print"></i> Imprimir</button>-->
    <!--                                                                <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Excluir</button>-->
    <!--                                                            </div>-->
    <!--                                                        </td>-->
    <!--                                                    </tr>-->
    <!--                                                    <tr>-->
    <!--                                                        <th style="width: 20%">20200601</th>-->
    <!--                                                        <td style="width: 20%">10/07/2020</td>-->
    <!--                                                        <td style="width: 20%">R$ 75.000,00</td>-->
    <!--                                                        <td style="width: 40%; text-align: right">-->
    <!--                                                            <div class="btn-group" role="group" aria-label="Basic example">-->
    <!--                                                                <button type="button" class="btn btn-success"><i class="fa fa-check-circle"></i> Baixar</button>-->
    <!--                                                                <button type="button" class="btn btn-info"><i class="fa fa-print"></i> Imprimir</button>-->
    <!--                                                                <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Excluir</button>-->
    <!--                                                            </div>-->
    <!--                                                        </td>-->
    <!--                                                    </tr>-->
    <!--                                                    </tbody>-->
    <!--                                                </table>-->

    <!--                                                <b-pagination-->
    <!--                                                        v-model="currentPage"-->
    <!--                                                        :total-rows="rows"-->
    <!--                                                        :per-page="perPage"-->
    <!--                                                        aria-controls="my-table"-->
    <!--                                                        align="center">-->
    <!--                                                </b-pagination>-->

    <!--                                            </b-form-group>-->
    <!--                                        </template>-->
    <!--                                    </b-card>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                        </div>-->
    <!--                    </template>-->
    <!--                </b-card>-->
    <!--            </b-col>-->
    <!--        </b-row>-->

    <br>

    <b-row>
      <b-col md="12">
        <b-card header="RECURSOS DO CONTRATO"
                header-tag="header"
                border-variant="primary"
                header-bg-variant="primary"
                header-text-variant="white">
          <template>
            <div>
              <b-row>
                <b-col md="2">
                  <b-form-group label="Tipo">
                    <b-form-select
                        name="tipoProduto"
                        v-model="dadosProdutosLista.tipo"
                        :options="l_.map(tipoProduto, (i, k) => ({value: k, text: i}))">
                    </b-form-select>
                  </b-form-group>
                </b-col>
                <b-col md="3">
                  <b-form-group>
                    <label>Produto</label>
                    <v-select name="produto"
                              v-model="dadosProdutosLista.produto_id"
                              :options="listaProdutosComputed">
                      <span slot="no-options">Não há dados a serem exibidos!</span>
                    </v-select>
                  </b-form-group>
                </b-col>
                <b-col md="3">
                  <b-form-group>
                    <label>Nomenclatura Contratual</label>
                    <v-select name="nmcontratual"
                              disabled="disabled"
                              v-model="dadosProdutosLista.nomenclatura_id">
                      <span slot="no-options">Não há dados a serem exibidos!</span>
                    </v-select>
                  </b-form-group>
                </b-col>
                <b-col md="2">
                  <b-form-group
                      id="qtde"
                      label="Quantidade"
                      label-for="qtde">
                    <b-form-input v-model="dadosProdutosLista.qtde_produto"></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col md="2" style="text-align: right">
                  <label style="visibility: hidden">Salvar</label>
                  <b-form-group>
                    <b-button-group>
                      <b-button variant="outline-success" @click="adicionarProduto">
                        <i class="fa fa-arrow-down"></i> Incluir
                      </b-button>
                    </b-button-group>
                  </b-form-group>
                </b-col>

              </b-row>
              <b-row>
                <b-col>
                  <b-card no-body class="shadow-lg">

                    <template #header>
                      <i class="fa fa-mobile"></i> Produtos do Contrato
                    </template>

                    <table class="table striped table-bordered table-condensed responsive table-striped">
                      <thead>
                      <th scope="col" style="width: 5%; text-align: center">#Código</th>
                      <th scope="col">Produto </th>
                      <th scope="col" style="width: 10%; text-align: center">Qtde </th>
                      <th scope="col" style="width: 5%; text-align: center">Ação</th>
                      </thead>
                      <tbody>
                      <template v-if="arrayItens.length > 0">
                        <tr v-for="pr in arrayItens">
                          <td style="text-align: center">{{pr.produtos.id}}</td>
                          <td>{{pr.produtos.nome}}</td>
                          <td style="text-align: center">{{pr.quantidade}}</td>
                          <td style="text-align: center">
                            <b-button variant="danger" @click="excluirAtividade(pr.id)">
                              <i class="far fa-trash-alt"></i>
                            </b-button>
                          </td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr>
                          <td colspan="22" style="text-align: center; "><b>Nenhum documento adicionado</b></td>
                        </tr>
                      </template>
                      </tbody>
                    </table>
                  </b-card>
                </b-col>
              </b-row>
            </div>
          </template>
        </b-card>
      </b-col>
    </b-row>

    <!--        <br>-->

    <!--        <b-row>-->
    <!--            <b-col md="12">-->
    <!--                <b-card header="DADOS DO CREDOR"-->
    <!--                        header-tag="header"-->
    <!--                        border-variant="primary"-->
    <!--                        header-bg-variant="primary"-->
    <!--                        header-text-variant="white">-->
    <!--                    <template>-->
    <!--                        <div>-->
    <!--                            <b-row>-->
    <!--                                <b-col md="2">-->
    <!--                                    <b-form-group-->
    <!--                                            id="emp"-->
    <!--                                            label="Empresa"-->
    <!--                                            label-for="emp">-->
    <!--                                        <b-form-select></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="2">-->
    <!--                                    <b-form-group-->
    <!--                                            id="cli"-->
    <!--                                            label="Cliente/Fornecedor"-->
    <!--                                            label-for="cli">-->
    <!--                                        <b-form-select></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="2">-->
    <!--                                    <b-form-group-->
    <!--                                            id="tp"-->
    <!--                                            label="Tipo"-->
    <!--                                            label-for="tp">-->
    <!--                                        <b-form-select></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="3">-->
    <!--                                    <b-form-group-->
    <!--                                            id="plc"-->
    <!--                                            label="Plano de Conta"-->
    <!--                                            label-for="plc">-->
    <!--                                        <b-form-select></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="3">-->
    <!--                                    <b-form-group-->
    <!--                                            id="fmpg"-->
    <!--                                            label="Forma de Pagamento"-->
    <!--                                            label-for="fmpg">-->
    <!--                                        <b-form-select></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                            <b-row>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="cc"-->
    <!--                                            label="Centro de Custo"-->
    <!--                                            label-for="cc">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="cb"-->
    <!--                                            label="Conta Bancária"-->
    <!--                                            label-for="cb">-->
    <!--                                        <b-form-select></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="st"-->
    <!--                                            label="Situação"-->
    <!--                                            label-for="st">-->
    <!--                                        <b-form-select v-model="selected" :options="optSituacao"></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                        </div>-->
    <!--                    </template>-->
    <!--                </b-card>-->
    <!--            </b-col>-->
    <!--        </b-row>-->

    <!--        <br>-->

    <!--        <b-row>-->
    <!--            <b-col md="12">-->
    <!--                <b-card header="DATAS / ESTIMATIVA DO CONTRATO"-->
    <!--                        header-tag="header"-->
    <!--                        border-variant="primary"-->
    <!--                        header-bg-variant="primary"-->
    <!--                        header-text-variant="white">-->
    <!--                    <template>-->
    <!--                        <div>-->
    <!--                            <b-row>-->
    <!--                                <b-col md="3">-->
    <!--                                    <b-form-group-->
    <!--                                            id="diaPg"-->
    <!--                                            label="Dia Pagamento/Vencimento"-->
    <!--                                            label-for="diaPg">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="2">-->
    <!--                                    <b-form-group-->
    <!--                                            id="ctr"-->
    <!--                                            label="Duração Contrato"-->
    <!--                                            label-for="ctr">-->
    <!--                                        <b-form-select v-model="selectedDuracao" :options="duracao"></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="2">-->
    <!--                                    <label style="visibility: hidden">Salvar</label>-->
    <!--                                    <b-form-group-->
    <!--                                            id="ctr"-->
    <!--                                            label=""-->
    <!--                                            label-for="ctr">-->
    <!--                                        <b-form-select v-model="selectedOptDuracao" :options="opcaoDuracao"></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="5">-->
    <!--                                    <b-form-group-->
    <!--                                            id="dtReajuste"-->
    <!--                                            label="Data Último Reajuste"-->
    <!--                                            label-for="dtReajuste">-->
    <!--                                        <b-form-datepicker id="example-datepicker" class="mb-2"></b-form-datepicker>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                            <b-row>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="proximoReajuste"-->
    <!--                                            label="Próximo Reajuste"-->
    <!--                                            label-for="proximoReajuste">-->
    <!--                                        <b-form-datepicker id="example-datepicker2" class="mb-2"></b-form-datepicker>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="dtInicio"-->
    <!--                                            label="Data Início"-->
    <!--                                            label-for="dtInicio">-->
    <!--                                        <b-form-datepicker id="example-datepicker3" class="mb-2"></b-form-datepicker>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="dtTermino"-->
    <!--                                            label="Data Término"-->
    <!--                                            label-for="dtTermino">-->
    <!--                                        <b-form-datepicker id="example-datepicker4" class="mb-2" disabled></b-form-datepicker>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                            <b-row>-->
    <!--                                <b-col md="3">-->
    <!--                                    <b-form-group-->
    <!--                                            id="diasCarencia"-->
    <!--                                            label="Dias Carência"-->
    <!--                                            label-for="diasCarencia">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                        </div>-->
    <!--                    </template>-->
    <!--                </b-card>-->
    <!--            </b-col>-->
    <!--        </b-row>-->

    <!--        <br>-->

    <!--        <b-row>-->
    <!--            <b-col md="12">-->
    <!--                <b-card header="INFORMAÇÕES DO VENDEDOR/ COMISSÃO"-->
    <!--                        header-tag="header"-->
    <!--                        border-variant="primary"-->
    <!--                        header-bg-variant="primary"-->
    <!--                        header-text-variant="white">-->
    <!--                    <template>-->
    <!--                        <div>-->
    <!--                            <b-row>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="vend"-->
    <!--                                            label="Vendedor"-->
    <!--                                            label-for="vend">-->
    <!--                                        <b-form-select></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="comissao"-->
    <!--                                            label="Comissão Vendedor (%)"-->
    <!--                                            label-for="comissao">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="comissaoPeriodo"-->
    <!--                                            label="Comissão Vendedor Período/Duração (R$)"-->
    <!--                                            label-for="comissaoPeriodo">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                            <b-row>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="comissaoTotal"-->
    <!--                                            label="Comissão Vendedor Total (R$)"-->
    <!--                                            label-for="comissaoTotal">-->
    <!--                                        <b-form-input ></b-form-input>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                        </div>-->
    <!--                    </template>-->
    <!--                </b-card>-->
    <!--            </b-col>-->
    <!--        </b-row>-->


    <!--        <b-row>-->
    <!--            <b-col md="12">-->
    <!--                <b-card header="OCORRÊNCIAS"-->
    <!--                        header-tag="header"-->
    <!--                        border-variant="primary"-->
    <!--                        header-bg-variant="primary"-->
    <!--                        header-text-variant="white">-->
    <!--                    <template>-->
    <!--                        <div>-->
    <!--                            <b-row>-->
    <!--                                <b-col md="4">-->
    <!--                                    <b-form-group-->
    <!--                                            id="tpOcorrencia"-->
    <!--                                            label="Tipo"-->
    <!--                                            label-for="tpOcorrencia">-->
    <!--                                        <b-form-select v-model="tpOcorrencia"></b-form-select>-->
    <!--                                    </b-form-group>-->
    <!--                                </b-col>-->
    <!--                            </b-row>-->
    <!--                        </div>-->
    <!--                    </template>-->
    <!--                </b-card>-->
    <!--            </b-col>-->
    <!--        </b-row>-->

    <!--        <br>-->

    <!--        <b-row>-->
    <!--            <b-col md="12">-->
    <!--                <b-card header="ADITIVOS" header-tag="header">-->
    <!--                    <template>-->
    <!--                        <span>FORMULÁRIO DE ADITIVOS</span>-->
    <!--                    </template>-->
    <!--                </b-card>-->
    <!--            </b-col>-->
    <!--        </b-row>-->
  </div>
</template>

<script>
import Card from "../geral/Card";
import SingleForm from "../geral/SingleForm";
import InputGeralClientes from "../geral/singleForm/InputGeralEmpresas";

import SelectTipoContrato from "./form/SelectTipoContrato";

import BtnBuscarClientesContratante from "../geral/BtnBuscarClientes";
import BtnBuscarClientesExecutante from "../geral/BtnBuscarClientes";
import TiposDeContrato from "./view/TiposDeContratos";
import BtnUpload from "../geral/BtnUpload";
import InputCep from "../geral/singleForm/InputCep";
import InputEstados from "../geral/singleForm/InputEstados";
import InputCidades from "../geral/singleForm/InputCidades";

export default {

  name: "FormContratos",

  props:['proposta'],

  mounted() {

    this.listaProdutos();

  },

  components: {BtnUpload, SelectTipoContrato, TiposDeContrato, InputGeralClientes, SingleForm},

  watch: {

    proposta: {
      deep:true,
      handler(proposta){

        var params = {
          pre_contrato_id: proposta.id
        }

        this.doGet('contrato/contrato/listaContratoPorPre', params).then(function (result) {
          this.contrato.status = result.status_contrato;
          this.contrato.contrato_id = result.id;
          this.contrato.codigo_contrato = result.codigo_contrato;
          this.contrato.data_inicio = result.data_inicio;
          this.contrato.data_fim = result.data_fim;
          this.arrayAnexoPropostas = proposta.proposta;

          this.listaItensContrato();
        })
      }
    },
  },

  computed: {
    listaProdutosComputed: function () {
      return _.map(this.arrayProdutos, function (item) {
        return {
          label: item.nome,
          value: item.id,
          dados: item
        };
      });
    },

    verificaDadas: function (){
      if(this.contrato.data_inicio != '' && this.contrato.data_fim != ''){
        return true;
      }
    }
  },

  methods: {
    listaProdutos: function(){

      var _this = this;

      this.doGet('estoque/produtos/buscar').then(function (result) {
        _this.arrayProdutos = result.data;
      })
    },

    adicionarProduto: function (){

      this.dadosProdutosLista.produto_id = this.dadosProdutosLista.produto_id;
      this.dadosProdutosLista.nomenclatura_id = this.dadosProdutosLista.nomenclatura_id;
      this.dadosProdutosLista.quantidade = this.dadosProdutosLista.quantidade;

      //Montar Lista em Tempo de execução.
      // this.lista_produtos.push(_.clone(this.dadosProdutosLista));

      var params = {
        contrato_id: this.contrato.contrato_id,
        produto_id: this.dadosProdutosLista.produto_id.dados.id,
        nomenclatura_id: '',
        quantidade: this.dadosProdutosLista.qtde_produto
      };

      this.doPost('contrato/contrato/salvarItemContrato', params).then(function (result) {
        if(result.code){
          this.alertErro('Produto já incluído na lista!');
        }else{
          this.alertSucesso();
          this.listaItensContrato();

          this.dadosProdutosLista.produto_id = '';
          this.dadosProdutosLista.nomenclatura_id = '';
          this.dadosProdutosLista.qtde_produto = '';
        }
      })
    },

    listaItensContrato: function (){

      var _this = this;

      this.doGet('contrato/contrato/listaItensContrato', {include: ['produtos'], contrato_id: this.contrato.contrato_id}).then(function (result) {
        _this.arrayItens = result;
      })
    },

    excluirAtividade: function ($item){

      var params = {
        codigo_item: $item
      }

      this.doPost('contrato/contrato/excluirAtividade', params).then(function (result) {
        this.alertSucesso();
        this.listaItensContrato();
      })
    },

    finalizarContrato: function ($contrato){

      var params = {
        contrato_id: $contrato,
        data_inicio: this.contrato.data_inicio,
        data_fim: this.contrato.data_fim

      }

      this.doPost('contrato/contrato/finalizarContrato', params).then(function (result) {
        this.contrato.status = result.status_contrato;
        this.alertSucesso();
      })

    }
  },

  data() {
    return {

      tipoProduto: {
        '1': 'PRODUTO',
        '2': 'SERVIÇO',
      },

      produto: {
        tipo: '1'
      },

      dadosProdutosLista: {
        tipo: '',
        produto_id: '',
        qtde_produto: '',

      },

      lista_produtos: [],

      registro: {
        tipo: '',
        status: '',
        estado: '',
        cidade: ''
      },

      propostas: {
        data_inicio: '',
        data_fim: ''
      },

      contrato: {
        contrato_id: '',
        codigo_contrato: '',
        status: '',
        data_inicio: '',
        data_fim: ''
      },

      operacao: null,

      perPage: 3,
      currentPage: 1,

      arrayAnexoPropostas: [],
      arrayProdutos: [],
      arrayItensContrato: [],
      arrayItens: [],

    };
  },
}
</script>

<style scoped>

</style>
