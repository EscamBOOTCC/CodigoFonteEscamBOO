<?php
namespace App\Models;

class Penalidade
{
    protected ?int $IdPenalidade = null;
    protected ?string $Motivo = null;
    protected ?int $Tipo = null;
    protected string $Status = 'APLICADA';
    protected ?string $Data = null;
    protected ?int $IdAdm = null;
    protected ?int $IdTrabalhador = null;
    protected ?int $IdDenuncia = null;

    public function getIdPenalidade(): ?int
    {
        return $this->IdPenalidade;
    }

    public function setIdPenalidade(?int $IdPenalidade): self
    {
        $this->IdPenalidade = $IdPenalidade;

        return $this;
    }

    public function getMotivo(): ?string
    {
        return $this->Motivo;
    }

    public function setMotivo(?string $Motivo): self
    {
        $this->Motivo = $Motivo;

        return $this;
    }

    public function getTipo(): ?int
    {
        return $this->Tipo;
    }

    public function setTipo(?int $Tipo): self
    {
        $this->Tipo = $Tipo;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

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

    public function getIdAdm(): ?int
    {
        return $this->IdAdm;
    }

    public function setIdAdm(?int $IdAdm): self
    {
        $this->IdAdm = $IdAdm;

        return $this;
    }

    public function getIdTrabalhador(): ?int
    {
        return $this->IdTrabalhador;
    }

    public function setIdTrabalhador(?int $IdTrabalhador): self
    {
        $this->IdTrabalhador = $IdTrabalhador;

        return $this;
    }

    public function getIdDenuncia(): ?int
    {
        return $this->IdDenuncia;
    }

    public function setIdDenuncia(?int $IdDenuncia): self
    {
        $this->IdDenuncia = $IdDenuncia;

        return $this;
    }
}
