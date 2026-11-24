<?php
namespace App\Services;

use App\Models\Avaliacao;
use App\Repositories\AvaliacaoRepository;
use App\Repositories\AcordoRepository;

class AvaliacaoService
{
    private AvaliacaoRepository $repository;
    private AcordoRepository $acordoRepository;

    public function __construct()
    {
        $this->repository       = new AvaliacaoRepository();
        $this->acordoRepository = new AcordoRepository();
    }

    // Cada trabalhador faz duas avaliações por acordo: do PARCEIRO (compõe a reputação) e do
    // ESCAMBO (o acordo em si). Cada tipo só pode ser registrado uma vez por avaliador (RN08).
    //
    // Quando é permitido avaliar:
    //  - acordo Concluído (Status = 1), ou
    //  - acordo em andamento (Status = 0) em que o próprio avaliador já confirmou a conclusão.
    // Isso permite guardar a nota de quem confirma primeiro, sem perdê-la. Ela só passa a contar
    // na reputação quando o acordo é concluído pelos dois (ver AvaliacaoRepository).
    public function registrarAvaliacao(
        int $idAcordo,
        int $idAvaliador,
        float $nota,
        ?string $comentario,
        string $tipo = Avaliacao::TIPO_PARCEIRO
    ): array {
        if ($nota < 1 || $nota > 5) {
            return ['sucesso' => false, 'mensagem' => 'A nota da avaliação deve ser entre 1 e 5.'];
        }

        if ($tipo !== Avaliacao::TIPO_PARCEIRO && $tipo !== Avaliacao::TIPO_ESCAMBO) {
            return ['sucesso' => false, 'mensagem' => 'Tipo de avaliação inválido.'];
        }

        $acordo = $this->acordoRepository->buscarPorId($idAcordo);
        if (! $acordo) {
            return ['sucesso' => false, 'mensagem' => 'Acordo não encontrado.'];
        }

        $statusAcordo = (int) $acordo['Status'];

        if ($statusAcordo === 2) {
            return ['sucesso' => false, 'mensagem' => 'Não é possível avaliar um acordo cancelado.'];
        }

        $idTrabalhadorUm   = (int) $acordo['IdTrabalhadorUm'];
        $idTrabalhadorDois = (int) $acordo['IdTrabalhadorDois'];

        if ($idAvaliador === $idTrabalhadorUm) {
            $idAvaliado    = $idTrabalhadorDois;
            $idServico     = (int) $acordo['IdServicoDois']; // Serviço prestado pelo avaliado
            $jaConfirmou   = (int) ($acordo['ConfirmacaoTrabalhadorUm'] ?? 0) === 1;
        } elseif ($idAvaliador === $idTrabalhadorDois) {
            $idAvaliado    = $idTrabalhadorUm;
            $idServico     = (int) $acordo['IdServicoUm'];
            $jaConfirmou   = (int) ($acordo['ConfirmacaoTrabalhadorDois'] ?? 0) === 1;
        } else {
            return ['sucesso' => false, 'mensagem' => 'Você não participa deste acordo.'];
        }

        if ($statusAcordo !== 1 && ! $jaConfirmou) {
            return ['sucesso' => false, 'mensagem' => 'Só é possível avaliar após a conclusão do acordo.'];
        }

        if ($idServico <= 0) {
            return ['sucesso' => false, 'mensagem' => 'Serviço do acordo não encontrado para registrar a avaliação.'];
        }

        // Avaliação não pode ser alterada/duplicada após registrada.
        if ($this->repository->jaAvaliou($idAcordo, $idAvaliador, $tipo)) {
            return ['sucesso' => false, 'mensagem' => 'Você já registrou esta avaliação para o acordo.'];
        }

        $idAvaliacao = $this->repository->registrar([
            'Nota'        => $nota,
            'Comentario'  => $comentario,
            'Tipo'        => $tipo,
            'IdServico'   => $idServico,
            'IdAcordo'    => $idAcordo,
            'IdAvaliador' => $idAvaliador,
            // A nota do escambo avalia o acordo, não uma pessoa.
            'IdAvaliado'  => $tipo === Avaliacao::TIPO_ESCAMBO ? null : $idAvaliado,
        ]);

        if ($idAvaliacao) {
            return ['sucesso' => true, 'mensagem' => 'Avaliação registrada com sucesso! Obrigado pelo feedback.'];
        }

        return ['sucesso' => false, 'mensagem' => 'Erro ao registrar a avaliação. Tente novamente.'];
    }

    public function jaAvaliou(int $idAcordo, int $idAvaliador, string $tipo = Avaliacao::TIPO_PARCEIRO): bool
    {
        return $this->repository->jaAvaliou($idAcordo, $idAvaliador, $tipo);
    }

    public function listarRecebidasPorTrabalhador(int $idTrabalhador): array
    {
        return $this->repository->listarRecebidasPorTrabalhador($idTrabalhador);
    }

    public function calcularReputacao(int $idTrabalhador): array
    {
        return $this->repository->calcularReputacao($idTrabalhador);
    }
}
