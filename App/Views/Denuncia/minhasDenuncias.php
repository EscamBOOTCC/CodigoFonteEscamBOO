<?php
$denuncias = $denuncias ?? [];
$usuarioLogado = $_SESSION['usuario_logado'] ?? [];
$nomeUsuarioLogado = $usuarioLogado['Nome'] ?? 'Usuário';
$fotoPerfilLogado = ! empty($usuarioLogado['FotoPerfil'])
    ? $usuarioLogado['FotoPerfil']
    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Denúncias - EscamBOO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #ded5c2;
            color: #2b1f1d;
            font-family: 'Poppins', sans-serif;
        }

        .navbar-brand img {
            object-fit: contain;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark mb-4" style="background-color: #590b1c;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="<?= URL_BASE ?>/trabalhador/servicos" class="navbar-brand fw-bold">
                <img src="<?= URL_BASE ?>/assets/logoEscamboo.png" alt="EscamBOO" width="36" height="36" class="me-2">
                EscamBOO
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= URL_BASE ?>/trabalhador/servicos" class="text-white text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Voltar aos serviços
                </a>
                <a href="<?= URL_BASE ?>/trabalhador/perfil" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Meu perfil">
                    <span class="d-none d-md-inline"><?= htmlspecialchars($nomeUsuarioLogado) ?></span>
                    <img src="<?= htmlspecialchars($fotoPerfilLogado) ?>" alt="Perfil" class="rounded-circle" width="38" height="38" style="object-fit: cover;">
                </a>
            </div>
        </div>
    </nav>

    <main class="container py-3 pb-5">
        <?php if (! empty($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                <?= htmlspecialchars($sucesso) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <?php if (! empty($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                <?= htmlspecialchars($erro) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <h1 class="h2 fw-bold" style="color: #590b1c;">Minhas Denúncias</h1>
            <p class="text-muted mb-0">Acompanhe as denúncias que você registrou e o andamento da análise.</p>
        </div>

        <?php if (empty($denuncias)): ?>
            <section class="bg-white rounded-4 shadow-sm p-5 text-center">
                <i class="bi bi-shield-check text-muted" style="font-size: 3rem;" aria-hidden="true"></i>
                <h2 class="h5 fw-bold mt-3">Você ainda não registrou denúncias</h2>
                <p class="text-muted mb-0">Quando você enviar uma denúncia, ela aparecerá nesta página.</p>
            </section>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($denuncias as $denuncia): ?>
                    <?php
                    $status = (int) ($denuncia['Status'] ?? 0);
                    $statusTexto = match ($status) {
                        1 => 'Procedente',
                        2 => 'Improcedente (arquivada)',
                        default => 'Em análise',
                    };
                    $statusClasse = match ($status) {
                        1 => 'text-bg-success',
                        2 => 'text-bg-secondary',
                        default => 'text-bg-warning',
                    };
                    ?>
                    <article class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                <div>
                                    <span class="text-muted small">Denúncia #<?= (int) $denuncia['IdDenuncia'] ?></span>
                                    <h2 class="h5 fw-bold mb-0 mt-1">Sobre <?= htmlspecialchars($denuncia['NomeDenunciado'] ?? 'usuário') ?></h2>
                                </div>
                                <span class="badge rounded-pill <?= $statusClasse ?> px-3 py-2"><?= $statusTexto ?></span>
                            </div>
                            <p class="fw-semibold text-danger mb-1"><?= htmlspecialchars($denuncia['Motivo'] ?? '') ?></p>
                            <?php if (! empty($denuncia['Descricao'])): ?>
                                <p class="text-muted mb-3"><?= nl2br(htmlspecialchars($denuncia['Descricao'])) ?></p>
                            <?php endif; ?>
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                                Registrada em <?= ! empty($denuncia['Data']) ? date('d/m/Y', strtotime($denuncia['Data'])) : 'data não informada' ?>
                            </small>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
