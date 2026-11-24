<?php
namespace App\Services;

use App\Repositories\AdministradorRepository;

class AdministradorService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new AdministradorRepository();
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

    public function salvar($adm)
    {
        return $this->repository->salvar($adm);
    }

    public function atualizar($adm)
    {
        return $this->repository->atualizar($adm);
    }

    public function alterarStatus(int $idAdm, string $status = 'ATIVO', ?string $dataExpiraStatus = null): bool
    {
        return $this->repository->alterarStatus($idAdm, $status, $dataExpiraStatus);
    }

}
