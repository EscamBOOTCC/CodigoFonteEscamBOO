<?php
namespace App\Models;

class Acordo
{
    protected ?int $IdAcordo = null;
    protected ?int $IdMatch = null;
    protected int $Status = 0;
    protected bool $ConfirmacaoTrabalhadorUm = false;
    protected bool $ConfirmacaoTrabalhadorDois = false;
    protected ?string $DataCriacao = null;
    protected ?string $DataConclusao = null;
    protected ?string $JustificativaCancelamento = null;

    public function getIdAcordo(): ?int
    {
        return $this->IdAcordo;
    }

    public function setIdAcordo(?int $IdAcordo): self
    {
        $this->IdAcordo = $IdAcordo;

        return $this;
    }

    public function getIdMatch(): ?int
    {
        return $this->IdMatch;
    }

    public function setIdMatch(?int $IdMatch): self
    {
        $this->IdMatch = $IdMatch;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->Status;
    }

    public function setStatus(int $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function isConfirmacaoTrabalhadorUm(): bool
    {
        return $this->ConfirmacaoTrabalhadorUm;
    }

    public function setConfirmacaoTrabalhadorUm(bool $ConfirmacaoTrabalhadorUm): self
    {
        $this->ConfirmacaoTrabalhadorUm = $ConfirmacaoTrabalhadorUm;

        return $this;
    }

    public function isConfirmacaoTrabalhadorDois(): bool
    {
        return $this->ConfirmacaoTrabalhadorDois;
    }

    public function setConfirmacaoTrabalhadorDois(bool $ConfirmacaoTrabalhadorDois): self
    {
        $this->ConfirmacaoTrabalhadorDois = $ConfirmacaoTrabalhadorDois;

        return $this;
    }

    public function getDataCriacao(): ?string
    {
        return $this->DataCriacao;
    }

    public function setDataCriacao(?string $DataCriacao): self
    {
        $this->DataCriacao = $DataCriacao;

        return $this;
    }

    public function getDataConclusao(): ?string
    {
        return $this->DataConclusao;
    }

    public function setDataConclusao(?string $DataConclusao): self
    {
        $this->DataConclusao = $DataConclusao;

        return $this;
    }

    public function getJustificativaCancelamento(): ?string
    {
        return $this->JustificativaCancelamento;
    }

    public function setJustificativaCancelamento(?string $JustificativaCancelamento): self
    {
        $this->JustificativaCancelamento = $JustificativaCancelamento;

        return $this;
    }
}
