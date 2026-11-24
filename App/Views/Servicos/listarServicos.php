<?php
$usuarioLogado     = $_SESSION['usuario_logado'] ?? [];
$nomeUsuarioLogado = $usuarioLogado['Nome'] ?? 'Usuário';
$fotoPerfilLogado  = ! empty($usuarioLogado['FotoPerfil'])
    ? $usuarioLogado['FotoPerfil']
    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop';

$servicosDisponiveis = $servicosDisponiveis ?? [];
$meusServicos        = $meusServicos ?? [];
$profissoes          = $profissoes ?? [];
$grandesAreas        = $grandesAreas ?? [];
$filtros              = $filtros ?? [];
$abaAtiva            = $_GET['aba'] ?? 'explorar';
$buscaAtual           = (string) ($filtros['busca'] ?? '');
$grandeAreaAtual      = (int) ($filtros['idGrandeArea'] ?? 0);
$pesquisaAreaAtual    = (string) ($filtros['pesquisaAreaAtuacao'] ?? '');
$profissaoAtual       = (int) ($filtros['idProfissao'] ?? 0);
$localizacaoAtual     = trim((string) ($filtros['localizacao'] ?? ''));
$partesLocalizacao    = array_map('trim', explode(',', $localizacaoAtual, 2));
$cidadeAtualBusca     = $partesLocalizacao[0] ?? '';
$ufAtualBusca         = strtoupper($partesLocalizacao[1] ?? '');
$temBuscaAtual        = trim($buscaAtual) !== '';
$temAreaAtual         = $grandeAreaAtual > 0;
$temProfissaoAtual    = trim($pesquisaAreaAtual) !== '' || $profissaoAtual > 0;
$temLocalizacaoAtual  = $localizacaoAtual !== '';
$nomeGrandeAreaPorId = [];
foreach ($grandesAreas as $grandeArea) {
    $nomeGrandeAreaPorId[(int) $grandeArea['IdGrandeArea']] = $grandeArea['Nome'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços e Oportunidades - EscamBOO</title>

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
            --escamboo-card-destaque: #f5eedf;
            --escamboo-ocre: #ba8238;
            --escamboo-ocre-escuro: #986524;
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
            max-width: 1400px;
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

        .nav-pills-escamboo {
            background-color: #cbbeaa;
            padding: 6px;
            border-radius: 50px;
            display: inline-flex;
            gap: 8px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .nav-pills-escamboo .nav-link {
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--escamboo-texto-escuro);
            transition: all 0.25s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-pills-escamboo .nav-link.active {
            background-color: var(--escamboo-vinho);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(89, 11, 28, 0.35);
        }

        .nav-pills-escamboo .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.35);
        }

        .tinder-wrapper {
            position: relative;
            max-width: 520px;
            margin: 0 auto;
            min-height: 620px;
        }

        .tinder-card-deck {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .tinder-card {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: var(--escamboo-card-bg);
            border-radius: 28px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.16);
            overflow: hidden;
            display: none;
            opacity: 0;
            transform: scale(0.95);
            transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.35s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .tinder-card.active-card {
            display: block;
            opacity: 1;
            transform: scale(1);
            z-index: 10;
        }

        .tinder-card.stack-card-1,
        .tinder-card.stack-card-2 {
            display: block;
            opacity: 1;
            pointer-events: none;
        }

        .tinder-card.stack-card-1 {
            transform: translateY(28px) scale(0.96);
            z-index: 8;
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(89, 11, 28, 0.28);
        }

        .tinder-card.stack-card-2 {
            transform: translateY(56px) scale(0.92);
            z-index: 6;
            box-shadow: 0 20px 38px rgba(0, 0, 0, 0.24);
            border: 2px solid rgba(89, 11, 28, 0.38);
        }

        .tinder-card.dismiss-anim {
            transform: translateX(-130%) rotate(-20deg) scale(0.85);
            opacity: 0;
            pointer-events: none;
        }

        .tinder-card.accept-anim {
            transform: translateX(130%) rotate(20deg) scale(0.85);
            opacity: 0;
            pointer-events: none;
        }

        .card-header-visual {
            position: relative;
            height: 270px;
            background-color: #3b0611;
            overflow: hidden;
        }

        .card-user-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            transition: transform 0.4s ease;
        }

        .card-gradient-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70%;
            background: linear-gradient(to top, rgba(15, 3, 5, 0.88) 0%, rgba(0, 0, 0, 0) 100%);
        }

        .card-header-info {
            position: absolute;
            bottom: 18px;
            left: 22px;
            right: 22px;
            color: #ffffff;
        }

        .card-user-name {
            font-size: 1.55rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
        }

        .badge-profissao {
            background-color: var(--escamboo-ocre);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-block;
            margin-top: 6px;
        }

        .btn-ver-perfil-overlay {
            position: absolute;
            right: 16px;
            bottom: 18px;
            z-index: 20;
            border-radius: 999px;
            padding: 0.5rem 1rem;
            font-size: 0.82rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.92);
            color: var(--escamboo-vinho);
            border: 1px solid rgba(89, 11, 28, 0.2);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-ver-perfil-overlay:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
            color: var(--escamboo-vinho);
        }

        .card-body-content {
            padding: 24px;
        }

        .service-highlight-box {
            background-color: #f2e9dc;
            border-left: 5px solid var(--escamboo-vinho);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 16px;
        }

        .service-highlight-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--escamboo-vinho);
            margin: 0 0 4px 0;
        }

        .service-desc-text {
            color: var(--escamboo-texto-escuro);
            font-size: 0.93rem;
            line-height: 1.55;
            margin: 0;
        }

        .worker-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--escamboo-texto-muted);
            margin-bottom: 6px;
        }

        .tinder-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 36px;
            padding-top: 18px;
            padding-bottom: 24px;
        }

        .btn-tinder {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            border: none;
            outline: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.16);
            transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }

        .btn-tinder:hover {
            transform: scale(1.12);
        }

        .btn-tinder:active {
            transform: scale(0.95);
        }

        .btn-tinder-skip {
            background-color: #ffffff;
            color: #dc3545;
            border: 2px solid #dc3545;
        }

        .btn-tinder-skip:hover {
            background-color: #dc3545;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(220, 53, 69, 0.45);
        }

        .btn-tinder-match {
            background-color: #ffffff;
            color: #198754;
            border: 2px solid #198754;
        }

        .btn-tinder-match:hover {
            background-color: #198754;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(25, 135, 84, 0.45);
        }

        .btn-tinder-back {
            width: 54px;
            height: 54px;
            background-color: #ffffff;
            color: var(--escamboo-vinho);
            border: 2px solid var(--escamboo-vinho);
            font-size: 1.35rem;
        }

        .btn-tinder-back:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
        }

        .empty-deck-card {
            background-color: var(--escamboo-card-bg);
            border-radius: 28px;
            padding: 50px 30px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        @media (max-width: 576px) {
            .tinder-actions {
                gap: 18px;
            }

            .btn-tinder {
                width: 62px;
                height: 62px;
                font-size: 1.6rem;
            }

            .btn-tinder-back {
                width: 48px;
                height: 48px;
                font-size: 1.2rem;
            }
        }

        @media (max-width: 900px) {
            .tinder-wrapper {
                margin-top: 0;
            }
        }

        @media (min-width: 1200px) {
            .tinder-wrapper {
                left: -40px;
                margin-top: calc(-4rem - 75px);
            }
        }

        .card-areas {
            border: none;
            border-radius: 24px;
            background-color: #f3ede2;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.16);
            overflow: hidden;
        }

        .cabecalho-areas {
            background-color: var(--escamboo-vinho);
            color: #ffffff;
            padding: 22px 30px;
            border-bottom: 2px solid var(--escamboo-vinho-escuro);
        }

        .cabecalho-areas h1 {
            font-size: 1.55rem;
            margin: 0;
            font-weight: 700;
        }

        .conteudo-areas {
            padding: 30px;
        }

        .formulario-cadastro {
            background-color: #e2d7c5;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 30px;
        }

        .formulario-cadastro h4 {
            color: var(--escamboo-vinho);
            font-weight: 700;
            margin-bottom: 18px;
        }

        .form-control,
        .form-select {
            border: 1px solid #d1c1aa;
            border-radius: 12px;
            padding: 10px 14px;
            background-color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--escamboo-vinho);
            box-shadow: 0 0 0 0.2rem rgba(89, 11, 28, 0.18);
        }

        .btn-principal {
            background-color: var(--escamboo-vinho);
            border-color: var(--escamboo-vinho);
            color: #ffffff;
            border-radius: 20px;
            padding: 8px 24px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-principal:hover {
            background-color: var(--escamboo-vinho-escuro);
            border-color: var(--escamboo-vinho-escuro);
            color: #ffffff;
        }

        .services-page-toolbar {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(340px, 430px);
            align-items: start;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .services-page-toolbar-manage {
            grid-template-columns: minmax(0, 1fr);
        }

        .services-page-intro {
            display: flex;
            min-width: 0;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.25rem;
        }

        .services-page-intro .nav-pills-escamboo {
            display: flex;
            max-width: 100%;
            flex-wrap: wrap;
        }

        .services-welcome {
            margin: 0;
        }

        .services-welcome h1 {
            margin: 0;
            color: var(--escamboo-vinho);
            font-size: clamp(1.45rem, 3vw, 2rem);
            font-weight: 800;
        }

        .services-welcome p {
            margin: 0.3rem 0 0;
            color: var(--escamboo-texto-muted);
        }

        .services-search-panel {
            position: relative;
            min-width: 0;
            margin: 2rem 0 0;
            padding: 1rem;
            border: 1px solid rgba(89, 11, 28, 0.12);
            border-radius: 18px;
            background: var(--escamboo-card-bg);
            box-shadow: 0 6px 18px rgba(43, 31, 29, 0.1);
        }

        .services-search-heading {
            margin-bottom: 0.75rem;
        }

        .services-search-heading h2 {
            margin: 0;
            color: var(--escamboo-vinho);
            font-size: 1.12rem;
            font-weight: 700;
        }

        .services-search-heading p {
            margin: 0.15rem 0 0;
            color: var(--escamboo-texto-muted);
            font-size: 0.88rem;
        }

        .services-search-panel .form-label {
            margin-bottom: 0.35rem;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .services-search-panel .form-control,
        .services-search-panel .form-select {
            min-height: 48px;
        }

        .services-filter-buttons {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .services-filter-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-height: 40px;
            padding: 0.45rem 0.65rem;
            border: 1px solid #c9b9a2;
            border-radius: 10px;
            background: transparent;
            color: var(--escamboo-texto-escuro);
            font: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
        }

        .services-filter-button:hover,
        .services-filter-button[aria-pressed="true"] {
            border-color: var(--escamboo-vinho);
            background: var(--escamboo-vinho);
            color: #ffffff;
        }

        .services-filter-button:focus-visible {
            outline: 3px solid var(--escamboo-ocre);
            outline-offset: 2px;
        }

        .services-search-panel fieldset[hidden] {
            display: none;
        }

        .services-search-filter-popover {
            position: absolute;
            z-index: 1090;
            top: calc(100% + 1rem);
            right: 0;
            width: min(560px, calc(100vw - 2rem));
            max-height: min(55vh, 460px);
            overflow-y: auto;
            padding: 0.8rem;
            border: 1px solid rgba(89, 11, 28, 0.2);
            border-radius: 16px;
            background: var(--escamboo-card-bg);
            box-shadow: 0 14px 36px rgba(0, 0, 0, 0.24);
        }

        .services-search-filter-popover[hidden] {
            display: none;
        }

        .services-search-filter-panel {
            min-width: 0;
            margin: 0.5rem 0 0;
            padding: 0.8rem;
            border: 1px solid rgba(89, 11, 28, 0.16);
            border-radius: 12px;
            background: var(--escamboo-card-destaque);
            animation: showSearchFilter 0.18s ease-out;
        }

        .services-search-filter-panel legend {
            float: none;
            width: auto;
            margin: 0 0 0.75rem;
            padding: 0;
            color: var(--escamboo-texto-escuro);
            font-size: 0.95rem;
            font-weight: 700;
        }

        .services-search-filter-panel:first-child {
            margin-top: 0;
        }

        @keyframes showSearchFilter {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .services-search-submit {
            min-height: 42px;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            white-space: nowrap;
        }

        .services-search-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: 0.75rem;
            flex-wrap: wrap;
        }

        .services-search-clear {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0;
            color: var(--escamboo-vinho);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
        }

        .services-search-clear:hover {
            color: var(--escamboo-vinho-escuro);
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .services-page-toolbar {
                grid-template-columns: minmax(0, 1fr);
                gap: 1rem;
            }

            .services-search-panel {
                width: 100%;
                margin-top: 0.5rem;
            }
        }

        .btn-excluir {
            background-color: var(--escamboo-ocre);
            border-color: var(--escamboo-ocre);
            color: #ffffff;
            border-radius: 20px;
            padding: 6px 18px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .btn-excluir:hover {
            background-color: var(--escamboo-ocre-escuro);
            border-color: var(--escamboo-ocre-escuro);
            color: #ffffff;
        }

        .table-servicos {
            margin-bottom: 0;
            vertical-align: middle;
            background-color: transparent;
        }

        .table-servicos thead th {
            background-color: #e2d7c5;
            color: var(--escamboo-vinho);
            font-weight: 700;
            border-bottom: 2px solid #d1c1aa;
            padding: 14px;
        }

        .table-servicos tbody tr {
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #e6dccb;
        }

        .table-servicos tbody tr:hover {
            background-color: #e9e0d1;
        }

        .table-servicos td {
            padding: 14px;
        }

        .card-counter-pill {
            background-color: rgba(89, 11, 28, 0.12);
            color: var(--escamboo-vinho);
            font-weight: 700;
            font-size: 0.85rem;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-block;
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
                <span class="badge bg-light text-dark rounded-pill px-3 py-1 d-none d-md-inline-block" style="font-size: 0.78rem;">
                    Conexão & Escambo
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="text-white text-decoration-none fw-medium fs-6 d-none d-sm-inline">
                    <i class="bi bi-house-door-fill me-1"></i> Início
                </a>
                <a href="<?php echo URL_BASE ?>/matches" class="text-white text-decoration-none fw-medium fs-6 d-none d-sm-inline position-relative">
                    <i class="bi bi-fire me-1"></i> Matches
                    <?php if (! empty($propostasRecebidasPendentes)): ?>
                        <span class="badge rounded-pill bg-danger ms-1"
                            aria-label="<?php echo (int) $propostasRecebidasPendentes ?> solicitações de match recebidas pendentes">
                            <?php echo (int) $propostasRecebidasPendentes ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo URL_BASE ?>/acordos" class="text-white text-decoration-none fw-medium fs-6 d-none d-sm-inline">
                    <i class="bi bi-file-earmark-check-fill me-1"></i> Acordos
                </a>
                <a href="<?php echo URL_BASE ?>/denuncias/minhas" class="text-white text-decoration-none fw-medium fs-6" title="Minhas Denúncias">
                    <i class="bi bi-shield-exclamation me-1"></i><span class="d-none d-lg-inline">Minhas Denúncias</span>
                </a>
                <a href="<?php echo URL_BASE ?>/trabalhador/perfil" class="d-flex align-items-center text-white text-decoration-none gap-2" title="Meu Perfil">
                    <img src="<?php echo htmlspecialchars($fotoPerfilLogado) ?>" alt="Perfil" class="nav-avatar-mini">
                </a>
            </div>
        </div>
    </nav>

    <main class="escamboo-container py-4">

        <?php if (! empty($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> <?php echo htmlspecialchars($sucesso) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (! empty($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> <?php echo htmlspecialchars($erro) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="services-page-toolbar <?php echo $abaAtiva === 'gerenciar' ? 'services-page-toolbar-manage' : ''; ?>">
            <div class="services-page-intro">
                <?php if ($abaAtiva !== 'gerenciar'): ?>
                    <section class="services-welcome" aria-labelledby="saudacao-servicos">
                        <h1 id="saudacao-servicos">Olá, <?php echo htmlspecialchars($nomeUsuarioLogado, ENT_QUOTES, 'UTF-8'); ?>!</h1>
                        <p>Encontre serviços e habilidades para trocar.</p>
                    </section>
                <?php endif; ?>

            <div class="nav-pills-escamboo">
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos?aba=explorar"
                    class="nav-link <?php echo $abaAtiva !== 'gerenciar' ? 'active' : '' ?>">
                    <i class="bi bi-fire"></i> Explorar Serviços
                </a>
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos?aba=gerenciar"
                    class="nav-link <?php echo $abaAtiva === 'gerenciar' ? 'active' : '' ?>">
                    <i class="bi bi-gear-fill"></i> Meus Serviços (Cadastrar & Gerenciar)
                </a>
            </div>

                <?php if ($abaAtiva !== 'gerenciar' && ! empty($servicosDisponiveis)): ?>
                    <div id="cardCounter" class="card-counter-pill">
                        <i class="bi bi-layers-fill me-1"></i> Serviço <span id="currentIndexText">1</span> de <?php echo count($servicosDisponiveis) ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($abaAtiva !== 'gerenciar'): ?>
            <form id="formPesquisaServicos" method="GET" action="<?php echo URL_BASE; ?>/trabalhador/servicos/filtrar" class="services-search-panel">
                <div class="services-search-heading">
                    <div>
                        <h2>Como você quer filtrar?</h2>
                        <p>Escolha uma ou mais opções. Os campos selecionados aparecem abaixo.</p>
                    </div>
                </div>

                <div class="services-filter-buttons" role="group" aria-label="Opções de pesquisa">
                    <button type="button" class="services-filter-button" data-search-filter-toggle="servico"
                        aria-controls="filtroBuscaServico" aria-expanded="<?php echo $temBuscaAtual ? 'true' : 'false'; ?>"
                        aria-pressed="<?php echo $temBuscaAtual ? 'true' : 'false'; ?>">
                        <i class="bi bi-search" aria-hidden="true"></i> Serviço
                    </button>
                    <button type="button" class="services-filter-button" data-search-filter-toggle="localizacao"
                        aria-controls="filtroBuscaLocalizacao" aria-expanded="<?php echo $temLocalizacaoAtual ? 'true' : 'false'; ?>"
                        aria-pressed="<?php echo $temLocalizacaoAtual ? 'true' : 'false'; ?>">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i> Localização
                    </button>
                    <button type="button" class="services-filter-button" data-search-filter-toggle="area"
                        aria-controls="filtroBuscaArea" aria-expanded="<?php echo $temAreaAtual ? 'true' : 'false'; ?>"
                        aria-pressed="<?php echo $temAreaAtual ? 'true' : 'false'; ?>">
                        <i class="bi bi-grid" aria-hidden="true"></i> Grande área
                    </button>
                    <button type="button" class="services-filter-button" data-search-filter-toggle="profissao"
                        aria-controls="filtroBuscaProfissao" aria-expanded="<?php echo $temProfissaoAtual ? 'true' : 'false'; ?>"
                        aria-pressed="<?php echo $temProfissaoAtual ? 'true' : 'false'; ?>">
                        <i class="bi bi-briefcase" aria-hidden="true"></i> Profissão
                    </button>
                </div>

                <div id="painelFiltrosPesquisa" class="services-search-filter-popover"
                    <?php echo ($temBuscaAtual || $temAreaAtual || $temProfissaoAtual || $temLocalizacaoAtual) ? '' : 'hidden'; ?>>
                    <fieldset id="filtroBuscaServico" class="services-search-filter-panel" data-search-filter-panel="servico"
                        <?php echo $temBuscaAtual ? '' : 'hidden disabled'; ?>>
                        <legend>Serviço ou habilidade</legend>
                        <label for="buscaServico" class="form-label">O que você procura?</label>
                        <input type="search" id="buscaServico" name="busca" class="form-control"
                            placeholder="Ex.: aulas de inglês, jardinagem..."
                            value="<?php echo htmlspecialchars($buscaAtual, ENT_QUOTES, 'UTF-8'); ?>">
                    </fieldset>

                    <fieldset id="filtroBuscaLocalizacao" class="services-search-filter-panel" data-search-filter-panel="localizacao"
                        <?php echo $temLocalizacaoAtual ? '' : 'hidden disabled'; ?>>
                        <legend>Localização</legend>
                        <div class="row g-3">
                            <div class="col-12 col-md-5">
                                <label for="estadoLocalizacao" class="form-label">Estado</label>
                                <select id="estadoLocalizacao" class="form-select">
                                    <option value="">Selecione um estado</option>
                                    <?php foreach ([
                                        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                                        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
                                        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
                                        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
                                        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                                        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
                                        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                                    ] as $siglaUf => $nomeUf): ?>
                                        <option value="<?php echo $siglaUf; ?>" <?php echo $ufAtualBusca === $siglaUf ? 'selected' : ''; ?>>
                                            <?php echo $nomeUf; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-7">
                                <label for="cidadeLocalizacao" class="form-label">Cidade</label>
                                <input type="search" id="cidadeLocalizacao" class="form-control"
                                    list="cidadesIbge" autocomplete="off"
                                    placeholder="Selecione o estado para pesquisar cidades"
                                    value="<?php echo htmlspecialchars($cidadeAtualBusca, ENT_QUOTES, 'UTF-8'); ?>"
                                    aria-describedby="ajudaCidadeLocalizacao">
                                <datalist id="cidadesIbge"></datalist>
                                <input type="hidden" id="localizacaoBusca" name="localizacao"
                                    value="<?php echo htmlspecialchars($localizacaoAtual, ENT_QUOTES, 'UTF-8'); ?>">
                                <small id="ajudaCidadeLocalizacao" class="form-text text-muted" role="status">
                                    As cidades são carregadas pela API oficial do IBGE.
                                </small>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="filtroBuscaArea" class="services-search-filter-panel" data-search-filter-panel="area"
                        <?php echo $temAreaAtual ? '' : 'hidden disabled'; ?>>
                        <legend>Grande área</legend>
                        <label for="idGrandeArea" class="form-label">Escolha uma área</label>
                        <select id="idGrandeArea" name="idGrandeArea" class="form-select">
                            <option value="">Todas as áreas</option>
                            <?php foreach ($grandesAreas as $ga): ?>
                                <option value="<?php echo (int) $ga['IdGrandeArea']; ?>" <?php echo $grandeAreaAtual === (int) $ga['IdGrandeArea'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ga['Nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </fieldset>

                    <fieldset id="filtroBuscaProfissao" class="services-search-filter-panel" data-search-filter-panel="profissao"
                        <?php echo $temProfissaoAtual ? '' : 'hidden disabled'; ?>>
                        <legend>Profissão</legend>
                        <label for="pesquisaAreaAtuacao" class="form-label">Pesquise por profissão ou área de atuação</label>
                        <input type="search" id="pesquisaAreaAtuacao" name="pesquisaAreaAtuacao" class="form-control mb-2"
                            data-profissao-busca="feed"
                            placeholder="Digite para encontrar uma profissão..."
                            value="<?php echo htmlspecialchars($pesquisaAreaAtual, ENT_QUOTES, 'UTF-8'); ?>">
                        <label for="filtroProfissaoBusca" class="form-label">Ou escolha uma profissão</label>
                        <select id="filtroProfissaoBusca" name="idProfissao" class="form-select"
                            data-profissao-select="feed" data-area-select="idGrandeArea">
                            <option value="">Todas as profissões</option>
                            <?php foreach ($profissoes as $prof): ?>
                                <option value="<?php echo (int) $prof['IdProfissao']; ?>"
                                    data-grande-area="<?php echo (int) $prof['IdGrandeArea']; ?>"
                                    data-grande-area-nome="<?php echo htmlspecialchars($nomeGrandeAreaPorId[(int) $prof['IdGrandeArea']] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo $profissaoAtual === (int) $prof['IdProfissao'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($prof['Nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </fieldset>
                </div>

                <div class="services-search-actions">
                    <button type="submit" class="btn btn-principal services-search-submit">
                        <i class="bi bi-search me-1" aria-hidden="true"></i> Buscar oportunidades
                    </button>
                    <?php if ($temBuscaAtual || $temAreaAtual || $temProfissaoAtual || $temLocalizacaoAtual): ?>
                        <a href="<?php echo URL_BASE; ?>/trabalhador/servicos" class="services-search-clear">
                            <i class="bi bi-x-circle" aria-hidden="true"></i> Limpar tudo
                        </a>
                    <?php endif; ?>
                </div>
            </form>
            <?php endif; ?>
        </div>

        <?php if ($abaAtiva !== 'gerenciar'): ?>
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                    <div class="tinder-wrapper">
                        <?php if (! empty($servicosDisponiveis)): ?>
                            <div class="tinder-card-deck" id="tinderCardDeck">
                                <?php foreach ($servicosDisponiveis as $idx => $item):
                                    $fotoCard = ! empty($item['FotoPerfil'])
                                        ? $item['FotoPerfil']
                                        : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=600&auto=format&fit=crop';
                                ?>
                                    <div class="tinder-card <?php echo $idx === 0 ? 'active-card' : '' ?>"
                                        id="card-<?php echo $idx ?>"
                                        data-index="<?php echo $idx ?>"
                                        data-id="<?php echo (int)$item['IdServico'] ?>">

                                        <div class="card-header-visual">
                                            <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo (int)($item['IdTrabalhador'] ?? 0) ?>"
                                                class="d-block h-100" title="Ver perfil de <?php echo htmlspecialchars($item['NomeTrabalhador'] ?? 'Trabalhador') ?>">
                                                <img src="<?php echo htmlspecialchars($fotoCard) ?>"
                                                    alt="<?php echo htmlspecialchars($item['NomeTrabalhador'] ?? 'Trabalhador') ?>"
                                                    class="card-user-img">
                                            </a>
                                            <div class="card-gradient-overlay"></div>

                                            <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo (int)($item['IdTrabalhador'] ?? 0) ?>"
                                                class="btn-ver-perfil-overlay" title="Ver perfil do profissional">
                                                <i class="bi bi-person-circle"></i> Ver perfil
                                            </a>

                                            <div class="card-header-info">
                                                <h3 class="card-user-name"><?php echo htmlspecialchars($item['NomeTrabalhador'] ?? 'Profissional') ?></h3>
                                                <span class="badge-profissao">
                                                    <i class="bi bi-briefcase-fill me-1"></i>
                                                    <?php echo htmlspecialchars(! empty($item['NomeProfissao']) ? $item['NomeProfissao'] : 'Geral') ?>
                                                    <?php if (! empty($item['NomeGrandeArea'])): ?>
                                                        • <?php echo htmlspecialchars($item['NomeGrandeArea']) ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="card-body-content">
                                            <div class="service-highlight-box">
                                                <div class="text-uppercase small fw-bold text-muted mb-1">
                                                    <i class="bi bi-award-fill text-warning me-1"></i> Serviço Oferecido:
                                                </div>
                                                <?php if (! empty($item['FotoServico']) || ! empty($item['FotoServico2'])): ?>
                                                    <div class="d-flex gap-2 mb-2">
                                                        <?php foreach (['FotoServico', 'FotoServico2'] as $indiceFoto => $campoFoto): ?>
                                                            <?php if (! empty($item[$campoFoto])): ?>
                                                                <img src="<?php echo htmlspecialchars($item[$campoFoto]) ?>"
                                                                    alt="Imagem <?php echo $indiceFoto + 1 ?> do serviço <?php echo htmlspecialchars($item['NomeServico']) ?>"
                                                                    style="width: 96px; height: 72px; object-fit: cover; border-radius: 10px;">
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <h4 class="service-highlight-title"><?php echo htmlspecialchars($item['NomeServico']) ?></h4>
                                                <p class="service-desc-text">
                                                    <?php echo ! empty($item['DescricaoServico'])
                                                        ? nl2br(htmlspecialchars($item['DescricaoServico']))
                                                        : 'Este usuário está oferecendo este serviço em troca de novas habilidades ou trocas de escambo.' ?>
                                                </p>
                                            </div>

                                            <?php if (! empty($item['Endereco'])): ?>
                                                <div class="worker-meta-item">
                                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                                    <span><?php echo htmlspecialchars($item['Endereco']) ?></span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (! empty($item['Biografia'])): ?>
                                                <div class="worker-meta-item mt-2">
                                                    <i class="bi bi-quote text-secondary fs-5"></i>
                                                    <span class="fst-italic text-truncate" style="max-width: 90%;">
                                                        "<?php echo htmlspecialchars($item['Biografia']) ?>"
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                            <div class="tinder-actions">
                                                <button type="button"
                                                    class="btn-tinder btn-tinder-back"
                                                    onclick="goToPreviousCard()"
                                                    title="Voltar ao serviço anterior"
                                                    aria-label="Voltar ao serviço anterior"
                                                    <?= $idx === 0 ? 'disabled' : '' ?>>
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn-tinder btn-tinder-skip"
                                                    onclick="skipCurrentCard(<?php echo $idx ?>)"
                                                    title="Pular serviço (não excluir)"
                                                    aria-label="Pular este serviço">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>

                                                <a href="<?php echo URL_BASE ?>/match/solicitar?idServico=<?php echo (int)$item['IdServico'] ?>"
                                                    class="btn-tinder btn-tinder-match"
                                                    title="Gostei! Propor troca"
                                                    aria-label="Propor troca para este serviço">
                                                    <i class="bi bi-check-lg"></i>
                                                </a>
                                            </div>

                                            <div class="text-center mt-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded-pill"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDenunciarServico"
                                                    data-id-servico="<?php echo (int) $item['IdServico'] ?>"
                                                    data-id-trabalhador="<?php echo (int) $item['IdTrabalhador'] ?>"
                                                    data-nome-servico="<?php echo htmlspecialchars($item['NomeServico'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <i class="bi bi-flag-fill me-1" aria-hidden="true"></i> Denunciar serviço
                                                </button>
                                            </div>

                                            <div class="text-center">
                                                <small class="text-muted" style="font-size: 0.78rem;">
                                                    <span class="text-danger fw-bold">✖ Pular</span> para o próximo |
                                                    <span class="text-success fw-bold">✔ Confere</span> para propor troca
                                                </small>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div id="deckFinished" class="empty-deck-card" style="display: none;">
                                <div class="mb-3 text-warning">
                                    <i class="bi bi-stars" style="font-size: 3.5rem;"></i>
                                </div>
                                <h3 class="fw-bold" style="color: var(--escamboo-vinho);">Você viu todos os serviços!</h3>
                                <p class="text-muted mb-4">
                                    Não há mais serviços novos para exibir neste momento. Você pode rever todos os cards ou cadastrar um novo serviço para receber propostas.
                                </p>
                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                    <button class="btn btn-outline-dark rounded-pill px-4 fw-semibold" onclick="goToPreviousCard()">
                                        <i class="bi bi-arrow-left me-1"></i> Voltar ao último serviço
                                    </button>
                                    <button class="btn btn-principal" onclick="restartDeck()">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Rever Cards
                                    </button>
                                    <a href="<?php echo URL_BASE ?>/trabalhador/servicos?aba=gerenciar" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">
                                        <i class="bi bi-plus-circle me-1"></i> Cadastrar Meu Serviço
                                    </a>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="empty-deck-card">
                                <div class="mb-3 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3.5rem;"></i>
                                </div>
                                <h3 class="fw-bold" style="color: var(--escamboo-vinho);">Nenhum serviço disponível ainda</h3>
                                <p class="text-muted mb-4">
                                    Assim que outros profissionais cadastrarem serviços, eles aparecerão aqui para você conferir e trocar!
                                </p>
                                <a href="<?php echo URL_BASE ?>/trabalhador/servicos?aba=gerenciar" class="btn btn-principal">
                                    <i class="bi bi-plus-circle me-1"></i> Cadastrar Meu Serviço Agora
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <?php if ($abaAtiva === 'gerenciar'): ?>
            <div class="card-areas">
                <div class="cabecalho-areas d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h1>Gerenciar Serviços Oferecidos</h1>
                        <p class="mb-0 text-white-50" style="font-size: 0.9rem;">
                            Cadastre o que você oferece (ex: "5 aulas de matemática", "Pintura residencial") e gerencie suas ofertas ativas.
                        </p>
                    </div>
                    <a href="<?php echo URL_BASE ?>/trabalhador/servicos?aba=explorar" class="btn btn-light rounded-pill px-4 fw-bold" style="color: var(--escamboo-vinho); font-size: 0.88rem;">
                        <i class="bi bi-fire me-1 text-danger"></i> Ir para Modo Match
                    </a>
                </div>

                <div class="conteudo-areas">
                    <div class="formulario-cadastro" id="formulario-cadastro">
                        <h4><i class="bi bi-plus-circle-fill me-2"></i>Cadastrar Novo Serviço</h4>
                        <form id="formCadastroServico"
                            action="<?php echo URL_BASE ?>/trabalhador/servicos/cadastrar"
                            method="POST"
                            enctype="multipart/form-data"
                            data-draft-key="rascunho-servico-<?php echo (int) ($usuarioLogado['IdTrabalhador'] ?? 0) ?>"
                            data-clear-draft="<?php echo ! empty($limparRascunhoServico) ? 'true' : 'false' ?>">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="nomeServico" class="form-label fw-semibold">Título do Serviço <span class="text-danger">*</span></label>
                                    <input type="text"
                                        id="nomeServico"
                                        name="nome"
                                        class="form-control"
                                        placeholder="Ex: 5 aulas particulares de matemática, Consultoria..."
                                        maxlength="100">
                                    <div class="d-flex justify-content-end mt-1">
                                        <small id="contadorNomeServico" class="text-muted" aria-live="polite">100 caracteres restantes</small>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="filtroGrandeAreaServico" class="form-label fw-semibold">Grande área de atuação</label>
                                    <select id="filtroGrandeAreaServico" name="filtroGrandeAreaServico" class="form-select mb-2">
                                        <option value="">Todas as grandes áreas</option>
                                        <?php foreach ($grandesAreas as $ga): ?>
                                            <option value="<?php echo (int) $ga['IdGrandeArea']; ?>">
                                                <?php echo htmlspecialchars($ga['Nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="buscaProfissaoServico" class="form-label fw-semibold">Profissão / área vinculada <span class="text-danger">*</span></label>
                                    <input type="search" id="buscaProfissaoServico" class="form-control mb-2" data-profissao-busca="cadastro"
                                        placeholder="Digite para encontrar uma profissão...">
                                    <select id="idProfissao" name="idProfissao" class="form-select" data-profissao-select="cadastro" data-area-select="filtroGrandeAreaServico">
                                        <option value="">Selecione uma profissão...</option>
                                        <?php if (! empty($profissoes)): ?>
                                            <?php foreach ($profissoes as $prof): ?>
                                                <option value="<?php echo (int)$prof['IdProfissao'] ?>"
                                                    data-grande-area="<?php echo (int) $prof['IdGrandeArea']; ?>"
                                                    data-grande-area-nome="<?php echo htmlspecialchars($nomeGrandeAreaPorId[(int) $prof['IdGrandeArea']] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo htmlspecialchars($prof['Nome']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="descricaoServico" class="form-label fw-semibold">Detalhes e Descrição do Serviço</label>
                                    <textarea id="descricaoServico"
                                        name="descricao"
                                        rows="3"
                                        class="form-control"
                                        maxlength="500"
                                        placeholder="Detalhe o que você oferece, horários disponíveis, formato (presencial ou online), o que espera em troca..."></textarea>
                                    <div class="d-flex justify-content-end mt-1">
                                        <small id="contadorDescricaoServico" class="text-muted" aria-live="polite">500 caracteres restantes</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="fotoServico" class="form-label fw-semibold">Imagem principal do serviço <span class="text-danger">*</span></label>
                                    <input type="url" id="fotoServico" name="fotoServico" class="form-control mb-2"
                                        placeholder="https://exemplo.com/imagem-servico.jpg">
                                    <input type="file" name="arquivoFotoServico" class="form-control"
                                        accept="image/jpeg,image/png,image/webp,image/gif">
                                    <small class="text-muted">Informe um link ou envie uma imagem JPG, PNG, WEBP ou GIF (máximo 2 MB).</small>
                                </div>

                                <div class="col-12">
                                    <label for="fotoServico2" class="form-label fw-semibold">Segunda imagem (opcional)</label>
                                    <input type="url" id="fotoServico2" name="fotoServico2" class="form-control mb-2"
                                        placeholder="https://exemplo.com/outra-imagem.jpg">
                                    <input type="file" name="arquivoFotoServico2" class="form-control"
                                        accept="image/jpeg,image/png,image/webp,image/gif">
                                    <small class="text-muted">Você pode adicionar mais uma imagem por link ou upload.</small>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-principal">
                                        <i class="bi bi-check2-circle me-1"></i> Cadastrar Serviço
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <h4 class="fw-bold mb-3" style="color: var(--escamboo-vinho);">
                        <i class="bi bi-list-stars me-2"></i>Meus Serviços Cadastrados (<?php echo count($meusServicos) ?>)
                    </h4>

                    <div class="table-responsive rounded-4 shadow-sm" style="background-color: #fcf9f2;">
                        <table class="table table-servicos align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Serviço</th>
                                    <th style="width: 20%;">Profissão</th>
                                    <th style="width: 40%;">Detalhes</th>
                                    <th style="width: 15%; text-align: center;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (! empty($meusServicos)): ?>
                                    <?php foreach ($meusServicos as $meu): ?>
                                        <tr>
                                            <td class="fw-bold" style="color: var(--escamboo-vinho);">
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php $fotoMeuServico = ! empty($meu['FotoServico']) ? $meu['FotoServico'] : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=200&auto=format&fit=crop'; ?>
                                                    <div class="d-flex gap-1">
                                                        <img src="<?php echo htmlspecialchars($fotoMeuServico) ?>" alt="<?php echo htmlspecialchars($meu['NomeServico']) ?>" style="width: 52px; height: 52px; object-fit: cover; border-radius: 12px; border: 1px solid rgba(89,11,28,0.2);">
                                                        <?php if (! empty($meu['FotoServico2'])): ?>
                                                            <img src="<?php echo htmlspecialchars($meu['FotoServico2']) ?>" alt="Segunda imagem de <?php echo htmlspecialchars($meu['NomeServico']) ?>" style="width: 52px; height: 52px; object-fit: cover; border-radius: 12px; border: 1px solid rgba(89,11,28,0.2);">
                                                        <?php endif; ?>
                                                    </div>
                                                    <span><?php echo htmlspecialchars($meu['NomeServico']) ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill" style="background-color: var(--escamboo-ocre); color: #fff; font-size: 0.8rem;">
                                                    <?php echo htmlspecialchars($meu['NomeProfissao'] ?? 'Geral') ?>
                                                </span>
                                            </td>
                                            <td class="text-muted" style="font-size: 0.9rem;">
                                                <?php echo ! empty($meu['DescricaoServico']) ? nl2br(htmlspecialchars($meu['DescricaoServico'])) : '<em class="text-black-50">Sem descrição adicional</em>' ?>
                                            </td>

                                            <td style="text-align: center;">
                                                <div class="d-flex flex-column gap-2 align-items-center">

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        title="Editar Serviço"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarServico"
                                                        data-id="<?php echo (int)$meu['IdServico']; ?>"
                                                        data-nome="<?php echo htmlspecialchars($meu['NomeServico']); ?>"
                                                        data-profissao="<?php echo (int)($meu['IdProfissao'] ?? 0); ?>"
                                                        data-descricao="<?php echo htmlspecialchars($meu['DescricaoServico'] ?? ''); ?>"
                                                        data-foto="<?php echo htmlspecialchars($meu['FotoServico'] ?? ''); ?>"
                                                        data-foto2="<?php echo htmlspecialchars($meu['FotoServico2'] ?? ''); ?>">
                                                        <i class="bi bi-pencil-fill me-1"></i> Editar
                                                    </button>

                                                    <form action="<?php echo URL_BASE ?>/trabalhador/servicos/excluir" method="POST" onsubmit="return confirm(<?= htmlspecialchars(json_encode('Tem certeza que deseja excluir o serviço ' . $meu['NomeServico'] . '?', JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG), ENT_QUOTES, 'UTF-8') ?>);" style="display: inline-block;">
                                                        <input type="hidden" name="idServico" value="<?php echo (int)$meu['IdServico'] ?>">
                                                        <button type="submit" class="btn btn-excluir" title="Excluir Serviço">
                                                            <i class="bi bi-trash3-fill me-1"></i> Excluir
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="bi bi-info-circle me-1"></i> Você ainda não cadastrou nenhum serviço. Use o formulário acima para adicionar seu primeiro serviço!
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        <?php endif; ?>

    </main>

    <div class="modal fade" id="modalDenunciarServico" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?php echo URL_BASE ?>/denuncia/criar" method="POST" class="modal-content rounded-4 border-0">
                <div class="modal-header" style="background-color: var(--escamboo-vinho); color: #fff;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-flag-fill me-2"></i>Denunciar serviço</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="idTrabalhador" id="denunciaServicoIdTrabalhador">
                    <input type="hidden" name="idServico" id="denunciaServicoIdServico">
                    <input type="hidden" name="origem" value="servicos">

                    <p class="small text-muted mb-3" id="denunciaServicoDescricao"></p>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="denunciaServicoMotivo">Motivo da denúncia <span class="text-danger">*</span></label>
                        <select name="motivo" id="denunciaServicoMotivo" class="form-select">
                            <option value="">Selecione o motivo...</option>
                            <option value="Oferta falsa / fraude">Oferta falsa / fraude</option>
                            <option value="Conteúdo impróprio">Conteúdo impróprio</option>
                            <option value="Informações enganosas">Informações enganosas</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="denunciaServicoDetalhes">Descrição dos detalhes</label>
                        <textarea name="descricao" id="denunciaServicoDetalhes" class="form-control" rows="3" maxlength="255" placeholder="Explique o problema com este serviço..."></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bloquearAposDenuncia" value="1" id="bloquearAutorServico">
                        <label class="form-check-label fw-semibold" for="bloquearAutorServico">
                            Bloquear também a pessoa que publicou este serviço
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Enviar denúncia</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('modalDenunciarServico').addEventListener('show.bs.modal', function (event) {
            const botao = event.relatedTarget;
            document.getElementById('bloquearAutorServico').checked = false;
            document.getElementById('denunciaServicoIdTrabalhador').value = botao.getAttribute('data-id-trabalhador');
            document.getElementById('denunciaServicoIdServico').value = botao.getAttribute('data-id-servico');
            document.getElementById('denunciaServicoDescricao').textContent =
                'Você está denunciando o serviço "' + botao.getAttribute('data-nome-servico') + '". A denúncia será avaliada pela administração.';
        });

        const totalCards = <?php echo count($servicosDisponiveis) ?>;
        let currentCardIndex = 0;
        let cardTransitionInProgress = false;

        function updateCounterUI() {
            const counterElement = document.getElementById('currentIndexText');
            if (counterElement) {
                counterElement.innerText = Math.min(currentCardIndex + 1, totalCards);
            }

            document.querySelectorAll('.btn-tinder-back').forEach(button => {
                button.disabled = currentCardIndex === 0 || cardTransitionInProgress;
            });
        }

        function renderDeckState() {
            const deck = document.getElementById('tinderCardDeck');
            const deckFinished = document.getElementById('deckFinished');
            const counterWrapper = document.getElementById('cardCounter');
            const isFinished = currentCardIndex >= totalCards;

            if (deck) {
                deck.style.display = isFinished ? 'none' : 'block';
            }
            if (deckFinished) {
                deckFinished.style.display = isFinished ? 'block' : 'none';
            }
            if (counterWrapper) {
                counterWrapper.style.display = isFinished ? 'none' : 'inline-block';
            }

            for (let index = 0; index < totalCards; index++) {
                const card = document.getElementById('card-' + index);
                if (!card) continue;

                card.classList.remove(
                    'active-card',
                    'stack-card-1',
                    'stack-card-2',
                    'dismiss-anim',
                    'accept-anim'
                );

                if (index === currentCardIndex) {
                    card.style.display = 'block';
                    card.classList.add('active-card');
                } else if (index === currentCardIndex + 1) {
                    card.style.display = 'block';
                    card.classList.add('stack-card-1');
                } else if (index === currentCardIndex + 2) {
                    card.style.display = 'block';
                    card.classList.add('stack-card-2');
                } else {
                    card.style.display = 'none';
                }
            }

            updateCounterUI();
        }

        function goToPreviousCard() {
            if (cardTransitionInProgress || currentCardIndex <= 0) return;

            currentCardIndex--;
            renderDeckState();
        }

        function skipCurrentCard(index) {
            if (cardTransitionInProgress || index !== currentCardIndex) return;

            const card = document.getElementById('card-' + index);
            if (!card) return;

            cardTransitionInProgress = true;
            updateCounterUI();
            card.classList.add('dismiss-anim');

            setTimeout(() => {
                currentCardIndex++;
                cardTransitionInProgress = false;
                renderDeckState();
            }, 300);
        }

        function restartDeck() {
            currentCardIndex = 0;
            cardTransitionInProgress = false;
            renderDeckState();
        }

        renderDeckState();

        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                const activeCard = document.querySelector('.tinder-card.active-card');
                if (activeCard) {
                    const idx = parseInt(activeCard.getAttribute('data-index'), 10);
                    skipCurrentCard(idx);
                }
            } else if (e.key === 'ArrowRight') {
                const activeCard = document.querySelector('.tinder-card.active-card');
                if (activeCard) {
                    const matchBtn = activeCard.querySelector('.btn-tinder-match');
                    if (matchBtn) matchBtn.click();
                }
            }
        });
    </script>
    <div class="modal fade" id="modalEditarServico" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?php echo URL_BASE; ?>/trabalhador/servicos/editar" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header" style="background-color: var(--escamboo-vinho); color: #fff;">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Serviço</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idServico" id="edit-idServico">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Título do Serviço</label>
                        <input type="text" name="nome" id="edit-nome" class="form-control" maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="edit-filtroGrandeArea">Grande área de atuação</label>
                        <select id="edit-filtroGrandeArea" class="form-select mb-2">
                            <option value="">Todas as grandes áreas</option>
                            <?php foreach ($grandesAreas as $ga): ?>
                                <option value="<?php echo (int) $ga['IdGrandeArea']; ?>"><?php echo htmlspecialchars($ga['Nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label class="form-label fw-bold" for="edit-buscaProfissao">Profissão</label>
                        <input type="search" id="edit-buscaProfissao" class="form-control mb-2" data-profissao-busca="edicao"
                            placeholder="Digite para encontrar uma profissão...">
                        <select name="idProfissao" id="edit-idProfissao" class="form-select" data-profissao-select="edicao" data-area-select="edit-filtroGrandeArea">
                            <option value="" selected disabled>Selecione...</option>
                            <?php foreach ($profissoes as $prof): ?>
                                <option value="<?php echo $prof['IdProfissao']; ?>"
                                    data-grande-area="<?php echo (int) $prof['IdGrandeArea']; ?>"
                                    data-grande-area-nome="<?php echo htmlspecialchars($nomeGrandeAreaPorId[(int) $prof['IdGrandeArea']] ?? '', ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($prof['Nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="edit-fotoServico">Imagem principal (URL, se quiser substituir)</label>
                        <input type="url" name="fotoServico" id="edit-fotoServico" class="form-control mb-2" placeholder="https://...">
                        <input type="file" name="arquivoFotoServico" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
                        <small class="text-muted">Enviar um arquivo substitui o link. Máximo 2 MB.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="edit-fotoServico2">Segunda imagem (opcional)</label>
                        <input type="url" name="fotoServico2" id="edit-fotoServico2" class="form-control mb-2" placeholder="https://...">
                        <input type="file" name="arquivoFotoServico2" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
                        <small class="text-muted">Enviar um arquivo substitui o link. Máximo 2 MB.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição</label>
                        <textarea name="descricao" id="edit-descricao" class="form-control" rows="3" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-principal">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const nomeServico = document.getElementById('nomeServico');
        const contadorNomeServico = document.getElementById('contadorNomeServico');
        const descricaoServico = document.getElementById('descricaoServico');
        const contadorDescricaoServico = document.getElementById('contadorDescricaoServico');
        const formCadastroServico = document.getElementById('formCadastroServico');
        const formPesquisaServicos = document.getElementById('formPesquisaServicos');

        if (formPesquisaServicos) {
            const painelFiltrosPesquisa = document.getElementById('painelFiltrosPesquisa');
            const atualizarPainelFiltros = () => {
                painelFiltrosPesquisa.hidden = !Array.from(
                    painelFiltrosPesquisa.querySelectorAll('[data-search-filter-panel]')
                ).some((panel) => !panel.hidden);
            };

            formPesquisaServicos.querySelectorAll('[data-search-filter-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const panel = document.getElementById(button.getAttribute('aria-controls'));
                    if (!panel) return;

                    const shouldOpen = panel.hidden;
                    panel.hidden = !shouldOpen;
                    panel.disabled = !shouldOpen;
                    button.setAttribute('aria-expanded', String(shouldOpen));
                    button.setAttribute('aria-pressed', String(shouldOpen));

                    if (!shouldOpen) {
                        panel.querySelectorAll('input').forEach((input) => {
                            input.value = '';
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                        });
                        panel.querySelectorAll('select').forEach((select) => {
                            select.selectedIndex = 0;
                            select.dispatchEvent(new Event('change', { bubbles: true }));
                        });
                    }

                    atualizarPainelFiltros();
                });
            });

            const estadoLocalizacao = document.getElementById('estadoLocalizacao');
            const cidadeLocalizacao = document.getElementById('cidadeLocalizacao');
            const cidadesIbge = document.getElementById('cidadesIbge');
            const localizacaoBusca = document.getElementById('localizacaoBusca');
            const ajudaCidadeLocalizacao = document.getElementById('ajudaCidadeLocalizacao');
            let requisicaoCidades = 0;

            const atualizarLocalizacaoBusca = () => {
                const cidade = cidadeLocalizacao.value.trim();
                localizacaoBusca.value = cidade && estadoLocalizacao.value
                    ? `${cidade}, ${estadoLocalizacao.value}`
                    : cidade;
            };

            const carregarCidadesBusca = async (uf, cidadeSelecionada = '') => {
                const idRequisicao = ++requisicaoCidades;
                cidadesIbge.replaceChildren();

                if (!uf) {
                    cidadeLocalizacao.disabled = false;
                    cidadeLocalizacao.placeholder = 'Selecione o estado para pesquisar cidades';
                    ajudaCidadeLocalizacao.textContent = 'Escolha um estado para carregar as cidades do IBGE.';
                    atualizarLocalizacaoBusca();
                    return;
                }

                cidadeLocalizacao.disabled = true;
                cidadeLocalizacao.placeholder = 'Carregando cidades...';
                ajudaCidadeLocalizacao.textContent = 'Carregando cidades pela API oficial do IBGE.';

                try {
                    const resposta = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${encodeURIComponent(uf)}/municipios?orderBy=nome`);
                    if (!resposta.ok) {
                        throw new Error(`A API do IBGE respondeu com status ${resposta.status}.`);
                    }

                    const municipios = await resposta.json();
                    if (idRequisicao !== requisicaoCidades || estadoLocalizacao.value !== uf) return;

                    municipios.forEach((municipio) => {
                        const opcao = document.createElement('option');
                        opcao.value = municipio.nome;
                        cidadesIbge.appendChild(opcao);
                    });

                    cidadeLocalizacao.disabled = false;
                    cidadeLocalizacao.placeholder = 'Digite ou selecione uma cidade';
                    if (cidadeSelecionada) {
                        cidadeLocalizacao.value = cidadeSelecionada;
                    }
                    ajudaCidadeLocalizacao.textContent = 'Digite para pesquisar ou escolha uma sugestão da lista.';
                    atualizarLocalizacaoBusca();
                } catch (erro) {
                    if (idRequisicao !== requisicaoCidades) return;
                    console.error('Não foi possível carregar as cidades do IBGE.', erro);
                    cidadeLocalizacao.disabled = false;
                    cidadeLocalizacao.placeholder = 'Digite o nome da cidade';
                    ajudaCidadeLocalizacao.textContent = 'Não foi possível carregar a lista do IBGE. Você ainda pode digitar a cidade.';
                }
            };

            estadoLocalizacao.addEventListener('change', () => {
                cidadeLocalizacao.value = '';
                localizacaoBusca.value = '';
                carregarCidadesBusca(estadoLocalizacao.value);
            });
            cidadeLocalizacao.addEventListener('input', atualizarLocalizacaoBusca);
            formPesquisaServicos.addEventListener('submit', atualizarLocalizacaoBusca);

            if (estadoLocalizacao.value) {
                carregarCidadesBusca(estadoLocalizacao.value, cidadeLocalizacao.value);
            } else {
                atualizarLocalizacaoBusca();
            }
        }

        function configurarPesquisaProfissoes(tipo) {
            const select = document.querySelector(`[data-profissao-select="${tipo}"]`);
            const busca = document.querySelector(`[data-profissao-busca="${tipo}"]`);
            const selectAreaId = select ? select.dataset.areaSelect : '';
            const area = selectAreaId ? document.getElementById(selectAreaId) : null;
            if (!select || !busca || !area) return;

            const filtrar = function () {
                const termo = busca.value.trim().toLocaleLowerCase('pt-BR');
                const idGrandeArea = area.value;

                Array.from(select.options).forEach(function (option, index) {
                    if (index === 0) return;
                    const correspondeArea = !idGrandeArea || option.dataset.grandeArea === idGrandeArea;
                    const nomeArea = (option.dataset.grandeAreaNome || '').toLocaleLowerCase('pt-BR');
                    const correspondeTexto = !termo
                        || option.text.toLocaleLowerCase('pt-BR').includes(termo)
                        || nomeArea.includes(termo);
                    option.hidden = !correspondeArea || !correspondeTexto;
                    option.disabled = !correspondeArea || !correspondeTexto;
                });

                if (select.selectedOptions.length && select.selectedOptions[0].disabled) {
                    select.value = '';
                }
            };

            busca.addEventListener('input', filtrar);
            area.addEventListener('change', filtrar);
            filtrar();
        }

        configurarPesquisaProfissoes('feed');
        configurarPesquisaProfissoes('cadastro');
        configurarPesquisaProfissoes('edicao');

        function atualizarContador(campo, contador) {
            if (campo && contador) {
                contador.textContent = `${campo.maxLength - campo.value.length} caracteres restantes`;
            }
        }

        if (formCadastroServico) {
            const chaveRascunho = formCadastroServico.dataset.draftKey;
            const camposRascunho = ['nome', 'filtroGrandeAreaServico', 'buscaProfissaoServico', 'idProfissao', 'descricao', 'fotoServico', 'fotoServico2'];

            if (formCadastroServico.dataset.clearDraft === 'true') {
                sessionStorage.removeItem(chaveRascunho);
            } else {
                const rascunho = sessionStorage.getItem(chaveRascunho);
                if (rascunho) {
                    const dadosRascunho = JSON.parse(rascunho);
                    camposRascunho.forEach(function (nomeCampo) {
                        const campo = formCadastroServico.elements.namedItem(nomeCampo);
                        if (campo && typeof dadosRascunho[nomeCampo] === 'string') {
                            campo.value = dadosRascunho[nomeCampo];
                        }
                    });
                    formCadastroServico.elements.namedItem('filtroGrandeAreaServico').dispatchEvent(new Event('change'));
                    formCadastroServico.elements.namedItem('buscaProfissaoServico').dispatchEvent(new Event('input'));
                }
            }

            const salvarRascunho = function () {
                const dadosRascunho = {};
                camposRascunho.forEach(function (nomeCampo) {
                    dadosRascunho[nomeCampo] = formCadastroServico.elements.namedItem(nomeCampo).value;
                });
                sessionStorage.setItem(chaveRascunho, JSON.stringify(dadosRascunho));
            };

            formCadastroServico.addEventListener('input', salvarRascunho);
            formCadastroServico.addEventListener('change', salvarRascunho);
            formCadastroServico.addEventListener('submit', salvarRascunho);

            if (nomeServico) {
                nomeServico.addEventListener('input', function () {
                    atualizarContador(nomeServico, contadorNomeServico);
                });
            }
            if (descricaoServico) {
                descricaoServico.addEventListener('input', function () {
                    atualizarContador(descricaoServico, contadorDescricaoServico);
                });
            }

            atualizarContador(nomeServico, contadorNomeServico);
            atualizarContador(descricaoServico, contadorDescricaoServico);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var modalEditar = document.getElementById('modalEditarServico');
            if (modalEditar) {
                modalEditar.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    document.getElementById('edit-idServico').value = button.getAttribute('data-id');
                    document.getElementById('edit-nome').value = button.getAttribute('data-nome');
                    var selectProfissao = document.getElementById('edit-idProfissao');
                    selectProfissao.value = button.getAttribute('data-profissao');
                    var profissaoSelecionada = selectProfissao.selectedOptions[0];
                    document.getElementById('edit-filtroGrandeArea').value = profissaoSelecionada
                        ? profissaoSelecionada.dataset.grandeArea
                        : '';
                    document.getElementById('edit-buscaProfissao').value = '';
                    document.getElementById('edit-filtroGrandeArea').dispatchEvent(new Event('change'));
                    document.getElementById('edit-descricao').value = button.getAttribute('data-descricao');
                    document.getElementById('edit-fotoServico').value = button.getAttribute('data-foto');
                    document.getElementById('edit-fotoServico2').value = button.getAttribute('data-foto2');
                });
            }
        });
    </script>
</body>

</html>