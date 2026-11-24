<?php
namespace App\Services;

use App\Models\Avaliacao;
use App\Repositories\AcordoRepository;

class AcordoService
{
    private AcordoRepository $repository;
    private TrabalhadorService $trabalhadorService;

    public function __construct()
    {
        $this->repository = new AcordoRepository();
        $this->trabalhadorService = new TrabalhadorService();
    }

    public function buscarPorId(int $idAcordo): ?array
    {
        return $this->repository->buscarPorId($idAcordo);
    }

    public function listarPorTrabalhador(int $idTrabalhador, ?int $status = null): array
    {
        return $this->repository->listarPorTrabalhador($idTrabalhador, $status);
    }

    public function contarAtivos(): int
    {
        return $this->repository->contarAtivos();
    }

    public function servicoTemAcordoAtivo(int $idServico): bool
    {
        return $this->repository->servicoTemAcordoAtivo($idServico);
    }

    public function listarCanceladosParaAdmin(): array
    {
        return $this->repository->listarCanceladosParaAdmin();
    }

    public function contarCancelados(): int
    {
        return $this->repository->contarCancelados();
    }

    public function listarServicosConcluidosPorTrabalhador(int $idTrabalhador): array
    {
        return $this->repository->listarServicosConcluidosPorTrabalhador($idTrabalhador);
    }

    public function concluirAcordo(int $idAcordo, int $idTrabalhador, ?float $nota = null, ?string $comentario = null, ?float $notaAcordo = null, ?string $comentarioAcordo = null): array
    {
        if ($nota === null || $nota < 1 || $nota > 5) {
            return ['sucesso' => false, 'mensagem' => 'Antes de fechar o acordo, você precisa avaliar seu parceiro com uma nota de 1 a 5.'];
        }

        if ($notaAcordo === null || $notaAcordo < 1 || $notaAcordo > 5) {
            return ['sucesso' => false, 'mensagem' => 'Antes de fechar o acordo, você também precisa avaliar o escambo com uma nota de 1 a 5.'];
        }

        $acordoAntes = $this->repository->buscarPorId($idAcordo);
        if (! $acordoAntes) {
            return ['sucesso' => false, 'mensagem' => 'Acordo não encontrado.'];
        }

        // 1) Registra a confirmação deste trabalhador. Se o outro já tinha confirmado, o acordo vira Concluído.
        //    Também valida participação e se o acordo ainda pode ser concluído.
        $resultado = $this->repository->registrarConfirmacaoConclusao($idAcordo, $idTrabalhador);

        if (empty($resultado['sucesso'])) {
            return $resultado;
        }

        // 2) Guarda as DUAS avaliações de quem acabou de confirmar (parceiro e escambo),
        //    inclusive de quem confirma primeiro. Elas só entram na reputação quando o acordo
        //    estiver concluído pelos dois (filtro feito no AvaliacaoRepository).
        $avaliacaoService = new AvaliacaoService();

        if (! $avaliacaoService->jaAvaliou($idAcordo, $idTrabalhador, Avaliacao::TIPO_PARCEIRO)) {
            $avaliacaoParceiro = $avaliacaoService->registrarAvaliacao(
                $idAcordo,
                $idTrabalhador,
                (float) $nota,
                $comentario !== '' ? $comentario : null,
                Avaliacao::TIPO_PARCEIRO
            );

            if (! $avaliacaoParceiro['sucesso']) {
                return $avaliacaoParceiro;
            }
        }

        if (! $avaliacaoService->jaAvaliou($idAcordo, $idTrabalhador, Avaliacao::TIPO_ESCAMBO)) {
            $avaliacaoEscambo = $avaliacaoService->registrarAvaliacao(
                $idAcordo,
                $idTrabalhador,
                (float) $notaAcordo,
                $comentarioAcordo !== '' ? $comentarioAcordo : null,
                Avaliacao::TIPO_ESCAMBO
            );

            if (! $avaliacaoEscambo['sucesso']) {
                return $avaliacaoEscambo;
            }
        }

        // 3) Acordo concluído pelos dois: libera os trabalhadores para novos matches.
        if (! empty($resultado['finalizado'])) {
            $this->trabalhadorService->atualizarStatusAcordo((int) $acordoAntes['IdTrabalhadorUm'], false);
            $this->trabalhadorService->atualizarStatusAcordo((int) $acordoAntes['IdTrabalhadorDois'], false);
        }

        return $resultado;
    }

    public function cancelarAcordo(int $idAcordo, int $idTrabalhador, string $justificativa): array
    {
        $justificativa = trim($justificativa);
        if (empty($justificativa)) {
            return ['sucesso' => false, 'mensagem' => 'Por favor, informe a justificativa de cancelamento do acordo.'];
        }
 
        $acordo = $this->repository->buscarPorId($idAcordo);
        if (! $acordo) {
            return ['sucesso' => false, 'mensagem' => 'Acordo não encontrado.'];
        }
 
        if ((int) $acordo['IdTrabalhadorUm'] !== $idTrabalhador && (int) $acordo['IdTrabalhadorDois'] !== $idTrabalhador) {
            return ['sucesso' => false, 'mensagem' => 'Você não tem permissão para cancelar este acordo.'];
        }
 
        //aqui nao tava liberando os dois trabalhadores, agora libera.
        $cancelou = $this->repository->cancelarAcordo($idAcordo, $idTrabalhador, $justificativa);
        if ($cancelou) 
        {
            //acordo cancelado libera os dois trabalhadores para novos matches.
            $this->trabalhadorService->atualizarStatusAcordo((int) $acordo['IdTrabalhadorUm'], false);
            $this->trabalhadorService->atualizarStatusAcordo((int) $acordo['IdTrabalhadorDois'], false);
 
            return ['sucesso' => true, 'mensagem' => 'Acordo cancelado com sucesso.'];
        }
 
        return ['sucesso' => false, 'mensagem' => 'Erro ao cancelar o acordo. Tente novamente.'];
    }
}
