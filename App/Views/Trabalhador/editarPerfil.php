<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - EscamBOO</title>

    <link rel="icon" type="image/png" href="<?php echo URL_BASE ?>/assets/logoEscamboo.png">
    <link rel="apple-touch-icon" href="<?php echo URL_BASE ?>/assets/logoEscamboo.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --escamboo-vinho: #590b1c;
            --escamboo-vinho-hover: #3d0511;
            --escamboo-fundo: #ded5c2;
            --escamboo-card-bg: #fcf9f2;
            --escamboo-card-destaque: #f5eedf;
            --escamboo-ocre: #ba8238;
            --escamboo-texto-escuro: #2b1f1d;
            --escamboo-texto-muted: #73675a;
            --escamboo-borda: #d1c5b0;
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

        
        .escamboo-navbar {
            background-color: var(--escamboo-vinho);
            padding: 0.85rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .escamboo-container {
            width: 92%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
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
            font-size: 1.9rem;
            color: #ffffff;
            letter-spacing: -0.5px;
            text-decoration: none;
        }

        .escamboo-logo:hover {
            color: #f7e6e8;
        }

        .nav-avatar-mini {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
        }

        
        .main-content {
            padding-top: 2rem;
            padding-bottom: 4rem;
        }

        .card-editar {
            background-color: var(--escamboo-card-bg);
            border-radius: 28px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
            padding: 2.5rem;
            border: none;
        }

        .btn-voltar {
            color: var(--escamboo-vinho);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            transition: transform 0.2s, color 0.2s;
        }

        .btn-voltar:hover {
            color: var(--escamboo-vinho-hover);
            transform: translateX(-4px);
        }

        .titulo-pagina {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--escamboo-vinho);
            margin-bottom: 0.25rem;
        }

        .subtitulo-pagina {
            color: var(--escamboo-texto-muted);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        
        .secao-titulo {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--escamboo-vinho);
            border-bottom: 2px solid var(--escamboo-borda);
            padding-bottom: 0.5rem;
            margin-top: 1.5rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--escamboo-texto-escuro);
            margin-bottom: 0.35rem;
        }

        .form-control, .form-select {
            border-radius: 14px;
            border: 1.5px solid var(--escamboo-borda);
            padding: 0.7rem 1rem;
            font-size: 0.92rem;
            background-color: #ffffff;
            color: var(--escamboo-texto-escuro);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--escamboo-vinho);
            box-shadow: 0 0 0 0.25rem rgba(89, 11, 28, 0.15);
            outline: none;
        }

        
        .avatar-preview-box {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 1.5rem;
            background-color: var(--escamboo-card-destaque);
            padding: 1.25rem;
            border-radius: 20px;
        }

        .avatar-preview-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--escamboo-vinho);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        
        .card-curriculo-box {
            background-color: var(--escamboo-card-destaque);
            border: 2px dashed var(--escamboo-ocre);
            border-radius: 22px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            margin-bottom: 2rem;
        }

        .curriculo-badge {
            background-color: var(--escamboo-ocre);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-curriculo-manage {
            background-color: var(--escamboo-vinho);
            color: #ffffff;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            font-family: inherit;
            padding: 9px 24px;
            border: 0;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(89, 11, 28, 0.2);
            transition: background-color 0.2s, transform 0.2s;
        }

        .btn-curriculo-manage:hover {
            background-color: var(--escamboo-vinho-hover);
            color: #ffffff;
            transform: translateY(-2px);
        }

        
        .btn-salvar {
            background-color: var(--escamboo-vinho);
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            padding: 11px 36px;
            border-radius: 50px;
            border: none;
            box-shadow: 0 6px 18px rgba(89, 11, 28, 0.25);
            transition: background-color 0.2s, transform 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-salvar:hover {
            background-color: var(--escamboo-vinho-hover);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-cancelar {
            background-color: transparent;
            color: var(--escamboo-texto-muted);
            font-weight: 600;
            font-size: 1rem;
            padding: 11px 26px;
            border-radius: 50px;
            border: 1.5px solid var(--escamboo-borda);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.2s, color 0.2s;
        }

        .btn-cancelar:hover {
            background-color: #eae1ce;
            color: var(--escamboo-texto-escuro);
        }

        .card-desativar-conta {
            margin-top: 1.5rem;
            padding: 1.5rem;
            border: 1px solid #e4b7bc;
            border-radius: 20px;
            background: #fff5f5;
        }

        .btn-desativar-conta {
            border: 0;
            border-radius: 50px;
            padding: 0.7rem 1.25rem;
            background: #a32132;
            color: #ffffff;
            font-weight: 700;
        }

        .btn-desativar-conta:hover {
            background: #7d1725;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .card-editar {
                padding: 1.5rem;
            }
            .avatar-preview-box {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    
    <nav class="escamboo-navbar">
        <div class="escamboo-container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="<?= URL_BASE ?>/trabalhador/servicos" class="escamboo-brand">
                    <img src="<?= URL_BASE ?>/assets/logoEscamboo.png" alt="EscamBOO" class="escamboo-brand-mark">
                    <span class="escamboo-logo">EscamBOO</span>
                </a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= URL_BASE ?>/trabalhador/servicos" class="text-white text-decoration-none fw-medium fs-5">Início</a>
                <a href="<?= URL_BASE ?>/trabalhador/perfil">
                    <img src="<?= htmlspecialchars(!empty($trabalhador['FotoPerfil']) ? $trabalhador['FotoPerfil'] : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop') ?>" alt="Perfil" class="nav-avatar-mini">
                </a>
            </div>
        </div>
    </nav>

    
    <main class="escamboo-container main-content">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">

                
                <a href="<?= URL_BASE ?>/trabalhador/perfil" class="btn-voltar">
                    <i class="bi bi-arrow-left"></i> Voltar ao Perfil
                </a>

                <div class="card-editar">
                    <?php if (!empty($sucesso)): ?>
                        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                            <?= htmlspecialchars($sucesso) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                            <?= htmlspecialchars($erro) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                        <div>
                            <h1 class="titulo-pagina">Editar Perfil</h1>
                            <p class="subtitulo-pagina">Personalize sua apresentação, contatos e mantenha seu currículo atualizado para encontrar trocas de serviços.</p>
                            
                        </div>
                        
                    </div>

                    <form action="<?= URL_BASE ?>/trabalhador/editar" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idTrabalhador" value="<?= htmlspecialchars($trabalhador['IdTrabalhador'] ?? '') ?>">

                        
                        <div class="avatar-preview-box">
                            <img src="<?= htmlspecialchars(!empty($trabalhador['FotoPerfil']) ? $trabalhador['FotoPerfil'] : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=350&auto=format&fit=crop') ?>"
                                 alt="Pré-visualização" id="previewFoto" class="avatar-preview-img">
                            <div class="flex-grow-1">
                                <label for="arquivoFotoPerfil" class="form-label">Enviar foto de perfil</label>
                                <input type="file" class="form-control mb-2" id="arquivoFotoPerfil" name="arquivoFotoPerfil"
                                       accept="image/jpeg,image/png,image/webp,image/gif">
                                <small class="text-muted d-block mb-3">JPG, PNG, WEBP ou GIF; até 2 MB.</small>
                                <label for="fotoPerfil" class="form-label">Ou use uma URL</label>
                                <input type="url" class="form-control" id="fotoPerfil" name="fotoPerfil" 
                                       placeholder="https://exemplo.com/sua-foto.jpg"
                                       value="<?= htmlspecialchars($trabalhador['FotoPerfil'] ?? '') ?>"
                                       oninput="atualizarPreviewFoto(this.value)">
                                <small class="text-muted">Se enviar um arquivo, ele será usado no lugar da URL.</small>
                            </div>
                        </div>

                        
                        <div class="secao-titulo">
                            <i class="bi bi-person-fill"></i> Informações Básicas
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-8">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       value="<?= htmlspecialchars($trabalhador['Nome'] ?? '') ?>">
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="genero" class="form-label">Gênero</label>
                                <select class="form-select" id="genero" name="genero">
                                    <option value="" <?= empty($trabalhador['Genero']) ? 'selected' : '' ?>>Não informar</option>
                                    <option value="M" <?= ($trabalhador['Genero'] ?? '') === 'M' ? 'selected' : '' ?>>Masculino</option>
                                    <option value="F" <?= ($trabalhador['Genero'] ?? '') === 'F' ? 'selected' : '' ?>>Feminino</option>
                                    <option value="O" <?= ($trabalhador['Genero'] ?? '') === 'O' ? 'selected' : '' ?>>Outro</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="selectUf" class="form-label">Cidade e Estado (Localização)</label>
                                <div class="row g-2">
                                    <div class="col-sm-4">
                                        <select id="selectUf" class="form-select" aria-label="Estado">
                                            <option value="">Carregando estados...</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <select id="selectCidade" class="form-select" aria-label="Cidade">
                                            <option value="">Selecione o Estado primeiro...</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="endereco" name="endereco"
                                       value="<?= htmlspecialchars($trabalhador['Endereco'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <small id="ajudaEndereco" class="form-text text-muted" aria-live="polite">
                                    Selecione o estado e a cidade; os municípios são carregados pela API oficial do IBGE.
                                </small>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="telefone" class="form-label">Telefone / WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 14px 0 0 14px; border-color: var(--escamboo-borda);">
                                        <i class="bi bi-whatsapp text-success"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="telefone" name="telefone"
                                           placeholder="+55 (45) 91234-5678"
                                           value="<?= htmlspecialchars($trabalhador['Telefone'] ?? '') ?>"
                                           style="border-radius: 0 14px 14px 0;">
                                </div>
                            </div>
                        </div>

                        
                        <div class="secao-titulo">
                            <i class="bi bi-share-fill"></i> Redes Sociais
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label for="instagram" class="form-label">Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 14px 0 0 14px; border-color: var(--escamboo-borda);">
                                        <i class="bi bi-instagram" style="color: #E1306C;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="instagram" name="instagram"
                                           placeholder="@seu.perfil ou link completo"
                                           value="<?= htmlspecialchars($trabalhador['Instagram'] ?? '') ?>"
                                           style="border-radius: 0 14px 14px 0;">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="twitter" class="form-label">X (antigo Twitter)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 14px 0 0 14px; border-color: var(--escamboo-borda);">
                                        <i class="bi bi-twitter-x"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="twitter" name="twitter"
                                           placeholder="@seu.usuario ou link completo"
                                           value="<?= htmlspecialchars($trabalhador['Twitter'] ?? '') ?>"
                                           style="border-radius: 0 14px 14px 0;">
                                </div>
                            </div>
                        </div>

                        
                        <div class="secao-titulo">
                            <i class="bi bi-card-text"></i> Biografia
                        </div>

                        <div class="mb-4">
                            <label for="biografia" class="form-label">Apresentação Pessoal e Profissional</label>
                            <textarea class="form-control" id="biografia" name="biografia" rows="4" 
                                      placeholder="Descreva suas experiências, especialidades, os tipos de serviço que você mais realiza e o que você gostaria de receber em troca..."><?= htmlspecialchars($trabalhador['Biografia'] ?? '') ?></textarea>
                            <small class="text-muted">Uma biografia clara e detalhada aumenta suas chances de dar match em propostas de troca.</small>
                        </div>

                        <button type="submit" name="acao" value="preferencias" formnovalidate class="btn-curriculo-manage">
                            <i class="bi bi-sliders"></i> Preferências profissionais
                        </button>
                        
                        <div class="card-curriculo-box">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-mortarboard-fill text-warning fs-4"></i>
                                    <h5 class="fw-bold mb-0" style="color: var(--escamboo-texto-escuro);">Currículo Profissional</h5>
                                </div>
                                <span class="curriculo-badge">Preferências de Troca</span>
                            </div>

                            <p class="text-muted mb-3" style="font-size: 0.88rem;">
                                Sua Grande Área e Profissão definem seu catálogo de serviços oferecidos e direcionam os matches no EscamBOO.
                            </p>

                            <div class="row g-2 mb-3 bg-white p-3 rounded-4 border">
                                <div class="col-12 col-md-6">
                                    <span class="text-muted d-block" style="font-size: 0.8rem;">Grande Área de Atuação:</span>
                                    <strong style="color: var(--escamboo-vinho); font-size: 0.95rem;">
                                        <?= htmlspecialchars(!empty($trabalhador['NomeGrandeArea']) ? $trabalhador['NomeGrandeArea'] : 'Não definida') ?>
                                    </strong>
                                </div>
                                <div class="col-12 col-md-6">
                                    <span class="text-muted d-block" style="font-size: 0.8rem;">Profissão Principal:</span>
                                    <strong style="color: var(--escamboo-vinho); font-size: 0.95rem;">
                                        <?= htmlspecialchars(!empty($trabalhador['NomeProfissao']) ? $trabalhador['NomeProfissao'] : 'Não definida') ?>
                                    </strong>
                                </div>
                            </div>

                        </div>

                        
                        <div class="d-flex align-items-center justify-content-end gap-3 pt-2">
                            <a href="<?= URL_BASE ?>/trabalhador/perfil?descartarRascunho=1" class="btn-cancelar">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-salvar">
                                <i class="bi bi-check2-circle"></i> Salvar Alterações
                            </button>
                        </div>

                    </form>

                    <section class="card-desativar-conta" aria-labelledby="tituloDesativarConta">
                        <h2 id="tituloDesativarConta" class="h5 fw-bold text-danger">Desativar conta</h2>
                        <p class="mb-3 text-muted">
                            Sua conta ficará inativa e você sairá da plataforma. Seus dados não serão excluídos;
                            para reativar o acesso, entre em contato com o suporte.
                        </p>
                        <form action="<?= URL_BASE ?>/trabalhador/desativar-conta" method="POST"
                              onsubmit="return confirm('Tem certeza de que deseja desativar sua conta? Você será desconectado.');">
                            <button type="submit" class="btn-desativar-conta">Desativar minha conta</button>
                        </form>
                    </section>

                </div>
            </div>
        </div>
    </main>

    
    <script>
        const enderecoAtual = <?= json_encode((string) ($trabalhador['Endereco'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
        const partesEndereco = enderecoAtual.split(",", 2);
        const cidadeSalva = (partesEndereco[0] || "").trim();
        const ufSalva = (partesEndereco[1] || "").trim().toUpperCase();
        const selectUf = document.getElementById("selectUf");
        const selectCidade = document.getElementById("selectCidade");
        const campoEndereco = document.getElementById("endereco");
        const ajudaEndereco = document.getElementById("ajudaEndereco");

        async function carregarCidades(uf, cidadePreSelecionada = "") {
            if (!uf) {
                selectCidade.innerHTML = '<option value="">Selecione o Estado primeiro...</option>';
                campoEndereco.value = "";
                ajudaEndereco.textContent = "Selecione o estado e a cidade; os municípios são carregados pela API oficial do IBGE.";
                return;
            }

            selectCidade.innerHTML = '<option value="">Carregando cidades do IBGE...</option>';
            ajudaEndereco.textContent = "Carregando municípios pela API oficial do IBGE.";

            try {
                const resposta = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${encodeURIComponent(uf)}/municipios?orderBy=nome`);
                if (!resposta.ok) {
                    throw new Error(`A API do IBGE respondeu com status ${resposta.status}.`);
                }

                const municipios = await resposta.json();
                selectCidade.innerHTML = '<option value="">Selecione a cidade...</option>';
                municipios.forEach((municipio) => {
                    const opcao = document.createElement("option");
                    opcao.value = municipio.nome;
                    opcao.textContent = municipio.nome;
                    selectCidade.appendChild(opcao);
                });

                if (cidadePreSelecionada) {
                    const opcaoSalva = Array.from(selectCidade.options).find(
                        (opcao) => opcao.value.toLocaleLowerCase("pt-BR") === cidadePreSelecionada.toLocaleLowerCase("pt-BR")
                    );
                    if (opcaoSalva) {
                        opcaoSalva.selected = true;
                        atualizarEnderecoFinal();
                    } else {
                        ajudaEndereco.textContent = "A cidade salva não foi localizada na lista do IBGE. Selecione outra para atualizar o endereço.";
                        return;
                    }
                }

                ajudaEndereco.textContent = "Selecione a cidade; os municípios são carregados pela API oficial do IBGE.";
            } catch (erro) {
                console.error("Não foi possível carregar as cidades do IBGE.", erro);
                selectCidade.innerHTML = '<option value="">Não foi possível carregar as cidades. Tente novamente.</option>';
                ajudaEndereco.textContent = "Falha ao consultar o IBGE. O endereço salvo será mantido até uma nova cidade ser selecionada.";
            }
        }

        async function carregarEstados() {
            try {
                const resposta = await fetch("https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome");
                if (!resposta.ok) {
                    throw new Error(`A API do IBGE respondeu com status ${resposta.status}.`);
                }

                const estados = await resposta.json();
                selectUf.innerHTML = '<option value="">Selecione o estado...</option>';
                estados.forEach((estado) => {
                    const opcao = document.createElement("option");
                    opcao.value = estado.sigla;
                    opcao.textContent = `${estado.nome} (${estado.sigla})`;
                    selectUf.appendChild(opcao);
                });

                if (ufSalva && Array.from(selectUf.options).some((opcao) => opcao.value === ufSalva)) {
                    selectUf.value = ufSalva;
                    await carregarCidades(ufSalva, cidadeSalva);
                } else {
                    ajudaEndereco.textContent = "Selecione o estado e a cidade; os municípios são carregados pela API oficial do IBGE.";
                }
            } catch (erro) {
                console.error("Não foi possível carregar os estados do IBGE.", erro);
                selectUf.innerHTML = '<option value="">Não foi possível carregar os estados. Tente novamente.</option>';
                ajudaEndereco.textContent = "Falha ao consultar o IBGE. O endereço salvo foi mantido.";
            }
        }

        function atualizarEnderecoFinal() {
            const uf = selectUf.value;
            const cidade = selectCidade.value;
            campoEndereco.value = cidade && uf ? `${cidade}, ${uf}` : "";
        }

        selectUf.addEventListener("change", () => carregarCidades(selectUf.value));
        selectCidade.addEventListener("change", atualizarEnderecoFinal);
        carregarEstados();

        function atualizarPreviewFoto(url) {
            document.getElementById('arquivoFotoPerfil').value = '';
            const preview = document.getElementById('previewFoto');
            if (url && url.trim().length > 5) {
                preview.src = url.trim();
            } else {
                preview.src = 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=350&auto=format&fit=crop';
            }
        }

        document.getElementById('arquivoFotoPerfil').addEventListener('change', function () {
            const arquivo = this.files && this.files[0];
            if (arquivo) {
                document.getElementById('fotoPerfil').value = '';
                document.getElementById('previewFoto').src = URL.createObjectURL(arquivo);
            }
        });
    </script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>