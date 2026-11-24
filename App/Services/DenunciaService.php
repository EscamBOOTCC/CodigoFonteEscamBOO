<?php

namespace App\Services;

use App\Repositories\DenunciaRepository;

class DenunciaService
{
    private DenunciaRepository $repository;

    public function __construct()
    {
        $this->repository = new DenunciaRepository();
    }

    public function criarDenuncia(int $idDenunciante, int $idDenunciado, string $motivo, ?string $descricao = null): array
    {
        if ($idDenunciante === $idDenunciado) {
            return ['sucesso' => false, 'mensagem' => 'Você não pode denunciar a si mesmo.'];
        }

        $motivo = trim($motivo);
        if (empty($motivo)) {
            return ['sucesso' => false, 'mensagem' => 'O motivo da denúncia é obrigatório.'];
        }

        $id = $this->repository->criar([
            'IdTrabalhador'  => $idDenunciado,
            'IdDenunciante'  => $idDenunciante,
            'Motivo'         => $motivo,
            'Descricao'      => $descricao ? trim($descricao) : null
        ]);

        if ($id) {
            return ['sucesso' => true, 'mensagem' => 'Denúncia registrada com sucesso! Ela será analisada pela equipe de administração.', 'idDenuncia' => $id];
        }

        return ['sucesso' => false, 'mensagem' => 'Erro ao registrar denúncia. Tente novamente.'];
    }

    public function listarDenuncias(?int $status = null): array
    {
        return $this->repository->listarTodas($status);
    }

    public function listarDenunciasDoUsuario(int $idDenunciante): array
    {
        return $this->repository->listarPorDenunciante($idDenunciante);
    }

    public function buscarPorId(int $idDenuncia): ?array
    {
        return $this->repository->buscarPorId($idDenuncia);
    }

    public function arquivarDenuncia(int $idDenuncia): bool
    {
        return $this->repository->atualizarStatus($idDenuncia, 2); 
    }

    public function marcarComoProcedente(int $idDenuncia): bool
    {
        return $this->repository->atualizarStatus($idDenuncia, 1); 
    }

    public function contarPendentes(): int
    {
        return $this->repository->contarPendentes();
    }
}
