<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;

class AdministradorRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT
                    IdAdm,
                    Nome,
                    CPF,
                    Genero,
                    Email,
                    DataNascimento,
                    FotoPerfil,
                    Endereco,
                    Ativo
                FROM ADM
                WHERE IdAdm = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $adm = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $adm ?: null;
    }

    public function findAll(): array
    {
        $sql = "SELECT
                    IdAdm,
                    Nome,
                    Email,
                    Genero,
                    FotoPerfil,
                    Ativo
                FROM ADM";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT
                    IdAdm,
                    Nome,
                    CPF,
                    Genero,
                    Email,
                    Senha,
                    FotoPerfil,
                    Ativo,
                    DataExpiraStatus
                FROM ADM
                WHERE Email = :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        $adm = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $adm ?: null;
    }

    public function salvar(array $adm)
    {
        $sql = "INSERT INTO ADM
                    (
                        Nome,
                        CPF,
                        Genero,
                        Email,
                        Senha,
                        DataNascimento,
                        FotoPerfil,
                        Endereco,
                        Ativo
                    )
                VALUES
                    (
                        :nome,
                        :cpf,
                        :genero,
                        :email,
                        :senha,
                        :dataNascimento,
                        :fotoPerfil,
                        :endereco,
                        :ativo
                    )";

        $stmt = $this->conn->prepare($sql);

        $sucesso = $stmt->execute([
            ':nome' => $adm['Nome'],
            ':cpf' => $adm['CPF'],
            ':genero' => $adm['Genero'],
            ':email' => $adm['Email'],
            ':senha' => password_hash(
                $adm['Senha'],
                PASSWORD_DEFAULT
            ),
            ':dataNascimento' => $adm['DataNascimento'],
            ':fotoPerfil' => $adm['FotoPerfil'],
            ':endereco' => $adm['Endereco'],
            ':ativo' => 'ATIVO'
        ]);

        if ($sucesso) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function atualizar(array $adm)
    {
        $sql = "UPDATE ADM SET
                    Nome = :nome,
                    CPF = :cpf,
                    Genero = :genero,
                    Email = :email,
                    DataNascimento = :dataNascimento,
                    FotoPerfil = :fotoPerfil,
                    Endereco = :endereco
                WHERE IdAdm = :idAdm";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome' => $adm['Nome'],
            ':cpf' => $adm['CPF'],
            ':genero' => $adm['Genero'],
            ':email' => $adm['Email'],
            ':dataNascimento' => $adm['DataNascimento'],
            ':fotoPerfil' => $adm['FotoPerfil'],
            ':endereco' => $adm['Endereco'],
            ':idAdm' => $adm['IdAdm']
        ]);
    }

    public function alterarStatus(int $idAdm, string $status, ?string $dataExpiraStatus = null): bool
    {
        $sql = "UPDATE ADM
                SET Ativo = :status,
                    DataExpiraStatus = :dataExpiraStatus
                WHERE IdAdm = :idAdm";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'           => $status,
            ':dataExpiraStatus' => $dataExpiraStatus,
            ':idAdm'            => $idAdm
        ]);
    }

}
