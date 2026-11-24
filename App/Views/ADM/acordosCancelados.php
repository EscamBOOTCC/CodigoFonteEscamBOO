<?php
$acordos = $acordos ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EscamBOO - Acordos Cancelados</title>

    <link rel="icon" type="image/png" href="<?= URL_BASE ?>/assets/logoEscamboo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #D9CAB3;
            min-height: 100vh;
            color: #2b2b2b;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #580816;
        }

        .sidebar h4 {
            color: #f1e9db;
            font-weight: 700;
            font-size: 38px;
        }

        .sidebar a {
            color: #e2d2be;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #40050f;
            color: #fff;
        }

        .card-custom {
            border: 0;
            border-radius: 16px;
            background-color: #f1e9db;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
        }

        .justificativa {
            min-width: 240px;
            max-width: 480px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-3 col-lg-2 sidebar p-3">
                <h4 class="text-center mb-2">EscamBOO</h4>
                <div class="mb-4 text-center text-light">
                    <small>Área Administrativa</small>
                </div>

                <a href="<?= URL_BASE ?>/adm">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="<?= URL_BASE ?>/usuarios">
                    <i class="bi bi-people-fill me-2"></i> Usuários
                </a>
                <a href="<?= URL_BASE ?>/adm/denuncias">
                    <i class="bi bi-shield-exclamation me-2"></i> Denúncias & Penalidades
                </a>
                <a href="<?= URL_BASE ?>/adm/acordos-cancelados" class="active">
                    <i class="bi bi-file-earmark-x me-2"></i> Acordos Cancelados
                </a>
                <a href="<?= URL_BASE ?>/grandes-areas">
                    <i class="bi bi-tags-fill me-2"></i> Grandes Áreas
                </a>
                <a href="<?= URL_BASE ?>/adm/cadastrar">
                    <i class="bi bi-person-plus-fill me-2"></i> Cadastrar ADM
                </a>
                <a href="<?= URL_BASE ?>/trabalhador/cadastrar">
                    <i class="bi bi-person-badge me-2"></i> Cadastrar Trabalhador
                </a>

                <hr class="text-secondary">

                <a href="<?= URL_BASE ?>/logout" class="text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Sair
                </a>
            </aside>

            <main class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h2 class="fw-bold mb-1" style="color: #580816;">Acordos Cancelados</h2>
                        <p class="text-muted mb-0">Consulte os participantes, serviços e justificativas informadas nos cancelamentos.</p>
                    </div>
                    <span class="badge text-bg-dark rounded-pill px-3 py-2">
                        <?= count($acordos) ?> <?= count($acordos) === 1 ? 'acordo' : 'acordos' ?>
                    </span>
                </div>

                <section class="card card-custom">
                    <div class="card-body p-4">
                        <?php if (empty($acordos)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-check fs-1" aria-hidden="true"></i>
                                <h5 class="mt-3 fw-bold">Nenhum acordo cancelado</h5>
                                <p class="mb-0">Quando um acordo for cancelado, a justificativa aparecerá aqui.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Acordo</th>
                                            <th scope="col">Cancelado em</th>
                                            <th scope="col">Participantes</th>
                                            <th scope="col">Serviços</th>
                                            <th scope="col">Justificativa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($acordos as $acordo): ?>
                                            <?php
                                            $dataCancelamento = $acordo['DataCancelamento'] ?? null;
                                            $timestampCancelamento = $dataCancelamento ? strtotime($dataCancelamento) : false;
                                            ?>
                                            <tr>
                                                <th scope="row">#<?= (int) $acordo['IdAcordo'] ?></th>
                                                <td>
                                                    <?= $timestampCancelamento !== false ? date('d/m/Y H:i', $timestampCancelamento) : 'Data indisponível' ?>
                                                </td>
                                                <td>
                                                    <div><?= htmlspecialchars($acordo['NomeTrabalhadorUm'] ?? 'Trabalhador não disponível', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <div class="text-muted small">e</div>
                                                    <div><?= htmlspecialchars($acordo['NomeTrabalhadorDois'] ?? 'Trabalhador não disponível', ENT_QUOTES, 'UTF-8') ?></div>
                                                </td>
                                                <td>
                                                    <div><?= htmlspecialchars($acordo['NomeServicoUm'] ?? 'Serviço não disponível', ENT_QUOTES, 'UTF-8') ?></div>
                                                    <div class="text-muted small">e</div>
                                                    <div><?= htmlspecialchars($acordo['NomeServicoDois'] ?? 'Serviço não disponível', ENT_QUOTES, 'UTF-8') ?></div>
                                                </td>
                                                <td class="justificativa">
                                                    <?php if (! empty($acordo['JustificativaCancelamento'])): ?>
                                                        <?= nl2br(htmlspecialchars($acordo['JustificativaCancelamento'], ENT_QUOTES, 'UTF-8')) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted fst-italic">Justificativa não registrada.</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>

</html>
