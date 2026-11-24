<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class DenunciaRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }


    public function criar(array $dados): int|bool
    {

        $sql = "INSERT INTO Denuncia (Motivo, Descricao, Status, Data, IdTrabalhador, IdDenunciante)
                VALUES (:motivo, :descricao, 0, CURDATE(), :idTrabalhador, :idDenunciante)";

        $stmt = $this->conn->prepare($sql);
        $sucesso = $stmt->execute([
            ':motivo'        => $dados['Motivo'],
            ':descricao'     => $dados['Descricao'] ?? null,
            ':idTrabalhador' => $dados['IdTrabalhador'],
            ':idDenunciante' => $dados['IdDenunciante'] ?? null
        ]);

        if ($sucesso) {
            return (int) $this->conn->lastInsertId();
        }

        return false;
    }

    public function listarTodas(?int $status = null): array
    {

        $sql = "SELECT 
                    d.IdDenuncia,
                    d.Motivo,
                    d.Descricao,
                    d.Status,
                    d.Data,
                    d.IdTrabalhador AS IdDenunciado,
                    tDenunciado.Nome AS NomeDenunciado,
                    tDenunciado.Email AS EmailDenunciado,
                    tDenunciado.Ativo AS StatusDenunciado,
                    tDenunciado.FotoPerfil AS FotoDenunciado,
                    d.IdDenunciante,
                    tDenunciante.Nome AS NomeDenunciante,
                    tDenunciante.Email AS EmailDenunciante,
                    tDenunciante.FotoPerfil AS FotoDenunciante
                FROM Denuncia d
                INNER JOIN Trabalhador tDenunciado ON d.IdTrabalhador = tDenunciado.IdTrabalhador
                LEFT JOIN Trabalhador tDenunciante ON d.IdDenunciante = tDenunciante.IdTrabalhador";

        if ($status !== null) {
            $sql .= " WHERE d.Status = :status";
        }

        $sql .= " ORDER BY d.IdDenuncia DESC";

        $stmt = $this->conn->prepare($sql);
        if ($status !== null) {
            $stmt->execute([':status' => $status]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorDenunciante(int $idDenunciante): array
    {
        $sql = "SELECT
                    d.IdDenuncia,
                    d.Motivo,
                    d.Descricao,
                    d.Status,
                    d.Data,
                    t.Nome AS NomeDenunciado
                FROM Denuncia d
                INNER JOIN Trabalhador t ON d.IdTrabalhador = t.IdTrabalhador
                WHERE d.IdDenunciante = :idDenunciante
                ORDER BY d.IdDenuncia DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idDenunciante' => $idDenunciante]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $idDenuncia): ?array
    {

        $sql = "SELECT 
                    d.IdDenuncia,
                    d.Motivo,
                    d.Descricao,
                    d.Status,
                    d.Data,
                    d.IdTrabalhador AS IdDenunciado,
                    tDenunciado.Nome AS NomeDenunciado,
                    tDenunciado.Email AS EmailDenunciado,
                    tDenunciado.Ativo AS StatusDenunciado,
                    tDenunciado.FotoPerfil AS FotoDenunciado,
                    d.IdDenunciante,
                    tDenunciante.Nome AS NomeDenunciante,
                    tDenunciante.Email AS EmailDenunciante
                FROM Denuncia d
                INNER JOIN Trabalhador tDenunciado ON d.IdTrabalhador = tDenunciado.IdTrabalhador
                LEFT JOIN Trabalhador tDenunciante ON d.IdDenunciante = tDenunciante.IdTrabalhador
                WHERE d.IdDenuncia = :idDenuncia";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idDenuncia' => $idDenuncia]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return $res ?: null;
    }

    public function atualizarStatus(int $idDenuncia, int $status): bool
    {

        $sql = "UPDATE Denuncia SET Status = :status WHERE IdDenuncia = :idDenuncia";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':status'     => $status,
            ':idDenuncia' => $idDenuncia
        ]);
    }

    public function contarPendentes(): int
    {

        $sql = "SELECT COUNT(*) FROM Denuncia WHERE Status = 0";
        $stmt = $this->conn->query($sql);
        return (int) $stmt->fetchColumn();
    }
}
