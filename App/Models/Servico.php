<?php
namespace App\Models;

class Servico
{
    protected ?int $IdServico = null;
    protected ?string $Nome = null;
    protected ?string $Descricao = null;
    protected ?string $FotoServico = null;
    protected ?string $FotoServico2 = null;
    protected ?int $IdTrabalhador = null;
    protected ?int $IdProfissao = null;

    public function getIdServico(): ?int
    {
        return $this->IdServico;
    }

    public function setIdServico(?int $IdServico): self
    {
        $this->IdServico = $IdServico;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->Nome;
    }

    public function setNome(?string $Nome): self
    {
        $this->Nome = $Nome;

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

    public function getFotoServico(): ?string
    {
        return $this->FotoServico;
    }

    public function setFotoServico(?string $FotoServico): self
    {
        $this->FotoServico = $FotoServico;

        return $this;
    }

    public function getFotoServico2(): ?string
    {
        return $this->FotoServico2;
    }

    public function setFotoServico2(?string $FotoServico2): self
    {
        $this->FotoServico2 = $FotoServico2;

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

    public function getIdProfissao(): ?int
    {
        return $this->IdProfissao;
    }

    public function setIdProfissao(?int $IdProfissao): self
    {
        $this->IdProfissao = $IdProfissao;

        return $this;
    }
}
