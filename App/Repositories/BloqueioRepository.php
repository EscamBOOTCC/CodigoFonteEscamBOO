<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class BloqueioRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }


    public function bloquear(int $idBloqueou, int $idBloqueado): bool
    {

        if ($this->estaBloqueadoDireto($idBloqueou, $idBloqueado)) {
            return true;
        }

        $sql = "INSERT INTO Bloqueio (Data, IdTrabalhadorBloqueou, IdTrabalhadorBloqueado)
                VALUES (CURDATE(), :idBloqueou, :idBloqueado)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':idBloqueou'  => $idBloqueou,
            ':idBloqueado' => $idBloqueado
        ]);
    }

    public function desbloquear(int $idBloqueou, int $idBloqueado): bool
    {

        $sql = "DELETE FROM Bloqueio 
                WHERE IdTrabalhadorBloqueou = :idBloqueou 
                  AND IdTrabalhadorBloqueado = :idBloqueado";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':idBloqueou'  => $idBloqueou,
            ':idBloqueado' => $idBloqueado
        ]);
    }

    public function estaBloqueadoDireto(int $idBloqueou, int $idBloqueado): bool
    {

        $sql = "SELECT 1 FROM Bloqueio 
                WHERE IdTrabalhadorBloqueou = :idBloqueou 
                  AND IdTrabalhadorBloqueado = :idBloqueado 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idBloqueou'  => $idBloqueou,
            ':idBloqueado' => $idBloqueado
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function existeBloqueioMutuo(int $idUsuarioA, int $idUsuarioB): bool
    {

        $sql = "SELECT 1 FROM Bloqueio 
                WHERE (IdTrabalhadorBloqueou = :userA AND IdTrabalhadorBloqueado = :userB)
                   OR (IdTrabalhadorBloqueou = :userB AND IdTrabalhadorBloqueado = :userA)
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':userA' => $idUsuarioA,
            ':userB' => $idUsuarioB
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function listarBloqueadosPorUsuario(int $idTrabalhador): array
    {

        $sql = "SELECT 
                    b.IdBloqueio,
                    b.Data AS DataBloqueio,
                    t.IdTrabalhador,
                    t.Nome,
                    t.FotoPerfil,
                    t.Endereco,
                    p.Nome AS NomeProfissao,
                    g.Nome AS NomeGrandeArea
                FROM Bloqueio b
                INNER JOIN Trabalhador t ON b.IdTrabalhadorBloqueado = t.IdTrabalhador
                LEFT JOIN Profissao p ON t.IdProfissao = p.IdProfissao
                LEFT JOIN GrandesAreas g ON t.IdGrandeArea = g.IdGrandeArea
                WHERE b.IdTrabalhadorBloqueou = :idTrabalhador
                ORDER BY b.IdBloqueio DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
