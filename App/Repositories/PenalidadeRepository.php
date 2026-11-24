<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class PenalidadeRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }


    public function aplicar(array $dados): int|bool
    {

        $sql = "INSERT INTO Penalidade (Motivo, Tipo, Status, Data, IdAdm, IdTrabalhador, IdDenuncia)
                VALUES (:motivo, :tipo, 'APLICADA', CURDATE(), :idAdm, :idTrabalhador, :idDenuncia)";

        $stmt = $this->conn->prepare($sql);
        $sucesso = $stmt->execute([
            ':motivo'        => $dados['Motivo'],
            ':tipo'          => $dados['Tipo'],
            ':idAdm'         => $dados['IdAdm'],
            ':idTrabalhador' => $dados['IdTrabalhador'],
            ':idDenuncia'    => $dados['IdDenuncia'] ?? null
        ]);

        if ($sucesso) {
            return (int) $this->conn->lastInsertId();
        }

        return false;
    }

    public function listarTodas(): array
    {

        $sql = "SELECT 
                    p.IdPenalidade,
                    p.Motivo,
                    p.Tipo,
                    p.Status,
                    p.Data,
                    p.IdAdm,
                    a.Nome AS NomeAdm,
                    p.IdTrabalhador,
                    t.Nome AS NomeTrabalhador,
                    t.Email AS EmailTrabalhador,
                    t.Ativo AS StatusTrabalhador,
                    t.DataExpiraStatus,
                    p.IdDenuncia
                FROM Penalidade p
                INNER JOIN ADM a ON p.IdAdm = a.IdAdm
                INNER JOIN Trabalhador t ON p.IdTrabalhador = t.IdTrabalhador
                ORDER BY p.IdPenalidade DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorTrabalhador(int $idTrabalhador): array
    {

        $sql = "SELECT 
                    p.*,
                    a.Nome AS NomeAdm
                FROM Penalidade p
                INNER JOIN ADM a ON p.IdAdm = a.IdAdm
                WHERE p.IdTrabalhador = :idTrabalhador
                ORDER BY p.IdPenalidade DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
