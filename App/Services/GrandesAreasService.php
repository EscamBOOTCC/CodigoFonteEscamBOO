<?php
namespace App\Services;

use App\Repositories\GrandesAreasRepository;

class GrandesAreasService
{
    private GrandesAreasRepository $repository;

    public function __construct()
    {
        $this->repository = new GrandesAreasRepository();
    }

    public function listarTodos(): array
    {
        return $this->repository->findAll();
    }

    public function buscarPorId(int $idGrandeArea): ?array
    {
        return $this->repository->findById($idGrandeArea);
    }

    public function salvar(string $nome): bool
    {
        $nome = trim($nome);

        if ($nome === '') {
            throw new \Exception('O nome da grande área é obrigatório.');
        }

        $existente = $this->repository->findByNome($nome);

        if ($existente) {
            throw new \Exception('Já existe uma grande área com esse nome.');
        }

        return $this->repository->salvar($nome);
    }

    public function atualizar(int $idGrandeArea, string $nome): bool
    {
        $nome = trim($nome);

        if ($nome === '') {
            throw new \Exception('O nome da grande área é obrigatório.');
        }

        $existente = $this->repository->findByNome($nome);

        if (
            $existente &&
            (int) $existente['IdGrandeArea'] !== $idGrandeArea
        ) {
            throw new \Exception('Já existe uma grande área com esse nome.');
        }

        $grandeArea = $this->repository->findById($idGrandeArea);

        if (! $grandeArea) {
            throw new \Exception('Grande área não encontrada.');
        }

        return $this->repository->atualizar(
            $idGrandeArea,
            $nome
        );
    }

    public function excluir(int $idGrandeArea): bool
    {
        $grandeArea = $this->repository->findById($idGrandeArea);

        if (! $grandeArea) {
            throw new \Exception('Grande área não encontrada.');
        }

        if ($this->repository->possuiProfissoes($idGrandeArea)) {
            throw new \Exception(
                'Não é possível excluir esta grande área porque existem profissões vinculadas a ela.'
            );
        }

        return $this->repository->excluir($idGrandeArea);
    }
}
