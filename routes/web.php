<?php


use App\Models\Frota\Rastreadores;

$router->get('/view/{tela}', ['middleware' => 'auth', 'uses' => 'GeralController@indexView']);
$router->get('/view/{modulo}/{tela}', [/*'middleware' => 'auth',*/ 'uses' => 'GeralController@indexView']);


$router->get('/login', 'AuthController@index');
$router->post('/login', 'AuthController@postLogin');
$router->get('/sair', 'AuthController@sair');

$router->get('/', ['middleware' => 'auth', 'uses' => 'GeralController@index']);

$router->get('/api/info-sessao', ['middleware' => 'auth', 'uses' => 'GeralController@infoSessao']);


/**
 * Api
 */

$router->get('/api/geral/file-storage/get/{uid}', 'Geral\FileStorageController@getFile');
$router->get('/api/geral/file-storage/get/{uid}/thumb', 'Geral\FileStorageController@getThumb');
$router->get('/api/geral/file-storage/thumb/{uid}', 'Geral\FileStorageController@getThumb');

$router->group(['middleware' => 'auth'], function () use ($router) {


    $router->get('/api/rastreio/ultima-posicao', 'Rastreio\LogsController@ultimaPosicao');


    $router->post('/api/geral/clientes/salvar', 'Geral\ClientesController@salvar');
    $router->post('/api/geral/clientes/excluir', 'Geral\ClientesController@excluir');
    $router->get('/api/geral/clientes/buscar', 'Geral\ClientesController@buscar');
    $router->get('/api/geral/clientes/listarTodasEmpresas', 'Geral\ClientesController@listarTodasEmpresas');


    $router->post('/api/geral/usuarios/salvar', 'Geral\UsuariosController@salvar');
    $router->post('/api/geral/usuarios/excluir', 'Geral\UsuariosController@excluir');
    $router->get('/api/geral/usuarios/buscar', 'Geral\UsuariosController@buscar');
    $router->post('/api/geral/usuarios/perfil', 'Geral\UsuariosController@salvarPerfil');


    $router->post('/api/geral/controle-acessos/salvar', 'Geral\ControleAcessosController@salvar');
    $router->post('/api/geral/controle-acessos/excluir', 'Geral\ControleAcessosController@excluir');
    $router->get('/api/geral/controle-acessos/buscar', 'Geral\ControleAcessosController@buscar');

    $router->get('/api/geral/notificacoes/buscar', 'Geral\NotificacoesController@buscar');
    $router->post('/api/geral/notificacoes/visualizar', 'Geral\NotificacoesController@visualizar');


    $router->get('/api/geral/grupos-usuarios/buscar', 'Geral\GruposUsuariosController@buscar');
    $router->get('/api/geral/grupos-usuarios/buscar-usuarios', 'Geral\GruposUsuariosController@buscarUsuarios');

    $router->post('/api/geral/grupos-usuarios/salvar', 'Geral\GruposUsuariosController@salvar');
    $router->post('/api/geral/grupos-usuarios/excluir', 'Geral\GruposUsuariosController@excluir');

    $router->post('/api/geral/grupos-usuarios/adicionar-usuario', 'Geral\GruposUsuariosController@adicionarUsuario');
    $router->post('/api/geral/grupos-usuarios/excluir-usuario', 'Geral\GruposUsuariosController@excluirUsuario');

    $router->post('/api/geral/file-storage/upload', 'Geral\FileStorageController@fileTempUpload');


    $router->get('/api/geral/file-storage/buscar', 'Geral\FileStorageController@buscar');


    /**
     * Rotas do modulo frotas
     */

    /**
     * Modulo Frotas
     */
    $router->post('/api/frota/veiculos/salvar', 'Frota\VeiculosController@salvar');
    $router->post('/api/frota/veiculos/excluir', 'Frota\VeiculosController@excluir');
    $router->get('/api/frota/veiculos/buscar', 'Frota\VeiculosController@buscar');


    $router->post('/api/frota/rastreadores/salvar', 'Frota\RastreadoresController@salvar');
    $router->post('/api/frota/rastreadores/excluir', 'Frota\RastreadoresController@excluir');
    $router->get('/api/frota/rastreadores/buscar', 'Frota\RastreadoresController@buscar');


    $router->post('/api/frota/tipo-veiculos/salvar', 'Frota\TipoVeiculosController@salvar');
    $router->post('/api/frota/tipo-veiculos/excluir', 'Frota\TipoVeiculosController@excluir');
    $router->get('/api/frota/tipo-veiculos/buscar', 'Frota\TipoVeiculosController@buscar');

    $router->post('/api/frota/marcas/salvar', 'Frota\MarcasController@salvar');
    $router->post('/api/frota/marcas/excluir', 'Frota\MarcasController@excluir');
    $router->get('/api/frota/marcas/buscar', 'Frota\MarcasController@buscar');

    $router->post('/api/frota/modelos/salvar', 'Frota\ModelosController@salvar');
    $router->post('/api/frota/modelos/excluir', 'Frota\ModelosController@excluir');
    $router->get('/api/frota/modelos/buscar', 'Frota\ModelosController@buscar');

    $router->get('/api/frota/logs/buscar', 'Frota\LogsController@buscar');
    $router->post('/api/frota/logs/geocode-save', 'Frota\LogsController@geocodeSave');
    $router->get('/api/frota/logs/geocode', 'Frota\LogsController@geocodeBuscar');

    $router->get('/api/frota/painel-admin/contadores', 'Frota\PainelAdminController@contadores');
    $router->get('/api/frota/painel-admin/buscar', 'Frota\PainelAdminController@buscar');
    $router->get('/api/frota/painel-admin/rastreador-status', 'Frota\PainelAdminController@rastreadorStatus');

    $router->post('/api/frota/eventos/salvar', 'Frota\EventosController@salvar');
    $router->post('/api/frota/eventos/excluir', 'Frota\EventosController@excluir');
    $router->get('/api/frota/eventos/buscar', 'Frota\EventosController@buscar');

    $router->get('/api/frota/relatorio/telemetria/kilometragem', 'Frota\Relatorio\TelemetriaController@kilometragem');
    $router->get('/api/frota/relatorio/telemetria/analitico', 'Frota\Relatorio\TelemetriaController@analitico');
    $router->get('/api/frota/relatorio/telemetria/paradas', 'Frota\Relatorio\TelemetriaController@paradas');
    $router->get('/api/frota/relatorio/telemetria/velocidade', 'Frota\Relatorio\TelemetriaController@velocidade');
    $router->get('/api/frota/relatorio/telemetria/horimetro', 'Frota\Relatorio\TelemetriaController@horimetro');
    $router->get('/api/frota/relatorio/telemetria/velocidade', 'Frota\Relatorio\TelemetriaController@velocidade');


    /**
     * Atividades - Chamados
     */
    $router->post('/api/atividades/atividades/incluir', 'Atividades\AtividadesController@incluir');
    $router->post('/api/atividades/atividades/atualizaAtividade', 'Atividades\AtividadesController@atualizaAtividade');
    $router->get('/api/atividades/atividades/buscarAtividades', 'Atividades\AtividadesController@buscarAtividades');
    $router->get('/api/atividades/atividades/buscarSubAtividades', 'Atividades\AtividadesController@buscarSubAtividades');
    $router->get('/api/atividades/atividades/hasMovement', 'Atividades\AtividadesController@hasMovement');
    $router->get('/api/atividades/atividades/consultaAtividade', 'Atividades\AtividadesController@consultaAtividade');
    $router->post('/api/atividades/atividades/excluirAtividade', 'Atividades\AtividadesController@excluirAtividade');

    $router->post('/api/atividades/atividades/incluirSubAtividade', 'Atividades\AtividadesController@incluirSubAtividade');
    $router->post('/api/atividades/atividades/alterarSubatividade', 'Atividades\AtividadesController@alterarSubatividade');
    $router->get('/api/atividades/atividades/hasMovementSubactivity', 'Atividades\AtividadesController@hasMovementSubactivity');
    $router->post('/api/atividades/atividades/excluirSubAtividade', 'Atividades\AtividadesController@excluirSubAtividade');
    $router->get('/api/atividades/atividades/buscarSubAtividades', 'Atividades\AtividadesController@buscarSubAtividades');


    $router->get('/api/atividades/atividades/listarChamados', 'Atividades\AtividadesController@listarChamados');
    $router->get('/api/atividades/atividades/listarChamadosPorNr', 'Atividades\AtividadesController@listarChamadosPorNr');
    $router->get('/api/atividades/atividades/listarFiltros', 'Atividades\AtividadesController@listarFiltros');
    $router->get('/api/atividades/atividades/listarColunas', 'Atividades\AtividadesController@listarColunas');
    $router->post('/api/atividades/atividades/iniciarChamado', 'Atividades\AtividadesController@iniciarChamado');
    $router->post('/api/atividades/atividades/atualizaStatusFiltros', 'Atividades\AtividadesController@atualizaStatusFiltros');
    $router->post('/api/atividades/atividades/atualizaStatusColunas', 'Atividades\AtividadesController@atualizaStatusColunas');
    $router->get('/api/atividades/atividades/listarAtividadesCadastradas', 'Atividades\AtividadesController@listarAtividadesCadastradas');
    $router->get('/api/atividades/atividades/listarSubAtividadePorAtividade', 'Atividades\AtividadesController@listarSubAtividadePorAtividade');
    $router->post('/api/atividades/atividades/criarChamado', 'Atividades\AtividadesController@criarChamado');
    $router->post('/api/atividades/atividades/criarListaAtendimentoChamado', 'Atividades\AtividadesController@criarListaAtendimentoChamado');
    $router->get('/api/atividades/atividades/listarDepartamentoPorId', 'Atividades\AtividadesController@listarDepartamentoPorId');
    $router->get('/api/atividades/atividades/consultaAtividade', 'Atividades\AtividadesController@consultaAtividade');
    $router->get('/api/atividades/atividades/listarHistorico', 'Atividades\AtividadesController@listarHistorico');


    $router->post('/api/atividades/atividades/iniciarIteracaoChamado', 'Atividades\\AtividadesController@iniciarIteracaoChamado');
    $router->post('/api/atividades/atividades/atualizaStatusFiltros', 'Atividades\\AtividadesController@atualizaStatusFiltros');
    $router->post('/api/atividades/atividades/atualizaStatusColunas', 'Atividades\\AtividadesController@atualizaStatusColunas');
    $router->post('/api/atividades/atividades/finalizaIteracaoChamado', 'Atividades\\AtividadesController@finalizaIteracaoChamado');
    $router->post('/api/atividades/atividades/atualizaUsuarioExecutor', 'Atividades\\AtividadesController@atualizaUsuarioExecutor');
    $router->post('/api/atividades/atividades/incluirIteracao', 'Atividades\\AtividadesController@incluirIteracao');
    $router->get('/api/atividades/atividades/listaIteracoes', 'Atividades\\AtividadesController@listaIteracoes');
    $router->get('/api/atividades/atividades/consultaAtendimento', 'Atividades\\AtividadesController@consultaAtendimento');
    $router->get('/api/atividades/atividades/listaUsuariosCompartilhados', 'Atividades\\AtividadesController@listaUsuariosCompartilhados');
    $router->get('/api/atividades/atividades/listaDepartamentoPorId', 'Atividades\\AtividadesController@listaDepartamentoPorId');
    $router->get('/api/atividades/atividades/listarColunas', 'Atividades\\AtividadesController@listarColunas');
    $router->get('/api/atividades/atividades/listarTotalChamadosPorDepartamento', 'Atividades\\AtividadesController@listarTotalChamadosPorDepartamento');
    $router->get('/api/atividades/atividades/listarTotalChamadosPorStatus', 'Atividades\\AtividadesController@listarTotalChamadosPorStatus');
    $router->get('/api/atividades/atividades/listarTotalAtividades', 'Atividades\\AtividadesController@listarTotalAtividades');
    $router->get('/api/atividades/atividades/filtrosRelatorioChamados', 'Atividades\\AtividadesController@filtrosRelatorioChamados');

    /** Criar Chamado */
    $router->post('/api/atividades/atividades/salvarChamado', 'Atividades\\AtividadesController@salvarChamado');
    $router->post('/api/atividades/atividades/alterarChamado', 'Atividades\\AtividadesController@alterarChamado');
    $router->post('/api/atividades/atividades/salvarAtendimentoChamado', 'Atividades\\AtividadesController@salvarAtendimentoChamado');
    $router->post('/api/atividades/atividades/salvarAnexos', 'Atividades\\AtividadesController@salvarAnexos');
    $router->get('/api/atividades/atividades/llistarChamados', 'Atividades\\AtividadesController@listarChamados');
    $router->get('/api/atividades/atividades/listarChamadosPorNr', 'Atividades\\AtividadesController@listarChamadosPorNr');
    $router->get('/api/atividades/atividades/listarFiltros', 'Atividades\\AtividadesController@listarFiltros');
    $router->get('/api/atividades/atividades/listarColunas', 'Atividades\\AtividadesController@listarColunas');
    $router->get('/api/atividades/atividades/listarFiltrosSelecionados', 'Atividades\\AtividadesController@listarFiltrosSelecionados');
    $router->post('/api/atividades/atividades/tranferirAtividadeExecutor', 'Atividades\\AtividadesController@tranferirAtividadeExecutor');


    $router->get('/api/geral/vaga-departamento/listaVagaDepartamento', 'Geral\VagaDepartamentoController@listaVagaDepartamento');

    $router->get('/api/geral/departamentos/listarDepartamentosPorEmpresa', 'Geral\DepartamentosController@listarDepartamentosPorEmpresa');

    $router->get('/api/geral/departamentos/buscar', 'Geral\DepartamentosController@buscar');

    $router->post('/api/geral/departamentos/salvar', 'Geral\DepartamentosController@salvar');
    $router->post('/api/geral/departamentos/excluir', 'Geral\DepartamentosController@excluir');

    /**
     * Listar vagasDepartamentos
     */
    $router->get('/api/geral/vaga-departamento/buscar', 'Geral\VagaDepartamentoController@buscar');
    $router->post('/api/geral/vaga-departamento/salvar', 'Geral\VagaDepartamentoController@salvar');
    $router->post('/api/geral/vaga-departamento/excluir', 'Geral\VagaDepartamentoController@excluir');

    /**
     * Listar Atividades do Departamento
     */
    $router->get('/api/atividades/atividades/buscar', 'Atividades\AtividadesController@buscar');

    /**
     * Moderar Chamado
     */
    $router->post('/api/atividades/atividades/salvarChamadoModerado', 'Atividades\AtividadesController@salvarChamadoModerado');


    /**
     *  Pré-Contrato
     */
    $router->post('/api/contrato/contrato/salvarPreContrato', 'Contrato\ContratoController@salvarPreContrato');
    $router->post('/api/contrato/contrato/salvarEdicaoPreContrato', 'Contrato\ContratoController@salvarEdicaoPreContrato');
    $router->post('/api/contrato/contrato/excluirPreContrato', 'Contrato\ContratoController@excluirPreContrato');
    $router->post('/api/contrato/contrato/aprovarReprovarPreContrato', 'Contrato\ContratoController@aprovarReprovarPreContrato');
    $router->post('/api/contrato/contrato/aprovarReprovarProposta', 'Contrato\ContratoController@aprovarReprovarProposta');
    $router->post('/api/contrato/contrato/salvarPropostas', 'Contrato\ContratoController@salvarPropostas');
    $router->post('/api/contrato/contrato/salvarContrato', 'Contrato\ContratoController@salvarContrato');

    $router->post('/api/contrato/contrato/salvarItemContrato', 'Contrato\ContratoController@salvarItemContrato');

    $router->get('/api/contrato/contrato/listarPreContratos', 'Contrato\ContratoController@listarPreContratos');
    $router->get('/api/contrato/contrato/listarPreContratoPorId', 'Contrato\ContratoController@listarPreContratoPorId');
    $router->get('/api/contrato/contrato/listaPropostasPorPreContrato', 'Contrato\ContratoController@listaPropostasPorPreContrato');
    $router->get('/api/contrato/contrato/listaContratoPorPre', 'Contrato\ContratoController@listaContratoPorPre');
    $router->get('/api/contrato/contrato/listaItensContrato', 'Contrato\ContratoController@listaItensContrato');
    $router->get('/api/contrato/contrato/listaAnexosPreContrato', 'Contrato\ContratoController@listaAnexosPreContrato');
    $router->post('/api/contrato/contrato/excluirAtividade', 'Contrato\ContratoController@excluirAtividade');
    $router->post('/api/contrato/contrato/finalizarContrato', 'Contrato\ContratoController@finalizarContrato');

    /**
     * Licitações
     */
    $router->post('/api/licitacao/licitacao/salvarLicitacao', 'Licitacao\LicitacaoController@salvarLicitacao');
    $router->post('/api/licitacao/licitacao/salvarEdicaoLicitacao', 'Licitacao\LicitacaoController@salvarEdicaoLicitacao');
    $router->get('/api/licitacao/licitacao/listarLicitacoes', 'Licitacao\LicitacaoController@listarLicitacoes');

    /**
     * Lotes Licitação
     */
    $router->post('/api/licitacao/licitacao/incluirItemLoteLicitacao', 'Licitacao\LicitacaoController@incluirItemLoteLicitacao');

    /**
     * Estoque
     */
    $router->post('/api/estoque/categorias/salvar', 'Estoque\CategoriasController@salvar');
    $router->post('/api/estoque/categorias/excluir', 'Estoque\CategoriasController@excluir');
    $router->get('/api/estoque/categorias/buscar', 'Estoque\CategoriasController@buscar');

    $router->post('/api/estoque/fabricantes/salvar', 'Estoque\FabricantesController@salvar');
    $router->post('/api/estoque/fabricantes/excluir', 'Estoque\FabricantesController@excluir');
    $router->get('/api/estoque/fabricantes/buscar', 'Estoque\FabricantesController@buscar');

    $router->post('/api/estoque/marcas/salvar', 'Estoque\MarcasController@salvar');
    $router->post('/api/estoque/marcas/excluir', 'Estoque\MarcasController@excluir');
    $router->get('/api/estoque/marcas/buscar', 'Estoque\MarcasController@buscar');

    $router->post('/api/estoque/modelos/salvar', 'Estoque\ModelosController@salvar');
    $router->post('/api/estoque/modelos/excluir', 'Estoque\ModelosController@excluir');
    $router->get('/api/estoque/modelos/buscar', 'Estoque\ModelosController@buscar');

    $router->post('/api/estoque/produtos/salvar', 'Estoque\ProdutosController@salvar');
    $router->post('/api/estoque/produtos/excluir', 'Estoque\ProdutosController@excluir');
    $router->get('/api/estoque/produtos/buscar', 'Estoque\ProdutosController@buscar');

    $router->post('/api/estoque/servicos/salvar', 'Estoque\ServicosController@salvar');
    $router->post('/api/estoque/servicos/excluir', 'Estoque\ServicosController@excluir');
    $router->get('/api/estoque/servicos/buscar', 'Estoque\ServicosController@buscar');

    $router->post('/api/estoque/linhas/salvar', 'Estoque\LinhasController@salvar');
    $router->post('/api/estoque/linhas/excluir', 'Estoque\LinhasController@excluir');
    $router->get('/api/estoque/linhas/buscar', 'Estoque\LinhasController@buscar');

    $router->post('/api/estoque/unidades/salvar', 'Estoque\UnidadesController@salvar');
    $router->post('/api/estoque/unidades/excluir', 'Estoque\UnidadesController@excluir');
    $router->get('/api/estoque/unidades/buscar', 'Estoque\UnidadesController@buscar');

    $router->get('/api/estoque/ncm/buscar', 'Estoque\NcmController@buscar');

    $router->post('/api/estoque/estoque/salvar', 'Estoque\EstoqueController@salvar');
    $router->post('/api/estoque/estoque/excluir', 'Estoque\EstoqueController@excluir');
    $router->get('/api/estoque/estoque/buscar', 'Estoque\EstoqueController@buscar');

    $router->post('/api/estoque/armazem/salvar', 'Estoque\ArmazemController@salvar');
    $router->post('/api/estoque/armazem/excluir', 'Estoque\ArmazemController@excluir');
    $router->get('/api/estoque/armazem/buscar', 'Estoque\ArmazemController@buscar');

    $router->post('/api/estoque/movimento/ajustar', 'Estoque\MovimentoController@ajustar');
    $router->get('/api/estoque/movimento/buscar', 'Estoque\MovimentoController@buscar');

    $router->post('/api/estoque/movimento/transferir', 'Estoque\MovimentoController@transferir');

    /**
     *
     **/
    $router->get('/api/comercial/nota-fiscal/buscar', 'Comercial\NotaFiscalController@buscar');
    $router->get('/api/comercial/nota-fiscal/contadores', 'Comercial\NotaFiscalController@contadores');


});

$router->get('/checkout', 'CheckoutFake@checkout');
$router->post('/checkout', 'CheckoutFake@checkoutStore');

