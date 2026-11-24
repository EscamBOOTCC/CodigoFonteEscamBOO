<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class ProfissaoRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }

    
    public function findAll(): array
    {
        $sql = "SELECT 
                    IdProfissao, 
                    Nome, 
                    IdGrandeArea 
                FROM Profissao 
                ORDER BY Nome ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function findById(int $idProfissao): ?array
    {
        $sql = "SELECT 
                    IdProfissao, 
                    Nome, 
                    IdGrandeArea 
                FROM Profissao 
                WHERE IdProfissao = :idProfissao";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idProfissao' => $idProfissao]);

        $profissao = $stmt->fetch(PDO::FETCH_ASSOC);

        return $profissao ?: null;
    }

    
    public function findByGrandeArea(int $idGrandeArea): array
    {
        $sql = "SELECT 
                    IdProfissao, 
                    Nome, 
                    IdGrandeArea 
                FROM Profissao 
                WHERE IdGrandeArea = :idGrandeArea 
                ORDER BY Nome ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idGrandeArea' => $idGrandeArea]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function findByNomeEArea(string $nome, int $idGrandeArea): ?array
    {
        $sql = "SELECT 
                    IdProfissao, 
                    Nome 
                FROM Profissao 
                WHERE LOWER(Nome) = LOWER(:nome) 
                  AND IdGrandeArea = :idGrandeArea 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':nome'         => $nome,
            ':idGrandeArea' => $idGrandeArea
        ]);

        $profissao = $stmt->fetch(PDO::FETCH_ASSOC);

        return $profissao ?: null;
    }

    
    public function salvar(string $nome, int $idGrandeArea): bool
    {
        $sql = "INSERT INTO Profissao (Nome, IdGrandeArea) 
                VALUES (:nome, :idGrandeArea)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome'         => $nome,
            ':idGrandeArea' => $idGrandeArea
        ]);
    }
}