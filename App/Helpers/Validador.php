<?php
namespace App\Helpers; //validacoes de campos do formulario

use DateTime;

class Validador
{
    private array $erros = [];

    public function obrigatorio(string $campo, mixed $valor, ?string $mensagem = null): self//seta o campo como obrigatório
    {
        if (empty($valor) && $valor !== '0' && $valor !== 0) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} é obrigatório";
        }

        return $this;
    }

    public function email(string $campo, string $email, ?string $mensagem = null): self
    {
        if (! empty($email) && ! filter_var($email, FILTER_VALIDATE_EMAIL)) { //verifica se a string tem a estrurura de um email válido, nome@dominio.com
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ser um e-mail válido";
        }

        return $this;
    }

    public function cpf(string $campo, string $cpf, ?string $mensagem = null): self
    {
        $cpfLimpo = preg_replace('/\D/', '', $cpf);

        if ($cpfLimpo === '' && trim($cpf) === '') {
            return $this;
        }

        if (
            preg_match('/\A[0-9.\-\s]+\z/', $cpf) !== 1 ||
            strlen($cpfLimpo) !== 11 ||
            preg_match('/\A(\d)\1{10}\z/', $cpfLimpo) === 1 ||
            ! $this->cpfTemDigitosVerificadoresValidos($cpfLimpo)
        ) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ser um CPF válido";
        }

        return $this;
    }

    private function cpfTemDigitosVerificadoresValidos(string $cpf): bool
    {
        $soma = 0;
        for ($indice = 0; $indice < 9; $indice++) {
            $soma += (int) $cpf[$indice] * (10 - $indice);
        }

        $resto          = $soma % 11;
        $primeiroDigito = $resto < 2 ? 0 : 11 - $resto;
        if ((int) $cpf[9] !== $primeiroDigito) {
            return false;
        }

        $soma = 0;
        for ($indice = 0; $indice < 10; $indice++) {
            $soma += (int) $cpf[$indice] * (11 - $indice);
        }

        $resto         = $soma % 11;
        $segundoDigito = $resto < 2 ? 0 : 11 - $resto;

        return (int) $cpf[10] === $segundoDigito;
    }

    public function minLength(string $campo, string $valor, int $minimo, ?string $mensagem = null): self//verifica se o campo tem o tamanho minimo,utilizado para verificar o tamanho da senha por exemplo
    {
        if (! empty($valor) && strlen($valor) < $minimo) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ter no mínimo {$minimo} caracteres";
        }

        return $this;
    }

    public function maxLength(string $campo, string $valor, int $maximo, ?string $mensagem = null): self//tamanho maximo do campo
    {
        if (! empty($valor) && strlen($valor) > $maximo) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ter no máximo {$maximo} caracteres";
        }

        return $this;
    }

    public function igual(string $campo, mixed $valor1, mixed $valor2, ?string $mensagem = null): self
    {
        if ($valor1 !== $valor2) {
            $this->erros[$campo] = $mensagem ?? "Os campos informados não conferem";
        }

        return $this;
    }

    public function confirmarSenha(string $campo, string $senha, string $confirmarSenha, ?string $mensagem = null): self
    {
        if ($senha !== $confirmarSenha) {
            $this->erros[$campo] = $mensagem ?? "As senhas não coincidem";
        }

        return $this;
    }

    public function senhaForte(string $campo, string $senha, ?string $mensagem = null): self
    {
        if (empty($senha)) {
            return $this;
        }

        if (! preg_match('/[A-Z]/', $senha)) {
            $this->erros[$campo] = $mensagem ?? "A senha deve conter pelo menos uma letra maiúscula.";
            return $this;
        }

        if (! preg_match('/[^a-zA-Z0-9]/', $senha)) {
            $this->erros[$campo] = $mensagem ?? "A senha deve conter pelo menos um caractere especial (ex: !@#$%&*...).";
            return $this;
        }

        return $this;
    }

    public function cidadeEstado(string $campo, string $valor, ?string $mensagem = null): self
    {
        $padrao = "/^[\\p{L}\\p{M}][\\p{L}\\p{M}\\s.'’\\-]*,\\s*(AC|AL|AP|AM|BA|CE|DF|ES|GO|MA|MT|MS|MG|PA|PB|PR|PE|PI|RJ|RN|RS|RO|RR|SC|SP|SE|TO)$/iu";

        if ($valor !== '' && ! preg_match($padrao, $valor)) {
            $this->erros[$campo] = $mensagem ?? 'Informe somente cidade e UF no formato "Cidade, UF" (ex.: Foz do Iguaçu, PR).';
        }

        return $this;
    }

    public function dataValida(
        string $campo,
        ?string $data,
        ?string $mensagem = null
    ): self {
        if (empty($data)) {
            return $this;
        }

        $dataFormatada = DateTime::createFromFormat('!Y-m-d', $data);

        if (
            ! $dataFormatada ||
            $dataFormatada->format('Y-m-d') !== $data
        ) {
            $this->erros[$campo] = $mensagem ?? "Informe uma data válida.";

        }

        return $this;
    }

    public function idadeMinima(string $campo, ?string $dataNascimento, int $idadeMinima = 18, ?string $mensagem = null): self//regra de negocio para verificar se o usuario tem a idade minima para se cadastrar no sistema, 18 anos
    {
        if (empty($dataNascimento)) {
            return $this;
        }

        $nascimento = DateTime::createFromFormat('Y-m-d', $dataNascimento);

        if (! $nascimento) {
            $this->erros[$campo] = $mensagem ?? "Data de nascimento inválida";
            return $this;
        }

        $idade = $nascimento->diff(new DateTime())->y;

        if ($idade < $idadeMinima) {
            $this->erros[$campo] = $mensagem ?? "Não aceitamos menores de {$idadeMinima} anos";
        }

        return $this;
    }

    public function imagem(string $campo, ?string $valor, ?string $mensagem = null): self//verifica se o campo é um link de imagem válido
    {
        if (empty($valor)) {
            $this->erros[$campo] = $mensagem ?? "O link da imagem é obrigatório.";
            return $this;
        }

        if (! filter_var($valor, FILTER_VALIDATE_URL)) {
            $this->erros[$campo] = $mensagem ?? "Informe um link de imagem válido.";
        }

        return $this;
    }

    public function profissaoValida(
        string $campo,
        mixed $valor,
        ?string $mensagem = null
    ): self {

        if (
            $valor === null ||
            $valor === '' ||
            ! ctype_digit((string) $valor) ||
            (int) $valor <= 0
        ) {
            $this->erros[$campo] = $mensagem ?? "Selecione uma profissão válida.";
        }

        return $this;
    }

    public function temErros(): bool//verifica se existem erros de validação
    {
        return ! empty($this->erros);
    }

    public function getErros(): array//retorna os erros de validação
    {
        return $this->erros;
    }
}
