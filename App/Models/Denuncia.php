<?php
namespace App\Models;

class Denuncia
{
    protected ?int $IdDenuncia = null;
    protected ?string $Motivo = null;
    protected ?string $Descricao = null;
    protected int $Status = 0;
    protected ?string $Data = null;
    protected ?int $IdTrabalhador = null;
    protected ?int $IdDenunciante = null;

    public function getIdDenuncia(): ?int
    {
        return $this->IdDenuncia;
    }

    public function setIdDenuncia(?int $IdDenuncia): self
    {
        $this->IdDenuncia = $IdDenuncia;

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

    public function getDescricao(): ?string
    {
        return $this->Descricao;
    }

    public function setDescricao(?string $Descricao): self
    {
        $this->Descricao = $Descricao;

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

    public function getData(): ?string
    {
        return $this->Data;
    }

    public function setData(?string $Data): self
    {
        $this->Data = $Data;

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

    public function getIdDenunciante(): ?int
    {
        return $this->IdDenunciante;
    }

    public function setIdDenunciante(?int $IdDenunciante): self
    {
        $this->IdDenunciante = $IdDenunciante;

        return $this;
    }
}
