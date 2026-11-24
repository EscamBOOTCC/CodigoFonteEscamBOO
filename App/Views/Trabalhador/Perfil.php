<?php
$trabalhador = $trabalhador ?? [];

$fotoPerfil = !empty($trabalhador['FotoPerfil']) ? $trabalhador['FotoPerfil'] : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=350&auto=format&fit=crop';
$nomeTrabalhador = !empty($trabalhador['Nome']) ? $trabalhador['Nome'] : 'Trabalhador';
$areaTrabalhador = !empty($trabalhador['NomeProfissao'])
    ? $trabalhador['NomeProfissao'] . (!empty($trabalhador['NomeGrandeArea']) ? ' & ' . $trabalhador['NomeGrandeArea'] : '')
    : (!empty($trabalhador['NomeGrandeArea']) ? $trabalhador['NomeGrandeArea'] : 'Profissional');
$cidadeEstado = !empty($trabalhador['Endereco']) ? $trabalhador['Endereco'] : 'Localização não informada';
$servicosOferecidos = $servicosOferecidos ?? [];
$servicosRealizados = $servicosRealizados ?? [];
$avaliacoes = $avaliacoes ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($nomeTrabalhador) ?> - EscamBOO</title>

    <link rel="icon" type="image/png" href="<?php echo URL_BASE ?>/assets/logoEscamboo.png">
    <link rel="apple-touch-icon" href="<?php echo URL_BASE ?>/assets/logoEscamboo.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --escamboo-vinho: #590b1c;
            --escamboo-fundo: #ded5c2;
            --escamboo-card-bg: #fcf9f2;
            --escamboo-ocre: #ba8238;
            --escamboo-texto-escuro: #2b1f1d;
            --escamboo-texto-muted: #73675a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--escamboo-fundo);
            color: var(--escamboo-texto-escuro);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        
        .escamboo-container {
            width: 94%;
            max-width: 1560px;
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
            object-fit: contain;
        }

        .escamboo-logo {
            font-weight: 800;
            font-size: 2rem;
            color: #ffffff;
            letter-spacing: -0.5px;
            text-decoration: none;
        }

        .escamboo-logo:hover {
            color: #f7e6e8;
        }

        .nav-avatar-mini {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
            transition: transform 0.2s;
        }

        .nav-avatar-mini:hover {
            transform: scale(1.05);
        }

        
        .btn-curriculo {
            background-color: #fcf9f2;
            color: var(--escamboo-vinho);
            font-weight: 700;
            font-size: 0.88rem;
            padding: 9px 22px;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transition: transform 0.2s, background-color 0.2s, color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-curriculo:hover {
            background-color: #ffffff;
            color: #3b0611;
            transform: translateY(-2px);
        }

        
        .main-content {
            padding-top: 2rem;
            padding-bottom: 3.5rem;
        }

        
        .top-action-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        
        .cards-grid {
            display: grid;
            grid-template-columns: 29% 43% 28%;
            gap: 24px;
            align-items: stretch;
        }

        
        .card-perfil {
            background-color: var(--escamboo-card-bg);
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            padding: 2.2rem 1.75rem;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .btn-edit-card {
            position: absolute;
            top: 18px;
            right: 20px;
            color: #8c7f70;
            font-size: 1.25rem;
            transition: color 0.2s;
            text-decoration: none;
        }

        .btn-edit-card:hover {
            color: var(--escamboo-vinho);
        }

        .avatar-container {
            position: relative;
            display: inline-block;
            margin: 0 auto;
        }

        .avatar-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #232c3d;
        }

        .badge-rating {
            position: absolute;
            right: -28px;
            bottom: 15px;
            font-size: 1.15rem;
            font-weight: 700;
            color: #b07d2b;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .nome-trabalhador {
            font-size: 1.45rem;
            font-weight: 700;
            color: #2b1f1d;
            margin-top: 0.5rem;
            margin-bottom: 0.2rem;
        }

        .area-trabalhador {
            color: #8c7f70;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .local-trabalhador {
            color: #635749;
            font-size: 0.88rem;
            margin-top: 0.25rem;
        }

        .badge-match {
            border: 1.8px solid #7ecc75;
            background-color: #eaf8e8;
            color: #2b7531;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 6px 18px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background-color: #43a047;
            display: inline-block;
        }

        .social-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #382c24;
            color: #fcf9f2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.95rem;
            transition: opacity 0.2s, transform 0.2s;
        }

        .social-btn:hover {
            opacity: 0.85;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .bio-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #2b1f1d;
            margin-bottom: 8px;
            text-align: center;
        }

        .bio-text {
            font-size: 0.84rem;
            line-height: 1.6;
            color: #4d433b;
            text-align: justify;
        }

        
        .stacked-panels {
            display: flex;
            flex-direction: column;
            gap: 20px;
            height: 100%;
        }

        .card-servicos {
            background-color: var(--escamboo-vinho);
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 1.75rem 1.85rem;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            min-height: 220px;
        }

        .card-servicos.secondary {
            background-color: #7c1c2e;
        }

        .pill-header {
            background-color: #ffffff;
            color: var(--escamboo-texto-escuro);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 7px 44px;
            border-radius: 50px;
            display: inline-block;
        }

        .mini-servico {
            background-color: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 18px;
            padding: 0.8rem;
            margin-top: 0.9rem;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }

        .mini-servico-imagem {
            width: 100%;
            aspect-ratio: 1 / 1;
            max-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 14px;
            background-color: rgba(0, 0, 0, 0.12);
        }

        .mini-servico-imagem.com-duas-imagens {
            aspect-ratio: 2 / 1;
        }

        .mini-servico-imagem.com-duas-imagens .mini-servico-thumb {
            width: calc(50% - 0.4rem);
        }

        .mini-servico-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.25);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .mini-servico-content {
            flex: 1;
            min-width: 0;
            text-align: center;
        }

        .mini-servico h6 {
            font-size: 0.96rem;
            margin: 0 0 0.25rem;
            color: #fff;
            font-weight: 700;
        }

        .mini-servico p {
            font-size: 0.78rem;
            line-height: 1.5;
            color: rgba(255,255,255,0.8);
            margin: 0;
        }

        .card-avaliacoes {
            background-color: var(--escamboo-ocre);
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 1.75rem 1.45rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .pill-header-ocre {
            background-color: #f7f1e6;
            color: var(--escamboo-texto-escuro);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 7px 44px;
            border-radius: 50px;
            display: inline-block;
        }

        .card-avaliacao-item {
            background-color: var(--escamboo-card-bg);
            border-radius: 20px;
            padding: 0.95rem 1.15rem;
            margin-bottom: 0.95rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .card-avaliacao-item:last-child {
            margin-bottom: 0;
        }

        .avaliacao-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #000;
        }

        .text-estrelas {
            color: #e59819;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        html:root[data-theme="dark"] body.pagina-perfil main.main-content .cards-grid .card-perfil .bio-title {
            color: #ffffff !important;
        }

        html:root[data-theme="dark"] body.pagina-perfil main.main-content .cards-grid .stacked-panels .card-servicos span.pill-header,
        html:root[data-theme="dark"] body.pagina-perfil main.main-content .cards-grid .card-avaliacoes span.pill-header-ocre {
            background-color: #51473d !important;
            background-image: none !important;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }

        html:root[data-theme="dark"] body.pagina-perfil main.main-content .cards-grid .stacked-panels .mini-servico-content .text-muted {
            color: #e6ddd2 !important;
        }

        
        @media (max-width: 1080px) {
            .cards-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .badge-rating {
                right: -20px;
            }
        }
    </style>
</head>

<body class="pagina-perfil">

    
    <nav class="escamboo-navbar">
        <div class="escamboo-container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="<?= URL_BASE ?>/trabalhador/servicos" class="escamboo-brand">
                    <img src="<?= URL_BASE ?>/assets/logoEscamboo.png" alt="EscamBOO" class="escamboo-brand-mark">
                    <span class="escamboo-logo">EscamBOO</span>
                </a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= URL_BASE ?>/trabalhador/servicos" class="text-white text-decoration-none fw-medium fs-6">Início</a>
                <a href="<?= URL_BASE ?>/matches" class="text-white text-decoration-none fw-medium fs-6">Matches</a>
                <a href="<?= URL_BASE ?>/acordos" class="text-white text-decoration-none fw-medium fs-6">Acordos</a>
                <a href="<?= URL_BASE ?>/trabalhador/perfil">
                    <img src="<?= htmlspecialchars(!empty($_SESSION['usuario_logado']['FotoPerfil']) ? $_SESSION['usuario_logado']['FotoPerfil'] : $fotoPerfil) ?>" alt="Perfil" class="nav-avatar-mini">
                </a>
            </div>
        </div>
    </nav>

    <main class="escamboo-container main-content">

        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> <?= htmlspecialchars($sucesso) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> <?= htmlspecialchars($erro) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php 
        $isMeuPerfil = isset($_SESSION['usuario_logado']['IdTrabalhador']) && 
                       $_SESSION['usuario_logado']['IdTrabalhador'] == ($trabalhador['IdTrabalhador'] ?? null);
        ?>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <a href="<?= URL_BASE ?>/trabalhador/servicos" class="btn-curriculo text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> VOLTAR PARA SERVIÇOS
                </a>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <?php if ($isMeuPerfil): ?>
                    <a href="<?= URL_BASE ?>/trabalhador/bloqueados" class="btn btn-outline-dark rounded-pill px-3 fw-semibold">
                        <i class="bi bi-person-slash me-1"></i> Usuários Bloqueados
                    </a>
                    <a href="<?= URL_BASE ?>/trabalhador/editar" class="btn btn-principal rounded-pill px-3 fw-semibold" style="background-color: var(--escamboo-vinho); color: #fff;">
                        <i class="bi bi-pencil-fill me-1"></i> Editar Perfil
                    </a>
                <?php else: ?>
                    <?php if (!empty($estaBloqueado)): ?>
                        <form action="<?= URL_BASE ?>/trabalhador/desbloquear" method="POST" class="d-inline" onsubmit="return confirm('Deseja desbloquear este trabalhador?');">
                            <input type="hidden" name="idTrabalhador" value="<?= (int)($trabalhador['IdTrabalhador'] ?? 0) ?>">
                            <input type="hidden" name="origem" value="perfil">
                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                                <i class="bi bi-unlock-fill me-1"></i> Desbloquear Trabalhador
                            </button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalBloquear">
                            <i class="bi bi-person-slash me-1"></i> Bloquear
                        </button>
                    <?php endif; ?>

                    <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalDenunciar">
                        <i class="bi bi-flag-fill text-danger me-1"></i> Denunciar
                    </button>
                <?php endif; ?>
                <a href="<?= URL_BASE ?>/logout" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> SAIR
                </a>
            </div>
        </div>

        
        <div class="cards-grid">

            
            <div class="col-item">
                <div class="card-perfil text-center">
                    
                    <?php if ($isMeuPerfil): ?>
                        <a href="<?= URL_BASE ?>/trabalhador/editar"
                            class="btn-edit-card"
                            title="<?= empty($trabalhador['Biografia']) ? 'Completar Perfil' : 'Editar Perfil' ?>">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                    <?php endif; ?>
                    
                    <div class="avatar-container mb-2">
                        <img src="<?= htmlspecialchars($fotoPerfil) ?>"
                            alt="<?= htmlspecialchars($nomeTrabalhador) ?>"
                            class="avatar-img">
                        <span class="badge-rating">
                            <i class="bi bi-star-fill text-warning"></i> <?= htmlspecialchars($trabalhador['NotaMedia'] ?? '4.8') ?>
                        </span>
                    </div>

                    
                    <h3 class="nome-trabalhador">
                        <?= htmlspecialchars($nomeTrabalhador) ?>
                    </h3>
                    <div class="area-trabalhador">
                        <?= htmlspecialchars($areaTrabalhador) ?>
                    </div>
                    <div class="local-trabalhador">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <?= htmlspecialchars($cidadeEstado) ?>
                    </div>

                    
                    <div class="my-3">
                        <span class="badge-match">
                            <span class="status-dot"></span>
                            <?= htmlspecialchars((int) ($trabalhador['StatusAcordo'] ?? 0) === 0 ? 'Disponível para Match' : 'Indisponível') ?>
                        </span>
                    </div>

                    
                    <?php
                    $isMeuPerfil = isset($_SESSION['usuario_logado']['IdTrabalhador']) && 
                                   $_SESSION['usuario_logado']['IdTrabalhador'] == ($trabalhador['IdTrabalhador'] ?? null);

                    $hasInstagram = !empty(trim($trabalhador['Instagram'] ?? ''));
                    $hasTwitter   = !empty(trim($trabalhador['Twitter'] ?? ''));
                    $hasTelefone  = !empty(trim($trabalhador['Telefone'] ?? ''));
                    $hasAnySocial = $hasInstagram || $hasTwitter || $hasTelefone;

                    $instagramUrl = $hasInstagram 
                        ? (strpos((string) $trabalhador['Instagram'], 'http') === 0 ? $trabalhador['Instagram'] : 'https://instagram.com/' . ltrim(trim((string) $trabalhador['Instagram']), '@'))
                        : null;

                    $twitterUrl = $hasTwitter 
                        ? (strpos((string) $trabalhador['Twitter'], 'http') === 0 ? $trabalhador['Twitter'] : 'https://x.com/' . ltrim(trim((string) $trabalhador['Twitter']), '@'))
                        : null;

                    $phoneDigits = preg_replace('/\D/', '', (string) ($trabalhador['Telefone'] ?? ''));
                    $phoneDisplay = $trabalhador['Telefone'] ?? '';
                    if (strlen($phoneDigits) === 11) {
                        $phoneDisplay = sprintf(
                            '(%s) %s - %s',
                            substr($phoneDigits, 0, 2),
                            substr($phoneDigits, 2, 5),
                            substr($phoneDigits, 7, 4)
                        );
                    } elseif (strlen($phoneDigits) === 10) {
                        $phoneDisplay = sprintf(
                            '(%s) %s - %s',
                            substr($phoneDigits, 0, 2),
                            substr($phoneDigits, 2, 4),
                            substr($phoneDigits, 6, 4)
                        );
                    }
                    if (!empty($phoneDigits) && strlen($phoneDigits) <= 11 && strpos($phoneDigits, '55') !== 0) {
                        $phoneDigits = '55' . $phoneDigits;
                    }
                    $whatsappUrl = !empty($phoneDigits) ? 'https://wa.me/' . $phoneDigits : null;
                    ?>
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-3 flex-wrap">
                        <?php if ($hasTwitter): ?>
                            <a href="<?= htmlspecialchars($twitterUrl) ?>" target="_blank" class="social-btn" title="X / Twitter: <?= htmlspecialchars($trabalhador['Twitter']) ?>">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ($hasInstagram): ?>
                            <a href="<?= htmlspecialchars($instagramUrl) ?>" target="_blank" class="social-btn" title="Instagram: <?= htmlspecialchars($trabalhador['Instagram']) ?>">
                                <i class="bi bi-instagram"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ($hasTelefone): ?>
                            <a href="<?= htmlspecialchars($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer" class="social-btn" title="WhatsApp: <?= htmlspecialchars($phoneDisplay) ?>">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <span class="fw-bold ms-1" style="font-size: 0.9rem; color: #2b1f1d;">
                                <?= htmlspecialchars($phoneDisplay) ?>
                            </span>
                        <?php endif; ?>

                        <?php if (!$hasAnySocial): ?>
                            <?php if ($isMeuPerfil): ?>
                                <a href="<?= URL_BASE ?>/trabalhador/editar" class="btn btn-sm rounded-pill px-3 py-1" style="font-size: 0.82rem; background-color: rgba(89, 11, 28, 0.1); color: var(--escamboo-vinho); font-weight: 600; text-decoration: none;">
                                    <i class="bi bi-plus-circle me-1"></i> Adicionar redes e WhatsApp
                                </a>
                            <?php else: ?>
                                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Contatos não informados</small>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    
                    <div class="mt-2">
                        <div class="bio-title">Biografia</div>
                        <p class="bio-text mb-0">
                            <?= nl2br(htmlspecialchars(!empty($trabalhador['Biografia']) ? $trabalhador['Biografia'] : 'Adicione sua biografia clicando no botão de editar acima para que outros usuários possam conhecer melhor você e seu trabalho.')) ?>
                        </p>
                    </div>
                </div>
        </div>

        
        <div class="col-item">
            <div class="stacked-panels">
                <div class="card-servicos">
                    <div class="d-flex justify-content-center align-items-center position-relative mb-2">
                        <span class="pill-header">
                            Serviços oferecidos (<?= htmlspecialchars(count($servicosOferecidos)) ?>)
                        </span>
                        <?php if ($isMeuPerfil): ?>
                            <a href="<?= URL_BASE ?>/trabalhador/servicos?aba=gerenciar#formulario-cadastro"
                                class="btn btn-sm rounded-circle position-absolute end-0 d-inline-flex align-items-center justify-content-center"
                                style="width: 34px; height: 34px; background-color: var(--escamboo-vinho); color: #fff;"
                                title="Criar serviço"
                                aria-label="Criar serviço">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($servicosOferecidos)): ?>
                        <div class="mini-servico">
                            <h6>Sem serviços cadastrados</h6>
                            <p>Adicione serviços para que outros usuários possam conhecer seu trabalho.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($servicosOferecidos, 0, 3) as $servico): ?>
                            <?php $fotoServicoPerfil = ! empty($servico['FotoServico']) ? $servico['FotoServico'] : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=300&auto=format&fit=crop'; ?>
                            <div class="mini-servico">
                                <div class="mini-servico-imagem <?= !empty($servico['FotoServico2']) ? 'com-duas-imagens' : '' ?>">
                                    <img src="<?= htmlspecialchars($fotoServicoPerfil) ?>" alt="<?= htmlspecialchars($servico['NomeServico'] ?? $servico['Nome'] ?? 'Serviço') ?>" class="mini-servico-thumb">
                                    <?php if (!empty($servico['FotoServico2'])): ?>
                                        <img src="<?= htmlspecialchars($servico['FotoServico2']) ?>" alt="Segunda imagem de <?= htmlspecialchars($servico['NomeServico'] ?? $servico['Nome'] ?? 'Serviço') ?>" class="mini-servico-thumb">
                                    <?php endif; ?>
                                </div>
                                <div class="mini-servico-content">
                                    <h6><?= htmlspecialchars($servico['NomeServico'] ?? $servico['Nome'] ?? 'Serviço') ?></h6>
                                    <p><?= htmlspecialchars($servico['DescricaoServico'] ?? $servico['Descricao'] ?? 'Sem descrição informada.') ?></p>
                                    <?php if (! $isMeuPerfil): ?>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-warning rounded-pill mt-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDenunciar"
                                            data-id-servico="<?= (int) ($servico['IdServico'] ?? 0) ?>"
                                            data-nome-servico="<?= htmlspecialchars($servico['NomeServico'] ?? $servico['Nome'] ?? 'Serviço', ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-flag-fill me-1" aria-hidden="true"></i> Denunciar serviço
                                        </button>
                                    <?php endif; ?>
                                    <?php if ((int) ($servico['TotalAvaliacoes'] ?? 0) > 0): ?>
                                        <div class="small fw-semibold" style="color: #986524;" aria-label="Nota média do serviço">
                                            <i class="bi bi-star-fill me-1" aria-hidden="true"></i>
                                            <?= number_format((float) $servico['NotaMedia'], 1, ',', '.') ?>
                                            <span class="text-muted fw-normal">
                                                (<?= (int) $servico['TotalAvaliacoes'] ?>
                                                <?= (int) $servico['TotalAvaliacoes'] === 1 ? 'avaliação' : 'avaliações' ?>)
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="small text-muted">Ainda sem avaliações</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="card-servicos secondary">
                    <div class="text-center mb-2">
                        <span class="pill-header">
                            Serviços realizados (<?= htmlspecialchars(count($servicosRealizados)) ?>)
                        </span>
                    </div>

                    <?php if (empty($servicosRealizados)): ?>
                        <div class="mini-servico">
                            <h6>Ainda não houve troca concluída</h6>
                            <p>Quando um acordo for finalizado, aqui aparecerá o histórico dos serviços realizados.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($servicosRealizados, 0, 3) as $servico): ?>
                            <?php $fotoServicoRealizado = ! empty($servico['FotoServico']) ? $servico['FotoServico'] : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=300&auto=format&fit=crop'; ?>
                            <div class="mini-servico">
                                <div class="mini-servico-imagem <?= !empty($servico['FotoServico2']) ? 'com-duas-imagens' : '' ?>">
                                    <img src="<?= htmlspecialchars($fotoServicoRealizado) ?>" alt="<?= htmlspecialchars($servico['NomeServico'] ?? 'Serviço concluído') ?>" class="mini-servico-thumb">
                                    <?php if (!empty($servico['FotoServico2'])): ?>
                                        <img src="<?= htmlspecialchars($servico['FotoServico2']) ?>" alt="Segunda imagem de <?= htmlspecialchars($servico['NomeServico'] ?? 'Serviço concluído') ?>" class="mini-servico-thumb">
                                    <?php endif; ?>
                                </div>
                                <div class="mini-servico-content">
                                    <h6><?= htmlspecialchars($servico['NomeServico'] ?? 'Serviço concluído') ?></h6>
                                    <p>Com <?= htmlspecialchars($servico['NomeParceiro'] ?? 'parceiro') ?> · <?= !empty($servico['DataConclusao']) ? date('d/m/Y', strtotime($servico['DataConclusao'])) : 'Data indisponível' ?></p>
                                    <?php if ((int) ($servico['TotalAvaliacoes'] ?? 0) > 0): ?>
                                        <div class="small fw-semibold" style="color: #986524;" aria-label="Nota média do serviço">
                                            <i class="bi bi-star-fill me-1" aria-hidden="true"></i>
                                            <?= number_format((float) $servico['NotaMedia'], 1, ',', '.') ?>
                                            <span class="text-muted fw-normal">
                                                (<?= (int) $servico['TotalAvaliacoes'] ?>
                                                <?= (int) $servico['TotalAvaliacoes'] === 1 ? 'avaliação' : 'avaliações' ?>)
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="small text-muted">Ainda sem avaliações</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-item">
            <div class="card-avaliacoes">
                <div class="text-center mb-3">
                    <span class="pill-header-ocre">
                        Avaliações (<?= htmlspecialchars(count($avaliacoes)) ?>)
                    </span>
                </div>

                <?php if (empty($avaliacoes)): ?>
                    <div class="card-avaliacao-item">
                        <p class="mb-0 text-muted" style="font-size: 0.82rem; line-height: 1.5;">
                            Ainda não há avaliações para este perfil.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($avaliacoes as $avaliacao): ?>
                            <?php $nota = (int) round((float) ($avaliacao['Nota'] ?? 0)); ?>
                            <div class="card-avaliacao-item">
                                <div class="text-end text-estrelas mb-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?= $i <= $nota ? '-fill' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <?php if (!empty($avaliacao['IdAvaliador'])): ?>
                                        <a href="<?= URL_BASE ?>/trabalhador/perfil?id=<?= (int) $avaliacao['IdAvaliador'] ?>" title="Ver perfil de <?= htmlspecialchars($avaliacao['NomeAvaliador'] ?? 'Avaliador') ?>">
                                    <?php endif; ?>
                                    <img src="<?= htmlspecialchars(!empty($avaliacao['FotoAvaliador']) ? $avaliacao['FotoAvaliador'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=150&auto=format&fit=crop') ?>" alt="<?= htmlspecialchars($avaliacao['NomeAvaliador'] ?? 'Avaliador') ?>" class="avaliacao-avatar">
                                    <?php if (!empty($avaliacao['IdAvaliador'])): ?>
                                        </a>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-bold" style="font-size: 0.9rem;">
                                            <?= htmlspecialchars($avaliacao['NomeAvaliador'] ?? 'Avaliador') ?>
                                        </div>
                                        <p class="mb-0 text-muted" style="font-size: 0.78rem; line-height: 1.35;">
                                            <?= !empty($avaliacao['Comentario']) ? '"' . htmlspecialchars($avaliacao['Comentario']) . '"' : 'Avaliação concluída sem comentário adicional.' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        </div>
    </main>

    <!-- Modal Bloquear Trabalhador -->
    <div class="modal fade" id="modalBloquear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_BASE ?>/trabalhador/bloquear" method="POST" class="modal-content rounded-4 border-0">
                <div class="modal-header" style="background-color: var(--escamboo-vinho); color: #fff;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-slash me-2"></i>Bloquear Trabalhador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="idTrabalhador" value="<?= (int)($trabalhador['IdTrabalhador'] ?? 0) ?>">
                    <input type="hidden" name="origem" value="perfil">
                    <p>Tem certeza de que deseja bloquear <strong><?= htmlspecialchars($nomeTrabalhador) ?></strong>?</p>
                    <div class="alert alert-warning small mb-0">
                        <i class="bi bi-info-circle-fill me-1"></i> Após o bloqueio, este usuário não aparecerá mais em suas buscas de serviços e ambos não poderão interagir ou realizar novos acordos na plataforma.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Confirmar Bloqueio</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Denunciar Trabalhador -->
    <div class="modal fade" id="modalDenunciar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_BASE ?>/denuncia/criar" method="POST" class="modal-content rounded-4 border-0">
                <div class="modal-header" style="background-color: var(--escamboo-vinho); color: #fff;">
                    <h5 class="modal-title fw-bold" id="tituloModalDenunciar"><i class="bi bi-flag-fill me-2"></i>Denunciar Trabalhador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="idTrabalhador" value="<?= (int)($trabalhador['IdTrabalhador'] ?? 0) ?>">
                    <input type="hidden" name="idServico" id="denunciaIdServico" value="0">
                    <input type="hidden" name="origem" value="perfil">

                    <p class="small text-muted mb-3" id="textoModalDenunciar">Denuncie violações aos termos da comunidade ou regras do EscamBOO. Sua denúncia será avaliada pela administração.</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Motivo da Denúncia <span class="text-danger">*</span></label>
                        <select name="motivo" class="form-select">
                            <option value="">Selecione o motivo...</option>
                            <option value="Descumprimento de Acordo">Descumprimento de Acordo</option>
                            <option value="Cancelamento Injustificado">Cancelamento Injustificado</option>
                            <option value="Comportamento Inadequado / Desrespeito">Comportamento Inadequado / Desrespeito</option>
                            <option value="Perfil ou Oferta Falsa / Fraude">Perfil ou Oferta Falsa / Fraude</option>
                            <option value="Conteúdo impróprio em serviço">Conteúdo impróprio em serviço</option>
                            <option value="Informações enganosas sobre serviço">Informações enganosas sobre serviço</option>
                            <option value="Cobrança Financeira Indevida">Cobrança Financeira Indevida</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição dos Detalhes</label>
                        <textarea name="descricao" class="form-control" rows="3" placeholder="Explique detalhadamente o que ocorreu..." maxlength="255"></textarea>
                        <small class="text-muted">Máximo de 255 caracteres.</small>
                    </div>

                    <div class="form-check" id="bloquearAutorServicoContainer" hidden>
                        <input class="form-check-input" type="checkbox" name="bloquearAposDenuncia" value="1" id="bloquearAutorServicoPerfil">
                        <label class="form-check-label fw-semibold" for="bloquearAutorServicoPerfil">
                            Bloquear também a pessoa que publicou este serviço
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Enviar Denúncia</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('modalDenunciar').addEventListener('show.bs.modal', function (event) {
            const botao = event.relatedTarget;
            const idServico = botao ? botao.getAttribute('data-id-servico') : null;
            const titulo = document.getElementById('tituloModalDenunciar');
            const texto = document.getElementById('textoModalDenunciar');
            const opcaoBloqueio = document.getElementById('bloquearAutorServicoContainer');
            const checkboxBloqueio = document.getElementById('bloquearAutorServicoPerfil');
            document.getElementById('denunciaIdServico').value = idServico || '0';

            if (idServico) {
                const nomeServico = botao.getAttribute('data-nome-servico');
                titulo.innerHTML = '<i class="bi bi-flag-fill me-2"></i>Denunciar serviço';
                texto.textContent = 'Você está denunciando o serviço "' + nomeServico + '". A denúncia será avaliada pela administração.';
                opcaoBloqueio.hidden = false;
            } else {
                titulo.innerHTML = '<i class="bi bi-flag-fill me-2"></i>Denunciar Trabalhador';
                texto.textContent = 'Denuncie violações aos termos da comunidade ou regras do EscamBOO. Sua denúncia será avaliada pela administração.';
                opcaoBloqueio.hidden = true;
            }
            checkboxBloqueio.checked = false;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>