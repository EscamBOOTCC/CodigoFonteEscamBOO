<?php
namespace App\Services;

use App\Repositories\ServicoRepository;

class ServicoService
{
    private ServicoRepository $repository;

    public function __construct()
    {
        $this->repository = new ServicoRepository();
    }

    public function cadastrar(array $dados): int | bool
    {
        return $this->repository->salvar($dados);
    }

    public function excluir(int $idServico, int $idTrabalhador): bool
    {
        return $this->repository->excluir($idServico, $idTrabalhador);
    }

    public function buscarParaDenuncia(int $idServico): ?array
    {
        return $this->repository->buscarParaDenuncia($idServico);
    }

    public function buscarDoTrabalhador(int $idServico, int $idTrabalhador): ?array
    {
        return $this->repository->buscarDoTrabalhador($idServico, $idTrabalhador);
    }

    public function listarDisponiveis(int $idTrabalhadorLogado): array
    {
        return $this->repository->listarDisponiveis($idTrabalhadorLogado);
    }

    public function listarMeusServicos(int $idTrabalhador): array
    {
        return $this->repository->listarMeusServicos($idTrabalhador);
    }

    public function listarProfissoes(): array
    {
        return $this->repository->listarProfissoes();
    }

    public function editarServico(int $idServico, int $idTrabalhador, array $dados): bool
    {
        return $this->repository->editarServico($idServico, $idTrabalhador, $dados);
    }

    public function filtrarServicos(array $filtros, int $idTrabalhadorLogado): array
    {
        return $this->repository->filtrarServicos($filtros, $idTrabalhadorLogado);
    }
}
