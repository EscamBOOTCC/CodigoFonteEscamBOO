<?php

namespace App\Services;

use App\Repositories\PenalidadeRepository;

class PenalidadeService
{
    private PenalidadeRepository $repository;
    private TrabalhadorService $trabalhadorService;
    private DenunciaService $denunciaService;

    // Constantes de tipos de penalidade
    public const TIPO_SUSPENSAO  = 1;
    public const TIPO_BANIMENTO    = 2;
    public const TIPO_ADVERTENCIA = 3;

    public function __construct()
    {
        $this->repository         = new PenalidadeRepository();
        $this->trabalhadorService = new TrabalhadorService();
        $this->denunciaService    = new DenunciaService();
    }

    public function aplicarPenalidade(int $idAdm, int $idTrabalhador, string $motivo, int $tipo, ?int $diasSuspensao = null, ?int $idDenuncia = null): array
    {
        $motivo = trim($motivo);
        if (empty($motivo)) {
            return ['sucesso' => false, 'mensagem' => 'O motivo da penalidade é obrigatório.'];
        }

        $trabalhador = $this->trabalhadorService->buscarPorId($idTrabalhador);
        if (!$trabalhador) {
            return ['sucesso' => false, 'mensagem' => 'Trabalhador não encontrado.'];
        }

        $dataExpiraStatus = null;

        if ($tipo === self::TIPO_SUSPENSAO) {
            $dias = ($diasSuspensao !== null && $diasSuspensao > 0) ? $diasSuspensao : 7;
            $dataExpiraStatus = date('Y-m-d H:i:s', strtotime("+{$dias} days"));
            $this->trabalhadorService->alterarStatus($idTrabalhador, 'SUSPENSO', $dataExpiraStatus);
        } elseif ($tipo === self::TIPO_BANIMENTO) {
            $this->trabalhadorService->alterarStatus($idTrabalhador, 'BANIDO', null);
        }
        // TIPO_ADVERTENCIA: o status do trabalhador permanece o mesmo, apenas registra a penalidade.

        $idPenalidade = $this->repository->aplicar([
            'Motivo'        => $motivo,
            'Tipo'          => $tipo,
            'IdAdm'         => $idAdm,
            'IdTrabalhador' => $idTrabalhador,
            'IdDenuncia'    => $idDenuncia
        ]);

        if ($idDenuncia !== null && $idDenuncia > 0) {
            $this->denunciaService->marcarComoProcedente($idDenuncia);
        }

        if ($idPenalidade) {
            $tipoTexto = match ($tipo) {
                self::TIPO_SUSPENSAO  => "Suspensão aplicada até " . date('d/m/Y H:i', strtotime($dataExpiraStatus)),
                self::TIPO_BANIMENTO    => "Banimento definitivo aplicado",
                self::TIPO_ADVERTENCIA => "Advertência formal registrada",
                default                => "Penalidade aplicada"
            };

            return [
                'sucesso'      => true,
                'mensagem'     => "{$tipoTexto} ao usuário {$trabalhador['Nome']}.",
                'idPenalidade' => $idPenalidade
            ];
        }

        return ['sucesso' => false, 'mensagem' => 'Erro ao registrar penalidade.'];
    }

    public function listarTodas(): array
    {
        return $this->repository->listarTodas();
    }

    public function listarPorTrabalhador(int $idTrabalhador): array
    {
        return $this->repository->listarPorTrabalhador($idTrabalhador);
    }
}
