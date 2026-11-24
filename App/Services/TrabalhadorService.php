<?php
namespace App\Services;

use App\Repositories\TrabalhadorRepository;

class TrabalhadorService
{
    private TrabalhadorRepository $repository;

    public function __construct()
    {
        $this->repository = new TrabalhadorRepository();
    }

    public function buscarPorId($id)
    {
        return $this->repository->findById($id);
    }

    public function listarTodos(): array
    {
        return $this->repository->findAll();
    }

    public function buscarPorEmail(string $email): ?array
    {
        return $this->repository->findByEmail($email);
    }

    public function cpfEmUso(string $cpf): bool
    {
        return $this->repository->cpfEmUso($cpf);
    }

    public function emailEmUso(string $email): bool
    {
        return $this->repository->emailEmUso($email);
    }

    public function salvar($trabalhador)
    {
        return $this->repository->salvar($trabalhador);
    }

    public function atualizar($trabalhador)
    {
        return $this->repository->atualizar($trabalhador);
    }

    public function alterarStatus(int $idTrabalhador, string $status = 'ATIVO', ?string $dataExpiraStatus = null): bool
    {
        return $this->repository->alterarStatus($idTrabalhador, $status, $dataExpiraStatus);
    }

    public function listarInteresses(int $idTrabalhador): array
    {
        return $this->repository->listarInteresses($idTrabalhador);
    }

    public function salvarPreferencias(
        int $idTrabalhador,
        int $idProfissao,
        int $idGrandeArea,
        array $interesses
    ): bool {
        return $this->repository->salvarPreferencias(
            $idTrabalhador,
            $idProfissao,
            $idGrandeArea,
            $interesses
        );
    }

    public function atualizarPerfil(int $idTrabalhador, array $dados): bool
    {
        return $this->repository->atualizarPerfil($idTrabalhador, $dados);
    }

    public function atualizarStatusAcordo(int $idTrabalhador, bool $emServico): bool
    {
        return $this->repository->atualizarStatusAcordo($idTrabalhador, $emServico);
    }

    public function estaEmServico(int $idTrabalhador): bool
    {
        return $this->repository->estaEmServico($idTrabalhador);
    }
}
