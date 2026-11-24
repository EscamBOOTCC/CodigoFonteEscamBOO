<?php

namespace App\Services;

use App\Repositories\BloqueioRepository;

class BloqueioService
{
    private BloqueioRepository $repository;

    public function __construct()
    {
        $this->repository = new BloqueioRepository();
    }

    public function bloquear(int $idBloqueou, int $idBloqueado): array
    {
        if ($idBloqueou === $idBloqueado) {
            return ['sucesso' => false, 'mensagem' => 'Você não pode bloquear a si mesmo.'];
        }

        if ($idBloqueou <= 0 || $idBloqueado <= 0) {
            return ['sucesso' => false, 'mensagem' => 'Identificador de usuário inválido.'];
        }

        $resultado = $this->repository->bloquear($idBloqueou, $idBloqueado);

        if ($resultado) {
            return ['sucesso' => true, 'mensagem' => 'Usuário bloqueado com sucesso. Ele não aparecerá mais em suas buscas e não poderá interagir com você (RN 15).'];
        }

        return ['sucesso' => false, 'mensagem' => 'Erro ao bloquear usuário. Tente novamente.'];
    }

    public function desbloquear(int $idBloqueou, int $idBloqueado): array
    {
        if ($idBloqueou <= 0 || $idBloqueado <= 0) {
            return ['sucesso' => false, 'mensagem' => 'Identificador de usuário inválido.'];
        }

        $resultado = $this->repository->desbloquear($idBloqueou, $idBloqueado);

        if ($resultado) {
            return ['sucesso' => true, 'mensagem' => 'Usuário desbloqueado com sucesso.'];
        }

        return ['sucesso' => false, 'mensagem' => 'Erro ao desbloquear usuário. Tente novamente.'];
    }

    public function estaBloqueado(int $idUsuarioA, int $idUsuarioB): bool
    {
        return $this->repository->existeBloqueioMutuo($idUsuarioA, $idUsuarioB);
    }

    public function estaBloqueadoDireto(int $idBloqueou, int $idBloqueado): bool
    {
        return $this->repository->estaBloqueadoDireto($idBloqueou, $idBloqueado);
    }

    public function listarBloqueados(int $idTrabalhador): array
    {
        return $this->repository->listarBloqueadosPorUsuario($idTrabalhador);
    }
}
