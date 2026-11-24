<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\MatchService;
use App\Services\ServicoService;

class MatchController extends Controller
{
    private MatchService $matchService;
    private ServicoService $servicoService;

    public function __construct()
    {
        $this->matchService   = new MatchService();
        $this->servicoService = new ServicoService();
    }

    public function solicitarMatch()
    {
        $this->autenticacaoRequired();

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idServicoDestino    = (int) ($_GET['idServico'] ?? 0);

        if ($idServicoDestino <= 0) {
            $_SESSION['erro'] = 'Serviço não informado para a proposta de match.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $todosDisponiveis = $this->servicoService->listarDisponiveis($idTrabalhadorLogado);
        $servicoDestino   = null;
        foreach ($todosDisponiveis as $serv) {
            if ((int) $serv['IdServico'] === $idServicoDestino) {
                $servicoDestino = $serv;
                break;
            }
        }

        if (! $servicoDestino) {
            $_SESSION['erro'] = 'Serviço solicitado não encontrado ou pertence a você.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $meusServicos = $this->servicoService->listarMeusServicos($idTrabalhadorLogado);

        return $this->view('Match/solicitar', [
            'servicoDestino' => $servicoDestino,
            'meusServicos'   => $meusServicos,
            'usuarioLogado'  => $_SESSION['usuario_logado'],
        ]);
    }

    public function registrarInteresseMatch()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $idTrabalhadorLogado  = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idServicoDestino     = (int) ($_POST['idServicoDestino'] ?? 0);
        $idTrabalhadorDestino = (int) ($_POST['idTrabalhadorDestino'] ?? 0);
        $idServicoOrigem      = ! empty($_POST['idServicoOrigem']) ? (int) $_POST['idServicoOrigem'] : null;
        $mensagem             = trim($_POST['mensagem'] ?? '');

        if ($idServicoDestino <= 0 || $idTrabalhadorDestino <= 0) {
            $_SESSION['erro'] = 'Dados inválidos para registrar a proposta de match.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $res = $this->matchService->proporMatch(
            $idTrabalhadorLogado,
            $idTrabalhadorDestino,
            $idServicoOrigem,
            $idServicoDestino,
            $mensagem
        );

        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
            if (! empty($res['propostaReciproca'])) {
                return $this->redirect(URL_BASE . '/matches#match-' . (int) $res['idMatch']);
            }
            return $this->redirect(URL_BASE . '/matches');
        } else {
            $_SESSION['erro'] = $res['mensagem'];
            // Se o erro for por falta de seleção de serviço, volta para a página de proposta.
            if ($idServicoDestino > 0) {
                return $this->redirect(URL_BASE . '/match/solicitar?idServico=' . $idServicoDestino);
            }
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }
    }

    public function listarMatches()
    {
        $this->autenticacaoRequired();

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $dadosMatches        = $this->matchService->listarMatchesDoUsuario($idTrabalhadorLogado);

        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro    = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);

        return $this->view('Match/listarMatches', [
            'recebidos'           => $dadosMatches['recebidos'],
            'enviados'            => $dadosMatches['enviados'],
            'deveFinalizarAcordo' => $dadosMatches['deveFinalizarAcordo'] ?? false,
            'usuarioLogado'       => $_SESSION['usuario_logado'],
            'sucesso'             => $sucesso,
            'erro'                => $erro,
        ]);
    }

    public function editarMensagemMatch()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/matches');
        }

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idMatch = (int) ($_POST['idMatch'] ?? 0);
        $mensagem = $_POST['mensagem'] ?? '';
        $mensagem = is_string($mensagem) ? trim($mensagem) : '';

        if ($idMatch <= 0) {
            $_SESSION['erro'] = 'Identificador do match inválido.';
            return $this->redirect(URL_BASE . '/matches');
        }

        $resultado = $this->matchService->editarMensagemMatch(
            $idMatch,
            $idTrabalhadorLogado,
            $mensagem
        );

        if ($resultado['sucesso']) {
            $_SESSION['sucesso'] = $resultado['mensagem'];
        } else {
            $_SESSION['erro'] = $resultado['mensagem'];
        }

        return $this->redirect(URL_BASE . '/matches');
    }

    public function aceitarInteresseMatch()
    {
        $this->autenticacaoRequired();

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idMatch             = (int) ($_POST['idMatch'] ?? $_GET['idMatch'] ?? 0);

        if ($idMatch <= 0) {
            $_SESSION['erro'] = 'Identificador do match inválido.';
            return $this->redirect(URL_BASE . '/matches');
        }

        $res = $this->matchService->aceitarMatch($idMatch, $idTrabalhadorLogado);

        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
            if (! empty($res['idAcordo'])) {
                return $this->redirect(URL_BASE . '/acordo/detalhes?id=' . $res['idAcordo']);
            }
            return $this->redirect(URL_BASE . '/acordos');
        } else {
            $_SESSION['erro'] = $res['mensagem'];
            return $this->redirect(URL_BASE . '/matches');
        }
    }

    public function recusarInteresseMatch()
    {
        $this->autenticacaoRequired();

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idMatch             = (int) ($_POST['idMatch'] ?? $_GET['idMatch'] ?? 0);

        if ($idMatch <= 0) {
            $_SESSION['erro'] = 'Identificador do match inválido.';
            return $this->redirect(URL_BASE . '/matches');
        }

        $res = $this->matchService->recusarMatch($idMatch, $idTrabalhadorLogado);

        if ($res['sucesso']) {
            $_SESSION['sucesso'] = $res['mensagem'];
        } else {
            $_SESSION['erro'] = $res['mensagem'];
        }

        return $this->redirect(URL_BASE . '/matches');
    }
}
