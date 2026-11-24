<?php
namespace App\Services;

use App\Repositories\ProfissaoRepository;

class ProfissaoService
{
    private ProfissaoRepository $repository;

    public function __construct()
    {
        $this->repository = new ProfissaoRepository();
    }

    public function listarTodos(): array
    {
        return $this->repository->findAll();
    }

    public function listarPorGrandeArea(int $idGrandeArea): array
    {
        return $this->repository->findByGrandeArea($idGrandeArea);
    }

    public function buscarPorId(int $idProfissao): ?array
    {
        return $this->repository->findById($idProfissao);
    }

    public function salvar(string $nome, int $idGrandeArea): bool
    {
        $nome = trim($nome);

        if ($nome === '') {
            throw new \Exception('O nome da profissão é obrigatório.');
        }

        if ($idGrandeArea <= 0) {
            throw new \Exception('A Grande Área informada é inválida.');
        }

        $existente = $this->repository->findByNomeEArea($nome, $idGrandeArea);

        if ($existente) {
            throw new \Exception('Já existe esta profissão cadastrada nesta Grande Área.');
        }

        return $this->repository->salvar($nome, $idGrandeArea);
    }
}
