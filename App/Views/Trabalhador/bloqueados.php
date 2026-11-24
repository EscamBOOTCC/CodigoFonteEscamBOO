<?php
$usuarioLogado = $usuarioLogado ?? $_SESSION['usuario_logado'] ?? [];
$nomeUsuarioLogado = $usuarioLogado['Nome'] ?? 'Usuário';
$fotoPerfilLogado = !empty($usuarioLogado['FotoPerfil'])
    ? $usuarioLogado['FotoPerfil']
    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop';
$bloqueados = $bloqueados ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários Bloqueados - EscamBOO</title>

    <link rel="icon" type="image/png" href="<?php echo URL_BASE ?>/assets/logoEscamboo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --escamboo-vinho: #590b1c;
            --escamboo-vinho-escuro: #3f060f;
            --escamboo-fundo: #ded5c2;
            --escamboo-card-bg: #fcf9f2;
            --escamboo-ocre: #ba8238;
            --escamboo-texto-escuro: #2b1f1d;
            --escamboo-texto-muted: #73675a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--escamboo-fundo);
            color: var(--escamboo-texto-escuro);
            min-height: 100vh;
        }

        .escamboo-container {
            width: 94%;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }

        .escamboo-navbar {
            background-color: var(--escamboo-vinho);
            padding: 0.85rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .escamboo-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .escamboo-brand-mark {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.75);
            background-color: #ffffff;
        }

        .escamboo-logo {
            font-weight: 800;
            font-size: 2rem;
            color: #ffffff;
            letter-spacing: -0.5px;
            text-decoration: none;
        }

        .nav-avatar-mini {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
        }

        .card-custom {
            background-color: var(--escamboo-card-bg);
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            border: none;
            overflow: hidden;
        }

        .card-header-custom {
            background-color: var(--escamboo-vinho);
            color: #ffffff;
            padding: 20px 28px;
        }

        .btn-principal {
            background-color: var(--escamboo-vinho);
            border-color: var(--escamboo-vinho);
            color: #ffffff;
            border-radius: 20px;
            padding: 6px 18px;
            font-weight: 600;
        }

        .btn-principal:hover {
            background-color: var(--escamboo-vinho-escuro);
            color: #ffffff;
        }

        .avatar-user {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--escamboo-ocre);
        }
    </style>
</head>

<body>
    <nav class="escamboo-navbar">
        <div class="escamboo-container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="escamboo-brand">
                    <img src="<?php echo URL_BASE ?>/assets/logoEscamboo.png" alt="EscamBOO" class="escamboo-brand-mark">
                    <span class="escamboo-logo">EscamBOO</span>
                </a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="text-white text-decoration-none fw-medium fs-6 d-none d-sm-inline">
                    <i class="bi bi-house-door-fill me-1"></i> Início
                </a>
                <a href="<?php echo URL_BASE ?>/matches" class="text-white text-decoration-none fw-medium fs-6 d-none d-sm-inline">
                    <i class="bi bi-fire me-1"></i> Matches
                </a>
                <a href="<?php echo URL_BASE ?>/acordos" class="text-white text-decoration-none fw-medium fs-6 d-none d-sm-inline">
                    <i class="bi bi-file-earmark-check-fill me-1"></i> Acordos
                </a>
                <a href="<?php echo URL_BASE ?>/trabalhador/perfil" class="d-flex align-items-center text-white text-decoration-none gap-2">
                    <span class="fw-semibold d-none d-md-inline"><?php echo htmlspecialchars($nomeUsuarioLogado) ?></span>
                    <img src="<?php echo htmlspecialchars($fotoPerfilLogado) ?>" alt="Perfil" class="nav-avatar-mini">
                </a>
            </div>
        </div>
    </nav>

    <main class="escamboo-container py-4">
        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> <?php echo htmlspecialchars($sucesso) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> <?php echo htmlspecialchars($erro) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="color: var(--escamboo-vinho);"><i class="bi bi-person-slash me-2"></i>Usuários Bloqueados</h2>
            <a href="<?php echo URL_BASE ?>/trabalhador/perfil" class="btn btn-outline-dark rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Voltar ao Perfil
            </a>
        </div>

        <div class="card-custom">
            <div class="card-header-custom">
                <small class="text-white-50">Usuários bloqueados não podem enviar mensagens, propor trocas nem visualizar seus serviços.</small>
            </div>
            <div class="p-4">
                <?php if (empty($bloqueados)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-check2-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 fw-bold">Nenhum usuário bloqueado</h5>
                        <p class="mb-0">Você não possui nenhum trabalhador bloqueado atualmente.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Usuário</th>
                                    <th>Profissão / Área</th>
                                    <th>Data do Bloqueio</th>
                                    <th class="text-center">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bloqueados as $b): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo (int) $b['IdTrabalhador'] ?>" title="Ver perfil de <?php echo htmlspecialchars($b['Nome']) ?>">
                                                    <img src="<?php echo htmlspecialchars(!empty($b['FotoPerfil']) ? $b['FotoPerfil'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=150&auto=format&fit=crop') ?>" class="avatar-user" alt="Foto de <?php echo htmlspecialchars($b['Nome']) ?>">
                                                </a>
                                                <div>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($b['Nome']) ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($b['Endereco'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill" style="background-color: var(--escamboo-ocre); color: #fff;">
                                                <?php echo htmlspecialchars($b['NomeProfissao'] ?? 'Geral') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo !empty($b['DataBloqueio']) ? date('d/m/Y', strtotime($b['DataBloqueio'])) : 'Recente' ?>
                                        </td>
                                        <td class="text-center">
                                            <form action="<?php echo URL_BASE ?>/trabalhador/desbloquear" method="POST" onsubmit="return confirm('Deseja realmente desbloquear <?php echo htmlspecialchars(addslashes($b['Nome'])) ?>?');">
                                                <input type="hidden" name="idTrabalhador" value="<?php echo (int)$b['IdTrabalhador'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                    <i class="bi bi-unlock-fill me-1"></i> Desbloquear
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
