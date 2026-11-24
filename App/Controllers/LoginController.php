<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Validador;
use App\Services\AdministradorService;
use App\Services\LoginService;
use App\Services\TrabalhadorService;
use DateTime;

class LoginController extends Controller
{
    private LoginService $loginService;

    public function __construct()
    {
        $this->loginService = new LoginService();
    }

    public function login()
    {
        return $this->view('Login/Login');
    }

    public function autenticar()
    {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $validador = new Validador();
        $validador
            ->obrigatorio('email', $email)
            ->obrigatorio('senha', $senha);

        if ($validador->temErros()) {
            return $this->view('Login/Login', [
                'erros' => $validador->getErros(),
                'email' => $email,
            ]);
        }
        $usuario = $this->loginService->buscarPorEmail($email);

        if (! $usuario) {
            return $this->view('Login/Login', [
                'erros' => ['login' => 'E-mail ou senha inválidos.'],
                'email' => $email,
            ]);
        }
        if (! password_verify($senha, $usuario['Senha'] ?? '')) {
            return $this->view('Login/Login', [
                'erros' => ['login' => 'E-mail ou senha inválidos.'],
                'email' => $email,
            ]);
        }
        $statusAtivo      = strtoupper((string) ($usuario['Ativo'] ?? 'ATIVO'));
        $dataExpiraStatus = $usuario['DataExpiraStatus'] ?? null;

        if ($statusAtivo !== 'ATIVO') {
            $agora      = new DateTime();
            $dataExpira = $dataExpiraStatus ? new DateTime($dataExpiraStatus) : null;
            if ($statusAtivo === 'SUSPENSO' && $dataExpira && $agora >= $dataExpira) {
                $statusAtivo = 'ATIVO';
                $usuario['Ativo'] = 'ATIVO';
                if (($usuario['Tipo'] ?? '') === 'trabalhador') {
                    (new TrabalhadorService())->alterarStatus((int) $usuario['IdTrabalhador'], 'ATIVO', null);
                } else {
                    (new AdministradorService())->alterarStatus((int) $usuario['IdAdm'], 'ATIVO', null);
                }
            } else {
                $mensagemErro = match ($statusAtivo) {
                    'INATIVO'  => 'Esta conta está inativa. Entre em contato com o suporte.',
                    'SUSPENSO' => 'Sua conta está suspensa' . ($dataExpira ? ' até ' . $dataExpira->format('d/m/Y H:i') : '') . '.',
                    'BANIDO'   => 'Esta conta foi permanentemente banida da plataforma.',
                    default    => 'Conta indisponível no momento.'
                };
                return $this->view('Login/Login', [
                    'erros' => ['login' => $mensagemErro],
                    'email' => $email,
                ]);
            }
        }
        // O hash da senha não deve ficar guardado na sessão.
        unset($usuario['Senha']);

        unset($_SESSION['aviso_perfil_dispensado']);
        $this->loginService->salvarUsuarioSessao($usuario);

        if (($usuario['Tipo'] ?? '') === 'adm') {
            return $this->redirect(URL_BASE . '/adm');
        }

        $preferencias = empty($usuario['IdGrandeArea']) || empty($usuario['IdProfissao']);

        if ($preferencias) {
            return $this->redirect(URL_BASE . '/trabalhador/preferencias');
        }

        return $this->redirect(URL_BASE . '/trabalhador/servicos');
    }

    public function logout()
    {
        $this->loginService->apagarDadosSessao();

        return $this->redirect(URL_BASE . '/login');
    }

    public function dispensarAvisoPerfil(): void
    {
        if (($_SESSION['usuario_logado']['Tipo'] ?? '') !== 'trabalhador') {
            http_response_code(403);
            exit();
        }

        $_SESSION['aviso_perfil_dispensado'] = true;
        http_response_code(204);
        exit();
    }

    public function getNomeUsuarioLogado()
    {
        return $this->loginService->getNomeUsuarioLogado();
    }

    public function getUsuarioLogado(): ?array
    {
        return $this->loginService->getUsuarioLogado();
    }

    public function usuarioEstaLogado(): bool
    {
        return $this->loginService->usuarioEstaLogado();
    }
}
