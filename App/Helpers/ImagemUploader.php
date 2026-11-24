<?php

namespace App\Helpers;

use RuntimeException;

class ImagemUploader
{
    private const MAX_BYTES = 2097152;
    private const MAX_PIXELS = 20000000;
    private const DIRETORIOS = ['perfis', 'servicos'];
    private const MIME_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    public function armazenar(?array $arquivo, string $categoria): ?string
    {
        $this->validarCategoria($categoria);

        if ($arquivo === null || (int) ($arquivo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $erroUpload = (int) ($arquivo['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($erroUpload !== UPLOAD_ERR_OK) {
            throw new RuntimeException($this->mensagemErroUpload($erroUpload));
        }

        $temporario = $arquivo['tmp_name'] ?? '';
        $tamanho = (int) ($arquivo['size'] ?? 0);

        if ($temporario === '' || ! is_uploaded_file($temporario)) {
            throw new RuntimeException('O arquivo enviado não pôde ser validado.');
        }
        if ($tamanho <= 0 || $tamanho > self::MAX_BYTES) {
            throw new RuntimeException('Cada imagem deve ter no máximo 2 MB.');
        }

        $informacoesImagem = @getimagesize($temporario);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($temporario);

        if (
            $informacoesImagem === false ||
            ! isset(self::MIME_EXTENSIONS[$mime]) ||
            ($informacoesImagem['mime'] ?? null) !== $mime
        ) {
            throw new RuntimeException('Envie uma imagem JPG, PNG, WEBP ou GIF válida.');
        }

        $largura = (int) ($informacoesImagem[0] ?? 0);
        $altura = (int) ($informacoesImagem[1] ?? 0);
        if ($largura <= 0 || $altura <= 0 || $largura * $altura > self::MAX_PIXELS) {
            throw new RuntimeException('A resolução da imagem é inválida ou muito grande.');
        }

        $diretorio = $this->caminhoDiretorio($categoria);
        if (! is_dir($diretorio) && ! mkdir($diretorio, 0755, true) && ! is_dir($diretorio)) {
            throw new RuntimeException('Não foi possível preparar o armazenamento das imagens.');
        }

        $nomeArquivo = bin2hex(random_bytes(16)) . '.' . self::MIME_EXTENSIONS[$mime];
        if (! move_uploaded_file($temporario, $diretorio . DIRECTORY_SEPARATOR . $nomeArquivo)) {
            throw new RuntimeException('Não foi possível salvar a imagem enviada.');
        }

        return rtrim(URL_BASE, '/') . '/uploads/' . $categoria . '/' . $nomeArquivo;
    }

    public function removerSeArmazenada(?string $url, string $categoria): void
    {
        $this->validarCategoria($categoria);
        if ($url === null || $url === '') {
            return;
        }

        $caminhoUrl = parse_url($url, PHP_URL_PATH);
        $hostUrl = parse_url($url, PHP_URL_HOST);
        $hostBase = parse_url(URL_BASE, PHP_URL_HOST);
        $prefixo = '/uploads/' . $categoria . '/';
        if (
            ! is_string($caminhoUrl) ||
            strpos($caminhoUrl, $prefixo) !== 0 ||
            ! is_string($hostUrl) ||
            ! is_string($hostBase) ||
            strcasecmp($hostUrl, $hostBase) !== 0
        ) {
            return;
        }

        $nomeArquivo = substr($caminhoUrl, strlen($prefixo));
        if (! preg_match('/\A[a-f0-9]{32}\.(?:jpg|png|webp|gif)\z/', $nomeArquivo)) {
            return;
        }

        $caminhoArquivo = $this->caminhoDiretorio($categoria) . DIRECTORY_SEPARATOR . $nomeArquivo;
        if (is_file($caminhoArquivo) && ! unlink($caminhoArquivo)) {
            error_log('Não foi possível remover a imagem enviada: ' . $caminhoArquivo);
        }
    }

    private function caminhoDiretorio(string $categoria): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'Public'
            . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $categoria;
    }

    private function validarCategoria(string $categoria): void
    {
        if (! in_array($categoria, self::DIRETORIOS, true)) {
            throw new RuntimeException('Categoria de imagem inválida.');
        }
    }

    private function mensagemErroUpload(int $codigo): string
    {
        if ($codigo === UPLOAD_ERR_INI_SIZE || $codigo === UPLOAD_ERR_FORM_SIZE) {
            return 'Cada imagem deve ter no máximo 2 MB.';
        }
        if ($codigo === UPLOAD_ERR_PARTIAL) {
            return 'O envio da imagem foi interrompido. Tente novamente.';
        }
        if ($codigo === UPLOAD_ERR_NO_TMP_DIR || $codigo === UPLOAD_ERR_CANT_WRITE) {
            return 'O servidor não conseguiu armazenar a imagem.';
        }

        return 'Não foi possível receber a imagem enviada.';
    }
}
