<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\ImagemUploader;
use App\Helpers\Validador;
use PDOException;
use RuntimeException;
use Throwable;
use App\Services\AcordoService;
use App\Services\AvaliacaoService;
use App\Services\BloqueioService;
use App\Services\GrandesAreasService;
use App\Services\ProfissaoService;
use App\Services\ServicoService;
use App\Services\TrabalhadorService;
use App\Services\LoginService;

class TrabalhadorController extends Controller
{
    private TrabalhadorService $trabalhadorService;
    private LoginService $loginService;
    private ImagemUploader $imagemUploader;

    public function __construct()
    {
        $this->trabalhadorService = new TrabalhadorService();
        $this->loginService = new LoginService();
        $this->imagemUploader = new ImagemUploader();
    }

    public function cadastrarTrabalhador()
    {
        return $this->view('Trabalhador/cadastrarTrabalhador');
    }

    public function salvarTrabalhador()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/usuarios');
        }

        $trabalhador = [
            'Nome'           => trim($_POST['nome'] ?? ''),
            'CPF'            => trim($_POST['cpf'] ?? ''),
            'Genero'         => $_POST['genero'] ?? null,
            'Email'          => trim($_POST['email'] ?? ''),
            'Senha'          => $_POST['senha'] ?? '',
            'DataNascimento' => $_POST['dataNascimento'] ?? null,
            'FotoPerfil'     => trim($_POST['fotoPerfil'] ?? ''),
            'Endereco'       => trim($_POST['endereco'] ?? ''),
            'Telefone'       => trim($_POST['telefone'] ?? ''),
        ];

        $confirmarSenha = $_POST['confirmarSenha'] ?? '';
        $arquivoFotoPerfil = $_FILES['arquivoFotoPerfil'] ?? null;
        $temUploadFotoPerfil = $arquivoFotoPerfil !== null
            && (int) ($arquivoFotoPerfil['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

        $fotoEnviada = null;
        try {
            $fotoEnviada = $this->imagemUploader->armazenar($arquivoFotoPerfil, 'perfis');
            if ($fotoEnviada !== null) {
                $trabalhador['FotoPerfil'] = $fotoEnviada;
            }
        } catch (RuntimeException $erro) {
            return $this->view('Trabalhador/cadastrarTrabalhador', [
                'erros' => ['fotoPerfil' => $erro->getMessage()],
                'usuario' => $trabalhador,
            ]);
        }

        $validador = new Validador();

        $validador
            ->obrigatorio('nome', $trabalhador['Nome'])
            ->obrigatorio('cpf', $trabalhador['CPF'])
            ->cpf('cpf', $trabalhador['CPF'])
            ->obrigatorio('email', $trabalhador['Email'])
            ->email('email', $trabalhador['Email'])
            ->obrigatorio('senha', $trabalhador['Senha'])
            ->minLength('senha', $trabalhador['Senha'], 6)
            ->senhaForte('senha', $trabalhador['Senha'])
            ->obrigatorio('confirmarSenha', $confirmarSenha, 'A confirmação de senha é obrigatória')
            ->confirmarSenha('confirmarSenha', $trabalhador['Senha'], $confirmarSenha, 'As senhas não coincidem')
            ->obrigatorio('dataNascimento', $trabalhador['DataNascimento'])
            ->idadeMinima(
                'dataNascimento',
                $trabalhador['DataNascimento'],
                18,
                'Não aceitamos menores de 18 anos'
            )
            ->datavalida('dataNascimento', $trabalhador['DataNascimento'], 'Informe uma data de nascimento válida')
            ->maxLength('endereco', $trabalhador['Endereco'], 255)
            ->obrigatorio('endereco', $trabalhador['Endereco'])
            ->cidadeEstado('endereco', $trabalhador['Endereco'])
            ->obrigatorio('genero', $trabalhador['Genero'])
            ->obrigatorio('telefone', $trabalhador['Telefone'])
            ->maxLength('telefone', $trabalhador['Telefone'], 11);

        if (! $temUploadFotoPerfil) {
            if (! empty($trabalhador['FotoPerfil'])) {
                if (! $this->urlFotoPerfilValida($trabalhador['FotoPerfil'])) {
                    $validador->imagem(
                        'fotoPerfil',
                        $trabalhador['FotoPerfil'],
                        'Informe uma URL de imagem válida (HTTP ou HTTPS).'
                    );
                }
            } else {
                // Foto padrão caso o usuário não envie arquivo nem URL
                $trabalhador['FotoPerfil'] = 'https://media.istockphoto.com/id/903053114/pt/vetorial/profile-anonymous-face-icon-gray-silhouette-person-male-default-avatar-photo-placeholder.jpg?s=612x612&w=0&k=20&c=j5boTBScOrbFmZZYH0kOFHFQTixg1AtZ7Phqe9u-ubI=';
            }
        }

        if ($validador->temErros()) {
            return $this->view('Trabalhador/cadastrarTrabalhador', [
                'erros'   => $validador->getErros(),
                'usuario' => $trabalhador,
            ]);
        }

        $errosDuplicidade = [];
        if ($this->trabalhadorService->cpfEmUso($trabalhador['CPF'])) {
            $errosDuplicidade['cpf'] = 'Este CPF já está cadastrado.';
        }
        if ($this->trabalhadorService->emailEmUso($trabalhador['Email'])) {
            $errosDuplicidade['email'] = 'Este e-mail já está cadastrado.';
        }
        if ($errosDuplicidade) {
            return $this->view('Trabalhador/cadastrarTrabalhador', [
                'erros' => $errosDuplicidade,
                'usuario' => $trabalhador,
            ]);
        }

        $trabalhador['CPF'] = preg_replace('/\D/', '', $trabalhador['CPF']);

        try {
            $salvo = $this->trabalhadorService->salvar($trabalhador);
        } catch (PDOException $erro) {
            if (! $this->ehViolacaoDeUnicidade($erro)) {
                $this->imagemUploader->removerSeArmazenada($fotoEnviada, 'perfis');
                throw $erro;
            }

            return $this->view('Trabalhador/cadastrarTrabalhador', [
                'erros' => ['cadastro' => 'Este CPF ou e-mail já está cadastrado.'],
                'usuario' => $trabalhador,
            ]);
        } catch (Throwable $erro) {
            $this->imagemUploader->removerSeArmazenada($fotoEnviada, 'perfis');
            throw $erro;
        }

        if (! $salvo) {
            return $this->view('Trabalhador/cadastrarTrabalhador', [
                'erros' => ['cadastro' => 'Não foi possível cadastrar o trabalhador. Tente novamente.'],
                'usuario' => $trabalhador,
            ]);
        }

        // Se cadastrado por um Administrador logado:
        if (isset($_SESSION['usuario_logado']) && (($_SESSION['usuario_logado']['Tipo'] ?? '') === 'adm' || ($_SESSION['usuario_tipo'] ?? '') === 'administrador')) {
            $_SESSION['sucesso'] = 'Trabalhador cadastrado com sucesso!';
            return $this->redirect(URL_BASE . '/usuarios');
        }

        // Auto-cadastro público de novo usuário:
        // Loga o usuário diretamente, limpando quaisquer mensagens antigas de erro de sessão
        $novoUsuario = $this->trabalhadorService->buscarPorEmail($trabalhador['Email']);
        if ($novoUsuario) {
            $novoUsuario['Tipo'] = 'trabalhador';
            unset($novoUsuario['Senha']);
            unset($_SESSION['erro']);
            $this->loginService->salvarUsuarioSessao($novoUsuario);
            $_SESSION['sucesso'] = 'Cadastro realizado com sucesso! Configure suas preferências profissionais para começar.';
            return $this->redirect(URL_BASE . '/trabalhador/preferencias');
        }

        unset($_SESSION['erro']);
        $_SESSION['sucesso'] = 'Cadastro realizado com sucesso! Faça login para continuar.';
        return $this->redirect(URL_BASE . '/login');
    }

    private function urlFotoPerfilValida(string $url): bool
    {
        $esquema = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return filter_var($url, FILTER_VALIDATE_URL) !== false
            && in_array($esquema, ['http', 'https'], true);
    }

    private function ehViolacaoDeUnicidade(PDOException $erro): bool
    {
        return (string) ($erro->errorInfo[0] ?? $erro->getCode()) === '23000'
            && (int) ($erro->errorInfo[1] ?? 0) === 1062;
    }

    public function exibirPreferencias()
    {
        $this->autenticacaoRequired();

        $idTrabalhador = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $trabalhador = $this->trabalhadorService->buscarPorId($idTrabalhador);

        if (! $trabalhador) {
            $_SESSION['erro'] = 'Trabalhador não encontrado.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $grandesAreasService = new GrandesAreasService();
        $profissaoService    = new ProfissaoService();

        $grandesAreas = $grandesAreasService->listarTodos();
        $profissoes   = $profissaoService->listarTodos();
        $interessesSelecionados = $this->trabalhadorService->listarInteresses($idTrabalhador);
        $erro = $_SESSION['erro'] ?? null;
        $origemEditar = ($_GET['origem'] ?? '') === 'editar';
        unset($_SESSION['erro']);

        return $this->view('Trabalhador/preferencias', [
            'grandesAreas'       => $grandesAreas,
            'profissoes'         => $profissoes,
            'interessesSelecionados' => $interessesSelecionados,
            'trabalhador'        => $trabalhador,
            'erro'               => $erro,
            'origemEditar'       => $origemEditar,
        ]);
    }

    public function exibirProfissoesPorGrandeArea()
    {

        $idGrandeArea = $_GET['id_grande_area'] ?? null;

        $profissaoService = new ProfissaoService();

        $profissoes = $profissaoService->listarPorGrandeArea((int) $idGrandeArea);

        $jsonProfissoes = json_encode($profissoes);

        header('Content-Type: application/json');
        print $jsonProfissoes;
    }

    public function salvarPreferencias()
    {
        $this->autenticacaoRequired();

        $idTrabalhador = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idProfissao   = (int) ($_POST['idProfissao'] ?? 0);
        $idGrandeArea  = (int) ($_POST['idGrandeArea'] ?? 0);
        $interessesPostados = $_POST['interesses'] ?? [];
        $origemEditar  = ($_POST['origem'] ?? '') === 'editar';
        $urlPreferencias = URL_BASE . '/trabalhador/preferencias' . ($origemEditar ? '?origem=editar' : '');

        if (! is_array($interessesPostados)) {
            $_SESSION['erro'] = 'Selecione interesses válidos.';
            return $this->redirect($urlPreferencias);
        }

        $areasDisponiveis = (new GrandesAreasService())->listarTodos();
        $idsAreasDisponiveis = array_map(
            static fn (array $area): int => (int) $area['IdGrandeArea'],
            $areasDisponiveis
        );
        $interesses = [];
        foreach ($interessesPostados as $interesse) {
            $idInteresse = filter_var($interesse, FILTER_VALIDATE_INT);
            if ($idInteresse === false || ! in_array($idInteresse, $idsAreasDisponiveis, true)) {
                $_SESSION['erro'] = 'Um ou mais interesses selecionados são inválidos. Revise suas escolhas.';
                return $this->redirect($urlPreferencias);
            }
            $interesses[] = $idInteresse;
        }
        $interesses = array_values(array_unique($interesses));

        if ($idTrabalhador <= 0 || $idProfissao <= 0 || $idGrandeArea <= 0) {
            $_SESSION['erro'] = 'Selecione sua área de atuação e profissão.';
            return $this->redirect($urlPreferencias);
        }

        $trabalhador = $this->trabalhadorService->buscarPorId($idTrabalhador);
        if (! $trabalhador) {
            $_SESSION['erro'] = 'Trabalhador não encontrado.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $primeiraConfiguracao = empty($trabalhador['IdProfissao']) || empty($trabalhador['IdGrandeArea']);
        $salvou = $this->trabalhadorService->salvarPreferencias(
            $idTrabalhador,
            $idProfissao,
            $idGrandeArea,
            $interesses
        );

        if (! $salvou) {
            $_SESSION['erro'] = 'Não foi possível salvar suas preferências. Tente novamente.';
            return $this->redirect($urlPreferencias);
        }

        $_SESSION['usuario_logado']['IdProfissao'] = $idProfissao;
        $_SESSION['usuario_logado']['IdGrandeArea'] = $idGrandeArea;

        if ($primeiraConfiguracao) {
            $_SESSION['sucesso'] = 'Preferências salvas! Sua profissão e experiência profissional podem ser atualizadas a qualquer momento em Editar Perfil.';
        } else {
            $_SESSION['sucesso'] = 'Preferências atualizadas com sucesso!';
        }

        return $this->redirect($origemEditar
            ? URL_BASE . '/trabalhador/editar'
            : URL_BASE . '/trabalhador/servicos');
    }

    public function mostrarPerfil()
    {
        $this->autenticacaoRequired();

        if (($_GET['descartarRascunho'] ?? '') === '1') {
            unset($_SESSION['rascunho_edicao_perfil']);
        }

        $idTrabalhador = $_GET['id'] ?? ($_SESSION['usuario_logado']['IdTrabalhador'] ?? null);

        if (! $idTrabalhador) {
            return $this->redirect(URL_BASE . '/login');
        }

        $trabalhador = $this->trabalhadorService->buscarPorId((int) $idTrabalhador);

        if (! $trabalhador) {
            $_SESSION['erro'] = 'Trabalhador não encontrado.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $avaliacaoService = new AvaliacaoService();
        $servicoService   = new ServicoService();
        $acordoService    = new AcordoService();
        $idLogado         = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);

        $avaliacoes         = $avaliacaoService->listarRecebidasPorTrabalhador((int) $idTrabalhador);
        $servicosOferecidos = $servicoService->listarMeusServicos((int) $idTrabalhador);
        $servicosRealizados = $acordoService->listarServicosConcluidosPorTrabalhador((int) $idTrabalhador);
        $reputacao          = $avaliacaoService->calcularReputacao((int) $idTrabalhador);

        $trabalhador['NotaMedia']       = $reputacao['media'];
        $trabalhador['TotalAvaliacoes'] = $reputacao['quantidade'];

        $bloqueioService  = new BloqueioService();
        $estaBloqueado    = ($idLogado > 0 && (int) $idTrabalhador !== $idLogado)
            ? $bloqueioService->estaBloqueadoDireto($idLogado, (int) $idTrabalhador)
            : false;

        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro    = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);

        return $this->view('Trabalhador/Perfil', [
            'trabalhador'        => $trabalhador,
            'avaliacoes'         => $avaliacoes,
            'servicosOferecidos' => $servicosOferecidos,
            'servicosRealizados' => $servicosRealizados,
            'totalAvaliacoes'    => count($avaliacoes),
            'totalServicos'      => count($servicosOferecidos),
            'estaBloqueado'      => $estaBloqueado,
            'sucesso'            => $sucesso,
            'erro'               => $erro,
        ]);
    }

    public function exibirEditarPerfil()
    {
        $this->autenticacaoRequired();

        $idTrabalhador = $_SESSION['usuario_logado']['IdTrabalhador'] ?? null;

        if (! $idTrabalhador) {
            return $this->redirect(URL_BASE . '/login');
        }

        $trabalhador = $this->trabalhadorService->buscarPorId((int) $idTrabalhador);

        if (! $trabalhador) {
            $_SESSION['erro'] = 'Trabalhador não encontrado.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $rascunho = $_SESSION['rascunho_edicao_perfil'] ?? null;
        if (
            is_array($rascunho)
            && (int) ($rascunho['idTrabalhador'] ?? 0) === (int) $idTrabalhador
            && is_array($rascunho['dados'] ?? null)
        ) {
            $trabalhador = array_merge($trabalhador, $rascunho['dados']);
        }

        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);

        return $this->view('Trabalhador/editarPerfil', [
            'trabalhador' => $trabalhador,
            'sucesso' => $sucesso,
            'erro' => $erro,
        ]);
    }

    public function editarPerfil()
    {
        $this->autenticacaoRequired();

        $idTrabalhador = $_POST['idTrabalhador'] ?? ($_SESSION['usuario_logado']['IdTrabalhador'] ?? null);

        if (! $idTrabalhador) {
            return $this->redirect(URL_BASE . '/login');
        }

        $trabalhadorAtual = $this->trabalhadorService->buscarPorId((int) $idTrabalhador);
        if (! $trabalhadorAtual) {
            $_SESSION['erro'] = 'Trabalhador não encontrado.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $dados = [
            'Nome'       => trim($_POST['nome'] ?? ''),
            'Genero'     => $_POST['genero'] ?? null,
            'FotoPerfil' => trim($_POST['fotoPerfil'] ?? ''),
            'Endereco'   => trim($_POST['endereco'] ?? ''),
            'Biografia'  => trim($_POST['biografia'] ?? ''),
            'Telefone'   => trim($_POST['telefone'] ?? ''),
            'Instagram'  => trim($_POST['instagram'] ?? ''),
            'Twitter'    => trim($_POST['twitter'] ?? ''),
        ];

        $fotoAnterior = $trabalhadorAtual['FotoPerfil'] ?? null;
        $fotoEnviada = null;
        try {
            $fotoEnviada = $this->imagemUploader->armazenar(
                $_FILES['arquivoFotoPerfil'] ?? null,
                'perfis'
            );
            if ($fotoEnviada !== null) {
                $dados['FotoPerfil'] = $fotoEnviada;
            }
        } catch (RuntimeException $erro) {
            $_SESSION['rascunho_edicao_perfil'] = [
                'idTrabalhador' => (int) $idTrabalhador,
                'dados' => $dados,
            ];
            $_SESSION['erro'] = $erro->getMessage();
            return $this->redirect(URL_BASE . '/trabalhador/editar');
        }

        $_SESSION['rascunho_edicao_perfil'] = [
            'idTrabalhador' => (int) $idTrabalhador,
            'dados' => $dados,
        ];

        if (($_POST['acao'] ?? '') === 'preferencias') {
            return $this->redirect(URL_BASE . '/trabalhador/preferencias?origem=editar');
        }

        $validador = new Validador();
        $validador->cidadeEstado(
            'endereco',
            $dados['Endereco'],
            'Informe somente cidade e UF no formato "Cidade, UF" (ex.: Foz do Iguaçu, PR).'
        );

        if ($validador->temErros()) {
            $_SESSION['erro'] = implode(' ', $validador->getErros());
            return $this->redirect(URL_BASE . '/trabalhador/editar');
        }

        if (! $this->trabalhadorService->atualizarPerfil((int) $idTrabalhador, $dados)) {
            $this->imagemUploader->removerSeArmazenada($fotoEnviada, 'perfis');
            if ($fotoEnviada !== null) {
                $dados['FotoPerfil'] = $fotoAnterior ?? '';
                $_SESSION['rascunho_edicao_perfil']['dados'] = $dados;
            }
            $_SESSION['erro'] = 'Não foi possível atualizar seu perfil. Tente novamente.';
            return $this->redirect(URL_BASE . '/trabalhador/editar');
        }

        if ($fotoEnviada !== null) {
            $this->imagemUploader->removerSeArmazenada(
                is_string($fotoAnterior) ? $fotoAnterior : null,
                'perfis'
            );
        }
        unset($_SESSION['rascunho_edicao_perfil']);

        if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado']['IdTrabalhador'] == $idTrabalhador) {
            if (! empty($dados['Nome'])) {
                $_SESSION['usuario_logado']['Nome'] = $dados['Nome'];
            }
            if (! empty($dados['FotoPerfil'])) {
                $_SESSION['usuario_logado']['FotoPerfil'] = $dados['FotoPerfil'];
            }
            $_SESSION['usuario_logado']['Telefone']  = $dados['Telefone'];
            $_SESSION['usuario_logado']['Instagram'] = $dados['Instagram'];
            $_SESSION['usuario_logado']['Twitter']   = $dados['Twitter'];
            $_SESSION['usuario_logado']['Biografia'] = $dados['Biografia'];
            $_SESSION['usuario_logado']['Endereco']  = $dados['Endereco'];
        }

        $_SESSION['sucesso'] = 'Perfil atualizado com sucesso!';
        return $this->redirect(URL_BASE . '/trabalhador/perfil?id=' . $idTrabalhador);
    }

    public function desativarConta()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/trabalhador/editar');
        }

        $idTrabalhador = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        if ($idTrabalhador <= 0) {
            return $this->redirect(URL_BASE . '/login');
        }

        if (! $this->trabalhadorService->alterarStatus($idTrabalhador, 'INATIVO', null)) {
            $_SESSION['erro'] = 'Não foi possível desativar sua conta. Tente novamente.';
            return $this->redirect(URL_BASE . '/trabalhador/editar');
        }

        unset($_SESSION['rascunho_edicao_perfil']);
        $this->loginService->apagarDadosSessao();
        $_SESSION['sucesso'] = 'Sua conta foi desativada. Para reativá-la, entre em contato com o suporte.';

        return $this->redirect(URL_BASE . '/login');
    }
}
