<?php
namespace App\Models;

class MatchClass
{
    protected ?int $IdMatch = null;
    protected ?string $Data = null;
    protected int $Status = 0;
    protected ?int $IdTrabalhadorUm = null;
    protected ?int $IdTrabalhadorDois = null;
    protected ?int $IdServicoOrigem = null;
    protected ?int $IdServicoDestino = null;
    protected ?string $Mensagem = null;

    public function getIdMatch(): ?int
    {
        return $this->IdMatch;
    }

    public function setIdMatch(?int $IdMatch): self
    {
        $this->IdMatch = $IdMatch;

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

    public function getStatus(): int
    {
        return $this->Status;
    }

    public function setStatus(int $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getIdTrabalhadorUm(): ?int
    {
        return $this->IdTrabalhadorUm;
    }

    public function setIdTrabalhadorUm(?int $IdTrabalhadorUm): self
    {
        $this->IdTrabalhadorUm = $IdTrabalhadorUm;

        return $this;
    }

    public function getIdTrabalhadorDois(): ?int
    {
        return $this->IdTrabalhadorDois;
    }

    public function setIdTrabalhadorDois(?int $IdTrabalhadorDois): self
    {
        $this->IdTrabalhadorDois = $IdTrabalhadorDois;

        return $this;
    }

    public function getIdServicoOrigem(): ?int
    {
        return $this->IdServicoOrigem;
    }

    public function setIdServicoOrigem(?int $IdServicoOrigem): self
    {
        $this->IdServicoOrigem = $IdServicoOrigem;

        return $this;
    }

    public function getIdServicoDestino(): ?int
    {
        return $this->IdServicoDestino;
    }

    public function setIdServicoDestino(?int $IdServicoDestino): self
    {
        $this->IdServicoDestino = $IdServicoDestino;

        return $this;
    }

    public function getMensagem(): ?string
    {
        return $this->Mensagem;
    }

    public function setMensagem(?string $Mensagem): self
    {
        $this->Mensagem = $Mensagem;

        return $this;
    }
}
