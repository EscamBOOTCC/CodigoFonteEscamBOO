<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\BloqueioService;
use App\Services\DenunciaService;
use App\Services\MatchService;
use App\Services\ServicoService;

class DenunciaController extends Controller
{
    private DenunciaService $denunciaService;
    private ServicoService $servicoService;
    private BloqueioService $bloqueioService;
    private MatchService $matchService;

    public function __construct()
    {
        $this->denunciaService = new DenunciaService();
        $this->servicoService = new ServicoService();
        $this->bloqueioService = new BloqueioService();
        $this->matchService = new MatchService();
    }

    public function criarDenuncia()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $idDenunciante = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idDenunciado  = (int) ($_POST['idTrabalhador'] ?? 0);
        $idServico     = (int) ($_POST['idServico'] ?? 0);
        $idMatch       = (int) ($_POST['idMatch'] ?? 0);
        $origem        = $_POST['origem'] ?? 'servicos';
        $urlRetorno     = URL_BASE . '/trabalhador/servicos';
        if ($origem === 'perfil') {
            $urlRetorno = URL_BASE . '/trabalhador/perfil?id=' . $idDenunciado;
        } elseif ($origem === 'matches') {
            $urlRetorno = URL_BASE . '/matches#match-' . $idMatch;
        }
        $motivo        = trim($_POST['motivo'] ?? '');
        $descricao     = trim($_POST['descricao'] ?? '');

        if ($idDenunciado <= 0 || empty($motivo)) {
            $_SESSION['erro'] = 'Por favor, selecione o motivo da denúncia.';
            return $this->redirect($urlRetorno);
        }

        if ($origem === 'matches') {
            $match = $idMatch > 0 ? $this->matchService->buscarMatch($idMatch) : null;
            if (
                ! $match ||
                (int) $match['IdTrabalhadorDois'] !== $idDenunciante ||
                (int) $match['IdTrabalhadorUm'] !== $idDenunciado ||
                (int) $match['IdServicoOrigem'] !== $idServico
            ) {
                $_SESSION['erro'] = 'A proposta selecionada não é válida para denúncia.';
                return $this->redirect($urlRetorno);
            }
        }

        if ($idServico > 0) {
            $servico = $this->servicoService->buscarParaDenuncia($idServico);
            if (! $servico || (int) $servico['IdTrabalhador'] !== $idDenunciado) {
                $_SESSION['erro'] = 'O serviço selecionado não pertence a este trabalhador ou não está mais disponível.';
                return $this->redirect($urlRetorno);
            }

            $prefixo = $origem === 'matches' ? 'Proposta de serviço #' : 'Serviço #';
            $motivo = $prefixo . $idServico . ' — ' . $motivo;
        }

        $res = $this->denunciaService->criarDenuncia($idDenunciante, $idDenunciado, $motivo, $descricao);

        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
            if ($idServico > 0 && ! empty($_POST['bloquearAposDenuncia'])) {
                $bloqueio = $this->bloqueioService->bloquear($idDenunciante, $idDenunciado);
                if ($bloqueio['sucesso']) {
                    $_SESSION['sucesso'] .= ' O trabalhador também foi bloqueado.';
                } else {
                    $_SESSION['erro'] = 'A denúncia foi registrada, mas não foi possível bloquear o trabalhador: ' . $bloqueio['mensagem'];
                }
            }
        } else {
            $_SESSION['erro'] = $res['mensagem'];
        }

        return $this->redirect($urlRetorno);
    }

    public function listarMinhasDenuncias()
    {
        $this->autenticacaoRequired();

        if (($_SESSION['usuario_logado']['Tipo'] ?? '') !== 'trabalhador') {
            return $this->redirect(URL_BASE . '/adm');
        }

        $idDenunciante = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        if ($idDenunciante <= 0) {
            $_SESSION['erro'] = 'Não foi possível identificar sua conta.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $denuncias = $this->denunciaService->listarDenunciasDoUsuario($idDenunciante);
        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);

        return $this->view('Denuncia/minhasDenuncias', [
            'denuncias' => $denuncias,
            'sucesso' => $sucesso,
            'erro' => $erro,
        ]);
    }
}