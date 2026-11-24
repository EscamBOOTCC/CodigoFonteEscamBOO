<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EscamBOO - Dashboard ADM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', 'Segoe UI Emoji', 'Apple Color Emoji', 'Noto Color Emoji', sans-serif;
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
            font-size: 45px;
        }

        .sidebar a {
            color: #e2d2be;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background-color: #40050f;
            color: #ffffff;
        }

        

        .dashboard-card {
            border: none;
            border-radius: 12px;
            background-color: #f1e9db;
            color: #3b080f;
        }

        .action-card {
            border: none;
            border-radius: 12px;
            background-color: #f1e9db;
            color: #3b080f;
            transition: 0.2s;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        

        .card {
            background-color: #f1e9db;
            color: #3b080f;
            border-radius: 12px !important;
        }

        

        .card-body h4 {
            color: #580816;
        }

        .card-body h5 {
            color: #580816;
        }

        .card-body h6 {
            color: #580816;
        }

        

        .card-body .border {
            border-color: #d1c4b2 !important;
            background-color: #ffffff;
            border-radius: 10px !important;
        }

        

        .card-body .text-muted {
            color: #6c5a4c !important;
        }

        main>div .text-muted {
            color: #6c5a4c !important;
        }

        

        .btn-dark {
            background-color: #580816 !important;
            border-color: #580816 !important;
            color: #ffffff !important;
            border-radius: 6px;
        }

        .btn-dark:hover {
            background-color: #40050f !important;
            border-color: #40050f !important;
        }

        

        .btn-outline-dark {
            color: #580816 !important;
            border-color: #580816 !important;
            background-color: transparent !important;
            border-radius: 6px;
        }

        .btn-outline-dark:hover {
            background-color: #580816 !important;
            color: #ffffff !important;
        }

        

        .badge.bg-dark {
            background-color: #580816 !important;
            border-radius: 20px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            
            <aside class="col-md-3 col-lg-2 sidebar p-3">

                <h4 class="text-center mb-4">
                    EscamBOO
                </h4>

                <div class="mb-4 text-center text-light">
                    <small>Área administrativa</small>
                </div>

                <a href="<?= URL_BASE ?>/adm" class="active">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>

                <a href="<?= URL_BASE ?>/usuarios">
                    <i class="bi bi-people-fill me-2"></i> Usuários
                </a>

                <a href="<?= URL_BASE ?>/adm/cadastrar">
                    <i class="bi bi-person-plus-fill me-2"></i> Cadastrar ADM
                </a>

                <a href="<?= URL_BASE ?>/trabalhador/cadastrar">
                    <i class="bi bi-person-badge me-2"></i> Cadastrar Trabalhador
                </a>

                <a href="<?= URL_BASE ?>/adm/denuncias">
                    <i class="bi bi-shield-exclamation me-2"></i> Denúncias & Penalidades
                </a>

                <a href="<?= URL_BASE ?>/adm/acordos-cancelados">
                    <i class="bi bi-file-earmark-x me-2"></i> Acordos Cancelados
                </a>

                <a href="<?= URL_BASE ?>/grandes-areas">
                    <i class="bi bi-tags-fill me-2"></i> Gerenciar Grandes Áreas
                </a>

                <hr class="text-secondary">

                <a href="<?= URL_BASE ?>/logout" class="text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Sair
                </a>

                

            </aside>

            
            <main class="col-md-9 col-lg-10 p-4">

                
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="fw-bold mb-1">
                            Dashboard
                        </h2>

                        <p class="text-muted mb-0">
                            Bem-vindo à área administrativa do EscamBOO.
                        </p>
                    </div>

                    <div>
                        <span class="badge bg-dark p-2">
                            Administrador
                        </span>
                    </div>

                </div>

                
                <div class="row g-4 mb-4">

                    <div class="col-md-3">

                        <div class="card dashboard-card shadow-sm p-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <small class="text-muted">
                                        Usuários cadastrados
                                    </small>

                                    <h3 class="fw-bold mt-2 mb-0">
                                        <?= htmlspecialchars((string) ($totalUsuarios ?? 0)) ?>
                                    </h3>
                                </div>

                                <div class="fs-1">
                                    <i class="bi bi-people-fill" style="color: #580816;"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card dashboard-card shadow-sm p-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <small class="text-muted">
                                        Acordos Ativos
                                    </small>

                                    <h3 class="fw-bold mt-2 mb-0">
                                        <?= htmlspecialchars((string) ($totalAcordosAtivos ?? 0)) ?>
                                    </h3>
                                    <a href="<?= URL_BASE ?>/adm/acordos-cancelados" class="small text-decoration-none text-danger">
                                        <?= htmlspecialchars((string) ($totalAcordosCancelados ?? 0)) ?> cancelados · ver justificativas
                                    </a>
                                </div>

                                <div class="fs-1">
                                    <i class="bi bi-tools" style="color: #580816;"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card dashboard-card shadow-sm p-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <small class="text-muted">
                                        Denúncias Pendentes
                                    </small>

                                    <h3 class="fw-bold mt-2 mb-0 text-danger">
                                        <?= htmlspecialchars((string) ($totalDenunciasPendentes ?? 0)) ?>
                                    </h3>
                                </div>

                                <div class="fs-1">
                                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card dashboard-card shadow-sm p-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <small class="text-muted">
                                        Administradores
                                    </small>

                                    <h3 class="fw-bold mt-2 mb-0">
                                        <?= htmlspecialchars((string) ($totalAdministradores ?? 0)) ?>
                                    </h3>
                                </div>

                                <div class="fs-1">
                                    <i class="bi bi-shield-fill-check" style="color: #580816;"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                
                <h4 class="fw-bold mb-3">
                    Ações rápidas
                </h4>

                <div class="row g-4">

                    
                    <div class="col-md-6 col-lg-4">

                        <a href="<?= URL_BASE ?>/usuarios" class="text-decoration-none text-dark">

                            <div class="card action-card shadow-sm h-100 p-4">

                                <div class="fs-1 mb-3">
                                    <i class="bi bi-people-fill" style="color: #580816;"></i>
                                </div>

                                <h5 class="fw-bold">
                                    Usuários
                                </h5>

                                <p class="text-muted mb-0">
                                    Visualize e gerencie os usuários cadastrados no sistema.
                                </p>

                            </div>

                        </a>

                    </div>

                    
                    <div class="col-md-6 col-lg-4">

                        <a href="<?= URL_BASE ?>/adm/cadastrar" class="text-decoration-none text-dark">

                            <div class="card action-card shadow-sm h-100 p-4">

                                <div class="fs-1 mb-3">
                                    <i class="bi bi-person-plus-fill" style="color: #580816;"></i>
                                </div>

                                <h5 class="fw-bold">
                                    Cadastrar ADM
                                </h5>

                                <p class="text-muted mb-0">
                                    Cadastre novos administradores para o sistema.
                                </p>

                            </div>

                        </a>

                    </div>

                    
                    <div class="col-md-6 col-lg-4">

                        <a href="<?= URL_BASE ?>/trabalhador/cadastrar" class="text-decoration-none text-dark">

                            <div class="card action-card shadow-sm h-100 p-4">

                                <div class="fs-1 mb-3">
                                    <i class="bi bi-person-badge-fill" style="color: #580816;"></i>
                                </div>

                                <h5 class="fw-bold">
                                    Cadastrar Trabalhador
                                </h5>

                                <p class="text-muted mb-0">
                                    Cadastre um novo trabalhador no sistema.
                                </p>

                            </div>

                        </a>

                    </div>

                    <div class="col-md-6 col-lg-4">
                        <a href="<?= URL_BASE ?>/adm/acordos-cancelados" class="text-decoration-none text-dark">
                            <div class="card action-card shadow-sm h-100 p-4">
                                <div class="fs-1 mb-3">
                                    <i class="bi bi-file-earmark-x-fill text-danger"></i>
                                </div>
                                <h5 class="fw-bold">Acordos Cancelados</h5>
                                <p class="text-muted mb-0">
                                    Consulte os motivos informados para cancelamentos de acordos.
                                </p>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <a href="<?= URL_BASE ?>/adm/denuncias" class="text-decoration-none text-dark">
                            <div class="card action-card shadow-sm h-100 p-4">
                                <div class="fs-1 mb-3">
                                    <i class="bi bi-shield-exclamation text-danger"></i>
                                </div>
                                <h5 class="fw-bold">Denúncias & Penalidades</h5>
                                <p class="text-muted mb-0">
                                    Modere denúncias de serviços e aplique penalidades.
                                </p>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <a href="<?= URL_BASE ?>/grandes-areas" class="text-decoration-none text-dark">
                            <div class="card action-card shadow-sm h-100 p-4">
                                <div class="fs-1 mb-3">
                                    <i class="bi bi-tags-fill" style="color: #580816;"></i>
                                </div>
                                <h5 class="fw-bold">Grandes Áreas</h5>
                                <p class="text-muted mb-0">
                                    Cadastre e organize as categorias de atuação do sistema.
                                </p>
                            </div>
                        </a>
                    </div>

                </div>

                
                <div class="card shadow-sm border-0 rounded-3 mt-4">

                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-3">
                            Gerenciamento
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <div class="border rounded p-3 dashboard-management-item">

                                    <h6 class="fw-bold">
                                        Gerenciar usuários
                                    </h6>

                                    <p class="text-muted small">
                                        Acesse a listagem para consultar e editar os usuários cadastrados.
                                    </p>

                                    <a href="<?= URL_BASE ?>/usuarios"
                                        class="btn btn-dark btn-sm">
                                        Acessar usuários
                                    </a>

                                </div>

                            </div>

                            <div class="col-md-6 mb-3">

                                <div class="border rounded p-3 dashboard-management-item">

                                    <h6 class="fw-bold">
                                        Editar usuário
                                    </h6>

                                    <p class="text-muted small">
                                        A edição dos usuários pode ser realizada diretamente pela listagem.
                                    </p>

                                    <a href="<?= URL_BASE ?>/usuarios"
                                        class="btn btn-outline-dark btn-sm">
                                        Gerenciar
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </main>

        </div>
    </div>

</body>

</html>