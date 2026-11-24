<?php
namespace App\Services;

use App\Repositories\AcordoRepository;
use App\Repositories\MatchRepository;

class MatchService
{
    private MatchRepository $matchRepository;
    private AcordoRepository $acordoRepository;
    private TrabalhadorService $trabalhadorService;
    private BloqueioService $bloqueioService;

    public function __construct()
    {
        $this->matchRepository    = new MatchRepository();
        $this->acordoRepository   = new AcordoRepository();
        $this->trabalhadorService = new TrabalhadorService();
        $this->bloqueioService    = new BloqueioService();
    }

    public function proporMatch(int $idTrabalhadorOrigem, int $idTrabalhadorDestino, ?int $idServicoOrigem, int $idServicoDestino, ?string $mensagem): array
    {
        if ($idTrabalhadorOrigem === $idTrabalhadorDestino) {
            return ['sucesso' => false, 'mensagem' => 'Você não pode propor match com seu próprio serviço.'];
        }

        if ($this->bloqueioService->estaBloqueado($idTrabalhadorOrigem, $idTrabalhadorDestino)) {
            return ['sucesso' => false, 'mensagem' => 'Não é possível propor match com este usuário devido a um bloqueio ativo (RN 15).'];
        }

        if ($this->usuarioTemAcordoEmVigor($idTrabalhadorOrigem)) {
            return ['sucesso' => false, 'mensagem' => 'Você já possui um acordo em andamento. Finalize ou cancele o acordo atual antes de criar outro match.'];
        }

        // Só pode propor match quem selecionar um dos seus serviços cadastrados.
        if (empty($idServicoOrigem)) {
            return ['sucesso' => false, 'mensagem' => 'Selecione um dos seus serviços cadastrados para oferecer na troca.'];
        }

        $propostaReciproca = $this->matchRepository->buscarPropostaReciprocaPendente(
            $idTrabalhadorOrigem,
            $idTrabalhadorDestino,
            $idServicoOrigem,
            $idServicoDestino
        );
        if ($propostaReciproca) {
            return [
                'sucesso' => true,
                'mensagem' => 'Esta troca corresponde a uma proposta que você já recebeu. Revise a proposta recebida para aceitar ou recusar.',
                'idMatch' => (int) $propostaReciproca['IdMatch'],
                'propostaReciproca' => true,
            ];
        }

        $existente = $this->matchRepository->verificarMatchExistente($idTrabalhadorOrigem, $idServicoDestino);
        if ($existente) {
            return ['sucesso' => false, 'mensagem' => 'Você já possui uma proposta de match pendente para este serviço.'];
        }

        $idMatch = $this->matchRepository->criarMatch([
            'IdTrabalhadorUm'   => $idTrabalhadorOrigem,
            'IdTrabalhadorDois' => $idTrabalhadorDestino,
            'IdServicoOrigem'   => $idServicoOrigem,
            'IdServicoDestino'  => $idServicoDestino,
            'Mensagem'          => $mensagem,
        ]);

        if ($idMatch) {
            return ['sucesso' => true, 'mensagem' => 'Proposta de match enviada com sucesso! Aguarde a resposta do outro usuário.', 'idMatch' => $idMatch];
        }

        return ['sucesso' => false, 'mensagem' => 'Ocorreu um erro ao enviar a proposta de match. Tente novamente.'];
    }

    public function buscarMatch(int $idMatch): ?array
    {
        return $this->matchRepository->buscarPorId($idMatch);
    }

    public function usuarioTemAcordoEmVigor(int $idTrabalhador): bool
    {
        // Fonte da verdade: tabela Acordo (Status=0 = em andamento).
        // Não usamos mais o campo StatusAcordo do Trabalhador como critério,
        // pois pode ficar dessincronizado entre acordos.
        return $this->acordoRepository->temAcordoAtivoParaTrabalhador($idTrabalhador);
    }

    public function listarMatchesDoUsuario(int $idTrabalhador): array
    {
        $deveFinalizarAcordo = $this->usuarioTemAcordoEmVigor($idTrabalhador);

        return [
            'recebidos' => $deveFinalizarAcordo ? [] : $this->matchRepository->listarRecebidos($idTrabalhador),
            'enviados'  => $deveFinalizarAcordo ? [] : $this->matchRepository->listarEnviados($idTrabalhador),
            'deveFinalizarAcordo' => $deveFinalizarAcordo,
        ];
    }

    public function contarPropostasRecebidasPendentes(int $idTrabalhador): int
    {
        return $this->matchRepository->contarRecebidosPendentes($idTrabalhador);
    }

    public function editarMensagemMatch(int $idMatch, int $idTrabalhadorLogado, string $mensagem): array
    {
        $match = $this->matchRepository->buscarPorId($idMatch);
        if (! $match) {
            return ['sucesso' => false, 'mensagem' => 'Match não encontrado.'];
        }

        if ((int) $match['IdTrabalhadorUm'] !== $idTrabalhadorLogado) {
            return ['sucesso' => false, 'mensagem' => 'Você não tem permissão para editar esta proposta.'];
        }

        if ((int) $match['Status'] !== 0) {
            return ['sucesso' => false, 'mensagem' => 'Só é possível editar a mensagem de propostas pendentes.'];
        }

        if (! $this->matchRepository->atualizarMensagemPendente(
            $idMatch,
            $idTrabalhadorLogado,
            $mensagem
        )) {
            return ['sucesso' => false, 'mensagem' => 'A proposta não está mais pendente e não pode ser editada.'];
        }

        return ['sucesso' => true, 'mensagem' => 'Mensagem da proposta atualizada com sucesso.'];
    }

    public function aceitarMatch(int $idMatch, int $idTrabalhadorLogado): array
    {
        $match = $this->matchRepository->buscarPorId($idMatch);
        if (! $match) {
            return ['sucesso' => false, 'mensagem' => 'Match não encontrado.'];
        }

        if ((int) $match['IdTrabalhadorDois'] !== $idTrabalhadorLogado) {
            return ['sucesso' => false, 'mensagem' => 'Você não tem permissão para responder a este match.'];
        }

        $idTrabalhadorUm   = (int) $match['IdTrabalhadorUm'];
        $idTrabalhadorDois = (int) $match['IdTrabalhadorDois'];

        if ($this->trabalhadorService->estaEmServico($idTrabalhadorUm)) {
            return ['sucesso' => false, 'mensagem' => 'O outro trabalhador já possui um acordo ativo no momento. Não é possível aceitar este match agora.'];
        }

        if ($this->trabalhadorService->estaEmServico($idTrabalhadorDois)) {
            return ['sucesso' => false, 'mensagem' => 'Você já possui um acordo ativo no momento. Conclua ou cancele-o antes de aceitar um novo match.'];
        }

        $aceitou = $this->matchRepository->responderMatch($idMatch, $idTrabalhadorLogado, 1);
        if (! $aceitou) {
            return ['sucesso' => false, 'mensagem' => 'Erro ao aceitar o match. Tente novamente.'];
        }

        $idAcordo = $this->acordoRepository->criarAcordo($idMatch);
        if (! $idAcordo) {
            return ['sucesso' => false, 'mensagem' => 'Match aceito, mas não foi possível criar o acordo.'];
        }

        $this->trabalhadorService->atualizarStatusAcordo($idTrabalhadorUm, true);
        $this->trabalhadorService->atualizarStatusAcordo($idTrabalhadorDois, true);

        return [
            'sucesso' => true,
            'mensagem' => 'Match aceito com sucesso! O acordo foi formalizado.',
            'idAcordo' => (int) $idAcordo,
        ];
    }

    public function recusarMatch(int $idMatch, int $idTrabalhadorLogado): array
    {
        $match = $this->matchRepository->buscarPorId($idMatch);
        if (! $match) {
            return ['sucesso' => false, 'mensagem' => 'Match não encontrado.'];
        }

        if ((int) $match['IdTrabalhadorDois'] !== $idTrabalhadorLogado) {
            return ['sucesso' => false, 'mensagem' => 'Você não tem permissão para responder a este match.'];
        }

        $recusou = $this->matchRepository->responderMatch($idMatch, $idTrabalhadorLogado, 2);
        if ($recusou) {
            return ['sucesso' => true, 'mensagem' => 'Proposta de match recusada.'];
        }

        return ['sucesso' => false, 'mensagem' => 'Erro ao recusar o match. Tente novamente.'];
    }
}
