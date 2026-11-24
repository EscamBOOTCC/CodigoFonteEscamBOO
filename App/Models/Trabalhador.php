<?php
namespace App\Models;

class Trabalhador
{
    protected ?int $IdTrabalhador = null;
    protected ?string $Nome = null;
    protected ?string $CPF = null;
    protected ?string $Genero = null;
    protected ?string $Email = null;
    protected ?string $Senha = null;
    protected ?string $DataNascimento = null;
    protected ?string $FotoPerfil = null;
    protected ?string $Endereco = null;
    protected bool $StatusAcordo = false;
    protected string $Ativo = 'ATIVO';
    protected ?string $DataExpiraStatus = null;
    protected ?int $IdProfissao = null;
    protected ?int $IdGrandeArea = null;
    protected ?string $Biografia = null;
    protected ?string $Telefone = null;
    protected ?string $Instagram = null;
    protected ?string $Twitter = null;

    public function getIdTrabalhador(): ?int
    {
        return $this->IdTrabalhador;
    }

    public function setIdTrabalhador(?int $IdTrabalhador): self
    {
        $this->IdTrabalhador = $IdTrabalhador;

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

    public function isStatusAcordo(): bool
    {
        return $this->StatusAcordo;
    }

    public function setStatusAcordo(bool $StatusAcordo): self
    {
        $this->StatusAcordo = $StatusAcordo;

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

    public function getIdProfissao(): ?int
    {
        return $this->IdProfissao;
    }

    public function setIdProfissao(?int $IdProfissao): self
    {
        $this->IdProfissao = $IdProfissao;

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

    public function getBiografia(): ?string
    {
        return $this->Biografia;
    }

    public function setBiografia(?string $Biografia): self
    {
        $this->Biografia = $Biografia;

        return $this;
    }

    public function getTelefone(): ?string
    {
        return $this->Telefone;
    }

    public function setTelefone(?string $Telefone): self
    {
        $this->Telefone = $Telefone;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->Instagram;
    }

    public function setInstagram(?string $Instagram): self
    {
        $this->Instagram = $Instagram;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->Twitter;
    }

    public function setTwitter(?string $Twitter): self
    {
        $this->Twitter = $Twitter;

        return $this;
    }
}
