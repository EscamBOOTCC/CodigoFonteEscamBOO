<?php
namespace App\Models;

class Bloqueio
{
    protected ?int $IdBloqueio = null;
    protected ?int $IdTrabalhadorBloqueou;
    protected ?int $IdTrabalhadorBloqueado;
    protected ?string $Data;

    public function __construct(?int $IdTrabalhadorBloqueou = null, ?int $IdTrabalhadorBloqueado = null, ?string $Data = null)
    {
        $this->IdTrabalhadorBloqueou  = $IdTrabalhadorBloqueou;
        $this->IdTrabalhadorBloqueado = $IdTrabalhadorBloqueado;
        $this->Data                   = $Data;
    }

    public function getIdBloqueio(): ?int
    {
        return $this->IdBloqueio;
    }

    public function setIdBloqueio(?int $IdBloqueio): self
    {
        $this->IdBloqueio = $IdBloqueio;
        return $this;
    }

    public function getIdTrabalhadorBloqueou(): ?int
    {
        return $this->IdTrabalhadorBloqueou;
    }

    public function setIdTrabalhadorBloqueou(?int $IdTrabalhadorBloqueou): self
    {
        $this->IdTrabalhadorBloqueou = $IdTrabalhadorBloqueou;
        return $this;
    }

    public function getIdTrabalhadorBloqueado(): ?int
    {
        return $this->IdTrabalhadorBloqueado;
    }

    public function setIdTrabalhadorBloqueado(?int $IdTrabalhadorBloqueado): self
    {
        $this->IdTrabalhadorBloqueado = $IdTrabalhadorBloqueado;
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
}
