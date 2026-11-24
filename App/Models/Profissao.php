<?php
namespace App\Models;

class Profissao
{
    protected ?int $IdProfissao = null;
    protected ?string $Nome = null;
    protected ?int $IdGrandeArea = null;

    public function getIdProfissao(): ?int
    {
        return $this->IdProfissao;
    }

    public function setIdProfissao(?int $IdProfissao): self
    {
        $this->IdProfissao = $IdProfissao;

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

    public function getIdGrandeArea(): ?int
    {
        return $this->IdGrandeArea;
    }

    public function setIdGrandeArea(?int $IdGrandeArea): self
    {
        $this->IdGrandeArea = $IdGrandeArea;

        return $this;
    }
}
