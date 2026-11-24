<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\BloqueioService;

class BloqueioController extends Controller
{
    private BloqueioService $bloqueioService;

    public function __construct()
    {
        $this->bloqueioService = new BloqueioService();
    }

    public function bloquearTrabalhador()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $idTrabalhadorLogado   = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idTrabalhadorBloquear = (int) ($_POST['idTrabalhador'] ?? 0);

        if ($idTrabalhadorBloquear <= 0) {
            $_SESSION['erro'] = 'Trabalhador inválido para bloqueio.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $res = $this->bloqueioService->bloquear($idTrabalhadorLogado, $idTrabalhadorBloquear);

        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
        } else {
            $_SESSION['erro'] = $res['mensagem'];
        }

        $origem = $_POST['origem'] ?? 'servicos';
        if ($origem === 'perfil') {
            return $this->redirect(URL_BASE . '/trabalhador/perfil?id=' . $idTrabalhadorBloquear);
        }

        return $this->redirect(URL_BASE . '/trabalhador/servicos');
    }

    public function desbloquearTrabalhador()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/trabalhador/bloqueados');
        }

        $idTrabalhadorLogado     = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idTrabalhadorDesbloquear = (int) ($_POST['idTrabalhador'] ?? 0);

        if ($idTrabalhadorDesbloquear <= 0) {
            $_SESSION['erro'] = 'Trabalhador inválido para desbloqueio.';
            return $this->redirect(URL_BASE . '/trabalhador/bloqueados');
        }

        $res = $this->bloqueioService->desbloquear($idTrabalhadorLogado, $idTrabalhadorDesbloquear);

        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
        } else {
            $_SESSION['erro'] = $res['mensagem'];
        }

        $origem = $_POST['origem'] ?? 'bloqueados';
        if ($origem === 'perfil') {
            return $this->redirect(URL_BASE . '/trabalhador/perfil?id=' . $idTrabalhadorDesbloquear);
        }

        return $this->redirect(URL_BASE . '/trabalhador/bloqueados');
    }

    public function listarBloqueados()
    {
        $this->autenticacaoRequired();

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $bloqueados          = $this->bloqueioService->listarBloqueados($idTrabalhadorLogado);

        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro    = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);

        return $this->view('Trabalhador/bloqueados', [
            'bloqueados'    => $bloqueados,
            'usuarioLogado' => $_SESSION['usuario_logado'] ?? [],
            'sucesso'       => $sucesso,
            'erro'          => $erro,
        ]);
    }
}
