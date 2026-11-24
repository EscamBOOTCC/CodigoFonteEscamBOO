<?php

namespace App\Helpers;

class ServicoImagemUploader
{
    private ImagemUploader $uploader;

    public function __construct()
    {
        $this->uploader = new ImagemUploader();
    }

    public function armazenar(?array $arquivo): ?string
    {
        return $this->uploader->armazenar($arquivo, 'servicos');
    }

    public function removerSeArmazenada(?string $url): void
    {
        $this->uploader->removerSeArmazenada($url, 'servicos');
    }
}
