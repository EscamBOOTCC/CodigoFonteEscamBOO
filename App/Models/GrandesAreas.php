<?php
namespace App\Models;

class GrandesAreas
{
    protected ?int $IdGrandeArea = null;
    protected ?string $Nome = null;

    public function getIdGrandeArea(): ?int
    {
        return $this->IdGrandeArea;
    }

    public function setIdGrandeArea(?int $IdGrandeArea): self
    {
        $this->IdGrandeArea = $IdGrandeArea;

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
}
