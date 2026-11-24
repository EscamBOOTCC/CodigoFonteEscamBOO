<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class GrandesAreasRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }

    
    public function findAll(): array
    {
        $sql = "SELECT
                    IdGrandeArea,
                    Nome
                FROM GrandesAreas
                ORDER BY Nome ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function findById(int $idGrandeArea): ?array
    {
        $sql = "SELECT
                    IdGrandeArea,
                    Nome
                FROM GrandesAreas
                WHERE IdGrandeArea = :idGrandeArea";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':idGrandeArea' => $idGrandeArea
        ]);

        $grandeArea = $stmt->fetch(PDO::FETCH_ASSOC);

        return $grandeArea ?: null;
    }

    
    public function findByNome(string $nome): ?array
    {
        $sql = "SELECT
                    IdGrandeArea,
                    Nome
                FROM GrandesAreas
                WHERE LOWER(Nome) = LOWER(:nome)
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':nome' => $nome
        ]);

        $grandeArea = $stmt->fetch(PDO::FETCH_ASSOC);

        return $grandeArea ?: null;
    }

    
    public function salvar(string $nome): bool
    {
        $sql = "INSERT INTO GrandesAreas (Nome)
                VALUES (:nome)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome' => $nome
        ]);
    }

    
    public function atualizar(int $idGrandeArea, string $nome): bool
    {
        $sql = "UPDATE GrandesAreas
                SET Nome = :nome
                WHERE IdGrandeArea = :idGrandeArea";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome' => $nome,
            ':idGrandeArea' => $idGrandeArea
        ]);
    }

    
    public function possuiProfissoes(int $idGrandeArea): bool
    {
        $sql = "SELECT COUNT(*) 
                FROM Profissao
                WHERE IdGrandeArea = :idGrandeArea";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':idGrandeArea' => $idGrandeArea
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    
    public function excluir(int $idGrandeArea): bool
    {
        $sql = "DELETE FROM GrandesAreas
                WHERE IdGrandeArea = :idGrandeArea";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':idGrandeArea' => $idGrandeArea
        ]);
    }
}
