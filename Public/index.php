<?php

require_once __DIR__ . '/../App/Core/Autoload.php';
require_once __DIR__ . '/../App/Config/Config.php';


use App\Core\Router;

$router = new Router();

\App\Database\ConnectionFactory::getConnection();

$router->get('/', 'LoginController@login');

$router->get('/login', 'LoginController@login');

$router->post('/login', 'LoginController@autenticar');

$router->post('/trabalhador/dispensar-aviso-perfil', 'LoginController@dispensarAvisoPerfil');

$router->get('/logout', 'LoginController@logout');

$router->get(
    '/usuarios',
    'AdministradorController@listarTodosUsuarios'
);

$router->get(
    '/usuarios/editar',
    'AdministradorController@editar'
);

$router->post(
    '/usuarios/editar',
    'AdministradorController@atualizar'
);

$router->get(
    '/trabalhador/cadastrar',
    'TrabalhadorController@cadastrarTrabalhador'
);

$router->post(
    '/trabalhador/cadastrar',
    'TrabalhadorController@salvarTrabalhador'
);

$router->get(
    '/adm/cadastrar',
    'AdministradorController@cadastrarAdm'
);

$router->post(
    '/adm/cadastrar',
    'AdministradorController@salvarAdm'
);

$router->get(
    '/grandes-areas',
    'GrandesAreasController@gerenciarEstrutura'
);

$router->post(
    '/grandes-areas/salvar',
    'GrandesAreasController@salvar'
);

$router->post(
    '/grandes-areas/atualizar',
    'GrandesAreasController@atualizar'
);

$router->post(
    '/grandes-areas/excluir',
    'GrandesAreasController@excluir'
);

$router->get('/matches', 'MatchController@listarMatches');
$router->get('/match/solicitar', 'MatchController@solicitarMatch');
$router->post('/match/registrar', 'MatchController@registrarInteresseMatch');
$router->post('/match/aceitar', 'MatchController@aceitarInteresseMatch');
$router->post('/match/recusar', 'MatchController@recusarInteresseMatch');
$router->post('/match/editar-mensagem', 'MatchController@editarMensagemMatch');

$router->get('/acordos', 'AcordoController@listarAcordos');
$router->get('/acordo/detalhes', 'AcordoController@detalhesAcordo');
$router->post('/acordo/concluir', 'AcordoController@concluirAcordo');
$router->post('/acordo/cancelar', 'AcordoController@cancelarAcordo');
$router->post('/avaliacao/registrar', 'AvaliacaoController@registrarAvaliacao');

$router->get(
    '/adm',
    'AdministradorController@dashboard'
);
$router->get('/adm/acordos-cancelados', 'AdministradorController@listarAcordosCancelados');

$router->get('/trabalhador/preferencias', 'TrabalhadorController@exibirPreferencias');
$router->post('/trabalhador/salvar-preferencias', 'TrabalhadorController@salvarPreferencias');

$router->get('/trabalhador/profissoes-por-grande-area', 'TrabalhadorController@exibirProfissoesPorGrandeArea');

$router->get('/trabalhador/servicos', 'ServicoController@listarServicos');
$router->post('/trabalhador/servicos/cadastrar', 'ServicoController@cadastrarServico');
$router->post('/trabalhador/servicos/editar', 'ServicoController@editarServico');
$router->post('/trabalhador/servicos/excluir', 'ServicoController@excluirServico');

$router->get('/trabalhador/perfil', 'TrabalhadorController@mostrarPerfil');

$router->get('/trabalhador/editar', 'TrabalhadorController@exibirEditarPerfil');
$router->post('/trabalhador/editar', 'TrabalhadorController@editarPerfil');
$router->post('/trabalhador/desativar-conta', 'TrabalhadorController@desativarConta');

$router->get('/trabalhador/servicos/filtrar', 'ServicoController@filtrarServico');

// Rotas de Bloqueio de Trabalhador
$router->post('/trabalhador/bloquear', 'BloqueioController@bloquearTrabalhador');
$router->post('/trabalhador/desbloquear', 'BloqueioController@desbloquearTrabalhador');
$router->get('/trabalhador/bloqueados', 'BloqueioController@listarBloqueados');

// Rotas de Denúncia e Penalidades
$router->post('/denuncia/criar', 'DenunciaController@criarDenuncia');
$router->get('/denuncias/minhas', 'DenunciaController@listarMinhasDenuncias');
$router->get('/adm/denuncias', 'AdministradorController@listarDenuncias');
$router->post('/adm/denuncias/avaliar', 'AdministradorController@avaliarDenuncia');
$router->post('/adm/penalidade/aplicar', 'PenalidadeController@aplicarPenalidade');

$router->post(
    '/usuarios/alterar-status',
    'AdministradorController@alterarStatus'
);

$router->run();