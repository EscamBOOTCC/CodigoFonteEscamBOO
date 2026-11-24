<?php
namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class AcordoRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }


    public function criarAcordo(int $idMatch): int | bool
    {

        $existente = $this->buscarPorMatch($idMatch);
        if ($existente) {
            return (int) $existente['IdAcordo'];
        }

        $sql = "INSERT INTO Acordo (Status, IdMatch, DataCriacao)
                VALUES (0, :idMatch, NOW())";

        $stmt    = $this->conn->prepare($sql);
        $sucesso = $stmt->execute([':idMatch' => $idMatch]);

        if ($sucesso) {
            return (int) $this->conn->lastInsertId();
        }

        return false;
    }

    public function buscarPorMatch(int $idMatch): ?array //busca os matches que tem acordo, se tiver acordo, não pode criar outro acordo, então retorna o acordo existente
    {

        $sql  = "SELECT * FROM Acordo WHERE IdMatch = :idMatch LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idMatch' => $idMatch]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return $res ?: null;
    }

    public function temAcordoAtivoParaTrabalhador(int $idTrabalhador): bool
    {

        $sql = "SELECT a.IdAcordo
                FROM Acordo a
                INNER JOIN `Match` m ON a.IdMatch = m.IdMatch
                WHERE (m.IdTrabalhadorUm = :idTrabalhador OR m.IdTrabalhadorDois = :idTrabalhador)
                  AND a.Status = 0
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);

        return (bool) $stmt->fetchColumn();
    }

    public function buscarPorId(int $idAcordo): ?array
    {

        $sql = "SELECT
                    a.*,
                    m.Data AS DataMatch,
                    m.IdTrabalhadorUm,
                    m.IdTrabalhadorDois,
                    m.Mensagem AS MensagemProposta,

                    t1.Nome AS NomeTrabalhadorUm,
                    t1.FotoPerfil AS FotoTrabalhadorUm,
                    t1.Telefone AS TelefoneTrabalhadorUm,
                    t1.Instagram AS InstagramTrabalhadorUm,
                    t1.Twitter AS TwitterTrabalhadorUm,
                    t1.Endereco AS EnderecoTrabalhadorUm,
                    p1.Nome AS ProfissaoTrabalhadorUm,

                    t2.Nome AS NomeTrabalhadorDois,
                    t2.FotoPerfil AS FotoTrabalhadorDois,
                    t2.Telefone AS TelefoneTrabalhadorDois,
                    t2.Instagram AS InstagramTrabalhadorDois,
                    t2.Twitter AS TwitterTrabalhadorDois,
                    t2.Endereco AS EnderecoTrabalhadorDois,
                    p2.Nome AS ProfissaoTrabalhadorDois,

                    so.IdServico AS IdServicoUm,
                    so.Nome AS NomeServicoUm,
                    so.Descricao AS DescricaoServicoUm,

                    sd.IdServico AS IdServicoDois,
                    sd.Nome AS NomeServicoDois,
                    sd.Descricao AS DescricaoServicoDois
                FROM Acordo a
                INNER JOIN `Match` m ON a.IdMatch = m.IdMatch
                INNER JOIN Trabalhador t1 ON m.IdTrabalhadorUm = t1.IdTrabalhador
                INNER JOIN Trabalhador t2 ON m.IdTrabalhadorDois = t2.IdTrabalhador
                LEFT JOIN Profissao p1 ON t1.IdProfissao = p1.IdProfissao
                LEFT JOIN Profissao p2 ON t2.IdProfissao = p2.IdProfissao
                LEFT JOIN Servico so ON m.IdServicoOrigem = so.IdServico
                LEFT JOIN Servico sd ON m.IdServicoDestino = sd.IdServico
                WHERE a.IdAcordo = :idAcordo";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idAcordo' => $idAcordo]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return $res ?: null;
    }

    public function contarAtivos(): int
    {

        $sql = "SELECT COUNT(*) FROM Acordo WHERE Status = 0";
        $stmt = $this->conn->query($sql);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Verifica se um serviço está vinculado a algum acordo em andamento (Status = 0).
     * Impede exclusão de serviços que ainda estão sendo executados.
     */
    public function servicoTemAcordoAtivo(int $idServico): bool
    {
        $sql = "SELECT 1
                FROM Acordo a
                INNER JOIN `Match` m ON a.IdMatch = m.IdMatch
                WHERE a.Status = 0
                  AND (m.IdServicoOrigem = :idServico OR m.IdServicoDestino = :idServico)
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idServico' => $idServico]);

        return (bool) $stmt->fetchColumn();
    }

    public function listarCanceladosParaAdmin(): array
    {
        $sql = "SELECT
                    a.IdAcordo,
                    a.DataCriacao,
                    COALESCE(a.DataConclusao, a.DataCriacao) AS DataCancelamento,
                    a.JustificativaCancelamento,
                    t1.Nome AS NomeTrabalhadorUm,
                    t2.Nome AS NomeTrabalhadorDois,
                    so.Nome AS NomeServicoUm,
                    sd.Nome AS NomeServicoDois
                FROM Acordo a
                INNER JOIN `Match` m ON a.IdMatch = m.IdMatch
                INNER JOIN Trabalhador t1 ON m.IdTrabalhadorUm = t1.IdTrabalhador
                INNER JOIN Trabalhador t2 ON m.IdTrabalhadorDois = t2.IdTrabalhador
                LEFT JOIN Servico so ON m.IdServicoOrigem = so.IdServico
                LEFT JOIN Servico sd ON m.IdServicoDestino = sd.IdServico
                WHERE a.Status = 2
                ORDER BY COALESCE(a.DataConclusao, a.DataCriacao) DESC, a.IdAcordo DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarCancelados(): int
    {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM Acordo WHERE Status = 2');

        return (int) $stmt->fetchColumn();
    }

    public function listarPorTrabalhador(int $idTrabalhador, ?int $status = null): array
    {

        $sql = "SELECT
                    a.*,
                    m.IdTrabalhadorUm,
                    m.IdTrabalhadorDois,
                    t1.Nome AS NomeTrabalhadorUm,
                    t1.FotoPerfil AS FotoTrabalhadorUm,
                    t2.Nome AS NomeTrabalhadorDois,
                    t2.FotoPerfil AS FotoTrabalhadorDois,
                    so.Nome AS NomeServicoUm,
                    sd.Nome AS NomeServicoDois
                FROM Acordo a
                INNER JOIN `Match` m ON a.IdMatch = m.IdMatch
                INNER JOIN Trabalhador t1 ON m.IdTrabalhadorUm = t1.IdTrabalhador
                INNER JOIN Trabalhador t2 ON m.IdTrabalhadorDois = t2.IdTrabalhador
                LEFT JOIN Servico so ON m.IdServicoOrigem = so.IdServico
                LEFT JOIN Servico sd ON m.IdServicoDestino = sd.IdServico
                WHERE (m.IdTrabalhadorUm = :idTrabalhador OR m.IdTrabalhadorDois = :idTrabalhador)";

        if ($status !== null) {
            $sql .= " AND a.Status = :status";
        }

        $sql .= " ORDER BY a.IdAcordo DESC";

        $stmt   = $this->conn->prepare($sql);
        $params = [':idTrabalhador' => $idTrabalhador];
        if ($status !== null) {
            $params[':status'] = $status;
        }

        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarServicosConcluidosPorTrabalhador(int $idTrabalhador): array
    {

        $sql = "SELECT
                    a.IdAcordo,
                    m.IdTrabalhadorUm,
                    m.IdTrabalhadorDois,
                    CASE
                        WHEN m.IdTrabalhadorUm = :idTrabalhador THEN so.Nome
                        ELSE sd.Nome
                    END AS NomeServico,
                    CASE
                        WHEN m.IdTrabalhadorUm = :idTrabalhador THEN so.IdServico
                        ELSE sd.IdServico
                    END AS IdServico,
                    CASE
                        WHEN m.IdTrabalhadorUm = :idTrabalhador THEN so.FotoServico
                        ELSE sd.FotoServico
                    END AS FotoServico,
                    CASE
                        WHEN m.IdTrabalhadorUm = :idTrabalhador THEN so.FotoServico2
                        ELSE sd.FotoServico2
                    END AS FotoServico2,
                    CASE
                        WHEN m.IdTrabalhadorUm = :idTrabalhador THEN t2.Nome
                        ELSE t1.Nome
                    END AS NomeParceiro,
                    CASE
                        WHEN m.IdTrabalhadorUm = :idTrabalhador THEN t2.FotoPerfil
                        ELSE t1.FotoPerfil
                    END AS FotoParceiro,
                    avaliacoes.MediaNota AS NotaMedia,
                    COALESCE(avaliacoes.TotalAvaliacoes, 0) AS TotalAvaliacoes,
                    a.DataConclusao
                FROM Acordo a
                INNER JOIN `Match` m ON a.IdMatch = m.IdMatch
                INNER JOIN Trabalhador t1 ON m.IdTrabalhadorUm = t1.IdTrabalhador
                INNER JOIN Trabalhador t2 ON m.IdTrabalhadorDois = t2.IdTrabalhador
                LEFT JOIN Servico so ON m.IdServicoOrigem = so.IdServico
                LEFT JOIN Servico sd ON m.IdServicoDestino = sd.IdServico
                LEFT JOIN (
                    SELECT
                        av.IdServico,
                        AVG(av.Nota) AS MediaNota,
                        COUNT(*) AS TotalAvaliacoes
                    FROM Avaliacao av
                    LEFT JOIN Acordo acordoAvaliado ON av.IdAcordo = acordoAvaliado.IdAcordo
                    WHERE av.Tipo = 'PARCEIRO'
                      AND (av.IdAcordo IS NULL OR acordoAvaliado.Status = 1)
                    GROUP BY av.IdServico
                ) avaliacoes ON avaliacoes.IdServico = CASE
                    WHEN m.IdTrabalhadorUm = :idTrabalhador THEN so.IdServico
                    ELSE sd.IdServico
                END
                WHERE a.Status = 1
                  AND (m.IdTrabalhadorUm = :idTrabalhador OR m.IdTrabalhadorDois = :idTrabalhador)
                ORDER BY a.DataConclusao DESC, a.IdAcordo DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idTrabalhador' => $idTrabalhador]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarConfirmacaoConclusao(int $idAcordo, int $idTrabalhador): array
    {

        $acordo = $this->buscarPorId($idAcordo);
        if (! $acordo) {
            return ['sucesso' => false, 'mensagem' => 'Acordo não encontrado.'];
        }

        if ((int) $acordo['Status'] === 1) {
            return ['sucesso' => false, 'mensagem' => 'Este acordo já foi concluído anteriormente.'];
        }

        if ((int) $acordo['Status'] === 2) {
            return ['sucesso' => false, 'mensagem' => 'Este acordo foi cancelado e não pode ser concluído.'];
        }

        $isTrabalhadorUm   = (int) $acordo['IdTrabalhadorUm'] === $idTrabalhador;
        $isTrabalhadorDois = (int) $acordo['IdTrabalhadorDois'] === $idTrabalhador;

        if (! $isTrabalhadorUm && ! $isTrabalhadorDois) {
            return ['sucesso' => false, 'mensagem' => 'Você não participa deste acordo.'];
        }

        $confUm   = (int) ($acordo['ConfirmacaoTrabalhadorUm'] ?? 0);
        $confDois = (int) ($acordo['ConfirmacaoTrabalhadorDois'] ?? 0);

        if ($isTrabalhadorUm) {
            $confUm         = 1;
            $outroConfirmou = ($confDois === 1);
            $nomeOutro      = $acordo['NomeTrabalhadorDois'] ?? 'o outro trabalhador';
        } else {
            $confDois       = 1;
            $outroConfirmou = ($confUm === 1);
            $nomeOutro      = $acordo['NomeTrabalhadorUm'] ?? 'o outro trabalhador';
        }

        if ($outroConfirmou) {
            $sql = "UPDATE Acordo
                    SET ConfirmacaoTrabalhadorUm = :confUm,
                        ConfirmacaoTrabalhadorDois = :confDois,
                        Status = 1,
                        DataConclusao = NOW()
                    WHERE IdAcordo = :idAcordo";
            $stmt    = $this->conn->prepare($sql);
            $sucesso = $stmt->execute([
                ':confUm'   => $confUm,
                ':confDois' => $confDois,
                ':idAcordo' => $idAcordo,
            ]);

            return [
                'sucesso'    => $sucesso,
                'finalizado' => true,
                'mensagem'   => 'Excelente! Ambas as partes confirmaram e o acordo foi 100% concluído com sucesso!',
            ];
        } else {
            $sql = "UPDATE Acordo
                    SET ConfirmacaoTrabalhadorUm = :confUm,
                        ConfirmacaoTrabalhadorDois = :confDois
                    WHERE IdAcordo = :idAcordo";
            $stmt    = $this->conn->prepare($sql);
            $sucesso = $stmt->execute([
                ':confUm'   => $confUm,
                ':confDois' => $confDois,
                ':idAcordo' => $idAcordo,
            ]);

            return [
                'sucesso'    => $sucesso,
                'finalizado' => false,
                'mensagem'   => 'Sua confirmação foi registrada com sucesso! O acordo será concluído assim que ' . htmlspecialchars($nomeOutro) . ' também confirmar a entrega.',
            ];
        }
    }

    public function cancelarAcordo(int $idAcordo, int $idTrabalhador, string $justificativa): bool
    {
        $sql = "UPDATE Acordo a
                INNER JOIN `Match` m ON a.IdMatch = m.IdMatch
                SET a.Status = 2,
                    a.DataConclusao = NOW(),
                    a.JustificativaCancelamento = :justificativa
                WHERE a.IdAcordo = :idAcordo
                  AND (m.IdTrabalhadorUm = :idTrabalhador OR m.IdTrabalhadorDois = :idTrabalhador)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':idAcordo'      => $idAcordo,
            ':idTrabalhador' => $idTrabalhador,
            ':justificativa' => $justificativa,
        ]);
    }
}
