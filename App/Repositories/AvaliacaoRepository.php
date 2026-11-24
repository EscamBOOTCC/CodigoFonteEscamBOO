<?php
namespace App\Repositories;

use App\Database\ConnectionFactory;
use App\Models\Avaliacao;
use PDO;

// A estrutura da tabela Avaliacao (inclusive IdAcordo, IdAvaliador, IdAvaliado e Tipo)
// vem do Database/Scripts/ScriptBD.sql. Este repositório não altera mais o schema.
class AvaliacaoRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectionFactory::getConnection();
    }

    // Verifica se este avaliador já registrou este tipo de avaliação neste acordo (não pode duplicar/alterar).
    public function jaAvaliou(int $idAcordo, int $idAvaliador, string $tipo = Avaliacao::TIPO_PARCEIRO): bool
    {
        $sql = "SELECT IdAvaliacao
                FROM Avaliacao
                WHERE IdAcordo = :idAcordo
                  AND IdAvaliador = :idAvaliador
                  AND Tipo = :tipo
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idAcordo'    => $idAcordo,
            ':idAvaliador' => $idAvaliador,
            ':tipo'        => $tipo,
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar(array $dados): int|bool
    {
        $sql = "INSERT INTO Avaliacao (Nota, Data, Comentario, Tipo, IdServico, IdAcordo, IdAvaliador, IdAvaliado)
                VALUES (:nota, CURDATE(), :comentario, :tipo, :idServico, :idAcordo, :idAvaliador, :idAvaliado)";

        $stmt    = $this->conn->prepare($sql);
        $sucesso = $stmt->execute([
            ':nota'        => $dados['Nota'],
            ':comentario'  => $dados['Comentario'] ?? null,
            ':tipo'        => $dados['Tipo'] ?? Avaliacao::TIPO_PARCEIRO,
            ':idServico'   => $dados['IdServico'],
            ':idAcordo'    => $dados['IdAcordo'],
            ':idAvaliador' => $dados['IdAvaliador'],
            ':idAvaliado'  => $dados['IdAvaliado'] ?? null,
        ]);

        return $sucesso ? (int) $this->conn->lastInsertId() : false;
    }

    // Lista as avaliações de PARCEIRO recebidas por um trabalhador.
    // Só entram avaliações de acordos concluídos (Status = 1): a nota do primeiro a confirmar
    // fica guardada, mas só aparece depois que o outro lado também concluir. Linhas antigas
    // sem IdAcordo continuam aparecendo.
    public function listarRecebidasPorTrabalhador(int $idTrabalhador): array
    {
        $sql = "SELECT
                    av.IdAvaliacao, av.Nota, av.Data, av.Comentario,
                    t.IdTrabalhador AS IdAvaliador,
                    t.Nome AS NomeAvaliador,
                    t.FotoPerfil AS FotoAvaliador,
                    s.Nome AS NomeServico
                FROM Avaliacao av
                LEFT JOIN Acordo a ON av.IdAcordo = a.IdAcordo
                LEFT JOIN Trabalhador t ON av.IdAvaliador = t.IdTrabalhador
                LEFT JOIN Servico s ON av.IdServico = s.IdServico
                WHERE av.IdAvaliado = :idTrabalhador
                  AND av.Tipo = :tipo
                  AND (av.IdAcordo IS NULL OR a.Status = 1)
                ORDER BY av.IdAvaliacao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idTrabalhador' => $idTrabalhador,
            ':tipo'          => Avaliacao::TIPO_PARCEIRO,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Média de avaliações recebidas + quantidade total (RF03/RF10: reputação do trabalhador).
    // Mesma regra de listarRecebidasPorTrabalhador: só PARCEIRO e só acordos concluídos.
    public function calcularReputacao(int $idTrabalhador): array
    {
        $sql = "SELECT AVG(av.Nota) AS Media, COUNT(*) AS Quantidade
                FROM Avaliacao av
                LEFT JOIN Acordo a ON av.IdAcordo = a.IdAcordo
                WHERE av.IdAvaliado = :idTrabalhador
                  AND av.Tipo = :tipo
                  AND (av.IdAcordo IS NULL OR a.Status = 1)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':idTrabalhador' => $idTrabalhador,
            ':tipo'          => Avaliacao::TIPO_PARCEIRO,
        ]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'media'      => $res && $res['Media'] !== null ? round((float) $res['Media'], 1) : 0.0,
            'quantidade' => $res ? (int) $res['Quantidade'] : 0,
        ];
    }
}
