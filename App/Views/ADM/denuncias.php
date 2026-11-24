<?php
$denuncias    = $denuncias ?? [];
$penalidades  = $penalidades ?? [];
$statusFiltro = $statusFiltro ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EscamBOO - Moderação e Denúncias</title>

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
            font-weight: bolder;
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
            color: #ffffff;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            background-color: #f1e9db;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .badge-status-0 {
            background-color: #ffc107;
            color: #000;
        }

        .badge-status-1 {
            background-color: #dc3545;
            color: #fff;
        }

        .badge-status-2 {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-vinho {
            background-color: #580816 !important;
            border-color: #580816 !important;
            color: #ffffff !important;
            border-radius: 20px;
        }

        .btn-vinho:hover {
            background-color: #40050f !important;
            color: #ffffff !important;
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
                <a href="<?= URL_BASE ?>/adm/denuncias" class="active">
                    <i class="bi bi-shield-exclamation me-2"></i> Denúncias & Penalidades
                </a>
                <a href="<?= URL_BASE ?>/adm/acordos-cancelados">
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

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h2 class="fw-bold mb-1" style="color: #580816;">Moderação de Denúncias e Penalidades</h2>
                        <p class="text-muted mb-0">Avalie denúncias de trabalhadores e aplique sanções cabíveis para manter a segurança da comunidade.</p>
                    </div>
                    <div>
                        <span class="badge bg-dark p-2 rounded-pill px-3">Moderação Ativa</span>
                    </div>
                </div>

                <!-- Painel de Denúncias -->
                <div class="card card-custom mb-5">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="fw-bold mb-0" style="color: #580816;">
                            <i class="bi bi-exclamation-octagon-fill me-2"></i> Denúncias Recebidas
                        </h4>
                        <div class="btn-group">
                            <a href="<?= URL_BASE ?>/adm/denuncias" class="btn btn-sm <?= $statusFiltro === null ? 'btn-dark' : 'btn-outline-dark' ?>">Todas</a>
                            <a href="<?= URL_BASE ?>/adm/denuncias?status=0" class="btn btn-sm <?= $statusFiltro === 0 ? 'btn-dark' : 'btn-outline-dark' ?>">Pendentes</a>
                            <a href="<?= URL_BASE ?>/adm/denuncias?status=1" class="btn btn-sm <?= $statusFiltro === 1 ? 'btn-dark' : 'btn-outline-dark' ?>">Procedentes</a>
                            <a href="<?= URL_BASE ?>/adm/denuncias?status=2" class="btn btn-sm <?= $statusFiltro === 2 ? 'btn-dark' : 'btn-outline-dark' ?>">Arquivadas</a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <?php if (empty($denuncias)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-check2-circle text-success fs-1"></i>
                                <h6 class="mt-2 fw-bold">Nenhuma denúncia encontrada para este filtro.</h6>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle bg-white rounded-3 overflow-hidden shadow-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Denunciado</th>
                                            <th>Denunciante</th>
                                            <th>Motivo / Descrição</th>
                                            <th>Data</th>
                                            <th>Status</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($denuncias as $d): ?>
                                            <tr>
                                                <td class="fw-bold"><?= (int)$d['IdDenuncia'] ?></td>
                                                <td>
                                                    <div class="fw-bold"><?= htmlspecialchars($d['NomeDenunciado']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($d['EmailDenunciado']) ?></small>
                                                    <div>
                                                        <span class="badge bg-secondary" style="font-size: 0.72rem;"><?= htmlspecialchars($d['StatusDenunciado'] ?? 'ATIVO') ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold"><?= htmlspecialchars($d['NomeDenunciante'] ?? 'Anônimo / Sistema') ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($d['EmailDenunciante'] ?? '') ?></small>
                                                </td>
                                                <td style="max-width: 320px;">
                                                    <div class="fw-bold text-danger"><?= htmlspecialchars($d['Motivo']) ?></div>
                                                    <div class="small text-muted"><?= !empty($d['Descricao']) ? nl2br(htmlspecialchars($d['Descricao'])) : '<em>Sem descrição</em>' ?></div>
                                                </td>
                                                <td><?= !empty($d['Data']) ? date('d/m/Y', strtotime($d['Data'])) : '—' ?></td>
                                                <td>
                                                    <?php if ((int)$d['Status'] === 0): ?>
                                                        <span class="badge badge-status-0 rounded-pill px-3 py-1">Pendente</span>
                                                    <?php elseif ((int)$d['Status'] === 1): ?>
                                                        <span class="badge badge-status-1 rounded-pill px-3 py-1">Procedente</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-status-2 rounded-pill px-3 py-1">Arquivada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ((int)$d['Status'] === 0): ?>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger rounded-pill px-2"
                                                                title="Aplicar Penalidade"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalPenalidade"
                                                                data-id-denuncia="<?= (int)$d['IdDenuncia'] ?>"
                                                                data-id-trabalhador="<?= (int)$d['IdDenunciado'] ?>"
                                                                data-nome="<?= htmlspecialchars($d['NomeDenunciado']) ?>"
                                                                data-motivo="<?= htmlspecialchars($d['Motivo']) ?>">
                                                                <i class="bi bi-hammer me-1"></i> Penalizar
                                                            </button>

                                                            <form action="<?= URL_BASE ?>/adm/denuncias/avaliar" method="POST" onsubmit="return confirm('Deseja arquivar esta denúncia como improcedente?');">
                                                                <input type="hidden" name="idDenuncia" value="<?= (int)$d['IdDenuncia'] ?>">
                                                                <input type="hidden" name="acao" value="arquivar">
                                                                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-2" title="Arquivar Denúncia">
                                                                    <i class="bi bi-archive-fill"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Avaliada</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Histórico de Penalidades Aplicadas -->
                <div class="card card-custom">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h4 class="fw-bold mb-0" style="color: #580816;">
                            <i class="bi bi-shield-shaded me-2"></i> Histórico de Penalidades Aplicadas
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($penalidades)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-shield-check text-muted fs-1"></i>
                                <h6 class="mt-2 fw-bold">Nenhuma penalidade aplicada até o momento.</h6>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle bg-white rounded-3 overflow-hidden shadow-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Trabalhador</th>
                                            <th>Tipo de Penalidade</th>
                                            <th>Motivo</th>
                                            <th>Data</th>
                                            <th>Administrador</th>
                                            <th>Status Atual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($penalidades as $p): ?>
                                            <tr>
                                                <td class="fw-bold"><?= (int)$p['IdPenalidade'] ?></td>
                                                <td>
                                                    <div class="fw-bold"><?= htmlspecialchars($p['NomeTrabalhador']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($p['EmailTrabalhador']) ?></small>
                                                </td>
                                                <td>
                                                    <?php if ((int)$p['Tipo'] === 1): ?>
                                                        <span class="badge bg-warning text-dark rounded-pill px-3"><i class="bi bi-clock-history me-1"></i> Suspensão</span>
                                                    <?php elseif ((int)$p['Tipo'] === 2): ?>
                                                        <span class="badge bg-danger rounded-pill px-3"><i class="bi bi-slash-circle me-1"></i> Banimento</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-info text-dark rounded-pill px-3"><i class="bi bi-exclamation-triangle me-1"></i> Advertência</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($p['Motivo']) ?></td>
                                                <td><?= !empty($p['Data']) ? date('d/m/Y', strtotime($p['Data'])) : '—' ?></td>
                                                <td><?= htmlspecialchars($p['NomeAdm']) ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= htmlspecialchars($p['StatusTrabalhador'] ?? 'ATIVO') ?></span>
                                                    <?php if (!empty($p['DataExpiraStatus']) && $p['StatusTrabalhador'] === 'SUSPENSO'): ?>
                                                        <br><small class="text-muted">Expira: <?= date('d/m/Y H:i', strtotime($p['DataExpiraStatus'])) ?></small>
                                                    <?php endif; ?>
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
        </div>
    </div>

    <!-- Modal Aplicar Penalidade -->
    <div class="modal fade" id="modalPenalidade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_BASE ?>/adm/penalidade/aplicar" method="POST" class="modal-content rounded-4 border-0">
                <div class="modal-header" style="background-color: #580816; color: #fff;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-hammer me-2"></i>Aplicar Penalidade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="idDenuncia" id="penalidade-idDenuncia">
                    <input type="hidden" name="idTrabalhador" id="penalidade-idTrabalhador">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Trabalhador</label>
                        <input type="text" id="penalidade-nome" class="form-control" readonly style="background-color: #f8f9fa;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de Sanção <span class="text-danger">*</span></label>
                        <select name="tipo" id="penalidade-tipo" class="form-select" onchange="toggleDiasSuspensao(this.value)">
                            <option value="1">Suspensão Temporária</option>
                            <option value="2">Banimento Definitivo</option>
                            <option value="3">Advertência Formal</option>
                        </select>
                    </div>

                    <div class="mb-3" id="campoDiasSuspensao">
                        <label class="form-label fw-bold">Prazo da Suspensão (Dias)</label>
                        <input type="number" name="diasSuspensao" class="form-control" value="7" min="1" max="365">
                        <small class="text-muted">Após o prazo, a conta retornará automaticamente ao status ATIVO no próximo login.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Motivo / Justificativa da Penalidade <span class="text-danger">*</span></label>
                        <textarea name="motivo" id="penalidade-motivo" class="form-control" rows="3" placeholder="Descreva o motivo da penalidade aplicada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Confirmar e Aplicar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDiasSuspensao(tipo) {
            const campo = document.getElementById('campoDiasSuspensao');
            if (campo) {
                campo.style.display = (tipo === '1') ? 'block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('modalPenalidade');
            if (modal) {
                modal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    document.getElementById('penalidade-idDenuncia').value = button.getAttribute('data-id-denuncia') || '';
                    document.getElementById('penalidade-idTrabalhador').value = button.getAttribute('data-id-trabalhador') || '';
                    document.getElementById('penalidade-nome').value = button.getAttribute('data-nome') || '';
                    document.getElementById('penalidade-motivo').value = button.getAttribute('data-motivo') || '';
                });
            }
        });
    </script>
</body>

</html>
