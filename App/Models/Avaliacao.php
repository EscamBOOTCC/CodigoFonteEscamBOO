<?php
namespace App\Models;

class Avaliacao
{
    // PARCEIRO: nota dada ao trabalhador do outro lado do acordo (compõe a reputação).
    // ESCAMBO: nota dada ao acordo/processo de troca em si (não tem avaliado).
    public const TIPO_PARCEIRO = 'PARCEIRO';
    public const TIPO_ESCAMBO  = 'ESCAMBO';

    protected ?int $IdAvaliacao = null;
    protected ?float $Nota;
    protected ?string $Data;
    protected ?string $Comentario;
    protected string $Tipo;
    protected ?int $IdServico;
    protected ?int $IdAcordo;
    protected ?int $IdAvaliador;
    protected ?int $IdAvaliado;

    public function __construct(
        ?float $Nota,
        ?string $Comentario,
        ?int $IdServico,
        ?int $IdAcordo,
        ?int $IdAvaliador,
        ?int $IdAvaliado = null,
        string $Tipo = self::TIPO_PARCEIRO,
        ?string $Data = null
    ) {
        $this->Nota        = $Nota;
        $this->Comentario  = $Comentario;
        $this->IdServico   = $IdServico;
        $this->IdAcordo    = $IdAcordo;
        $this->IdAvaliador = $IdAvaliador;
        $this->IdAvaliado  = $IdAvaliado;
        $this->Tipo        = $Tipo;
        $this->Data        = $Data;
    }

    public function getIdAvaliacao(): ?int
    {
        return $this->IdAvaliacao;
    }

    public function setIdAvaliacao(?int $IdAvaliacao): self
    {
        $this->IdAvaliacao = $IdAvaliacao;

        return $this;
    }

    public function getNota(): ?float
    {
        return $this->Nota;
    }

    public function setNota(?float $Nota): self
    {
        $this->Nota = $Nota;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->Data;
    }

    public function setData(?string $Data): self
    {
        $this->Data = $Data;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->Comentario;
    }

    public function setComentario(?string $Comentario): self
    {
        $this->Comentario = $Comentario;

        return $this;
    }

    public function getTipo(): string
    {
        return $this->Tipo;
    }

    public function setTipo(string $Tipo): self
    {
        $this->Tipo = $Tipo;

        return $this;
    }

    public function getIdServico(): ?int
    {
        return $this->IdServico;
    }

    public function setIdServico(?int $IdServico): self
    {
        $this->IdServico = $IdServico;

        return $this;
    }

    public function getIdAcordo(): ?int
    {
        return $this->IdAcordo;
    }

    public function setIdAcordo(?int $IdAcordo): self
    {
        $this->IdAcordo = $IdAcordo;

        return $this;
    }

    public function getIdAvaliador(): ?int
    {
        return $this->IdAvaliador;
    }

    public function setIdAvaliador(?int $IdAvaliador): self
    {
        $this->IdAvaliador = $IdAvaliador;

        return $this;
    }

    public function getIdAvaliado(): ?int
    {
        return $this->IdAvaliado;
    }

    public function setIdAvaliado(?int $IdAvaliado): self
    {
        $this->IdAvaliado = $IdAvaliado;

        return $this;
    }
}
