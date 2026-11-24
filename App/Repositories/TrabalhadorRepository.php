<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;

class TrabalhadorRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }

    public function findById(int $id): ?array
    {

        $sql = "SELECT
                    t.*,
                    p.Nome AS NomeProfissao,
                    g.Nome AS NomeGrandeArea
                FROM Trabalhador t
                LEFT JOIN Profissao p ON t.IdProfissao = p.IdProfissao
                LEFT JOIN GrandesAreas g ON t.IdGrandeArea = g.IdGrandeArea
                WHERE t.IdTrabalhador = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $trabalhador = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $trabalhador ?: null;
    }

    public function findAll(): array
    {
        $sql = "SELECT
                    IdTrabalhador,
                    Nome,
                    Email,
                    Genero,
                    FotoPerfil,
                    Ativo
                FROM Trabalhador";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT
                    IdTrabalhador,
                    Nome,
                    CPF,
                    Genero,
                    Email,
                    Senha,
                    FotoPerfil,
                    Ativo,
                    DataExpiraStatus,
                    IdGrandeArea,
                    IdProfissao,
                    Biografia
                FROM Trabalhador
                WHERE Email = :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        $trabalhador = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $trabalhador ?: null;
    }

    public function cpfEmUso(string $cpf): bool
    {
        $cpfNormalizado = preg_replace('/\D/', '', $cpf);
        $sql = "SELECT
                    EXISTS(
                        SELECT 1 FROM Trabalhador
                        WHERE REPLACE(REPLACE(REPLACE(CPF, '.', ''), '-', ''), ' ', '') = :cpfTrabalhador
                    )
                    OR EXISTS(
                        SELECT 1 FROM ADM
                        WHERE REPLACE(REPLACE(REPLACE(CPF, '.', ''), '-', ''), ' ', '') = :cpfAdm
                    )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':cpfTrabalhador' => $cpfNormalizado,
            ':cpfAdm' => $cpfNormalizado,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function emailEmUso(string $email): bool
    {
        $sql = "SELECT
                    EXISTS(SELECT 1 FROM Trabalhador WHERE Email = :emailTrabalhador)
                    OR EXISTS(SELECT 1 FROM ADM WHERE Email = :emailAdm)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':emailTrabalhador' => $email,
            ':emailAdm' => $email,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function salvar(array $trabalhador)
    {
        $sql = "INSERT INTO Trabalhador
                    (
                        Nome,
                        CPF,
                        Genero,
                        Email,
                        Senha,
                        DataNascimento,
                        FotoPerfil,
                        Endereco,
                        Telefone,
                        Ativo,
                        StatusAcordo
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
                        :telefone,
                        :ativo,
                        :statusAcordo
                    )";

        $stmt = $this->conn->prepare($sql);

        $sucesso = $stmt->execute([
            ':nome'           => $trabalhador['Nome'],
            ':cpf'            => $trabalhador['CPF'],
            ':genero'         => $trabalhador['Genero'],
            ':email'          => $trabalhador['Email'],
            ':senha'          => password_hash($trabalhador['Senha'], PASSWORD_DEFAULT),
            ':dataNascimento' => $trabalhador['DataNascimento'],
            ':fotoPerfil'     => $trabalhador['FotoPerfil'],
            ':endereco'       => $trabalhador['Endereco'],
            ':telefone'       => $trabalhador['Telefone'] ?? '',
            ':ativo'          => 'ATIVO',
            ':statusAcordo'   => 0
        ]);

        if ($sucesso) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function atualizar(array $trabalhador)
    {
        $sql = "UPDATE Trabalhador SET
                    Nome = :nome,
                    CPF = :cpf,
                    Genero = :genero,
                    Email = :email,
                    DataNascimento = :dataNascimento,
                    FotoPerfil = :fotoPerfil,
                    Endereco = :endereco
                WHERE IdTrabalhador = :idTrabalhador";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome' => $trabalhador['Nome'],
            ':cpf' => $trabalhador['CPF'],
            ':genero' => $trabalhador['Genero'],
            ':email' => $trabalhador['Email'],
            ':dataNascimento' => $trabalhador['DataNascimento'],
            ':fotoPerfil' => $trabalhador['FotoPerfil'],
            ':endereco' => $trabalhador['Endereco'],
            ':idTrabalhador' => $trabalhador['IdTrabalhador']
        ]);
    }

    public function alterarStatus(int $idTrabalhador, string $status, ?string $dataExpiraStatus = null): bool
    {

        $sql = "UPDATE Trabalhador
            SET Ativo = :status,
                DataExpiraStatus = :dataExpiraStatus
            WHERE IdTrabalhador = :idTrabalhador";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'           => $status,
            ':dataExpiraStatus' => $dataExpiraStatus,
            ':idTrabalhador'    => $idTrabalhador
        ]);
    }

    //Atualiza o campo StatusAcordo (disponibilidade do trabalhador) para indicar se ele está "Em Serviço" (tem acordo ativo) ou não.
    public function atualizarStatusAcordo(int $idTrabalhador, bool $emServico): bool
    {
        $sql = "UPDATE Trabalhador
                SET StatusAcordo = :statusAcordo
                WHERE IdTrabalhador = :idTrabalhador";
 
        $stmt = $this->conn->prepare($sql);
 
        return $stmt->execute([
            ':statusAcordo'  => $emServico ? 1 : 0,
            ':idTrabalhador' => $idTrabalhador,
        ]);
    }
 
    // Verifica o valor atual de StatusAcordo de um trabalhador.
    public function estaEmServico(int $idTrabalhador): bool
    {
        $sql  = "SELECT StatusAcordo FROM Trabalhador WHERE IdTrabalhador = :idTrabalhador";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
 
        return $res ? (bool) $res['StatusAcordo'] : false;
    }

    public function listarInteresses(int $idTrabalhador): array
    {
        $sql = "SELECT IdGrandeArea
                FROM TrabalhadorInteresseGrandeArea
                WHERE IdTrabalhador = :idTrabalhador";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);

        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    public function salvarPreferencias(
        int $idTrabalhador,
        int $idProfissao,
        int $idGrandeArea,
        array $interesses
    ): bool {
        $this->conn->beginTransaction();

        try {
            $stmt = $this->conn->prepare(
                "UPDATE Trabalhador
                 SET IdProfissao = :idProfissao, IdGrandeArea = :idGrandeArea
                 WHERE IdTrabalhador = :idTrabalhador"
            );
            $stmt->execute([
                ':idProfissao'   => $idProfissao,
                ':idGrandeArea'  => $idGrandeArea,
                ':idTrabalhador' => $idTrabalhador,
            ]);

            $stmt = $this->conn->prepare(
                "DELETE FROM TrabalhadorInteresseGrandeArea
                 WHERE IdTrabalhador = :idTrabalhador"
            );
            $stmt->execute([':idTrabalhador' => $idTrabalhador]);

            $stmt = $this->conn->prepare(
                "INSERT INTO TrabalhadorInteresseGrandeArea (IdTrabalhador, IdGrandeArea)
                 VALUES (:idTrabalhador, :idGrandeArea)"
            );
            foreach ($interesses as $idInteresse) {
                $stmt->execute([
                    ':idTrabalhador' => $idTrabalhador,
                    ':idGrandeArea'  => $idInteresse,
                ]);
            }

            return $this->conn->commit();
        } catch (\Throwable $erro) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $erro;
        }
    }

    public function atualizarPerfil(int $idTrabalhador, array $dados): bool
    {
        $sql = "UPDATE Trabalhador SET
                    Nome = :nome,
                    Genero = :genero,
                    FotoPerfil = :fotoPerfil,
                    Endereco = :endereco,
                    Biografia = :biografia,
                    Telefone = :telefone,
                    Instagram = :instagram,
                    Twitter = :twitter
                WHERE IdTrabalhador = :idTrabalhador";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome'          => $dados['Nome'] ?? '',
            ':genero'        => $dados['Genero'] ?? null,
            ':fotoPerfil'    => $dados['FotoPerfil'] ?? '',
            ':endereco'      => $dados['Endereco'] ?? '',
            ':biografia'     => $dados['Biografia'] ?? '',
            ':telefone'      => $dados['Telefone'] ?? '',
            ':instagram'     => $dados['Instagram'] ?? '',
            ':twitter'       => $dados['Twitter'] ?? '',
            ':idTrabalhador' => $idTrabalhador
        ]);
    }

}
