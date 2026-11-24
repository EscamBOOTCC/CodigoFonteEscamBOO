<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EscamBOO - Login</title>
    <link rel="icon" type="image/png" href="<?php echo URL_BASE ?>/assets/fantasminha.png">
    <link rel="apple-touch-icon" href="<?php echo URL_BASE ?>/assets/fantasminha.png">

    <!-- Preload do Fantasminha com olhos cobertos para troca instantânea -->
    <link rel="preload" as="image" href="<?php echo URL_BASE ?>/assets/fantasminha_esconder_olhos.png">

    <!-- Bootstrap 5.3.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-wine: #5e0b15;
            --brand-wine-dark: #3e060d;
            --brand-wine-light: #7b121e;
            --brand-gold: #ba8238;
            --brand-gold-light: #d49544;
            --bg-light: #f5ede2;
            --card-light: #ffffff;
            --text-dark: #2c221e;
            --text-muted: #7d6f66;
            --border-light: rgba(186, 130, 56, 0.22);
            --input-bg-light: #fcfaf8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 50% 15%, #fbf8f3 0%, #eee3d3 50%, #e2d2be 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 2.5rem 1rem;
            position: relative;
            overflow-x: hidden;
            color: var(--text-dark);
            transition: background 0.4s ease, color 0.4s ease;
        }

        /* Ambient background glow */
        .ambient-glow {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background:
                radial-gradient(circle at 18% 22%, rgba(186, 130, 56, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 82% 78%, rgba(94, 11, 21, 0.10) 0%, transparent 50%);
            transition: opacity 0.4s ease;
        }

        /* Fantasminha viajante no fundo (atrás do card de login) */
        .ambient-ghost {
            position: fixed;
            top: 0;
            left: 0;
            width: 105px;
            height: auto;
            z-index: 1; /* Fica atrás da div de login que possui z-index: 10 */
            pointer-events: none;
            user-select: none;
            transform: translate3d(-200px, -200px, 0);
            filter: drop-shadow(0 0 18px rgba(186, 130, 56, 0.45)) drop-shadow(0 10px 22px rgba(94, 11, 21, 0.2));
            transition: filter 0.3s ease;
            opacity: 0.88;
        }

        .ambient-ghost-sprite {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Container principal centrado */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* 1. Header externo: Frase EscamBOO centralizada fora do card, fantasminha à esquerda */
        .brand-external-header {
            width: 100%;
            margin-bottom: 1.75rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .brand-header-flex {
            display: inline-flex;
            align-items: center;
            gap: 1.25rem;
            text-align: left;
        }

        .brand-mascot-container {
            position: relative;
            flex-shrink: 0;
        }

        .main-mascot-img {
            width: 95px;
            height: auto;
            display: block;
            filter: drop-shadow(0 8px 18px rgba(94, 11, 21, 0.22)) drop-shadow(0 0 12px rgba(186, 130, 56, 0.3));
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.3s ease;
            animation: mascotGentleHover 4s ease-in-out infinite alternate;
        }

        .brand-mascot-container:hover .main-mascot-img {
            transform: scale(1.08) rotate(-4deg);
            filter: drop-shadow(0 12px 24px rgba(186, 130, 56, 0.45));
        }

        .main-mascot-img.shy-bounce {
            animation: shySquish 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes mascotGentleHover {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-7px) rotate(2deg); }
        }

        @keyframes shySquish {
            0% { transform: scale(1); }
            35% { transform: scale(1.15, 0.9) rotate(-3deg); }
            70% { transform: scale(0.95, 1.05) rotate(2deg); }
            100% { transform: scale(1); }
        }

        /* Balãozinho divertido quando esconde os olhos */
        .mascot-speech-bubble {
            position: absolute;
            top: -26px;
            left: 50%;
            transform: translateX(-50%) scale(0);
            background: var(--brand-wine);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            pointer-events: none;
            opacity: 0;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
            z-index: 20;
        }

        .mascot-speech-bubble.show {
            transform: translateX(-50%) scale(1);
            opacity: 1;
        }

        .brand-text-block {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--brand-wine);
            margin: 0;
            line-height: 1.1;
        }

        .brand-title .brand-accent {
            color: var(--brand-gold);
            display: inline-block;
        }

        .brand-tagline {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 0.35rem 0 0;
            font-weight: 500;
            line-height: 1.35;
            max-width: 280px;
        }

        @media (max-width: 480px) {
            .brand-header-flex {
                gap: 0.9rem;
            }
            .main-mascot-img {
                width: 78px;
            }
            .brand-title {
                font-size: 2.1rem;
            }
            .brand-tagline {
                font-size: 0.76rem;
                max-width: 220px;
            }
        }

        /* 2. Quadrado de Login (Card) */
        .login-card {
            position: relative;
            z-index: 10; /* Fica acima do fantasminha viajante que tem z-index: 1 */
            width: 100%;
            background: var(--card-light);
            border: 1px solid var(--border-light);
            border-radius: 28px;
            box-shadow: 0 25px 60px rgba(94, 11, 21, 0.10), 0 8px 24px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease, border-color 0.3s ease;
        }

        /* Faixa decorativa superior */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--brand-wine), var(--brand-gold), var(--brand-wine));
        }

        .login-card-body {
            padding: 2.5rem 2.25rem 2.25rem;
        }

        @media (max-width: 576px) {
            .login-card-body {
                padding: 2rem 1.5rem 1.75rem;
            }
        }

        /* Título "Login" no topo dentro do card */
        .login-card-header {
            margin-bottom: 1.75rem;
            text-align: left;
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--brand-wine);
            margin: 0 0 0.3rem;
            letter-spacing: -0.3px;
        }

        .login-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* Form Controls */
        .form-group-custom {
            margin-bottom: 1.35rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #3b2e2a;
            margin-bottom: 0.45rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .form-label i {
            color: var(--brand-wine);
            font-size: 1rem;
        }

        .form-control {
            border-radius: 14px;
            border: 1.5px solid #d9cbba;
            padding: 0.72rem 1.05rem;
            font-size: 0.95rem;
            background-color: var(--input-bg-light);
            color: var(--text-dark);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .form-control::placeholder {
            color: #a39588;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--brand-wine);
            box-shadow: 0 0 0 0.25rem rgba(94, 11, 21, 0.16);
            background-color: #ffffff;
        }

        /* Password Input Group com o Olhinho */
        .password-input-group {
            position: relative;
            display: flex;
            width: 100%;
        }

        .password-input-group .form-control {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            padding-right: 0.75rem;
        }

        .btn-toggle-password {
            border-radius: 0 14px 14px 0 !important;
            border: 1.5px solid #d9cbba;
            border-left: none;
            background-color: var(--input-bg-light);
            color: #7d6f66;
            padding: 0 1.05rem;
            font-size: 1.15rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-toggle-password:hover {
            background-color: #efe5d8;
            color: var(--brand-wine);
        }

        .password-input-group:focus-within .btn-toggle-password {
            border-color: var(--brand-wine);
        }

        /* Botão Entrar */
        .btn-login {
            background: linear-gradient(135deg, var(--brand-wine) 0%, #7e121f 100%);
            border: none;
            color: #ffffff !important;
            border-radius: 14px;
            padding: 0.8rem 1.5rem;
            font-weight: 700;
            font-size: 1.02rem;
            letter-spacing: 0.3px;
            box-shadow: 0 8px 22px rgba(94, 11, 21, 0.32);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #74111d 0%, #991a2a 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(94, 11, 21, 0.42);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 5px 14px rgba(94, 11, 21, 0.3);
        }

        /* Rodapé de Cadastro */
        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
            padding-top: 1.35rem;
            border-top: 1px solid rgba(186, 130, 56, 0.18);
            font-size: 0.92rem;
        }

        .login-footer-text {
            color: var(--text-muted);
        }

        .login-footer-link {
            color: var(--brand-wine);
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-left: 0.35rem;
            transition: all 0.2s ease;
        }

        .login-footer-link:hover {
            color: var(--brand-wine-dark);
            text-decoration: underline;
            transform: translateX(2px);
        }

        /* =========================================================
           ADAPTAÇÃO: MODO ESCURO (DARK MODE)
           ========================================================= */
        :root[data-theme="dark"] body {
            background: radial-gradient(circle at 50% 15%, #241c18 0%, #171310 50%, #0e0b09 100%) !important;
            color: #f3ede4 !important;
        }

        :root[data-theme="dark"] .ambient-glow {
            background:
                radial-gradient(circle at 18% 22%, rgba(186, 130, 56, 0.18) 0%, transparent 45%),
                radial-gradient(circle at 82% 78%, rgba(160, 25, 45, 0.14) 0%, transparent 50%);
        }

        :root[data-theme="dark"] .ambient-ghost {
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.65)) drop-shadow(0 0 35px rgba(224, 163, 75, 0.45));
            opacity: 0.92;
        }

        :root[data-theme="dark"] .main-mascot-img {
            filter: drop-shadow(0 0 16px rgba(255, 255, 255, 0.55)) drop-shadow(0 0 26px rgba(186, 130, 56, 0.4));
        }

        :root[data-theme="dark"] .mascot-speech-bubble {
            background: var(--brand-gold);
            color: #1c1409;
            box-shadow: 0 0 14px rgba(186, 130, 56, 0.6);
        }

        :root[data-theme="dark"] .brand-title {
            color: #f7f0e7 !important;
        }

        :root[data-theme="dark"] .brand-title .brand-accent {
            color: var(--brand-gold-light) !important;
            text-shadow: 0 0 22px rgba(212, 149, 68, 0.45);
        }

        :root[data-theme="dark"] .brand-tagline {
            color: #bfaea0 !important;
        }

        :root[data-theme="dark"] .login-card {
            background: #251f1b !important;
            border-color: #4a3e34 !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.65), 0 0 35px rgba(186, 130, 56, 0.09) !important;
        }

        :root[data-theme="dark"] .login-card::before {
            background: linear-gradient(90deg, #871624, var(--brand-gold-light), #871624);
        }

        :root[data-theme="dark"] .login-title {
            color: #f7f0e7 !important;
        }

        :root[data-theme="dark"] .login-subtitle {
            color: #a8988a !important;
        }

        :root[data-theme="dark"] .form-label {
            color: #eddcd0 !important;
        }

        :root[data-theme="dark"] .form-label i {
            color: var(--brand-gold-light) !important;
        }

        :root[data-theme="dark"] .form-control {
            background-color: #1b1613 !important;
            border-color: #55483c !important;
            color: #f3ede4 !important;
        }

        :root[data-theme="dark"] .form-control::placeholder {
            color: #8c7d72 !important;
        }

        :root[data-theme="dark"] .form-control:focus {
            border-color: var(--brand-gold-light) !important;
            box-shadow: 0 0 0 0.25rem rgba(212, 149, 68, 0.25) !important;
            background-color: #201a16 !important;
        }

        :root[data-theme="dark"] .btn-toggle-password {
            background-color: #1b1613 !important;
            border-color: #55483c !important;
            color: #bfaea0 !important;
        }

        :root[data-theme="dark"] .btn-toggle-password:hover {
            background-color: #2c241e !important;
            color: var(--brand-gold-light) !important;
        }

        :root[data-theme="dark"] .password-input-group:focus-within .btn-toggle-password {
            border-color: var(--brand-gold-light) !important;
        }

        :root[data-theme="dark"] .btn-login {
            background: linear-gradient(135deg, #7d1320 0%, #aa1e2f 100%) !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45), 0 0 20px rgba(125, 19, 32, 0.35) !important;
        }

        :root[data-theme="dark"] .btn-login:hover {
            background: linear-gradient(135deg, #991a2a 0%, #c4293d 100%) !important;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.55), 0 0 25px rgba(196, 41, 61, 0.45) !important;
        }

        :root[data-theme="dark"] .login-footer {
            border-top-color: #4a3e34 !important;
        }

        :root[data-theme="dark"] .login-footer-text {
            color: #a8988a !important;
        }

        :root[data-theme="dark"] .login-footer-link {
            color: var(--brand-gold-light) !important;
        }

        :root[data-theme="dark"] .login-footer-link:hover {
            color: #ffd899 !important;
        }

        @media (prefers-reduced-motion: reduce) {
            .main-mascot-img {
                animation: none !important;
            }
            .ambient-ghost {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <!-- Ambient background light/dark glow -->
    <div class="ambient-glow" aria-hidden="true"></div>

    <!-- 2. Fantasminha decorativo viajando POR TRÁS da div de login com aura -->
    <div id="ambientGhost" class="ambient-ghost" aria-hidden="true">
        <img src="<?php echo URL_BASE ?>/assets/fantasminha.png" alt="" class="ambient-ghost-sprite">
    </div>

    <!-- Container Centralizado -->
    <main class="login-wrapper">

        <!-- 1. Frase EscamBOO centralizada FORA do quadrado de login, com fantasminha à esquerda -->
        <header class="brand-external-header">
            <div class="brand-header-flex">
                <div class="brand-mascot-container" id="brandMascotBox" title="Fantasminha do EscamBOO 👻">
                    <img
                        src="<?php echo URL_BASE ?>/assets/fantasminha.png"
                        alt="Mascote EscamBOO"
                        id="mainMascotImg"
                        class="main-mascot-img"
                        data-normal="<?php echo URL_BASE ?>/assets/fantasminha.png"
                        data-shy="<?php echo URL_BASE ?>/assets/fantasminha_esconder_olhos.png">
                    <div class="mascot-speech-bubble" id="mascotBubble">Não estou olhando! 🙈</div>
                </div>

                <div class="brand-text-block">
                    <h1 class="brand-title">
                        Escam<span class="brand-accent">BOO</span>
                    </h1>
                    <p class="brand-tagline">
                        Conectando Serviços através de Troca de Habilidades
                    </p>
                </div>
            </div>
        </header>

        <!-- 2. Quadrado de Login (Card) -->
        <section class="login-card">
            <div class="login-card-body">

                <!-- Título Login acima dos campos -->
                <div class="login-card-header">
                    <h2 class="login-title">Login</h2>
                    <p class="login-subtitle">Entre com suas credenciais de acesso</p>
                </div>

                <!-- Alertas de Erro / Sucesso -->
                <?php if (! empty($erros)): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                        <div>
                            <?php foreach ($erros as $erro): ?>
                                <div><?php echo htmlspecialchars($erro) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                        <div><?php echo htmlspecialchars($_SESSION['erro']) ?></div>
                    </div>
                    <?php unset($_SESSION['erro']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['sucesso'])): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2 rounded-3" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
                        <div><?php echo htmlspecialchars($_SESSION['sucesso']) ?></div>
                    </div>
                    <?php unset($_SESSION['sucesso']); ?>
                <?php endif; ?>

                <!-- Formulário de Login -->
                <form action="<?php echo URL_BASE ?>/login" method="post" id="loginForm" autocomplete="on">

                    <!-- Campo E-mail -->
                    <div class="form-group-custom">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> E-mail
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="seu.email@exemplo.com"
                            value="<?php echo htmlspecialchars($email ?? '') ?>"
                            autocomplete="email"
                            autofocus>
                    </div>

                    <!-- Campo Senha com o Olhinho -->
                    <div class="form-group-custom">
                        <label for="senha" class="form-label">
                            <i class="bi bi-shield-lock"></i> Senha
                        </label>
                        <div class="password-input-group">
                            <input
                                type="password"
                                class="form-control"
                                id="senha"
                                name="senha"
                                placeholder="Digite sua senha de acesso"
                                autocomplete="current-password">
                            <button
                                type="button"
                                class="btn-toggle-password"
                                id="btnToggleSenha"
                                aria-label="Mostrar senha"
                                title="Mostrar senha">
                                <i class="bi bi-eye" id="iconeOlhoSenha" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Botão de Ação -->
                    <button type="submit" class="btn btn-login w-100 mt-2">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </button>

                </form>

                <!-- Rodapé de Cadastro -->
                <div class="login-footer">
                    <span class="login-footer-text">
                        Ainda não tem uma conta?
                    </span>
                    <a href="<?php echo URL_BASE ?>/trabalhador/cadastrar" class="login-footer-link">
                        <i class="bi bi-person-plus"></i> Cadastre-se
                    </a>
                </div>

            </div>
        </section>

    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Comportamento: Olhinho de Senha + Fantasma Escondendo Olhos + Fantasminha Viajante de Fundo -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* -------------------------------------------------------------------------
             * 1 & 3. Olhinho da Senha & Fantasminha Principal Escondendo os Olhos
             * ------------------------------------------------------------------------- */
            const senhaInput = document.getElementById('senha');
            const btnToggleSenha = document.getElementById('btnToggleSenha');
            const iconeOlhoSenha = document.getElementById('iconeOlhoSenha');
            const mainMascotImg = document.getElementById('mainMascotImg');
            const ambientGhostImg = document.querySelector('.ambient-ghost-sprite');
            const mascotBubble = document.getElementById('mascotBubble');
            let bubbleTimeout = null;

            if (btnToggleSenha && senhaInput && iconeOlhoSenha && mainMascotImg) {
                btnToggleSenha.addEventListener('click', () => {
                    const isPassword = senhaInput.getAttribute('type') === 'password';

                    if (isPassword) {
                        // 1. Mostrar senha em texto puro
                        senhaInput.setAttribute('type', 'text');
                        iconeOlhoSenha.className = 'bi bi-eye-slash-fill';
                        btnToggleSenha.setAttribute('aria-label', 'Ocultar senha');
                        btnToggleSenha.setAttribute('title', 'Ocultar senha');

                        // 3. O fantasminha principal esconde os olhos! 🙈
                        mainMascotImg.src = mainMascotImg.dataset.shy;
                        if (ambientGhostImg) ambientGhostImg.src = mainMascotImg.dataset.shy;
                        mainMascotImg.classList.remove('shy-bounce');
                        void mainMascotImg.offsetWidth; // trigger reflow
                        mainMascotImg.classList.add('shy-bounce');

                        if (mascotBubble) {
                            mascotBubble.textContent = 'Não estou olhando! 🙈';
                            mascotBubble.classList.add('show');
                            if (bubbleTimeout) clearTimeout(bubbleTimeout);
                            bubbleTimeout = setTimeout(() => {
                                mascotBubble.classList.remove('show');
                            }, 2500);
                        }
                    } else {
                        // 1. Ocultar senha
                        senhaInput.setAttribute('type', 'password');
                        iconeOlhoSenha.className = 'bi bi-eye';
                        btnToggleSenha.setAttribute('aria-label', 'Mostrar senha');
                        btnToggleSenha.setAttribute('title', 'Mostrar senha');

                        // 3. O fantasminha abre os olhos novamente! 👻
                        mainMascotImg.src = mainMascotImg.dataset.normal;
                        if (ambientGhostImg) ambientGhostImg.src = mainMascotImg.dataset.normal;
                        mainMascotImg.classList.remove('shy-bounce');

                        if (mascotBubble) {
                            mascotBubble.classList.remove('show');
                            if (bubbleTimeout) clearTimeout(bubbleTimeout);
                        }
                    }
                    senhaInput.focus();
                });
            }

            // Clique interativo opcional no mascote principal
            if (mainMascotImg && mascotBubble) {
                mainMascotImg.addEventListener('click', () => {
                    mainMascotImg.classList.remove('shy-bounce');
                    void mainMascotImg.offsetWidth;
                    mainMascotImg.classList.add('shy-bounce');

                    const isShy = senhaInput && senhaInput.getAttribute('type') === 'text';
                    mascotBubble.textContent = isShy ? 'Continuo sem olhar! 🙈' : 'BOO! 👻 Bora trocar?';
                    mascotBubble.classList.add('show');

                    if (bubbleTimeout) clearTimeout(bubbleTimeout);
                    bubbleTimeout = setTimeout(() => {
                        mascotBubble.classList.remove('show');
                    }, 2200);
                });
            }

            /* -------------------------------------------------------------------------
             * 2. Fantasminha Viajando Suavemente por Trás da Div de Login (Enfeite)
             * ------------------------------------------------------------------------- */
            const ambientGhost = document.getElementById('ambientGhost');

            if (ambientGhost) {
                let ghostWidth = 105;
                let ghostHeight = 125;

                const updateGhostSize = () => {
                    const isMobile = window.innerWidth <= 576;
                    ghostWidth = isMobile ? 80 : 105;
                    ghostHeight = isMobile ? 96 : 125;
                    ambientGhost.style.width = ghostWidth + 'px';
                };
                updateGhostSize();
                window.addEventListener('resize', updateGhostSize);

                let startTime = performance.now();

                const travelLoop = (now) => {
                    requestAnimationFrame(travelLoop);

                    const elapsed = (now - startTime) * 0.0005; // Velocidade suave e relaxada

                    const W = window.innerWidth;
                    const H = window.innerHeight;
                    const centerX = W * 0.5;
                    const centerY = H * 0.5;

                    // Curva em Lemniscata (Figura em 8 / infinito):
                    // Passa diretamente pelo centro (por trás do card de login) e viaja para as laterais
                    const radiusX = Math.max(140, W * 0.38);
                    const radiusY = Math.max(100, H * 0.28);

                    const posX = centerX + Math.sin(elapsed) * radiusX - (ghostWidth * 0.5);
                    const posY = centerY + Math.sin(elapsed * 2) * radiusY + Math.cos(elapsed * 0.7) * 40 - (ghostHeight * 0.5);

                    // Inclinação natural sutil de acordo com a direção do movimento horizontal
                    const velX = Math.cos(elapsed) * radiusX;
                    const tilt = Math.max(-14, Math.min(14, velX * 0.035));

                    // Bobbing vertical orgânico
                    const bob = Math.sin(now * 0.0035) * 6;

                    ambientGhost.style.transform = `translate3d(${posX}px, ${posY + bob}px, 0) rotate(${tilt}deg)`;
                };

                requestAnimationFrame(travelLoop);
            }

        });
    </script>
</body>

</html>