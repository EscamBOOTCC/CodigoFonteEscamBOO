<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\ImagemUploader;
use App\Helpers\Validador;
use App\Services\AdministradorService;
use App\Services\AcordoService;
use App\Services\TrabalhadorService;
use App\Services\DenunciaService;
use App\Services\PenalidadeService;
use PDOException;
use RuntimeException;
use Throwable;

class AdministradorController extends Controller
{
    private $administradorService;
    private $trabalhadorService;
    private $acordoService;
    private $denunciaService;
    private $penalidadeService;
    private ImagemUploader $imagemUploader;

    public function __construct()
    {
        $this->administradorService = new AdministradorService();
        $this->trabalhadorService    = new TrabalhadorService();
        $this->acordoService         = new AcordoService();
        $this->denunciaService       = new DenunciaService();
        $this->penalidadeService     = new PenalidadeService();
        $this->imagemUploader        = new ImagemUploader();
    }

    public function dashboard()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        $totalUsuarios             = count($this->trabalhadorService->listarTodos());
        $totalAdministradores      = count($this->administradorService->listarTodos());
        $totalAcordosAtivos        = $this->acordoService->contarAtivos();
        $totalAcordosCancelados    = $this->acordoService->contarCancelados();
        $totalDenunciasPendentes   = $this->denunciaService->contarPendentes();

        return $this->view('ADM/DashboardAdm', [
            'totalUsuarios'           => $totalUsuarios,
            'totalAdministradores'    => $totalAdministradores,
            'totalAcordosAtivos'      => $totalAcordosAtivos,
            'totalAcordosCancelados'  => $totalAcordosCancelados,
            'totalDenunciasPendentes' => $totalDenunciasPendentes,
        ]);
    }

    public function listarAcordosCancelados()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        return $this->view('ADM/acordosCancelados', [
            'acordos' => $this->acordoService->listarCanceladosParaAdmin(),
        ]);
    }

    public function cadastrarAdm()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        return $this->view('ADM/cadastrarADM');
    }

    public function salvarAdm()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/usuarios');
        }

        $adm = [
            'Nome'           => trim($_POST['nome'] ?? ''),
            'CPF'            => trim($_POST['cpf'] ?? ''),
            'Genero'         => $_POST['genero'] ?? null,
            'Email'          => trim($_POST['email'] ?? ''),
            'Senha'          => $_POST['senha'] ?? '',
            'DataNascimento' => $_POST['dataNascimento'] ?? null,
            'FotoPerfil'     => trim($_POST['fotoPerfil'] ?? ''),
            'Endereco'       => trim($_POST['endereco'] ?? ''),
        ];

        $confirmarSenha = $_POST['confirmarSenha'] ?? '';
        $arquivoFotoPerfil = $_FILES['arquivoFotoPerfil'] ?? null;
        $temUploadFotoPerfil = $arquivoFotoPerfil !== null
            && (int) ($arquivoFotoPerfil['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

        $fotoEnviada = null;
        try {
            $fotoEnviada = $this->imagemUploader->armazenar($arquivoFotoPerfil, 'perfis');
            if ($fotoEnviada !== null) {
                $adm['FotoPerfil'] = $fotoEnviada;
            }
        } catch (RuntimeException $erro) {
            return $this->view('ADM/cadastrarADM', [
                'erros' => ['fotoPerfil' => $erro->getMessage()],
                'usuario' => $adm,
            ]);
        }

        $validador = new Validador();

        $validador
            ->obrigatorio('nome', $adm['Nome'])
            ->obrigatorio('cpf', $adm['CPF'])
            ->cpf('cpf', $adm['CPF'])
            ->obrigatorio('email', $adm['Email'])
            ->email('email', $adm['Email'])
            ->obrigatorio('senha', $adm['Senha'])
            ->minLength('senha', $adm['Senha'], 6)
            ->senhaForte('senha', $adm['Senha'])
            ->obrigatorio('confirmarSenha', $confirmarSenha, 'A confirmação de senha é obrigatória')
            ->confirmarSenha('confirmarSenha', $adm['Senha'], $confirmarSenha, 'As senhas não coincidem')
            ->obrigatorio('dataNascimento', $adm['DataNascimento'])
            ->idadeMinima(
                'dataNascimento',
                $adm['DataNascimento'],
                18,
                'Não aceitamos menores de 18 anos'
            )
            ->maxLength('endereco', $adm['Endereco'], 255)
            ->obrigatorio('endereco', $adm['Endereco'])
            ->obrigatorio('genero', $adm['Genero']);

        if (! $temUploadFotoPerfil) {
            if (! empty($adm['FotoPerfil'])) {
                if (! $this->urlFotoPerfilValida($adm['FotoPerfil'])) {
                    $validador->imagem(
                        'fotoPerfil',
                        $adm['FotoPerfil'],
                        'Informe uma URL de imagem válida (HTTP ou HTTPS).'
                    );
                }
            } else {
                $adm['FotoPerfil'] = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=350&auto=format&fit=crop';
            }
        }

        if ($validador->temErros()) {
            return $this->view('ADM/cadastrarADM', [
                'erros'   => $validador->getErros(),
                'usuario' => $adm,
            ]);
        }

        if (! $temUploadFotoPerfil && $adm['FotoPerfil'] !== ''
            && ! $this->urlFotoPerfilValida($adm['FotoPerfil'])) {
            return $this->view('ADM/cadastrarADM', [
                'erros' => ['fotoPerfil' => 'A URL da foto deve usar HTTP ou HTTPS.'],
                'usuario' => $adm,
            ]);
        }

        try {
            $salvo = $this->administradorService->salvar($adm);
        } catch (PDOException $erro) {
            if ((string) ($erro->errorInfo[0] ?? $erro->getCode()) !== '23000'
                || (int) ($erro->errorInfo[1] ?? 0) !== 1062) {
                $this->imagemUploader->removerSeArmazenada($fotoEnviada, 'perfis');
                throw $erro;
            }

            return $this->view('ADM/cadastrarADM', [
                'erros' => ['cadastro' => 'Este CPF ou e-mail já está cadastrado.'],
                'usuario' => $adm,
            ]);
        } catch (Throwable $erro) {
            $this->imagemUploader->removerSeArmazenada($fotoEnviada, 'perfis');
            throw $erro;
        }

        if (! $salvo) {
            return $this->view('ADM/cadastrarADM', [
                'erros' => ['cadastro' => 'Não foi possível cadastrar o administrador. Tente novamente.'],
                'usuario' => $adm,
            ]);
        }

        return $this->redirect(URL_BASE . '/usuarios');
    }

    private function urlFotoPerfilValida(string $url): bool
    {
        $esquema = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return filter_var($url, FILTER_VALIDATE_URL) !== false
            && in_array($esquema, ['http', 'https'], true);
    }

    public function listarTodosUsuarios()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        $trabalhadores   = $this->trabalhadorService->listarTodos();
        $administradores = $this->administradorService->listarTodos();

        $usuarios = [];

        foreach ($trabalhadores as $trabalhador) {
            $usuarios[] = [
                'IdUsuario'  => $trabalhador['IdTrabalhador'],
                'Nome'       => $trabalhador['Nome'],
                'Email'      => $trabalhador['Email'],
                'Genero'     => $trabalhador['Genero'],
                'FotoPerfil' => $trabalhador['FotoPerfil'],
                'Ativo'      => $trabalhador['Ativo'],
                'Tipo'       => 'trabalhador',
            ];
        }

        foreach ($administradores as $adm) {
            $usuarios[] = [
                'IdUsuario'  => $adm['IdAdm'],
                'Nome'       => $adm['Nome'],
                'Email'      => $adm['Email'],
                'Genero'     => $adm['Genero'],
                'FotoPerfil' => $adm['FotoPerfil'],
                'Ativo'      => $adm['Ativo'],
                'Tipo'       => 'adm',
            ];
        }

        return $this->view('Usuarios/listarTodos', [
            'usuarios' => $usuarios,
        ]);
    }

    public function editar()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        $idUsuario = $_GET['idUsuario'] ?? null;
        $tipo      = $_GET['tipo'] ?? null;

        if (! $idUsuario || ! $tipo) {
            return $this->redirect(URL_BASE . '/usuarios');
        }

        $usuario = $tipo === 'adm'
            ? $this->administradorService->buscarPorId((int) $idUsuario)
            : $this->trabalhadorService->buscarPorId((int) $idUsuario);

        if (! $usuario) {
            return $this->redirect(URL_BASE . '/usuarios');
        }

        $usuario['IdUsuario'] = $tipo === 'adm' ? $usuario['IdAdm'] : $usuario['IdTrabalhador'];
        $usuario['Tipo']      = $tipo;

        return $this->view('Usuarios/editarUsuario', [
            'usuario' => $usuario,
        ]);
    }

    public function atualizar()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/usuarios');
        }

        $tipo = $_POST['tipo'] ?? null;

        $usuario = [
            'Nome'           => trim($_POST['nome'] ?? ''),
            'CPF'            => trim($_POST['cpf'] ?? ''),
            'Genero'         => $_POST['genero'] ?? null,
            'Email'          => trim($_POST['email'] ?? ''),
            'DataNascimento' => $_POST['dataNascimento'] ?? null,
            'FotoPerfil'     => trim($_POST['fotoPerfil'] ?? ''),
            'Endereco'       => trim($_POST['endereco'] ?? ''),
        ];

        $validador = new Validador();

        $validador
            ->obrigatorio('nome', $usuario['Nome'])
            ->obrigatorio('cpf', $usuario['CPF'])
            ->cpf('cpf', $usuario['CPF'])
            ->obrigatorio('email', $usuario['Email'])
            ->email('email', $usuario['Email'])
            ->obrigatorio('dataNascimento', $usuario['DataNascimento'])
            ->idadeMinima(
                'dataNascimento',
                $usuario['DataNascimento'],
                18,
                'Não aceitamos menores de 18 anos'
            )
            ->maxLength('endereco', $usuario['Endereco'], 255)
            ->obrigatorio('endereco', $usuario['Endereco'])
            ->obrigatorio('genero', $usuario['Genero'])
            ->imagem('fotoPerfil', $usuario['FotoPerfil']);

        if ($validador->temErros()) {
            $usuario['IdUsuario'] = $_POST['idUsuario'] ?? null;
            $usuario['Tipo']      = $tipo;

            return $this->view('Usuarios/editarUsuario', [
                'erros'   => $validador->getErros(),
                'usuario' => $usuario,
            ]);
        }

        if ($tipo === 'adm') {
            $usuario['IdAdm'] = $_POST['idUsuario'] ?? null;
            $this->administradorService->atualizar($usuario);
        } else {
            $usuario['IdTrabalhador'] = $_POST['idUsuario'] ?? null;
            $this->trabalhadorService->atualizar($usuario);
        }

        return $this->redirect(URL_BASE . '/usuarios');
    }

    public function alterarStatus()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/usuarios');
        }

        $idUsuario     = $_POST['idUsuario'] ?? null;
        $tipo          = $_POST['tipo'] ?? null;
        $status        = strtoupper(trim($_POST['ativo'] ?? ''));
        $diasSuspensao = !empty($_POST['diasSuspensao']) ? (int) $_POST['diasSuspensao'] : 7;
        $motivo        = trim($_POST['motivo'] ?? '');

        $statusValidos = ['ATIVO', 'INATIVO', 'SUSPENSO', 'BANIDO'];

        if (! $idUsuario || ! $tipo || ! in_array($status, $statusValidos, true)) {
            return $this->redirect(URL_BASE . '/usuarios');
        }

        $dataExpiraStatus = null;
        if ($status === 'SUSPENSO') {
            $dataExpiraStatus = date('Y-m-d H:i:s', strtotime("+{$diasSuspensao} days"));
        }

        if ($tipo === 'adm') {
            $this->administradorService->alterarStatus((int) $idUsuario, $status, $dataExpiraStatus);
        } else {
            $this->trabalhadorService->alterarStatus((int) $idUsuario, $status, $dataExpiraStatus);

            // Se for suspensão ou banimento e tiver motivo, registra penalidade
            if (in_array($status, ['SUSPENSO', 'BANIDO'], true) && !empty($motivo)) {
                $idAdm = (int) ($_SESSION['usuario_logado']['IdAdm'] ?? 0);
                $tipoPenalidade = $status === 'SUSPENSO'
                    ? PenalidadeService::TIPO_SUSPENSAO
                    : PenalidadeService::TIPO_BANIMENTO;
                $this->penalidadeService->aplicarPenalidade(
                    $idAdm,
                    (int) $idUsuario,
                    $motivo,
                    $tipoPenalidade,
                    $diasSuspensao
                );
            }
        }

        $_SESSION['sucesso'] = 'Status do usuário atualizado com sucesso!';
        return $this->redirect(URL_BASE . '/usuarios');
    }

    public function listarDenuncias()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        $statusFiltro = isset($_GET['status']) && $_GET['status'] !== '' ? (int) $_GET['status'] : null;
        $denuncias    = $this->denunciaService->listarDenuncias($statusFiltro);
        $penalidades  = $this->penalidadeService->listarTodas();

        $sucesso = $_SESSION['sucesso'] ?? null;
        $erro    = $_SESSION['erro'] ?? null;
        unset($_SESSION['sucesso'], $_SESSION['erro']);

        return $this->view('ADM/denuncias', [
            'denuncias'    => $denuncias,
            'penalidades'  => $penalidades,
            'statusFiltro' => $statusFiltro,
            'sucesso'      => $sucesso,
            'erro'         => $erro,
        ]);
    }

    public function avaliarDenuncia()
    {
        $this->autenticacaoRequired();
        $this->adminRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect(URL_BASE . '/adm/denuncias');
        }

        $idDenuncia = (int) ($_POST['idDenuncia'] ?? 0);
        $acao       = trim($_POST['acao'] ?? ''); // 'arquivar' ou 'penalizar'

        if ($idDenuncia <= 0) {
            $_SESSION['erro'] = 'Denúncia inválida.';
            return $this->redirect(URL_BASE . '/adm/denuncias');
        }

        if ($acao === 'arquivar') {
            $this->denunciaService->arquivarDenuncia($idDenuncia);
            $_SESSION['sucesso'] = 'Denúncia arquivada como improcedente.';
            return $this->redirect(URL_BASE . '/adm/denuncias');
        }

        return $this->redirect(URL_BASE . '/adm/denuncias');
    }


}
