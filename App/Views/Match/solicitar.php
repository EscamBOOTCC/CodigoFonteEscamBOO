<?php
    $usuarioLogado     = $usuarioLogado ?? $_SESSION['usuario_logado'] ?? [];
    $servicoDestino    = $servicoDestino ?? [];
    $meusServicos      = $meusServicos ?? [];
    $erroSessao        = $_SESSION['erro'] ?? null;
    unset($_SESSION['erro']);
    $fotoPerfilLogado = ! empty($usuarioLogado['FotoPerfil'])
    ? $usuarioLogado['FotoPerfil']
    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposta de Match - EscamBOO</title>

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

        .nav-avatar-mini {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
        }

        .card-proposta {
            background-color: var(--escamboo-card-bg);
            border-radius: 28px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            border: none;
        }

        .card-header-destaque {
            background: linear-gradient(135deg, var(--escamboo-vinho) 0%, #430612 100%);
            color: #ffffff;
            padding: 24px 30px;
        }

        .box-servico-alvo {
            background-color: #f2e9dc;
            border-left: 5px solid var(--escamboo-vinho);
            border-radius: 16px;
            padding: 20px;
        }

        .btn-enviar-match {
            background-color: #198754;
            color: #ffffff;
            border-radius: 50px;
            padding: 12px 32px;
            font-weight: 700;
            font-size: 1.05rem;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 6px 18px rgba(25, 135, 84, 0.35);
        }

        .btn-enviar-match:hover {
            background-color: #146c43;
            transform: translateY(-2px);
            color: #ffffff;
            box-shadow: 0 8px 22px rgba(25, 135, 84, 0.45);
        }

        .btn-cancelar {
            color: var(--escamboo-vinho);
            font-weight: 600;
            text-decoration: none;
            border-radius: 50px;
            padding: 10px 24px;
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
                <span class="badge bg-light text-dark rounded-pill px-3 py-1 d-none d-md-inline-block">Proposta de Match</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="text-white text-decoration-none fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Voltar ao Feed
                </a>
                <a href="<?php echo URL_BASE ?>/trabalhador/perfil">
                    <img src="<?php echo htmlspecialchars($fotoPerfilLogado) ?>" alt="Perfil" class="nav-avatar-mini">
                </a>
            </div>
        </div>
    </nav>

    <main class="escamboo-container py-3 mb-5">

        <?php if (! empty($erroSessao)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> <?php echo htmlspecialchars($erroSessao) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <div class="card-proposta">
            <div class="card-header-destaque d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h2 class="h3 fw-bold mb-1"><i class="bi bi-fire text-warning me-2"></i>Propor Troca de Serviços</h2>
                    <p class="mb-0 text-white-50">Diga o que você oferece em troca para que o profissional possa aceitar seu match!</p>
                </div>
                <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold">
                    <i class="bi bi-arrow-left-right me-1"></i> Escambo
                </span>
            </div>

            <div class="p-4 p-md-5">
                <form action="<?php echo URL_BASE ?>/match/registrar" method="POST">
                    <input type="hidden" name="idServicoDestino" value="<?php echo (int)$servicoDestino['IdServico'] ?>">
                    <input type="hidden" name="idTrabalhadorDestino" value="<?php echo (int)$servicoDestino['IdTrabalhador'] ?>">

                    <div class="row g-4 mb-4">

                        <div class="col-12 col-md-6">
                            <h5 class="fw-bold mb-3" style="color: var(--escamboo-vinho);">
                                <i class="bi bi-box-seam-fill me-2"></i>1. Serviço que você deseja receber:
                            </h5>

                            <div class="box-servico-alvo h-100">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <a href="<?php echo URL_BASE ?>/trabalhador/perfil?id=<?php echo (int) $servicoDestino['IdTrabalhador'] ?>" title="Ver perfil de <?php echo htmlspecialchars($servicoDestino['NomeTrabalhador'] ?? 'Profissional') ?>">
                                        <img src="<?php echo htmlspecialchars(! empty($servicoDestino['FotoPerfil']) ? $servicoDestino['FotoPerfil'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop') ?>"
                                             alt="Prestador" class="rounded-circle shadow-sm" width="60" height="60" style="object-fit: cover;">
                                    </a>
                                    <div>
                                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($servicoDestino['NomeTrabalhador'] ?? 'Profissional') ?></h5>
                                        <span class="badge rounded-pill" style="background-color: var(--escamboo-ocre); color: #fff; font-size: 0.8rem;">
                                            <?php echo htmlspecialchars(! empty($servicoDestino['NomeProfissao']) ? $servicoDestino['NomeProfissao'] : 'Geral') ?>
                                        </span>
                                    </div>
                                </div>

                                <h4 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($servicoDestino['NomeServico'] ?? 'Serviço') ?></h4>
                                <p class="text-muted small mb-0">
                                    <?php echo nl2br(htmlspecialchars(! empty($servicoDestino['DescricaoServico']) ? $servicoDestino['DescricaoServico'] : 'Sem detalhes adicionais.')) ?>
                                </p>
                            </div>
                        </div>


                        <div class="col-12 col-md-6">
                            <h5 class="fw-bold mb-3" style="color: var(--escamboo-vinho);">
                                <i class="bi bi-gift-fill me-2"></i>2. O que você oferece em troca:
                            </h5>

                            <div class="mb-3">
                                <label for="idServicoOrigem" class="form-label fw-semibold">Selecione um dos seus serviços cadastrados:</label>
                                <?php if (! empty($meusServicos)): ?>
                                    <select class="form-select form-select-lg rounded-4 shadow-sm" id="idServicoOrigem" name="idServicoOrigem">
                                        <option value="">-- Escolha o serviço para a troca --</option>
                                        <?php foreach ($meusServicos as $meu): ?>
                                            <option value="<?php echo (int)$meu['IdServico'] ?>">
                                                <?php echo htmlspecialchars($meu['NomeServico']) ?> (<?php echo htmlspecialchars($meu['NomeProfissao'] ?? 'Geral') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted d-block mt-2">
                                        <i class="bi bi-check-circle text-success me-1"></i>O prestador poderá avaliar seu serviço antes de aceitar.
                                    </small>
                                <?php else: ?>
                                    <div class="alert alert-warning rounded-4 shadow-sm">
                                        <i class="bi bi-exclamation-circle me-1"></i> Você ainda não tem serviços cadastrados para oferecer em troca!
                                        <div class="mt-2">
                                            <a href="<?php echo URL_BASE ?>/trabalhador/servicos?aba=gerenciar" class="btn btn-sm btn-dark rounded-pill">
                                                Cadastrar um serviço agora
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="mensagem" class="form-label fw-semibold">Mensagem / Proposta inicial (opcional):</label>
                                <textarea class="form-control rounded-4 shadow-sm" id="mensagem" name="mensagem" rows="4"
                                          placeholder="Olá! Gostei muito do seu serviço de <?php echo htmlspecialchars($servicoDestino['NomeServico']) ?>. Posso realizar o meu serviço para você aos sábados ou de forma online..."></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color: #d1c1aa;">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <a href="<?php echo URL_BASE ?>/trabalhador/servicos" class="btn btn-cancelar">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>

                        <button type="submit" class="btn btn-enviar-match" <?php echo empty($meusServicos) ? 'disabled' : '' ?>>
                            <i class="bi bi-check2-circle me-2 fs-5"></i> Enviar Proposta de Match
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validação client-side: exige seleção de serviço antes de enviar
        document.querySelector('form[action$="/match/registrar"]')?.addEventListener('submit', function(e) {
            const select = document.getElementById('idServicoOrigem');
            if (select && !select.value) {
                e.preventDefault();
                // Exibe alerta inline
                let alerta = document.getElementById('alertaServicoNaoSelecionado');
                if (!alerta) {
                    alerta = document.createElement('div');
                    alerta.id = 'alertaServicoNaoSelecionado';
                    alerta.className = 'alert alert-warning rounded-3 mt-2 small py-2 px-3';
                    alerta.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Por favor, selecione um dos seus serviços para oferecer na troca.';
                    select.parentNode.appendChild(alerta);
                }
                select.focus();
            }
        });
    </script>
</body>
</html>
