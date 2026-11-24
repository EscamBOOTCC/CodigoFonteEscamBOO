<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class MatchRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }


    public function criarMatch(array $dados): int|bool
    {

        $sql = "INSERT INTO `Match` 
                (Data, Status, IdTrabalhadorUm, IdTrabalhadorDois, IdServicoOrigem, IdServicoDestino, Mensagem)
                VALUES (CURDATE(), 0, :idTrabalhadorUm, :idTrabalhadorDois, :idServicoOrigem, :idServicoDestino, :mensagem)";

        $stmt = $this->conn->prepare($sql);
        $sucesso = $stmt->execute([
            ':idTrabalhadorUm'   => $dados['IdTrabalhadorUm'],
            ':idTrabalhadorDois' => $dados['IdTrabalhadorDois'],
            ':idServicoOrigem'   => !empty($dados['IdServicoOrigem']) ? $dados['IdServicoOrigem'] : null,
            ':idServicoDestino'  => !empty($dados['IdServicoDestino']) ? $dados['IdServicoDestino'] : null,
            ':mensagem'          => !empty($dados['Mensagem']) ? $dados['Mensagem'] : null
        ]);

        if ($sucesso) {
            return (int) $this->conn->lastInsertId();
        }

        return false;
    }

    public function buscarPorId(int $idMatch): ?array
    {

        $sql = "SELECT 
                    m.*,
                    t1.Nome AS NomeProponente,
                    t1.FotoPerfil AS FotoProponente,
                    t1.Telefone AS TelefoneProponente,
                    t1.Instagram AS InstagramProponente,
                    t1.Twitter AS TwitterProponente,
                    t1.Endereco AS EnderecoProponente,
                    p1.Nome AS ProfissaoProponente,
                    
                    t2.Nome AS NomeDestinatario,
                    t2.FotoPerfil AS FotoDestinatario,
                    t2.Telefone AS TelefoneDestinatario,
                    t2.Instagram AS InstagramDestinatario,
                    t2.Twitter AS TwitterDestinatario,
                    t2.Endereco AS EnderecoDestinatario,
                    p2.Nome AS ProfissaoDestinatario,

                    so.Nome AS NomeServicoOrigem,
                    so.Descricao AS DescricaoServicoOrigem,

                    sd.Nome AS NomeServicoDestino,
                    sd.Descricao AS DescricaoServicoDestino,

                    a.IdAcordo,
                    a.Status AS StatusAcordo
                FROM `Match` m
                INNER JOIN Trabalhador t1 ON m.IdTrabalhadorUm = t1.IdTrabalhador
                INNER JOIN Trabalhador t2 ON m.IdTrabalhadorDois = t2.IdTrabalhador
                LEFT JOIN Profissao p1 ON t1.IdProfissao = p1.IdProfissao
                LEFT JOIN Profissao p2 ON t2.IdProfissao = p2.IdProfissao
                LEFT JOIN Servico so ON m.IdServicoOrigem = so.IdServico
                LEFT JOIN Servico sd ON m.IdServicoDestino = sd.IdServico
                LEFT JOIN Acordo a ON m.IdMatch = a.IdMatch
                WHERE m.IdMatch = :idMatch";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idMatch' => $idMatch]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return $res ?: null;
    }

    public function listarRecebidos(int $idTrabalhador): array
    {

        $sql = "SELECT 
                    m.*,
                    t1.IdTrabalhador AS IdOutro,
                    t1.Nome AS NomeOutro,
                    t1.FotoPerfil AS FotoOutro,
                    t1.Telefone AS TelefoneOutro,
                    t1.Endereco AS EnderecoOutro,
                    p1.Nome AS ProfissaoOutro,
                    so.Nome AS NomeServicoOferecido,
                    so.Descricao AS DescricaoServicoOferecido,
                    sd.Nome AS NomeMeuServicoDesejado,
                    sd.Descricao AS DescricaoMeuServicoDesejado,
                    a.IdAcordo
                FROM `Match` m
                INNER JOIN Trabalhador t1 ON m.IdTrabalhadorUm = t1.IdTrabalhador
                LEFT JOIN Profissao p1 ON t1.IdProfissao = p1.IdProfissao
                LEFT JOIN Servico so ON m.IdServicoOrigem = so.IdServico
                LEFT JOIN Servico sd ON m.IdServicoDestino = sd.IdServico
                LEFT JOIN Acordo a ON m.IdMatch = a.IdMatch
                WHERE m.IdTrabalhadorDois = :idTrabalhador
                  AND m.Status = 0
                ORDER BY m.IdMatch DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarRecebidosPendentes(int $idTrabalhador): int
    {
        $sql = "SELECT COUNT(*) 
                FROM `Match`
                WHERE IdTrabalhadorDois = :idTrabalhador
                  AND Status = 0";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);

        return (int) $stmt->fetchColumn();
    }

    public function listarEnviados(int $idTrabalhador): array
    {

        $sql = "SELECT 
                    m.*,
                    t2.IdTrabalhador AS IdOutro,
                    t2.Nome AS NomeOutro,
                    t2.FotoPerfil AS FotoOutro,
                    t2.Telefone AS TelefoneOutro,
                    t2.Endereco AS EnderecoOutro,
                    p2.Nome AS ProfissaoOutro,
                    so.Nome AS NomeMeuServicoOferecido,
                    so.Descricao AS DescricaoMeuServicoOferecido,
                    sd.Nome AS NomeServicoDesejado,
                    sd.Descricao AS DescricaoServicoDesejado,
                    a.IdAcordo
                FROM `Match` m
                INNER JOIN Trabalhador t2 ON m.IdTrabalhadorDois = t2.IdTrabalhador
                LEFT JOIN Profissao p2 ON t2.IdProfissao = p2.IdProfissao
                LEFT JOIN Servico so ON m.IdServicoOrigem = so.IdServico
                LEFT JOIN Servico sd ON m.IdServicoDestino = sd.IdServico
                LEFT JOIN Acordo a ON m.IdMatch = a.IdMatch
                WHERE m.IdTrabalhadorUm = :idTrabalhador
                  AND m.Status = 0
                ORDER BY m.IdMatch DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function responderMatch(int $idMatch, int $idTrabalhadorDestinatario, int $novoStatus): bool
    {
        $sql = "UPDATE `Match` 
                SET Status = :novoStatus 
                WHERE IdMatch = :idMatch 
                  AND IdTrabalhadorDois = :idDestinatario";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':novoStatus'     => $novoStatus,
            ':idMatch'        => $idMatch,
            ':idDestinatario' => $idTrabalhadorDestinatario
        ]);
    }

    public function atualizarMensagemPendente(int $idMatch, int $idTrabalhadorOrigem, string $mensagem): bool
    {
        $this->conn->beginTransaction();

        try {
            $stmtMatch = $this->conn->prepare(
                'SELECT Status, Mensagem
                 FROM `Match`
                 WHERE IdMatch = :idMatch
                   AND IdTrabalhadorUm = :idTrabalhador
                 FOR UPDATE'
            );
            $stmtMatch->execute([
                ':idMatch' => $idMatch,
                ':idTrabalhador' => $idTrabalhadorOrigem,
            ]);
            $match = $stmtMatch->fetch(PDO::FETCH_ASSOC);

            if (! $match || (int) $match['Status'] !== 0) {
                $this->conn->rollBack();
                return false;
            }

            if ($match['Mensagem'] !== $mensagem) {
                $stmtAtualizacao = $this->conn->prepare(
                    'UPDATE `Match`
                     SET Mensagem = :mensagem
                     WHERE IdMatch = :idMatch'
                );
                $stmtAtualizacao->execute([
                    ':mensagem' => $mensagem !== '' ? $mensagem : null,
                    ':idMatch' => $idMatch,
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (\Throwable $erro) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $erro;
        }
    }

    public function verificarMatchExistente(int $idTrabalhadorUm, int $idServicoDestino): ?array
    {

        $sql = "SELECT * FROM `Match` 
                WHERE IdTrabalhadorUm = :idUm 
                  AND IdServicoDestino = :idDestino 
                  AND Status = 0
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idUm'      => $idTrabalhadorUm,
            ':idDestino' => $idServicoDestino
        ]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return $res ?: null;
    }

    public function buscarPropostaReciprocaPendente(
        int $idTrabalhadorOrigem,
        int $idTrabalhadorDestino,
        int $idServicoOrigem,
        int $idServicoDestino
    ): ?array {
        $sql = "SELECT IdMatch
                FROM `Match`
                WHERE IdTrabalhadorUm = :idTrabalhadorDestino
                  AND IdTrabalhadorDois = :idTrabalhadorOrigem
                  AND IdServicoOrigem = :idServicoDestino
                  AND IdServicoDestino = :idServicoOrigem
                  AND Status = 0
                ORDER BY IdMatch DESC
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idTrabalhadorOrigem'  => $idTrabalhadorOrigem,
            ':idTrabalhadorDestino' => $idTrabalhadorDestino,
            ':idServicoOrigem'      => $idServicoOrigem,
            ':idServicoDestino'     => $idServicoDestino,
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }
}
