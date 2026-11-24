<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\PenalidadeService;

class PenalidadeController extends Controller
{
    private PenalidadeService $penalidadeService;

    public function __construct()
    {
        $this->penalidadeService = new PenalidadeService();
    }

    public function aplicarPenalidade()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/adm/denuncias');
        }

        $idAdm          = (int) ($_SESSION['usuario_logado']['IdAdm'] ?? 0);
        $idTrabalhador  = (int) ($_POST['idTrabalhador'] ?? 0);
        $idDenuncia     = !empty($_POST['idDenuncia']) ? (int) $_POST['idDenuncia'] : null;
        $motivo         = trim($_POST['motivo'] ?? '');
        $tipo           = (int) ($_POST['tipo'] ?? 0);
        $diasSuspensao  = !empty($_POST['diasSuspensao']) ? (int) $_POST['diasSuspensao'] : 7;

        if ($idTrabalhador <= 0 || empty($motivo) || $tipo <= 0) {
            $_SESSION['erro'] = 'Preencha todos os campos obrigatórios para aplicar a penalidade.';
            return $this->redirect(URL_BASE . '/adm/denuncias');
        }

        $res = $this->penalidadeService->aplicarPenalidade(
            $idAdm,
            $idTrabalhador,
            $motivo,
            $tipo,
            $diasSuspensao,
            $idDenuncia
        );

        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
        } else {
            $_SESSION['erro'] = $res['mensagem'];
        }

        return $this->redirect(URL_BASE . '/adm/denuncias');
    }
}
