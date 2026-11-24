<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\ServicoImagemUploader;
use App\Helpers\Validador;
use App\Services\AcordoService;
use App\Services\GrandesAreasService;
use App\Services\MatchService;
use App\Services\ServicoService;

class ServicoController extends Controller
{
    private ServicoService $servicoService;
    private MatchService $matchService;
    private AcordoService $acordoService;
    private ServicoImagemUploader $imagemUploader;

    public function __construct()
    {
        $this->servicoService = new ServicoService();
        $this->matchService   = new MatchService();
        $this->acordoService  = new AcordoService();
        $this->imagemUploader = new ServicoImagemUploader();
    }

    public function listarServicos()
    {
        $this->autenticacaoRequired();

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);

        $grandesAreasService = new GrandesAreasService();
        $grandesAreas        = $grandesAreasService->listarTodos();

        $servicosDisponiveis = $this->servicoService->listarDisponiveis($idTrabalhadorLogado);
        $meusServicos        = $this->servicoService->listarMeusServicos($idTrabalhadorLogado);
        $profissoes          = $this->servicoService->listarProfissoes();
        $propostasRecebidasPendentes = $this->matchService->contarPropostasRecebidasPendentes($idTrabalhadorLogado);

        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro    = $_SESSION['erro'] ?? null;
        $limparRascunhoServico = $_SESSION['limparRascunhoServico'] ?? false;
        unset($_SESSION['sucesso'], $_SESSION['erro'], $_SESSION['limparRascunhoServico']);

        return $this->view('Servicos/listarServicos', [
            'servicosDisponiveis' => $servicosDisponiveis,
            'meusServicos'        => $meusServicos,
            'profissoes'          => $profissoes,
            'grandesAreas'        => $grandesAreas,
            'propostasRecebidasPendentes' => $propostasRecebidasPendentes,
            'sucesso'             => $sucesso,
            'erro'                => $erro,
            'limparRascunhoServico' => $limparRascunhoServico,
        ]);
    }

    public function cadastrarServico()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $nome                = trim($_POST['nome'] ?? '');
        $idProfissao         = (int) ($_POST['idProfissao'] ?? 0);
        $descricao           = trim($_POST['descricao'] ?? '');

        $validador = new Validador();

        $validador
            ->obrigatorio('nome', $nome, 'O título do serviço é obrigatório.')
            ->maxLength('nome', $nome, 100, 'O título do serviço deve ter no máximo 100 caracteres.')
            ->obrigatorio('idProfissao', $idProfissao, 'Selecione uma profissão.')
            ->profissaoValida('idProfissao', $idProfissao, 'Selecione uma profissão válida.')
            ->maxLength('descricao', $descricao, 500, 'A descrição deve ter no máximo 500 caracteres.')
            ->obrigatorio('fotoServico', $_POST['fotoServico'] ?? '', 'Envie ou informe a URL da primeira imagem do serviço.');

        if ($validador->temErros()) {
            $_SESSION['erro'] = implode(' ', $validador->getErros());
            return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
        }

        try {
            $imagens = $this->processarImagensServico([], true);
        } catch (\RuntimeException $erro) {
            $_SESSION['erro'] = $erro->getMessage();
            return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
        }

        try {
            $resultado = $this->servicoService->cadastrar([
                'IdTrabalhador' => $idTrabalhadorLogado,
                'IdProfissao'   => $idProfissao,
                'Nome'          => $nome,
                'Descricao'     => $descricao,
                'FotoServico'   => $imagens['FotoServico'],
                'FotoServico2'  => $imagens['FotoServico2'],
            ]);
        } catch (\Throwable $erro) {
            $this->removerImagensNovas($imagens);
            throw $erro;
        }

        if ($resultado) {
            $_SESSION['sucesso'] = 'Serviço cadastrado com sucesso!';
            $_SESSION['limparRascunhoServico'] = true;
        } else {
            $this->removerImagensNovas($imagens);
            $_SESSION['erro'] = 'Não foi possível cadastrar o serviço. Verifique os dados e tente novamente.';
            $_SESSION['limparRascunhoServico'] = false;
        }

        return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
    }

    public function excluirServico()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idServico           = (int) ($_POST['idServico'] ?? 0);

        if ($idServico <= 0) {
            $_SESSION['erro'] = 'Serviço inválido para exclusão.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
        }

        $servico = $this->servicoService->buscarDoTrabalhador($idServico, $idTrabalhadorLogado);

        if ($servico === null) {
            $_SESSION['erro'] = 'Serviço não encontrado ou você não tem permissão para excluí-lo.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
        }

        // Bloqueia exclusão se o serviço estiver vinculado a um acordo em andamento.
        if ($this->acordoService->servicoTemAcordoAtivo($idServico)) {
            $_SESSION['erro'] = 'Não é possível excluir este serviço pois ele está vinculado a um acordo em andamento. Finalize ou cancele o acordo antes de excluí-lo.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
        }

        $excluiu = $this->servicoService->excluir($idServico, $idTrabalhadorLogado);

        if ($excluiu) {
            $this->imagemUploader->removerSeArmazenada($servico['FotoServico'] ?? null);
            $this->imagemUploader->removerSeArmazenada($servico['FotoServico2'] ?? null);
            $_SESSION['sucesso'] = 'Serviço excluído com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao excluir o serviço ou serviço não encontrado.';
        }

        return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
    }

    public function editarServico()
    {
        $this->autenticacaoRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/trabalhador/servicos');
        }

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);
        $idServico           = (int) ($_POST['idServico'] ?? 0);
        $nome                = trim($_POST['nome'] ?? '');
        $idProfissao         = (int) ($_POST['idProfissao'] ?? 0);
        $descricao           = trim($_POST['descricao'] ?? '');

        $validador = new Validador();

        $validador
            ->obrigatorio('idServico', $idServico, 'Serviço inválido.')
            ->obrigatorio('nome', $nome, 'O título do serviço é obrigatório.')
            ->maxLength('nome', $nome, 100, 'O título do serviço deve ter no máximo 100 caracteres.')
            ->obrigatorio('idProfissao', $idProfissao, 'Selecione uma profissão.')
            ->maxLength('descricao', $descricao, 500, 'A descrição deve ter no máximo 500 caracteres.');

        if ($validador->temErros()) {
            $_SESSION['erro'] = implode(' ', $validador->getErros());
            return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
        }

        $servicoAtual = $this->servicoService->buscarDoTrabalhador($idServico, $idTrabalhadorLogado);
        if ($servicoAtual === null) {
            $_SESSION['erro'] = 'Serviço não encontrado ou você não tem permissão para editá-lo.';
            return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
        }

        try {
            $imagens = $this->processarImagensServico($servicoAtual, empty($servicoAtual['FotoServico']));
        } catch (\RuntimeException $erro) {
            $_SESSION['erro'] = $erro->getMessage();
            return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
        }

        try {
            $resultado = $this->servicoService->editarServico($idServico, $idTrabalhadorLogado, [
                'IdTrabalhador' => $idTrabalhadorLogado,
                'IdProfissao'   => $idProfissao,
                'Nome'          => $nome,
                'Descricao'     => $descricao,
                'FotoServico'   => $imagens['FotoServico'],
                'FotoServico2'  => $imagens['FotoServico2'],
            ]);
        } catch (\Throwable $erro) {
            $this->removerImagensNovas($imagens, $servicoAtual);
            throw $erro;
        }

        if ($resultado) {
            foreach (['FotoServico', 'FotoServico2'] as $campoImagem) {
                if (($servicoAtual[$campoImagem] ?? null) !== $imagens[$campoImagem]) {
                    $this->imagemUploader->removerSeArmazenada($servicoAtual[$campoImagem] ?? null);
                }
            }
            $_SESSION['sucesso'] = 'Serviço editado com sucesso!';
        } else {
            $this->removerImagensNovas($imagens, $servicoAtual);
            $_SESSION['erro'] = 'Não foi possível editar o serviço. Verifique os dados e tente novamente.';
        }

        return $this->redirect(URL_BASE . '/trabalhador/servicos?aba=gerenciar');
    }

    public function filtrarServico()
    {
        $this->autenticacaoRequired();

        $idTrabalhadorLogado = (int) ($_SESSION['usuario_logado']['IdTrabalhador'] ?? 0);

        $filtros = [
            'busca'        => trim($_GET['busca'] ?? ''),
            'idGrandeArea' => (int) ($_GET['idGrandeArea'] ?? 0),
            'idProfissao'  => (int) ($_GET['idProfissao'] ?? 0),
            'pesquisaAreaAtuacao' => trim($_GET['pesquisaAreaAtuacao'] ?? ''),
            'localizacao'  => trim($_GET['localizacao'] ?? '')
        ];

        $grandesAreasService = new GrandesAreasService();
        $grandesAreas        = $grandesAreasService->listarTodos();

        $servicosDisponiveis = $this->servicoService->filtrarServicos($filtros, $idTrabalhadorLogado);
        $meusServicos        = $this->servicoService->listarMeusServicos($idTrabalhadorLogado);
        $profissoes          = $this->servicoService->listarProfissoes();
        $propostasRecebidasPendentes = $this->matchService->contarPropostasRecebidasPendentes($idTrabalhadorLogado);

        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro    = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);

        return $this->view('Servicos/listarServicos', [
            'servicosDisponiveis' => $servicosDisponiveis,
            'meusServicos'        => $meusServicos,
            'profissoes'          => $profissoes,
            'grandesAreas'        => $grandesAreas,
            'propostasRecebidasPendentes' => $propostasRecebidasPendentes,
            'filtros'             => $filtros,
            'sucesso'             => $sucesso,
            'erro'                => $erro,
        ]);
    }

    private function processarImagensServico(array $atuais, bool $exigeImagemPrincipal = false): array
    {
        $campos = [
            'fotoServico' => 'FotoServico',
            'fotoServico2' => 'FotoServico2',
        ];
        $imagens = [];
        $imagensNovas = [];

        try {
            foreach ($campos as $campoFormulario => $campoBanco) {
                $valorUrl = $_POST[$campoFormulario] ?? '';
                if (! is_string($valorUrl)) {
                    throw new \RuntimeException('O endereço da imagem informado é inválido.');
                }
                $url = trim($valorUrl);
                $campoArquivo = 'arquivo' . ucfirst($campoFormulario);
                $arquivo = $_FILES[$campoArquivo] ?? null;
                $arquivoEnviado = $arquivo !== null
                    && (int) ($arquivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

                if ($arquivoEnviado) {
                    $imagem = $this->imagemUploader->armazenar($arquivo);
                    $imagensNovas[] = $imagem;
                } elseif ($url !== '') {
                    $esquema = strtolower((string) parse_url($url, PHP_URL_SCHEME));
                    if (! filter_var($url, FILTER_VALIDATE_URL) || ! in_array($esquema, ['http', 'https'], true)) {
                        throw new \RuntimeException('Informe uma URL de imagem válida (HTTP ou HTTPS).');
                    }
                    $imagem = $url;
                } else {
                    $imagem = $atuais[$campoBanco] ?? null;
                }

                if ($campoBanco === 'FotoServico' && $exigeImagemPrincipal && empty($imagem)) {
                    throw new \RuntimeException('Envie ou informe a URL da primeira imagem do serviço.');
                }

                $imagens[$campoBanco] = $imagem;
            }
        } catch (\Throwable $erro) {
            foreach ($imagensNovas as $imagemNova) {
                $this->imagemUploader->removerSeArmazenada($imagemNova);
            }
            throw $erro;
        }

        return $imagens;
    }

    private function removerImagensNovas(array $imagens, array $imagensAnteriores = []): void
    {
        foreach (['FotoServico', 'FotoServico2'] as $campoImagem) {
            $imagem = $imagens[$campoImagem] ?? null;
            if ($imagem !== ($imagensAnteriores[$campoImagem] ?? null)) {
                $this->imagemUploader->removerSeArmazenada($imagem);
            }
        }
    }
}
