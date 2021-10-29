/**
 * Tela modulo geral
 */

import viewUsuarios from './components/geral/view/Usuarios';
import viewUsuariosGrupos from './components/geral/view/UsuariosGrupos'
import viewPermisoes from './components/geral/view/Permissoes'
import viewEmpresas from './components/geral/view/Empresas';
import viewWebmail from './components/geral/view/Webmail';
/**
 * Tela modulo Fluxo de Atividades
 */
import viewAtividades from './components/atividade/view/Atividades';
import viewChamados from './components/atividade/view/Chamados';
import viewDashboardRelatorios from './components/atividade/view/DashboardRelatorios';


/**
 * Tela modulo Frotas
 */
import viewPainel from './components/frota/view/Painel'
import viewEquipes from './components/frota/view/Equipes'
import viewGruposVeiculos from './components/frota/view/GruposVeiculos'
import viewManutencao from './components/frota/view/Manutencao'
import viewMarcas from './components/frota/view/Marcas'
import viewModelos from './components/frota/view/Modelos'
import viewMotoristas from './components/frota/view/Motoristas'
import viewOficinas from './components/frota/view/Oficinas'
import viewRastreadores from './components/frota/view/Rastreadores'
import viewTiposVeiculos from './components/frota/view/TiposVeiculos'
import viewVeiculos from './components/frota/view/Veiculos'

import viewNotFound from './components/geral/view/NotFound'


/**
 * Modulo de contratos
 */
import Contratos from "./components/contratos/view/Contratos";
import TiposDeContratos from "./components/contratos/view/TiposDeContratos";


export default [

    // {path: '/', component: viewPainel},
    {path: '/', component: viewChamados},
    {path: '/frota/view/painel', component: viewPainel},
    {path: '/geral/view/empresas', component: viewEmpresas},

    /**
     * Tela globais (modulo geral)
     */
    {path: '/', component: viewPainel},
    {path: '/geral/view/perfil', component: () => import('./components/geral/perfil/Perfil')},
    {path: '/geral/view/usuarios', component: viewUsuarios},
    {path: '/geral/view/usuariosGrupos', component: viewUsuariosGrupos},
    {path: '/geral/view/permissoes', component: viewPermisoes},
    {path: '/geral/view/empresas', component: viewEmpresas},
    {path: '/geral/view/webmail', component: viewWebmail},
    {path: '/geral/view/vagas', component: () => import('./components/geral/view/Vagas')},

    /**
     * Telas do Frotas
     */
    {path: '/frota/view/equipes', component: () => import('./components/frota/view/Equipes')},
    {path: '/frota/view/gruposveiculos', component: () => import('./components/frota/view/GruposVeiculos')},
    {path: '/frota/view/manutencao', component: () => import('./components/frota/view/Manutencao')},
    {path: '/frota/view/marcas', component: () => import('./components/frota/view/Marcas')},
    {path: '/frota/view/modelos', component: () => import('./components/frota/view/Modelos')},
    {path: '/frota/view/motoristas', component: () => import('./components/frota/view/Motoristas')},
    {path: '/frota/view/oficinas', component: () => import('./components/frota/view/Oficinas')},
    {path: '/frota/view/rastreadores', component: () => import('./components/frota/view/Rastreadores')},
    {path: '/frota/view/tiposveiculos', component: () => import('./components/frota/view/TiposVeiculos')},
    {path: '/frota/view/veiculos', component: () => import('./components/frota/view/Veiculos')},
    {path: '/frota/view/painel', component: () => import('./components/frota/view/Painel')},
    {path: '/frota/view/painel-admin', component: () => import('./components/frota/view/PainelAdmin')},
    {path: '/frota/view/eventos', component: () => import('./components/frota/view/Eventos')},

    /**
     * Telas do modulo estoque
     */

    {path: '/estoque/produtos', component: () => import('./components/estoque/produtos/Tela')},
    {path: '/estoque/servicos', component: () => import('./components/estoque/servicos/Tela')},
    {path: '/estoque/fabricantes', component: () => import('./components/estoque/fabricantes/Tela')},
    {path: '/estoque/marcas', component: () => import('./components/estoque/marcas/Tela')},
    {path: '/estoque/modelos', component: () => import('./components/estoque/modelos/Tela')},
    {path: '/estoque/categorias', component: () => import('./components/estoque/categorias/Tela')},
    {path: '/estoque/linhas', component: () => import('./components/estoque/linhas/Tela')},
    {path: '/estoque/unidades', component: () => import('./components/estoque/unidades/Tela')},

    {path: '/estoque/estoques', component: () => import('./components/estoque/estoque/Tela')},
    {path: '/estoque/armazem', component: () => import('./components/estoque/armazem/Tela')},
    {path: '/estoque/ajuste', component: () => import('./components/estoque/ajuste/Tela')},
    {path: '/estoque/transferencia', component: () => import('./components/estoque/transferencia/Tela')},

    //
    //  Telas do modulo relatorios

    // {path: 'frota/relatorio/view/kilometragem', component: () => import('./components/frota/relatorio/Kilometragem')},
    // {path: 'frota/relatorio/view/analitico', component: () => import('./components/frota/relatorio/Analitico')},
    // {path: 'frota/relatorio/view/paradas', component: () => import('./components/frota/relatorio/Paradas')},
    // {path: 'frota/relatorio/view/velocidade', component: () => import('./components/frota/relatorio/Velocidade.vue')},
    // {path: 'frota/relatorio/view/horimetro', component: () => import('./components/frota/relatorio/Horimetro')},


    /**
     * Modulo de contratos
     */
    {path: '/contratos/view/contratos', component: Contratos},
    {path: '/contratos/view/tipos-contratos', component: TiposDeContratos},


    /**
     * Modulo Docs (gestor de documentos)
     */
    {path: '/docs/view/meus-arquivos', component: () => import('./components/docs/view/MeusArquivos')},

    /**
     * Modulo Crm
     */
    {path: '/crm/notas', component: () => import('./components/comercial/PainelNotas')},

    /**
     * Modulo Licitações (gestor de licitações)
     */
    {path: '/licitacao/view/licitacao', component: () => import('./components/licitacao/view/Licitacao')},


    /**
     *  Módulo Fluxo de Atividades
     */
    {path: '/atividade/view/atividades', component: viewAtividades},
    {path: '/atividade/view/chamados', component: viewChamados},
    {path: '/atividade/view/dashboard-relatorios', component: viewDashboardRelatorios},


    /**
     * 404
     */
    {path: '*', component: viewNotFound},

    {path: '/checkout', component: () => import('./components/Checkout')},

]
