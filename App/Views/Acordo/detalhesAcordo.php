<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $acordo = $acordo ?? [];?>
    <title>Acordo #<?php echo (int) ($acordo['IdAcordo'] ?? 0) ?> - EscamBOO</title>
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
            max-width: 1100px;
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

        .card-detalhes {
            background-color: var(--escamboo-card-bg);
            border-radius: 28px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            border: none;
        }

        .header-acordo {
            background: linear-gradient(135deg, var(--escamboo-vinho) 0%, #3a050f 100%);
            color: #ffffff;
            padding: 28px 32px;
        }

        .service-box-highlight {
            background-color: #f2e9dc;
            border-radius: 18px;
            padding: 22px;
            border-left: 6px solid var(--escamboo-vinho);
        }

        .contact-pill {
            background-color: #ffffff;
            border-radius: 50px;
            padding: 10px 22px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--escamboo-texto-escuro);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
        }

        .contact-pill:hover {
            transform: translateY(-2px);
            color: var(--escamboo-vinho);
        }

        .btn-concluir {
            background-color: #198754;
            color: #fff;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            border: none;
            transition: all 0.2s;
            box-shadow: 0 6px 18px rgba(25, 135, 84, 0.35);
        }

        .btn-concluir:hover {
            background-color: #146c43;
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-cancelar-acordo {
            background-color: transparent;
            color: #dc3545;
            border: 2px solid #dc3545;
            border-radius: 50px;
            padding: 10px 26px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-cancelar-acordo:hover {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>

<body>
    <?php
        $acordo = $acordo ?? [];
        $usuarioLogado = $usuarioLogado ?? ($_SESSION['usuario_logado'] ?? []);
        $sucesso = $sucesso ?? null;
        $erro = $erro ?? null;
        $jaAvaliei = $jaAvaliei ?? false;
    ?>

    <nav class="escamboo-navbar mb-4">
        <div class="escamboo-container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="escamboo-brand">
                    <img src="<?php echo URL_BASE ?>/assets/logoEscamboo.png" alt="EscamBOO" class="escamboo-brand-mark">
                    <span class="escamboo-logo">EscamBOO</span>
                </a>
                <span class="badge bg-light text-dark rounded-pill px-3 py-1 d-none d-md-inline-block">Acordo Formalizado</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo URL_BASE ?>/acordos" class="text-white text-decoration-none fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Voltar aos Acordos
                </a>
                <a href="<?php echo URL_BASE ?>/trabalhador/perfil">
                    <img src="<?php echo htmlspecialchars(! empty(($usuarioLogado ?? $_SESSION['usuario_logado'] ?? [])['FotoPerfil']) ? ($usuarioLogado ?? $_SESSION['usuario_logado'] ?? [])['FotoPerfil'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop') ?>" alt="Perfil" class="rounded-circle" width="44" height="44" style="object-fit: cover; border: 2px solid #fff;">
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

        <div class="card-detalhes">
            <div class="header-acordo d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-1 mb-2 fw-bold">
                        Acordo Oficial #<?php echo (int) $acordo['IdAcordo'] ?>
                    </span>
                    <h2 class="h3 fw-bold mb-1">Formalização do Escambo</h2>
                    <p class="mb-0 text-white-50">Ambas as partes aceitaram os termos da troca de serviços.</p>
                </div>

                <div>
                    <?php if ((int) $acordo['Status'] === 0): ?>
                        <span class="badge bg-warning text-dark rounded-pill px-4 py-2 fs-6 fw-bold">
                            <i class="bi bi-clock-history me-1"></i> Em Andamento
                        </span>
                    <?php elseif ((int) $acordo['Status'] === 1): ?>
                        <span class="badge bg-success rounded-pill px-4 py-2 fs-6 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Concluído com Sucesso
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger rounded-pill px-4 py-2 fs-6 fw-bold">
                            <i class="bi bi-x-circle-fill me-1"></i> Cancelado
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-4 p-md-5">

                <?php
                    $isTrabalhadorUm = (int) $acordo['IdTrabalhadorUm'] === (int) (($usuarioLogado ?? $_SESSION['usuario_logado'] ?? [])['IdTrabalhador'] ?? 0);

                    $nomeOutro      = $isTrabalhadorUm ? $acordo['NomeTrabalhadorDois'] : $acordo['NomeTrabalhadorUm'];
                    $fotoOutro      = $isTrabalhadorUm ? $acordo['FotoTrabalhadorDois'] : $acordo['FotoTrabalhadorUm'];
                    $idOutro        = $isTrabalhadorUm ? $acordo['IdTrabalhadorDois'] : $acordo['IdTrabalhadorUm'];
                    $telefoneOutro  = $isTrabalhadorUm ? $acordo['TelefoneTrabalhadorDois'] : $acordo['TelefoneTrabalhadorUm'];
                    $instagramOutro = $isTrabalhadorUm ? $acordo['InstagramTrabalhadorDois'] : $acordo['InstagramTrabalhadorUm'];
                    $twitterOutro   = $isTrabalhadorUm ? $acordo['TwitterTrabalhadorDois'] : $acordo['TwitterTrabalhadorUm'];
                    $enderecoOutro  = $isTrabalhadorUm ? $acordo['EnderecoTrabalhadorDois'] : $acordo['EnderecoTrabalhadorUm'];
                    $profissaoOutro = $isTrabalhadorUm ? $acordo['ProfissaoTrabalhadorDois'] : $acordo['ProfissaoTrabalhadorUm'];

                    $waDigits = (string) preg_replace('/\D/', '', (string) ($telefoneOutro ?? ''));
                    if ($waDigits !== '' && strlen($waDigits) <= 11 && strpos($waDigits, '55') !== 0) {
                        $waDigits = '55' . $waDigits;
                    }

                    $mensagemWhatsApp = sprintf(
                        'Olá, %s! Estou entrando em contato pelo EscamBOO sobre o nosso acordo #%d. Podemos combinar os detalhes da troca por aqui?',
                        $nomeOutro,
                        (int) ($acordo['IdAcordo'] ?? 0)
                    );
                    $waUrl = $waDigits !== ''
                        ? 'https://wa.me/' . $waDigits . '?text=' . rawurlencode($mensagemWhatsApp)
                        : null;
                ?>

                <div class="p-4 rounded-4 mb-4" style="background-color: #f7f1e6; border: 1px solid #e5d8c3;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo (int) $idOutro ?>" title="Ver perfil de <?php echo htmlspecialchars($nomeOutro) ?>">
                                <img src="<?php echo htmlspecialchars(! empty($fotoOutro) ? $fotoOutro : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop') ?>"
                                     alt="Foto de <?php echo htmlspecialchars($nomeOutro) ?>" class="rounded-circle shadow-sm" width="65" height="65" style="object-fit: cover;">
                            </a>
                            <div>
                                <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($nomeOutro) ?></h4>
                                <span class="badge rounded-pill" style="background-color: var(--escamboo-ocre); color: #fff;">
                                    <?php echo htmlspecialchars(! empty($profissaoOutro) ? $profissaoOutro : 'Profissional') ?>
                                </span>
                                <?php if (! empty($enderecoOutro)): ?>
                                    <span class="text-muted small ms-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?php echo htmlspecialchars($enderecoOutro) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <?php if (! empty($waUrl)): ?>
                                <a href="<?php echo htmlspecialchars($waUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="contact-pill text-success" title="Conversar sobre o acordo no WhatsApp">
                                    <i class="bi bi-whatsapp fs-5"></i>
                                    <span><?php echo htmlspecialchars($telefoneOutro) ?></span>
                                </a>
                            <?php endif; ?>

                            <?php if (! empty($instagramOutro)): ?>
                                <a href="<?php echo strpos((string) $instagramOutro, 'http') === 0 ? htmlspecialchars($instagramOutro) : 'https://instagram.com/' . ltrim(htmlspecialchars((string) $instagramOutro), '@') ?>" target="_blank" class="contact-pill" title="Instagram">
                                    <i class="bi bi-instagram fs-5 text-danger"></i>
                                    <span>Instagram</span>
                                </a>
                            <?php endif; ?>

                            <?php if (! empty($twitterOutro)): ?>
                                <a href="<?php echo strpos((string) $twitterOutro, 'http') === 0 ? htmlspecialchars($twitterOutro) : 'https://x.com/' . ltrim(htmlspecialchars((string) $twitterOutro), '@') ?>" target="_blank" class="contact-pill" title="Twitter / X">
                                    <i class="bi bi-twitter-x fs-5 text-dark"></i>
                                    <span>X</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php
                    $euJaConfirmei = $isTrabalhadorUm
                        ? (int) ($acordo['ConfirmacaoTrabalhadorUm'] ?? 0) === 1
                        : (int) ($acordo['ConfirmacaoTrabalhadorDois'] ?? 0) === 1;

                    $parceiroJaConfirmou = $isTrabalhadorUm
                        ? (int) ($acordo['ConfirmacaoTrabalhadorDois'] ?? 0) === 1
                        : (int) ($acordo['ConfirmacaoTrabalhadorUm'] ?? 0) === 1;
                ?>

                <div class="card rounded-4 border-0 p-4 mb-4 shadow-sm" style="background-color: #fff;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h5 class="fw-bold mb-0" style="color: var(--escamboo-vinho);">
                            <i class="bi bi-shield-check me-2 text-success"></i>Confirmação Mútua de Conclusão
                        </h5>
                        <span class="badge rounded-pill bg-light text-muted border px-3 py-2">
                            Ambos devem confirmar para concluir
                        </span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-4 border <?php echo $euJaConfirmei ? 'bg-success-subtle border-success' : 'bg-light' ?>">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fw-semibold d-block">Sua Confirmação:</span>
                                        <small class="text-muted"><?php echo htmlspecialchars(($usuarioLogado ?? $_SESSION['usuario_logado'] ?? [])['Nome'] ?? 'Você') ?></small>
                                    </div>
                                    <?php if ($euJaConfirmei): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Confirmado
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            <i class="bi bi-hourglass me-1"></i> Pendente
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-4 border <?php echo $parceiroJaConfirmou ? 'bg-success-subtle border-success' : 'bg-light' ?>">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fw-semibold d-block">Confirmação de <?php echo htmlspecialchars($nomeOutro) ?>:</span>
                                        <small class="text-muted">Parceiro de escambo</small>
                                    </div>
                                    <?php if ($parceiroJaConfirmou): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Confirmado
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            <i class="bi bi-hourglass me-1"></i> Pendente
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ((int) $acordo['Status'] === 0): ?>
                        <div class="mt-3">
                            <?php if ($parceiroJaConfirmou && ! $euJaConfirmei): ?>
                                <div class="alert alert-info rounded-3 mb-0 small py-2 px-3">
                                    <i class="bi bi-info-circle-fill me-1"></i>
                                    <strong><?php echo htmlspecialchars($nomeOutro) ?></strong> já confirmou a entrega do serviço! Clique em <strong>Confirmar Conclusão</strong> abaixo para finalizar o acordo.
                                </div>
                            <?php elseif ($euJaConfirmei && ! $parceiroJaConfirmou): ?>
                                <div class="alert alert-warning rounded-3 mb-0 small py-2 px-3">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Você já registrou sua confirmação. O acordo será finalizado assim que <strong><?php echo htmlspecialchars($nomeOutro) ?></strong> também confirmar.
                                </div>
                            <?php elseif (! $euJaConfirmei && ! $parceiroJaConfirmou): ?>
                                <div class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Quando os serviços forem realizados, ambos devem clicar em "Confirmar Conclusão" para finalizar a troca.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ((int) $acordo['Status'] === 1): ?>
                    <div class="card rounded-4 border-0 p-4 mb-4 shadow-sm" style="background-color: #fff;">
                        <h5 class="fw-bold mb-3" style="color: var(--escamboo-vinho);">
                            <i class="bi bi-star-fill me-2 text-warning"></i>Avaliar <?php echo htmlspecialchars($nomeOutro) ?>
                        </h5>

                        <?php if ($jaAvaliei ?? false): ?>
                            <div class="alert alert-success rounded-3 mb-0 small py-2 px-3">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Você já avaliou este acordo. Obrigado pelo feedback!
                            </div>
                        <?php else: ?>
                            <form action="<?php echo URL_BASE ?>/avaliacao/registrar" method="POST">
                                <input type="hidden" name="idAcordo" value="<?php echo (int) $acordo['IdAcordo'] ?>">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nota</label>
                                    <select name="nota" class="form-select" style="max-width: 220px;">
                                        <option value="">Selecione...</option>
                                        <option value="5">5 - Excelente</option>
                                        <option value="4">4 - Muito bom</option>
                                        <option value="3">3 - Bom</option>
                                        <option value="2">2 - Regular</option>
                                        <option value="1">1 - Ruim</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Comentário (opcional)</label>
                                    <textarea name="comentario" class="form-control" rows="3" maxlength="255" placeholder="Como foi a experiência com este trabalhador?"></textarea>
                                </div>

                                <button type="submit" class="btn btn-concluir">
                                    <i class="bi bi-send-fill me-1"></i> Enviar Avaliação
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                    $meuServicoNome   = $isTrabalhadorUm ? $acordo['NomeServicoUm'] : $acordo['NomeServicoDois'];
                    $meuServicoDesc   = $isTrabalhadorUm ? $acordo['DescricaoServicoUm'] : $acordo['DescricaoServicoDois'];
                    $servicoOutroNome = $isTrabalhadorUm ? $acordo['NomeServicoDois'] : $acordo['NomeServicoUm'];
                    $servicoOutroDesc = $isTrabalhadorUm ? $acordo['DescricaoServicoDois'] : $acordo['DescricaoServicoUm'];
                ?>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-6">
                        <div class="service-box-highlight h-100">
                            <span class="text-uppercase small fw-bold text-success d-block mb-1">
                                <i class="bi bi-gift-fill me-1"></i> O que você se comprometeu a realizar:
                            </span>
                            <h4 class="fw-bold mb-2"><?php echo htmlspecialchars($meuServicoNome ?? 'Seu serviço') ?></h4>
                            <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars(! empty($meuServicoDesc) ? $meuServicoDesc : 'Sem detalhes adicionais informados.')) ?></p>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="service-box-highlight h-100" style="border-left-color: var(--escamboo-ocre);">
                            <span class="text-uppercase small fw-bold text-primary d-block mb-1">
                                <i class="bi bi-box-seam-fill me-1"></i> O que <?php echo htmlspecialchars($nomeOutro) ?> irá realizar para você:
                            </span>
                            <h4 class="fw-bold mb-2"><?php echo htmlspecialchars($servicoOutroNome ?? 'Serviço do parceiro') ?></h4>
                            <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars(! empty($servicoOutroDesc) ? $servicoOutroDesc : 'Sem detalhes adicionais informados.')) ?></p>
                        </div>
                    </div>
                </div>

                <?php if (! empty($acordo['MensagemProposta'])): ?>
                    <div class="p-3 bg-white rounded-4 border mb-4">
                        <span class="small fw-semibold text-muted d-block mb-1"><i class="bi bi-chat-left-text-fill me-1"></i>Mensagem acordada no Match:</span>
                        <p class="fst-italic mb-0">"<?php echo nl2br(htmlspecialchars($acordo['MensagemProposta'])) ?>"</p>
                    </div>
                <?php endif; ?>

                <?php if ((int) $acordo['Status'] === 2 && ! empty($acordo['JustificativaCancelamento'])): ?>
                    <div class="alert alert-danger rounded-4 p-4 mb-4">
                        <h5 class="fw-bold"><i class="bi bi-exclamation-octagon-fill me-2"></i>Justificativa de Cancelamento:</h5>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($acordo['JustificativaCancelamento'])) ?></p>
                    </div>
                <?php endif; ?>

                <div class="p-3 rounded-4 mb-4 bg-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <small class="text-muted d-block"><i class="bi bi-calendar3 me-1"></i>Formalizado em: <?php echo ! empty($acordo['DataCriacao']) ? date('d/m/Y \à\s H:i', strtotime($acordo['DataCriacao'])) : 'Recente' ?></small>
                        <?php if (! empty($acordo['DataConclusao'])): ?>
                            <small class="text-muted d-block"><i class="bi bi-flag-fill me-1"></i>Finalizado em: <?php echo date('d/m/Y \à\s H:i', strtotime($acordo['DataConclusao'])) ?></small>
                        <?php endif; ?>
                    </div>

                    <?php if ((int) $acordo['Status'] === 0): ?>
                        <div class="d-flex gap-3 flex-wrap align-items-center">
                            <button type="button" class="btn btn-cancelar-acordo" data-bs-toggle="modal" data-bs-target="#modalCancelar">
                                <i class="bi bi-x-circle me-1"></i> Cancelar Acordo
                            </button>

                            <?php if ($euJaConfirmei): ?>
                                <button type="button" class="btn btn-outline-success rounded-pill px-4 py-2 fw-semibold" disabled>
                                    <i class="bi bi-check-circle-fill me-1"></i> Você confirmou a conclusão
                                </button>
                            <?php else: ?>
                                <form action="<?php echo URL_BASE ?>/acordo/concluir" method="POST" id="formConcluir">
                                    <input type="hidden" name="idAcordo" value="<?php echo (int) $acordo['IdAcordo'] ?>">

                                    <div class="row g-3 mb-3">
                                        <div class="col-12 col-lg-6">
                                            <div class="p-3 border rounded-4 h-100" style="background-color: #fffaf1; border-color: #e7d7b5 !important;">
                                                <h6 class="fw-bold mb-3" style="color: var(--escamboo-vinho);">
                                                    <i class="bi bi-person-check-fill me-2"></i>Avaliação da pessoa
                                                </h6>
                                                <div class="mb-3">
                                                    <label for="notaParceiro" class="form-label fw-semibold">Nota do parceiro *</label>
                                                    <select id="notaParceiro" name="notaParceiro" class="form-select">
                                                        <option value="">Selecione...</option>
                                                        <option value="5">5 - Excelente</option>
                                                        <option value="4">4 - Muito bom</option>
                                                        <option value="3">3 - Bom</option>
                                                        <option value="2">2 - Regular</option>
                                                        <option value="1">1 - Ruim</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="comentarioParceiro" class="form-label fw-semibold">Comentário do parceiro</label>
                                                    <textarea id="comentarioParceiro" name="comentarioParceiro" class="form-control" rows="3" maxlength="255" placeholder="Como foi a experiência com esta pessoa?"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="p-3 border rounded-4 h-100" style="background-color: #f4f6ff; border-color: #c9d4f5 !important;">
                                                <h6 class="fw-bold mb-3" style="color: #1d3d7a;">
                                                    <i class="bi bi-clipboard-check-fill me-2"></i>Avaliação do acordo
                                                </h6>
                                                <div class="mb-3">
                                                    <label for="notaAcordo" class="form-label fw-semibold">Nota do escambo *</label>
                                                    <select id="notaAcordo" name="notaAcordo" class="form-select">
                                                        <option value="">Selecione...</option>
                                                        <option value="5">5 - Excelente</option>
                                                        <option value="4">4 - Muito bom</option>
                                                        <option value="3">3 - Bom</option>
                                                        <option value="2">2 - Regular</option>
                                                        <option value="1">1 - Ruim</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="comentarioAcordo" class="form-label fw-semibold">Comentário do acordo</label>
                                                    <textarea id="comentarioAcordo" name="comentarioAcordo" class="form-control" rows="3" maxlength="255" placeholder="O que você achou do processo de troca e da entrega?"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-concluir" data-bs-toggle="modal" data-bs-target="#modalConcluir">
                                        <i class="bi bi-check2-all me-1"></i> Confirmar Conclusão
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </main>

    <div class="modal fade" id="modalCancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger" id="modalCancelarLabel">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Cancelar Acordo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form action="<?php echo URL_BASE ?>/acordo/cancelar" method="POST">
                    <div class="modal-body py-3">
                        <input type="hidden" name="idAcordo" value="<?php echo (int) $acordo['IdAcordo'] ?>">
                        <p class="text-muted small">
                            Conforme as regras do EscamBOO, informe o motivo pelo qual você está cancelando esta troca de serviços.
                        </p>
                        <div class="mb-3">
                            <label for="justificativa" class="form-label fw-semibold">Justificativa de Cancelamento *</label>
                            <textarea class="form-control rounded-3" id="justificativa" name="justificativa" rows="4"
                                      placeholder="Ex: Não conseguimos alinhar os horários; Houve um imprevisto de saúde; etc..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Voltar</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Confirmar Cancelamento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Confirmar Conclusão do Acordo -->
    <div class="modal fade" id="modalConcluir" tabindex="-1" aria-labelledby="modalConcluirLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalConcluirLabel" style="color: var(--escamboo-vinho);">
                        <i class="bi bi-check2-circle me-2 text-success"></i> Confirmar Conclusão do Acordo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="text-muted small mb-0">
                        Você está confirmando que o seu serviço foi <strong>entregue e concluído</strong>. 
                        Certifique-se de ter avaliado o parceiro e o acordo antes de continuar.
                        O acordo só será encerrado quando <strong>ambas as partes</strong> confirmarem.
                    </p>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success rounded-pill px-4 fw-bold"
                            onclick="document.getElementById('formConcluir').submit();">
                        <i class="bi bi-check2-all me-1"></i> Sim, confirmar conclusão
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>