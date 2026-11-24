<?php
$usuarioLogado = $usuarioLogado ?? $_SESSION['usuario_logado'] ?? [];
$fotoPerfilLogado = !empty($usuarioLogado['FotoPerfil']) 
    ? $usuarioLogado['FotoPerfil'] 
    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop';

$acordos = $acordos ?? [];
$statusFiltro = $statusFiltro ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Acordos - EscamBOO</title>

    <link rel="icon" type="image/png" href="<?php echo URL_BASE ?>/assets/logoEscamboo.png">
    <link rel="apple-touch-icon" href="<?php echo URL_BASE ?>/assets/logoEscamboo.png">
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
            max-width: 1200px;
            margin: 0 auto;
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .escamboo-logo {
            font-weight: 800;
            font-size: 2rem;
            color: #ffffff;
            text-decoration: none;
        }

        .nav-avatar-mini {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
        }

        .card-acordo {
            background-color: var(--escamboo-card-bg);
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
        }

        .card-acordo:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        .nav-pills-escamboo {
            background-color: #cbbeaa;
            padding: 6px;
            border-radius: 50px;
            display: inline-flex;
            gap: 8px;
        }

        .nav-pills-escamboo .nav-link {
            border-radius: 50px;
            padding: 9px 20px;
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--escamboo-texto-escuro);
            border: none;
            text-decoration: none;
        }

        .nav-pills-escamboo .nav-link.active {
            background-color: var(--escamboo-vinho);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(89, 11, 28, 0.35);
        }
    </style>
</head>

<body>

    <nav class="escamboo-navbar mb-4">
        <div class="escamboo-container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="<?= URL_BASE ?>/trabalhador/servicos" class="escamboo-brand">
                    <img src="<?= URL_BASE ?>/assets/logoEscamboo.png" alt="EscamBOO" class="escamboo-brand-mark">
                    <span class="escamboo-logo">EscamBOO</span>
                </a>
                <span class="badge bg-light text-dark rounded-pill px-3 py-1 d-none d-md-inline-block">Central de Acordos</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= URL_BASE ?>/trabalhador/servicos" class="text-white text-decoration-none fw-medium">
                    <i class="bi bi-house-door-fill me-1"></i> Início
                </a>
                <a href="<?= URL_BASE ?>/matches" class="text-white text-decoration-none fw-medium">
                    <i class="bi bi-fire me-1"></i> Matches
                </a>
                <a href="<?= URL_BASE ?>/trabalhador/perfil">
                    <img src="<?= htmlspecialchars($fotoPerfilLogado) ?>" alt="Perfil" class="nav-avatar-mini">
                </a>
            </div>
        </div>
    </nav>

    <main class="escamboo-container py-3 mb-5">

        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> <?= htmlspecialchars($sucesso) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> <?= htmlspecialchars($erro) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1" style="color: var(--escamboo-vinho);">Acordos de Escambo</h1>
                <p class="text-muted mb-0">Acompanhe a realização dos serviços combinados até a conclusão da troca.</p>
            </div>
            <a href="<?= URL_BASE ?>/matches" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">
                <i class="bi bi-fire me-1"></i> Ver Propostas de Match
            </a>
        </div>

        
        <div class="nav-pills-escamboo mb-4">
            <a href="<?= URL_BASE ?>/acordos" class="nav-link <?= $statusFiltro === null ? 'active' : '' ?>">
                Todos (<?= count($acordos) ?>)
            </a>
            <a href="<?= URL_BASE ?>/acordos?status=0" class="nav-link <?= $statusFiltro === 0 ? 'active' : '' ?>">
                <i class="bi bi-hourglass-split me-1"></i> Em Andamento
            </a>
            <a href="<?= URL_BASE ?>/acordos?status=1" class="nav-link <?= $statusFiltro === 1 ? 'active' : '' ?>">
                <i class="bi bi-check2-circle me-1"></i> Concluídos
            </a>
            <a href="<?= URL_BASE ?>/acordos?status=2" class="nav-link <?= $statusFiltro === 2 ? 'active' : '' ?>">
                <i class="bi bi-x-circle me-1"></i> Cancelados
            </a>
        </div>

        <?php if (!empty($acordos)): ?>
            <div class="row g-4">
                <?php foreach ($acordos as $ac): 
                    $isEuTrabalhadorUm = (int)$ac['IdTrabalhadorUm'] === (int)$usuarioLogado['IdTrabalhador'];
                    $nomeParceiro = $isEuTrabalhadorUm ? $ac['NomeTrabalhadorDois'] : $ac['NomeTrabalhadorUm'];
                    $fotoParceiro = $isEuTrabalhadorUm ? $ac['FotoTrabalhadorDois'] : $ac['FotoTrabalhadorUm'];
                    $idParceiro = $isEuTrabalhadorUm ? $ac['IdTrabalhadorDois'] : $ac['IdTrabalhadorUm'];
                    $meuServico = $isEuTrabalhadorUm ? $ac['NomeServicoUm'] : $ac['NomeServicoDois'];
                    $servicoParceiro = $isEuTrabalhadorUm ? $ac['NomeServicoDois'] : $ac['NomeServicoUm'];
                ?>
                    <div class="col-12 col-lg-6">
                        <div class="card-acordo h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge rounded-pill bg-light text-dark px-3 py-2 fw-semibold">
                                        Acordo #<?= (int)$ac['IdAcordo'] ?>
                                    </span>

                                    <?php if ((int)$ac['Status'] === 0): 
                                        $confCount = (int)($ac['ConfirmacaoTrabalhadorUm'] ?? 0) + (int)($ac['ConfirmacaoTrabalhadorDois'] ?? 0);
                                    ?>
                                        <?php if ($confCount === 1): ?>
                                            <span class="badge bg-info text-dark rounded-pill px-3 py-2 fw-bold">
                                                <i class="bi bi-check-circle me-1"></i> 1/2 Confirmações
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-bold">
                                                <i class="bi bi-clock-history me-1"></i> Em Andamento
                                            </span>
                                        <?php endif; ?>
                                    <?php elseif ((int)$ac['Status'] === 1): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Concluído
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-x-circle-fill me-1"></i> Cancelado
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <a href="<?= URL_BASE ?>/trabalhador/perfil?id=<?= (int) $idParceiro ?>" title="Ver perfil de <?= htmlspecialchars($nomeParceiro) ?>">
                                        <img src="<?= htmlspecialchars(!empty($fotoParceiro) ? $fotoParceiro : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop') ?>"
                                             alt="Foto de <?= htmlspecialchars($nomeParceiro) ?>" class="rounded-circle shadow-sm" width="55" height="55" style="object-fit: cover;">
                                    </a>
                                    <div>
                                        <h5 class="fw-bold mb-0">Troca com <?= htmlspecialchars($nomeParceiro) ?></h5>
                                        <small class="text-muted">Iniciado em <?= !empty($ac['DataCriacao']) ? date('d/m/Y', strtotime($ac['DataCriacao'])) : 'Recente' ?></small>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded-4 mb-3 border">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small fw-semibold text-muted">Você realiza:</span>
                                        <span class="fw-bold" style="color: var(--escamboo-vinho);"><?= htmlspecialchars($meuServico ?? 'Serviço sob combinação') ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small fw-semibold text-muted">Você recebe:</span>
                                        <span class="fw-bold text-success"><?= htmlspecialchars($servicoParceiro ?? 'Serviço sob combinação') ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2 border-top">
                                <a href="<?= URL_BASE ?>/acordo/detalhes?id=<?= (int)$ac['IdAcordo'] ?>" class="btn btn-dark rounded-pill px-4 fw-semibold">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Ver Detalhes e Contatos
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="p-5 text-center bg-white rounded-4 shadow-sm">
                <i class="bi bi-file-earmark-check text-muted" style="font-size: 3.5rem;"></i>
                <h4 class="fw-bold mt-3" style="color: var(--escamboo-vinho);">Nenhum acordo encontrado</h4>
                <p class="text-muted mb-3">Assim que uma proposta de match for aceita, o acordo de escambo aparecerá aqui automaticamente!</p>
                <a href="<?= URL_BASE ?>/matches" class="btn btn-dark rounded-pill px-4">
                    Ir para Central de Matches
                </a>
            </div>
        <?php endif; ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
