<?php
namespace App\Services;

class LoginService
{
    private TrabalhadorService $trabalhadorService;
    private AdministradorService $administradorService;

    public function __construct()
    {
        $this->trabalhadorService   = new TrabalhadorService();
        $this->administradorService = new AdministradorService();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $trabalhador = $this->trabalhadorService->buscarPorEmail($email);

        if ($trabalhador) {
            $trabalhador['Tipo'] = 'trabalhador';
            return $trabalhador;
        }

        $adm = $this->administradorService->buscarPorEmail($email);

        if ($adm) {
            $adm['Tipo'] = 'adm';
            return $adm;
        }

        return null;
    }

    public function salvarUsuarioSessao(array $usuario): void
    {
        $_SESSION['usuario_logado'] = $usuario;
    }

    public function apagarDadosSessao(): void
    {
        unset($_SESSION['usuario_logado']);
    }

    public function getNomeUsuarioLogado(): ?string
    {
        return $_SESSION['usuario_logado']['Nome'] ?? null;
    }

    public function getUsuarioLogado(): ?array
    {
        return $_SESSION['usuario_logado'] ?? null;
    }

    public function usuarioEstaLogado(): bool
    {
        return isset($_SESSION['usuario_logado']);
    }

}
