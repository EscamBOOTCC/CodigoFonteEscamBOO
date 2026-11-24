<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferências do Perfil - EscamBOO</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --escamboo-vinho: #590b1c;
            --escamboo-fundo: #ded5c2;
            --escamboo-card-bg: #fcf9f2;
            --escamboo-ocre: #ba8238;
            --escamboo-texto-escuro: #2b1f1d;
            --escamboo-texto-muted: #73675a;
            --escamboo-border: #e0d5c1;
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

        /* Container Padrão */
        .escamboo-container {
            width: 94%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Navbar EscamBOO */
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

        /* Conteúdo Principal */
        .main-content {
            padding-top: 2.5rem;
            padding-bottom: 4rem;
        }

        /* Card do Formulário */
        .card-preferencias {
            background-color: var(--escamboo-card-bg);
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            padding: 2.5rem 2rem;
            border: none;
        }

        .card-titulo {
            color: var(--escamboo-vinho);
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .card-subtitulo {
            color: var(--escamboo-texto-muted);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        /* Form Labels & Controls */
        .form-label-custom {
            color: var(--escamboo-texto-escuro);
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .form-select-custom {
            background-color: #ffffff;
            border: 2px solid var(--escamboo-border);
            border-radius: 14px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: var(--escamboo-texto-escuro);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-select-custom:focus {
            border-color: var(--escamboo-vinho);
            box-shadow: 0 0 0 0.25rem rgba(89, 11, 28, 0.15);
            outline: none;
        }

        /* Custom Checkboxes */
        .checkbox-card {
            background-color: #ffffff;
            border: 2px solid var(--escamboo-border);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            transition: border-color 0.2s, background-color 0.2s;
            cursor: pointer;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .checkbox-card:hover {
            border-color: var(--escamboo-ocre);
            background-color: #fffefb;
        }

        .interesses-lista {
            max-height: 340px;
            overflow-y: auto;
            padding: 0.75rem;
            border: 1px solid var(--escamboo-border);
            border-radius: 18px;
            background-color: #f7f2e9;
        }

        .checkbox-card.selecionado {
            border-color: var(--escamboo-vinho);
            background-color: #f9edf0;
        }

        .form-check-input {
            width: 1.25em;
            height: 1.25em;
            margin-top: 0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--escamboo-vinho);
            border-color: var(--escamboo-vinho);
        }

        .form-check-input:focus {
            border-color: var(--escamboo-vinho);
            box-shadow: 0 0 0 0.25rem rgba(89, 11, 28, 0.15);
        }

        .form-check-label {
            color: var(--escamboo-texto-escuro);
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            margin-left: 0.3rem;
            width: 100%;
        }

        /* Botão Ação Principal */
        .btn-escamboo-submit {
            background-color: var(--escamboo-vinho);
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            padding: 14px 28px;
            border-radius: 50px;
            border: none;
            box-shadow: 0 6px 18px rgba(89, 11, 28, 0.25);
            transition: transform 0.2s, background-color 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 100%;
        }

        .btn-escamboo-submit:hover {
            background-color: #3b0611;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(89, 11, 28, 0.35);
        }

        /* Responsividade */
        @media (max-width: 576px) {
            .card-preferencias {
                padding: 1.75rem 1.25rem;
                border-radius: 20px;
            }

            .card-titulo {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Header / Navbar -->
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
                    <img src="<?= htmlspecialchars(!empty($_SESSION['usuario_logado']['FotoPerfil']) ? $_SESSION['usuario_logado']['FotoPerfil'] : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=350&auto=format&fit=crop') ?>" alt="Perfil" class="nav-avatar-mini">
                </a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo do Formulário -->
    <main class="escamboo-container main-content">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7">
                <div class="card-preferencias">
                    <?php if (!empty($origemEditar)): ?>
                        <a href="<?= URL_BASE ?>/trabalhador/editar" class="text-decoration-none fw-semibold d-inline-flex align-items-center gap-2 mb-3" style="color: var(--escamboo-vinho);">
                            <i class="bi bi-arrow-left"></i> Voltar para Editar Perfil
                        </a>
                    <?php endif; ?>

                    <h2 class="card-titulo text-center">Preferências profissionais</h2>
                    <p class="card-subtitulo text-center">Defina sua área de atuação e profissão para encontrar as melhores oportunidades de troca de serviços.</p>

                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                            <?= htmlspecialchars($erro) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= URL_BASE ?>/trabalhador/salvar-preferencias" method="POST">
                        <?php if (!empty($origemEditar)): ?>
                            <input type="hidden" name="origem" value="editar">
                        <?php endif; ?>

                        <!-- Select Grande Área -->
                        <div class="mb-4">
                            <label for="idGrandeArea" class="form-label-custom">
                                <i class="bi bi-grid-fill text-muted me-1"></i> Sua Grande Área de Atuação
                            </label>
                            <select class="form-select form-select-custom" id="idGrandeArea" name="idGrandeArea">
                                <option value="" disabled <?= empty($trabalhador['IdGrandeArea']) ? 'selected' : '' ?>>Selecione uma área...</option>
                                <?php if (!empty($grandesAreas)): ?>
                                    <?php foreach ($grandesAreas as $area): ?>
                                        <option value="<?= (int) $area['IdGrandeArea'] ?>"
                                            <?= (int) ($trabalhador['IdGrandeArea'] ?? 0) === (int) $area['IdGrandeArea'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($area['Nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Select Profissão -->
                        <div class="mb-4">
                            <label for="idProfissao" class="form-label-custom">
                                <i class="bi bi-briefcase-fill text-muted me-1"></i> Sua Profissão Principal
                            </label>
                            <select class="form-select form-select-custom" id="idProfissao" name="idProfissao">
                                <option value="" disabled <?= empty($trabalhador['IdProfissao']) ? 'selected' : '' ?>>Selecione sua profissão...</option>
                                <?php if (!empty($trabalhador['IdProfissao']) && !empty($trabalhador['NomeProfissao'])): ?>
                                    <option value="<?= (int) $trabalhador['IdProfissao'] ?>" selected>
                                        <?= htmlspecialchars($trabalhador['NomeProfissao']) ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom d-block mb-3">
                                <i class="bi bi-heart-fill text-muted me-1"></i> Áreas de interesse (o que você busca?)
                            </label>
                            <p class="small text-muted mb-2">Selecione uma ou mais áreas. A lista acompanha as Grandes Áreas cadastradas.</p>
                            <input type="search"
                                id="buscaInteresses"
                                class="form-control form-select-custom mb-2"
                                placeholder="Buscar uma área..."
                                aria-label="Buscar áreas de interesse">
                            <div class="d-flex justify-content-between align-items-center small mb-2">
                                <span id="contadorInteresses" class="text-muted" aria-live="polite">0 áreas selecionadas</span>
                                <button type="button" id="limparInteresses" class="btn btn-link btn-sm p-0 text-decoration-none" style="color: var(--escamboo-vinho);">Limpar seleção</button>
                            </div>
                            <div class="interesses-lista">
                                <div class="row g-2" id="listaInteresses">
                                    <?php $interessesSelecionados = $interessesSelecionados ?? []; ?>
                                    <?php foreach ($grandesAreas as $area): ?>
                                        <?php $idAreaInteresse = (int) $area['IdGrandeArea']; ?>
                                        <?php $interesseMarcado = in_array($idAreaInteresse, $interessesSelecionados, true); ?>
                                        <div class="col-sm-6 interesse-item">
                                            <div class="checkbox-card <?= $interesseMarcado ? 'selecionado' : '' ?>">
                                                <div class="form-check m-0 d-flex align-items-center w-100">
                                                    <input class="form-check-input interesse-checkbox"
                                                        type="checkbox"
                                                        name="interesses[]"
                                                        value="<?= $idAreaInteresse ?>"
                                                        id="interesseArea<?= $idAreaInteresse ?>"
                                                        <?= $interesseMarcado ? 'checked' : '' ?>>
                                                    <label class="form-check-label ms-2" for="interesseArea<?= $idAreaInteresse ?>">
                                                        <?= htmlspecialchars($area['Nome']) ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <p id="semInteressesEncontrados" class="text-center small text-muted my-3 d-none">Nenhuma área encontrada.</p>
                            </div>
                        </div>

                        <!-- Botão Submeter -->
                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn-escamboo-submit">
                                <?= !empty($origemEditar)
                                    ? 'Salvar Preferências e Voltar para Editar Perfil'
                                    : 'Salvar Preferências e Ver Serviços' ?>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                            <?php if (empty($origemEditar)): ?>
                                <a href="<?= URL_BASE ?>/trabalhador/servicos"
                                   class="d-block mt-3 text-center fw-semibold text-decoration-none"
                                   style="color: var(--escamboo-vinho);">
                                    Pular por enquanto
                                </a>
                            <?php endif; ?>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const grandeArea = document.querySelector("#idGrandeArea");
        const profissao = document.querySelector("#idProfissao");
        const buscaInteresses = document.querySelector("#buscaInteresses");
        const itensInteresse = Array.from(document.querySelectorAll(".interesse-item"));
        const checkboxesInteresse = Array.from(document.querySelectorAll(".interesse-checkbox"));
        const contadorInteresses = document.querySelector("#contadorInteresses");
        const semInteressesEncontrados = document.querySelector("#semInteressesEncontrados");

        function atualizarInteresses() {
            const totalSelecionados = checkboxesInteresse.filter(checkbox => checkbox.checked).length;
            contadorInteresses.textContent = `${totalSelecionados} ${totalSelecionados === 1 ? "área selecionada" : "áreas selecionadas"}`;
        }

        function filtrarInteresses() {
            const busca = buscaInteresses.value.trim().toLocaleLowerCase("pt-BR");
            let itensVisiveis = 0;

            itensInteresse.forEach(item => {
                const corresponde = item.textContent.toLocaleLowerCase("pt-BR").includes(busca);
                item.classList.toggle("d-none", !corresponde);
                if (corresponde) {
                    itensVisiveis++;
                }
            });

            semInteressesEncontrados.classList.toggle("d-none", itensVisiveis > 0);
        }

        checkboxesInteresse.forEach(checkbox => checkbox.addEventListener("change", function() {
            this.closest(".checkbox-card").classList.toggle("selecionado", this.checked);
            atualizarInteresses();
        }));
        buscaInteresses.addEventListener("input", filtrarInteresses);
        document.querySelector("#limparInteresses").addEventListener("click", function() {
            checkboxesInteresse.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.closest(".checkbox-card").classList.remove("selecionado");
            });
            atualizarInteresses();
        });
        atualizarInteresses();

        grandeArea.addEventListener("change", function() {

            const idGrandeArea = this.value;
            const profissaoSelecionada = <?= (int) ($trabalhador['IdProfissao'] ?? 0) ?>;

            profissao.innerHTML = "<option value=''>Carregando...</option>";

            if (idGrandeArea === "") {
                profissao.innerHTML = "<option value=''>Selecione uma profissão</option>";
                return;
            }

            fetch("<?php echo URL_BASE; ?>/trabalhador/profissoes-por-grande-area?id_grande_area=" + idGrandeArea)
                .then(response => response.json())
                .then(profissoes => {

                    profissao.innerHTML = "<option value=''>Selecione uma profissão</option>";

                    profissoes.forEach(profissaoItem => {

                        const option = document.createElement("option");

                        option.value = profissaoItem.IdProfissao;
                        option.textContent = profissaoItem.Nome;
                        option.selected = Number(profissaoItem.IdProfissao) === profissaoSelecionada;

                        profissao.appendChild(option);
                    });

                })
                .catch(error => {

                    console.error("Erro:", error);

                    profissao.innerHTML =
                        "<option value=''>Erro ao carregar profissões</option>";

                });

        });
    </script>
</body>

</html>