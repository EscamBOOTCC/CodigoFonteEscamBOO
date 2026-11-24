<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\AcordoService;
use App\Services\AvaliacaoService;

class AcordoController extends Controller
{
    private AcordoService $acordoService;
    private AvaliacaoService $avaliacaoService;

    public function __construct()
    {
        $this->acordoService = new AcordoService();
        $this->avaliacaoService = new AvaliacaoService();
    }

    
    public function listarAcordos()
    {
        $this->autenticacaoRequired();
 
        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $statusFiltro = isset($_GET['status']) && $_GET['status'] !== '' ? (int) $_GET['status'] : null;
 
        $acordos = $this->acordoService->listarPorTrabalhador($idTrabalhadorLogado, $statusFiltro);
 
        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);
 
        return $this->view('Acordo/listarAcordos', [
            'acordos'       => $acordos,
            'statusFiltro'  => $statusFiltro,
            'usuarioLogado' => $_SESSION['usuario_logado'],
            'sucesso'       => $sucesso,
            'erro'          => $erro
        ]);
    }

    

    public function detalhesAcordo(): void
    {
        $this->autenticacaoRequired();
 
        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idAcordo = (int) ($_GET['id'] ?? $_GET['idAcordo'] ?? 0);
 
        if ($idAcordo <= 0) {
            $_SESSION['erro'] = 'Identificador do acordo não informado.';
            $this->redirect(URL_BASE . '/acordos');
            return;
        }
 
        $acordo = $this->acordoService->buscarPorId($idAcordo);
 
        if (!$acordo) {
            $_SESSION['erro'] = 'Acordo não encontrado.';
            $this->redirect(URL_BASE . '/acordos');
            return;
        }
 
        
        if ((int)$acordo['IdTrabalhadorUm'] !== $idTrabalhadorLogado && (int)$acordo['IdTrabalhadorDois'] !== $idTrabalhadorLogado) {
            $_SESSION['erro'] = 'Você não tem permissão para visualizar este acordo.';
            $this->redirect(URL_BASE . '/acordos');
            return;
        }
 
        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);
 
        $jaAvaliei = (int) $acordo['Status'] === 1
            ? $this->avaliacaoService->jaAvaliou($idAcordo, $idTrabalhadorLogado)
            : false;
 
        $this->view('Acordo/detalhesAcordo', [
            'acordo'        => $acordo,
            'usuarioLogado' => $_SESSION['usuario_logado'],
            'sucesso'       => $sucesso,
            'erro'          => $erro,
            'jaAvaliei'     => $jaAvaliei,
        ]);
    }

    
    public function concluirAcordo()
    {
        $this->autenticacaoRequired();
 
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/acordos');
        }
 
        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idAcordo = (int) ($_POST['idAcordo'] ?? 0);
        $notaParceiro = isset($_POST['notaParceiro']) && $_POST['notaParceiro'] !== '' ? (float) $_POST['notaParceiro'] : null;
        $comentarioParceiro = trim($_POST['comentarioParceiro'] ?? '');
        $notaAcordo = isset($_POST['notaAcordo']) && $_POST['notaAcordo'] !== '' ? (float) $_POST['notaAcordo'] : null;
        $comentarioAcordo = trim($_POST['comentarioAcordo'] ?? '');
 
        if ($idAcordo <= 0) {
            $_SESSION['erro'] = 'Acordo inválido.';
            return $this->redirect(URL_BASE . '/acordos');
        }

        if ($notaParceiro === null || $notaParceiro < 1 || $notaParceiro > 5) {
            $_SESSION['erro'] = 'Antes de fechar o acordo, você precisa avaliar o seu parceiro com uma nota de 1 a 5.';
            return $this->redirect(URL_BASE . '/acordo/detalhes?id=' . $idAcordo);
        }

        if ($notaAcordo === null || $notaAcordo < 1 || $notaAcordo > 5) {
            $_SESSION['erro'] = 'Antes de fechar o acordo, você também precisa avaliar o escambo com uma nota de 1 a 5.';
            return $this->redirect(URL_BASE . '/acordo/detalhes?id=' . $idAcordo);
        }
 
        $res = $this->acordoService->concluirAcordo(
            $idAcordo,
            $idTrabalhadorLogado,
            $notaParceiro,
            $comentarioParceiro !== '' ? $comentarioParceiro : null,
            $notaAcordo,
            $comentarioAcordo !== '' ? $comentarioAcordo : null
        );
 
        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
        } else {
            $_SESSION['erro'] = $res['mensagem'];
        }
 
        return $this->redirect(URL_BASE . '/acordo/detalhes?id=' . $idAcordo);
    }

    
    public function cancelarAcordo()
    {
        $this->autenticacaoRequired();
 
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/acordos');
        }
 
        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idAcordo = (int) ($_POST['idAcordo'] ?? 0);
        $justificativa = trim($_POST['justificativa'] ?? '');
 
        if ($idAcordo <= 0) {
            $_SESSION['erro'] = 'Acordo inválido.';
            return $this->redirect(URL_BASE . '/acordos');
        }
 
        if (empty($justificativa)) {
            $_SESSION['erro'] = 'Por favor, informe a justificativa para o cancelamento do acordo.';
            return $this->redirect(URL_BASE . '/acordo/detalhes?id=' . $idAcordo);
        }
 
        $res = $this->acordoService->cancelarAcordo($idAcordo, $idTrabalhadorLogado, $justificativa);
 
        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
        } else {
            $_SESSION['erro'] = $res['mensagem'];
        }
 
        return $this->redirect(URL_BASE . '/acordo/detalhes?id=' . $idAcordo);
    }
}
