<?php
namespace App\Models;

class Administrador
{
    protected ?int $IdAdm = null;
    protected ?string $Nome = null;
    protected ?string $CPF = null;
    protected ?string $Genero = null;
    protected ?string $Email = null;
    protected ?string $Senha = null;
    protected ?string $DataNascimento = null;
    protected ?string $FotoPerfil = null;
    protected ?string $Endereco = null;
    protected string $Ativo = 'ATIVO';
    protected ?string $DataExpiraStatus = null;

    public function getIdAdm(): ?int
    {
        return $this->IdAdm;
    }

    public function setIdAdm(?int $IdAdm): self
    {
        $this->IdAdm = $IdAdm;

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

    public function getCPF(): ?string
    {
        return $this->CPF;
    }

    public function setCPF(?string $CPF): self
    {
        $this->CPF = $CPF;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->Genero;
    }

    public function setGenero(?string $Genero): self
    {
        $this->Genero = $Genero;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(?string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getSenha(): ?string
    {
        return $this->Senha;
    }

    public function setSenha(?string $Senha): self
    {
        $this->Senha = $Senha;

        return $this;
    }

    public function getDataNascimento(): ?string
    {
        return $this->DataNascimento;
    }

    public function setDataNascimento(?string $DataNascimento): self
    {
        $this->DataNascimento = $DataNascimento;

        return $this;
    }

    public function getFotoPerfil(): ?string
    {
        return $this->FotoPerfil;
    }

    public function setFotoPerfil(?string $FotoPerfil): self
    {
        $this->FotoPerfil = $FotoPerfil;

        return $this;
    }

    public function getEndereco(): ?string
    {
        return $this->Endereco;
    }

    public function setEndereco(?string $Endereco): self
    {
        $this->Endereco = $Endereco;

        return $this;
    }

    public function getAtivo(): string
    {
        return $this->Ativo;
    }

    public function setAtivo(string $Ativo): self
    {
        $this->Ativo = $Ativo;

        return $this;
    }

    public function getDataExpiraStatus(): ?string
    {
        return $this->DataExpiraStatus;
    }

    public function setDataExpiraStatus(?string $DataExpiraStatus): self
    {
        $this->DataExpiraStatus = $DataExpiraStatus;

        return $this;
    }
}
