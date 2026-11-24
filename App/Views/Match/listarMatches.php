<?php
    $usuarioLogado    = $usuarioLogado ?? $_SESSION['usuario_logado'] ?? [];
    $fotoPerfilLogado = ! empty($usuarioLogado['FotoPerfil'])
    ? $usuarioLogado['FotoPerfil']
    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop';

    $recebidos = $recebidos ?? [];
    $enviados  = $enviados ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Matches - EscamBOO</title>

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

        .card-match-item {
            background-color: var(--escamboo-card-bg);
            border-radius: 22px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease;
        }

        .card-match-item:hover {
            transform: translateY(-2px);
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
            padding: 10px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--escamboo-texto-escuro);
            border: none;
            transition: all 0.2s;
        }

        .nav-pills-escamboo .nav-link.active {
            background-color: var(--escamboo-vinho);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(89, 11, 28, 0.35);
        }

        .exchange-box {
            background-color: #f2e9dc;
            border-radius: 14px;
            padding: 14px 18px;
        }

        .btn-aceitar {
            background-color: #198754;
            color: #fff;
            border-radius: 50px;
            padding: 8px 22px;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
        }

        .btn-aceitar:hover {
            background-color: #146c43;
            color: #fff;
        }

        .btn-recusar {
            background-color: #dc3545;
            color: #fff;
            border-radius: 50px;
            padding: 8px 22px;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
        }

        .btn-recusar:hover {
            background-color: #b02a37;
            color: #fff;
        }
    </style>
</head>

<body>

    <nav class="escamboo-navbar mb-4">
        <div class="escamboo-container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="escamboo-brand">
                    <img src="<?php echo URL_BASE ?>/assets/logoEscamboo.png" alt="EscamBOO" class="escamboo-brand-mark">
                    <span class="escamboo-logo">EscamBOO</span>
                </a>
                <span class="badge bg-light text-dark rounded-pill px-3 py-1 d-none d-md-inline-block">Central de Matches</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="text-white text-decoration-none fw-medium">
                    <i class="bi bi-house-door-fill me-1"></i> Início
                </a>
                <a href="<?php echo URL_BASE ?>/acordos" class="text-white text-decoration-none fw-medium">
                    <i class="bi bi-file-earmark-check-fill me-1"></i> Acordos
                </a>
                <a href="<?php echo URL_BASE ?>/denuncias/minhas" class="text-white text-decoration-none fw-medium" title="Minhas Denúncias">
                    <i class="bi bi-shield-exclamation me-1"></i><span class="d-none d-lg-inline">Minhas Denúncias</span>
                </a>
                <a href="<?php echo URL_BASE ?>/trabalhador/perfil">
                    <img src="<?php echo htmlspecialchars($fotoPerfilLogado) ?>" alt="Perfil" class="nav-avatar-mini">
                </a>
            </div>
        </div>
    </nav>

    <main class="escamboo-container py-3 mb-5">

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

        <?php if (! empty($deveFinalizarAcordo)): ?>
            <div class="alert alert-warning alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                Você já possui um acordo em andamento. Finalize ou cancele o seu acordo atual antes de aceitar ou enviar novos matches.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1" style="color: var(--escamboo-vinho);">Minhas Propostas de Match</h1>
                <p class="text-muted mb-0">Gerencie as trocas propostas e recebidas de outros trabalhadores do EscamBOO.</p>
            </div>
            <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Explorar Novos Serviços
            </a>
        </div>

        <?php if (! empty($deveFinalizarAcordo)): ?>
            <div class="p-5 text-center bg-white rounded-4 shadow-sm mb-4">
                <i class="bi bi-lock-fill text-warning" style="font-size: 3rem;"></i>
                <h4 class="fw-bold mt-3" style="color: var(--escamboo-vinho);">Ação bloqueada enquanto você tiver um acordo em vigor</h4>
                <p class="text-muted mb-0">Finalize o seu acordo atual para continuar fechando novos matches.</p>
            </div>
            <?php return; ?>
        <?php endif; ?>

        <ul class="nav nav-pills-escamboo mb-4" id="matchTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="recebidos-tab" data-bs-toggle="pill" data-bs-target="#recebidos" type="button" role="tab">
                    <i class="bi bi-inbox-fill me-1"></i> Propostas Recebidas
                    <span class="badge bg-warning text-dark rounded-pill ms-2"><?php echo count($recebidos) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="enviados-tab" data-bs-toggle="pill" data-bs-target="#enviados" type="button" role="tab">
                    <i class="bi bi-send-fill me-1"></i> Propostas Enviadas
                    <span class="badge bg-light text-dark rounded-pill ms-2"><?php echo count($enviados) ?></span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="matchTabsContent">
            <div class="tab-pane fade show active" id="recebidos" role="tabpanel">
                <?php if (! empty($recebidos)): ?>
                    <?php foreach ($recebidos as $m): ?>
                        <div class="card-match-item" id="match-<?php echo (int) $m['IdMatch'] ?>">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <?php $idOutroPerfil = (int) ($m['IdOutro'] ?? 0); ?>
                                    <?php if ($idOutroPerfil > 0): ?>
                                        <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo $idOutroPerfil ?>" title="Ver perfil de <?php echo htmlspecialchars($m['NomeOutro'] ?? 'Usuário') ?>">
                                    <?php endif; ?>
                                    <img src="<?php echo htmlspecialchars(! empty($m['FotoOutro']) ? $m['FotoOutro'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop') ?>"
                                         alt="Perfil" class="rounded-circle" width="55" height="55" style="object-fit: cover;">
                                    <?php if ($idOutroPerfil > 0): ?>
                                        </a>
                                    <?php endif; ?>
                                    <div>
                                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($m['NomeOutro'] ?? 'Usuário') ?></h5>
                                        <span class="badge rounded-pill" style="background-color: var(--escamboo-ocre); color: #fff;">
                                            <?php echo htmlspecialchars(! empty($m['ProfissaoOutro']) ? $m['ProfissaoOutro'] : 'Profissional') ?>
                                        </span>
                                        <?php if ($idOutroPerfil > 0): ?>
                                            <div class="mt-2">
                                                <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo $idOutroPerfil ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 fw-semibold">
                                                    <i class="bi bi-person-circle me-1"></i> Ver perfil
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div>
                                    <?php if ((int) $m['Status'] === 0): ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-clock me-1"></i> Aguardando sua resposta
                                        </span>
                                    <?php elseif ((int) $m['Status'] === 1): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-check-circle me-1"></i> Match Aceito
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-x-circle me-1"></i> Recusado
                                        </span>
                                    <?php endif; ?>
                                    <?php if (! empty($m['IdServicoOrigem']) && $idOutroPerfil > 0): ?>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-pill mt-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDenunciarProposta"
                                            data-id-match="<?php echo (int) $m['IdMatch'] ?>"
                                            data-id-servico="<?php echo (int) $m['IdServicoOrigem'] ?>"
                                            data-id-trabalhador="<?php echo $idOutroPerfil ?>"
                                            data-nome-servico="<?php echo htmlspecialchars($m['NomeServicoOferecido'] ?? 'serviço oferecido', ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-flag-fill me-1" aria-hidden="true"></i> Denunciar proposta
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <div class="exchange-box h-100">
                                        <span class="text-uppercase small fw-bold text-success d-block mb-1">
                                            <i class="bi bi-arrow-down-left-circle me-1"></i> O que te oferecem:
                                        </span>
                                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($m['NomeServicoOferecido'] ?? 'Serviço sob combinação') ?></h6>
                                        <p class="small text-muted mb-0"><?php echo htmlspecialchars($m['DescricaoServicoOferecido'] ?? 'Sem descrição detalhada') ?></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="exchange-box h-100">
                                        <span class="text-uppercase small fw-bold text-primary d-block mb-1">
                                            <i class="bi bi-arrow-up-right-circle me-1"></i> Do seu catálogo desejado:
                                        </span>
                                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($m['NomeMeuServicoDesejado'] ?? 'Serviço solicitado') ?></h6>
                                        <p class="small text-muted mb-0"><?php echo htmlspecialchars($m['DescricaoMeuServicoDesejado'] ?? '') ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if (! empty($m['Mensagem'])): ?>
                                <div class="p-3 bg-white rounded-3 border mb-3">
                                    <span class="fw-semibold small text-muted d-block mb-1"><i class="bi bi-chat-quote-fill me-1"></i>Mensagem:</span>
                                    <p class="fst-italic mb-0" style="font-size: 0.93rem;">"<?php echo nl2br(htmlspecialchars($m['Mensagem'])) ?>"</p>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>Proposto em <?php echo date('d/m/Y', strtotime($m['Data'])) ?></small>

                                <?php if ((int) $m['Status'] === 0): ?>
                                    <div class="d-flex gap-2 flex-wrap align-items-center">
                                        <?php $idOutroPerfil = (int) ($m['IdOutro'] ?? 0); ?>
                                        <?php if ($idOutroPerfil > 0): ?>
                                            <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo $idOutroPerfil ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-2 fw-semibold">
                                                <i class="bi bi-person-circle me-1"></i> Ver perfil
                                            </a>
                                        <?php endif; ?>

                                        <form action="<?php echo URL_BASE ?>/match/recusar" method="POST" onsubmit="return confirm('Deseja realmente recusar esta proposta de match?');">
                                            <input type="hidden" name="idMatch" value="<?php echo (int)$m['IdMatch'] ?>">
                                            <button type="submit" class="btn btn-recusar">
                                                <i class="bi bi-x-lg me-1"></i> Recusar
                                            </button>
                                        </form>

                                        <form action="<?php echo URL_BASE ?>/match/aceitar" method="POST">
                                            <input type="hidden" name="idMatch" value="<?php echo (int)$m['IdMatch'] ?>">
                                            <button type="submit" class="btn btn-aceitar">
                                                <i class="bi bi-check-lg me-1"></i> Aceitar Match
                                            </button>
                                        </form>
                                    </div>
                                <?php elseif ((int) $m['Status'] === 1 && ! empty($m['IdAcordo'])): ?>
                                    <a href="<?php echo URL_BASE ?>/acordo/detalhes?id=<?php echo (int)$m['IdAcordo'] ?>" class="btn btn-sm btn-dark rounded-pill px-3">
                                        <i class="bi bi-file-earmark-text me-1"></i> Ver Acordo Formalizado
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-5 text-center bg-white rounded-4 shadow-sm">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold mt-3" style="color: var(--escamboo-vinho);">Nenhuma proposta recebida ainda</h4>
                        <p class="text-muted mb-0">Cadastre serviços atraentes no seu perfil para receber propostas de escambo de outros profissionais!</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="enviados" role="tabpanel">
                <?php if (! empty($enviados)): ?>
                    <?php foreach ($enviados as $m): ?>
                        <div class="card-match-item">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <?php $idOutroPerfil = (int) ($m['IdOutro'] ?? 0); ?>
                                    <?php if ($idOutroPerfil > 0): ?>
                                        <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo $idOutroPerfil ?>" title="Ver perfil de <?php echo htmlspecialchars($m['NomeOutro'] ?? 'Usuário') ?>">
                                    <?php endif; ?>
                                    <img src="<?php echo htmlspecialchars(! empty($m['FotoOutro']) ? $m['FotoOutro'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop') ?>"
                                         alt="Perfil" class="rounded-circle" width="55" height="55" style="object-fit: cover;">
                                    <?php if ($idOutroPerfil > 0): ?>
                                        </a>
                                    <?php endif; ?>
                                    <div>
                                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($m['NomeOutro'] ?? 'Usuário') ?></h5>
                                        <span class="badge rounded-pill" style="background-color: var(--escamboo-ocre); color: #fff;">
                                            <?php echo htmlspecialchars(! empty($m['ProfissaoOutro']) ? $m['ProfissaoOutro'] : 'Profissional') ?>
                                        </span>
                                        <?php if ($idOutroPerfil > 0): ?>
                                            <div class="mt-2">
                                                <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo $idOutroPerfil ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 fw-semibold">
                                                    <i class="bi bi-person-circle me-1"></i> Ver perfil
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div>
                                    <?php if ((int) $m['Status'] === 0): ?>
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-hourglass-split me-1"></i> Aguardando Resposta
                                        </span>
                                    <?php elseif ((int) $m['Status'] === 1): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-check-circle me-1"></i> Match Aceito!
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-x-circle me-1"></i> Recusado
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <div class="exchange-box h-100">
                                        <span class="text-uppercase small fw-bold text-primary d-block mb-1">
                                            <i class="bi bi-award-fill me-1"></i> O serviço que você quer:
                                        </span>
                                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($m['NomeServicoDesejado'] ?? 'Serviço solicitado') ?></h6>
                                        <p class="small text-muted mb-0"><?php echo htmlspecialchars($m['DescricaoServicoDesejado'] ?? '') ?></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="exchange-box h-100">
                                        <span class="text-uppercase small fw-bold text-success d-block mb-1">
                                            <i class="bi bi-gift-fill me-1"></i> O que você ofereceu:
                                        </span>
                                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($m['NomeMeuServicoOferecido'] ?? 'Seu serviço') ?></h6>
                                        <p class="small text-muted mb-0"><?php echo htmlspecialchars($m['DescricaoMeuServicoOferecido'] ?? '') ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if ((int) $m['Status'] === 0): ?>
                                <div class="p-3 bg-white rounded-3 border mb-3">
                                    <form action="<?php echo URL_BASE ?>/match/editar-mensagem" method="POST">
                                        <input type="hidden" name="idMatch" value="<?php echo (int) $m['IdMatch'] ?>">
                                        <label for="mensagem-match-<?php echo (int) $m['IdMatch'] ?>" class="fw-semibold small text-muted d-block mb-2">
                                            Sua mensagem enviada:
                                        </label>
                                        <textarea
                                            id="mensagem-match-<?php echo (int) $m['IdMatch'] ?>"
                                            name="mensagem"
                                            class="form-control mb-2"
                                            rows="3"><?php echo htmlspecialchars($m['Mensagem'] ?? '') ?></textarea>
                                        <button type="submit" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Salvar mensagem
                                        </button>
                                    </form>
                                </div>
                            <?php elseif (! empty($m['Mensagem'])): ?>
                                <div class="p-3 bg-white rounded-3 border mb-3">
                                    <span class="fw-semibold small text-muted d-block mb-1">Sua mensagem enviada:</span>
                                    <p class="fst-italic mb-0" style="font-size: 0.93rem;">"<?php echo nl2br(htmlspecialchars($m['Mensagem'])) ?>"</p>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>Enviado em <?php echo date('d/m/Y', strtotime($m['Data'])) ?></small>

                                <?php if ((int) $m['Status'] === 1 && ! empty($m['IdAcordo'])): ?>
                                    <a href="<?php echo URL_BASE ?>/acordo/detalhes?id=<?php echo (int)$m['IdAcordo'] ?>" class="btn btn-sm btn-dark rounded-pill px-3">
                                        <i class="bi bi-file-earmark-check me-1"></i> Acompanhar Acordo
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-5 text-center bg-white rounded-4 shadow-sm">
                        <i class="bi bi-send text-muted" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold mt-3" style="color: var(--escamboo-vinho);">Nenhuma proposta enviada ainda</h4>
                        <p class="text-muted mb-3">Navegue pelos cards na homepage e clique no botão verde ✔ para propor trocas!</p>
                        <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="btn btn-dark rounded-pill px-4">
                            Explorar Serviços Agora
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <div class="modal fade" id="modalDenunciarProposta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?php echo URL_BASE ?>/denuncia/criar" method="POST" class="modal-content rounded-4 border-0">
                <div class="modal-header" style="background-color: var(--escamboo-vinho); color: #fff;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-flag-fill me-2"></i>Denunciar proposta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="origem" value="matches">
                    <input type="hidden" name="idMatch" id="denunciaPropostaIdMatch">
                    <input type="hidden" name="idServico" id="denunciaPropostaIdServico">
                    <input type="hidden" name="idTrabalhador" id="denunciaPropostaIdTrabalhador">

                    <p class="small text-muted mb-3" id="denunciaPropostaDescricao"></p>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="denunciaPropostaMotivo">Motivo da denúncia <span class="text-danger">*</span></label>
                        <select name="motivo" id="denunciaPropostaMotivo" class="form-select">
                            <option value="">Selecione o motivo...</option>
                            <option value="Oferta falsa / fraude">Oferta falsa / fraude</option>
                            <option value="Conteúdo impróprio">Conteúdo impróprio</option>
                            <option value="Informações enganosas">Informações enganosas</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="denunciaPropostaDetalhes">Descrição dos detalhes</label>
                        <textarea name="descricao" id="denunciaPropostaDetalhes" class="form-control" rows="3" maxlength="255" placeholder="Explique o problema com esta proposta..."></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bloquearAposDenuncia" value="1" id="bloquearAutorProposta">
                        <label class="form-check-label fw-semibold" for="bloquearAutorProposta">
                            Bloquear também a pessoa que enviou esta proposta
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
        document.getElementById('modalDenunciarProposta').addEventListener('show.bs.modal', function (event) {
            const botao = event.relatedTarget;
            document.getElementById('bloquearAutorProposta').checked = false;
            document.getElementById('denunciaPropostaIdMatch').value = botao.getAttribute('data-id-match');
            document.getElementById('denunciaPropostaIdServico').value = botao.getAttribute('data-id-servico');
            document.getElementById('denunciaPropostaIdTrabalhador').value = botao.getAttribute('data-id-trabalhador');
            document.getElementById('denunciaPropostaDescricao').textContent =
                'Você está denunciando a proposta do serviço "' + botao.getAttribute('data-nome-servico') + '". A denúncia será avaliada pela administração.';
        });
    </script>
</body>
</html>
