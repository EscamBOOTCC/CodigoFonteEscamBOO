<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\AvaliacaoService;

class AvaliacaoController extends Controller
{
    private AvaliacaoService $avaliacaoService;

    public function __construct()
    {
        $this->avaliacaoService = new AvaliacaoService();
    }

    public function registrarAvaliacao(): void
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(URL_BASE . '/acordos');
            return;
        }

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idAcordo            = (int) ($_POST['idAcordo'] ?? 0);
        $nota                = (float) ($_POST['nota'] ?? 0);
        $comentario          = trim($_POST['comentario'] ?? '');

        if ($idAcordo <= 0) {
            $_SESSION['erro'] = 'Acordo inválido.';
            $this->redirect(URL_BASE . '/acordos');
            return;
        }

        $res = $this->avaliacaoService->registrarAvaliacao(
            $idAcordo,
            $idTrabalhadorLogado,
            $nota,
            $comentario !== '' ? $comentario : null
        );

        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
        } else {
            $_SESSION['erro'] = $res['mensagem'];
        }

        $this->redirect(URL_BASE . '/acordo/detalhes?id=' . $idAcordo);
    }
}