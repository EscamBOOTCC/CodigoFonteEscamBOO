<?php

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class ServicoRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }


    public function salvar(array $dados): int|bool
    {
        $sql = "INSERT INTO Servico (Nome, Descricao, FotoServico, FotoServico2, IdTrabalhador, IdProfissao)
                VALUES (:nome, :descricao, :fotoServico, :fotoServico2, :idTrabalhador, :idProfissao)";

        $stmt = $this->conn->prepare($sql);
        $sucesso = $stmt->execute([
            ':nome'          => $dados['Nome'],
            ':descricao'     => $dados['Descricao'] ?? null,
            ':fotoServico'   => $dados['FotoServico'] ?? null,
            ':fotoServico2'  => $dados['FotoServico2'] ?? null,
            ':idTrabalhador' => $dados['IdTrabalhador'],
            ':idProfissao'   => $dados['IdProfissao']
        ]);

        return $sucesso ? (int) $this->conn->lastInsertId() : false;
    }

    public function excluir(int $idServico, int $idTrabalhador): bool
    {
        $sql = "DELETE FROM Servico
                WHERE IdServico = :idServico
                  AND IdTrabalhador = :idTrabalhador";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':idServico'     => $idServico,
            ':idTrabalhador' => $idTrabalhador
        ]);
    }

    public function buscarParaDenuncia(int $idServico): ?array
    {
        $sql = "SELECT IdServico, Nome, IdTrabalhador
                FROM Servico
                WHERE IdServico = :idServico";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':idServico' => $idServico]);
        $servico = $stmt->fetch(PDO::FETCH_ASSOC);

        return $servico ?: null;
    }

    public function buscarDoTrabalhador(int $idServico, int $idTrabalhador): ?array
    {
        $sql = "SELECT IdServico, FotoServico, FotoServico2
                FROM Servico
                WHERE IdServico = :idServico AND IdTrabalhador = :idTrabalhador";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idServico' => $idServico,
            ':idTrabalhador' => $idTrabalhador,
        ]);
        $servico = $stmt->fetch(PDO::FETCH_ASSOC);

        return $servico ?: null;
    }

    public function listarDisponiveis(int $idTrabalhadorLogado): array
    {
        $sql = "SELECT
                    s.IdServico,
                    s.Nome AS NomeServico,
                    s.Descricao AS DescricaoServico,
                    s.FotoServico,
                    s.FotoServico2,
                    s.IdProfissao,
                    s.IdTrabalhador,
                    t.Nome AS NomeTrabalhador,
                    t.FotoPerfil,
                    t.Endereco,
                    t.Biografia,
                    p.Nome AS NomeProfissao,
                    g.Nome AS NomeGrandeArea,
                    CASE WHEN EXISTS (
                        SELECT 1
                        FROM TrabalhadorInteresseGrandeArea tiga
                        WHERE tiga.IdTrabalhador = :idTrabalhadorInteresses
                          AND tiga.IdGrandeArea = p.IdGrandeArea
                    ) THEN 1 ELSE 0 END AS RecomendadoPorInteresse
                FROM Servico s
                INNER JOIN Trabalhador t ON s.IdTrabalhador = t.IdTrabalhador
                LEFT JOIN Profissao p ON s.IdProfissao = p.IdProfissao
                LEFT JOIN GrandesAreas g ON p.IdGrandeArea = g.IdGrandeArea
                WHERE s.IdTrabalhador != :idTrabalhadorLogado
                  AND t.Ativo = 'ATIVO'
                  AND NOT EXISTS (
                      SELECT 1
                      FROM `Match` m
                      WHERE m.IdTrabalhadorDois = :idTrabalhadorLogado
                        AND m.IdServicoOrigem = s.IdServico
                        AND m.Status = 2
                  )
                  AND NOT EXISTS (
                      SELECT 1 FROM Bloqueio b
                      WHERE (b.IdTrabalhadorBloqueou = :idTrabalhadorLogado AND b.IdTrabalhadorBloqueado = s.IdTrabalhador)
                         OR (b.IdTrabalhadorBloqueou = s.IdTrabalhador AND b.IdTrabalhadorBloqueado = :idTrabalhadorLogado)
                  )
                ORDER BY RecomendadoPorInteresse DESC, s.IdServico DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idTrabalhadorLogado' => $idTrabalhadorLogado,
            ':idTrabalhadorInteresses' => $idTrabalhadorLogado,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarMeusServicos(int $idTrabalhador): array
    {
        $sql = "SELECT
                    s.IdServico,
                    s.Nome AS NomeServico,
                    s.Descricao AS DescricaoServico,
                    s.FotoServico,
                    s.FotoServico2,
                    s.IdProfissao,
                    p.Nome AS NomeProfissao,
                    avaliacoes.MediaNota AS NotaMedia,
                    COALESCE(avaliacoes.TotalAvaliacoes, 0) AS TotalAvaliacoes
                FROM Servico s
                LEFT JOIN Profissao p ON s.IdProfissao = p.IdProfissao
                LEFT JOIN (
                    SELECT
                        av.IdServico,
                        AVG(av.Nota) AS MediaNota,
                        COUNT(*) AS TotalAvaliacoes
                    FROM Avaliacao av
                    INNER JOIN Servico servicoAvaliado ON servicoAvaliado.IdServico = av.IdServico
                    LEFT JOIN Acordo a ON av.IdAcordo = a.IdAcordo
                    WHERE av.Tipo = 'PARCEIRO'
                      AND (av.IdAcordo IS NULL OR a.Status = 1)
                      AND servicoAvaliado.IdTrabalhador = :idTrabalhadorAvaliacoes
                    GROUP BY av.IdServico
                ) avaliacoes ON avaliacoes.IdServico = s.IdServico
                WHERE s.IdTrabalhador = :idTrabalhador
                ORDER BY s.IdServico DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idTrabalhador' => $idTrabalhador,
            ':idTrabalhadorAvaliacoes' => $idTrabalhador,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarProfissoes(): array
    {
        $sql = "SELECT IdProfissao, Nome, IdGrandeArea
                FROM Profissao
                ORDER BY Nome ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editarServico(int $idServico, int $idTrabalhador, array $dados): bool
    {
        $sql = "UPDATE Servico
                SET Nome = :nome, Descricao = :descricao, FotoServico = :fotoServico,
                    FotoServico2 = :fotoServico2, IdProfissao = :idProfissao
                WHERE IdServico = :idServico AND IdTrabalhador = :idTrabalhador";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome'          => $dados['Nome'],
            ':descricao'     => $dados['Descricao'] ?? null,
            ':fotoServico'   => $dados['FotoServico'] ?? null,
            ':fotoServico2'  => $dados['FotoServico2'] ?? null,
            ':idProfissao'   => $dados['IdProfissao'],
            ':idServico'     => $idServico,
            ':idTrabalhador' => $idTrabalhador
        ]);
    }

    public function filtrarServicos(array $filtros, int $idTrabalhadorLogado): array
    {
        $sql = "SELECT 
                    s.IdServico,
                    s.Nome AS NomeServico,
                    s.Descricao AS DescricaoServico,
                    s.FotoServico,
                    s.FotoServico2,
                    s.IdProfissao,
                    s.IdTrabalhador,
                    t.Nome AS NomeTrabalhador,
                    t.FotoPerfil,
                    t.Endereco,
                    t.Biografia,
                    p.Nome AS NomeProfissao,
                    g.Nome AS NomeGrandeArea,
                    g.IdGrandeArea
                FROM Servico s
                INNER JOIN Trabalhador t ON s.IdTrabalhador = t.IdTrabalhador
                LEFT JOIN Profissao p ON s.IdProfissao = p.IdProfissao
                LEFT JOIN GrandesAreas g ON p.IdGrandeArea = g.IdGrandeArea
                WHERE s.IdTrabalhador != :idTrabalhadorLogado
                AND t.Ativo = 'ATIVO'
                AND NOT EXISTS (
                    SELECT 1
                    FROM `Match` m
                    WHERE m.IdTrabalhadorDois = :idTrabalhadorLogado
                        AND m.IdServicoOrigem = s.IdServico
                        AND m.Status = 2
                )
                AND NOT EXISTS (
                    SELECT 1 FROM Bloqueio b
                    WHERE (b.IdTrabalhadorBloqueou = :idTrabalhadorLogado AND b.IdTrabalhadorBloqueado = s.IdTrabalhador)
                       OR (b.IdTrabalhadorBloqueou = s.IdTrabalhador AND b.IdTrabalhadorBloqueado = :idTrabalhadorLogado)
                )";

        $params = [':idTrabalhadorLogado' => $idTrabalhadorLogado];

        if (!empty($filtros['busca'])) {
            $sql .= " AND (s.Nome LIKE :busca OR s.Descricao LIKE :busca OR p.Nome LIKE :busca)";
            $params[':busca'] = '%' . $filtros['busca'] . '%';
        }

        if (!empty($filtros['idGrandeArea'])) {
            $sql .= " AND (g.IdGrandeArea = :idGrandeArea OR p.IdGrandeArea = :idGrandeArea)";
            $params[':idGrandeArea'] = (int) $filtros['idGrandeArea'];
        }

        if (!empty($filtros['idProfissao'])) {
            $sql .= " AND s.IdProfissao = :idProfissao";
            $params[':idProfissao'] = (int) $filtros['idProfissao'];
        }

        if (!empty($filtros['pesquisaAreaAtuacao'])) {
            $sql .= " AND (p.Nome LIKE :pesquisaAreaAtuacaoProfissao
                      OR g.Nome LIKE :pesquisaAreaAtuacaoGrandeArea)";
            $termoAreaAtuacao = '%' . $filtros['pesquisaAreaAtuacao'] . '%';
            $params[':pesquisaAreaAtuacaoProfissao'] = $termoAreaAtuacao;
            $params[':pesquisaAreaAtuacaoGrandeArea'] = $termoAreaAtuacao;
        }

        if (!empty($filtros['localizacao'])) {
            $sql .= " AND t.Endereco LIKE :localizacao";
            $params[':localizacao'] = '%' . $filtros['localizacao'] . '%';
        }

        $sql .= " ORDER BY s.IdServico DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
