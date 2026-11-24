<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Trabalhador</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #5E0B15 !important;
            min-height: 100vh;
            padding-bottom: 40px;
            color: #2b2b2b;
        }

        .cadastro-card {
            width: calc(100% - 2rem);
            max-width: 760px;
            margin-top: 1rem !important;
        }

        .card {
            border: none !important;
            border-radius: 24px !important;
            background-color: #f3ede2 !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25) !important;
        }

        .card-body h3 {
            color: #550a16;
            font-weight: 700;
        }

        .form-label {
            color: #550a16;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 1px solid #d1c1aa;
            background-color: #ffffff;
            padding: 8px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #550a16;
            box-shadow: 0 0 0 0.25rem rgba(85, 10, 22, 0.25);
        }

        .btn-primary {
            background-color: #550a16 !important;
            border-color: #550a16 !important;
            color: #ffffff !important;
            border-radius: 20px !important;
            padding: 8px 24px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #3f060f !important;
            border-color: #3f060f !important;
        }

        .btn-outline-secondary {
            color: #550a16 !important;
            border: 2px solid #550a16 !important;
            background-color: transparent !important;
            border-radius: 20px !important;
            padding: 6px 20px;
            font-weight: 600;
        }

        .btn-outline-secondary:hover {
            background-color: #550a16 !important;
            color: #ffffff !important;
        }
    </style>

</head>

<body>

    <div class="card shadow-sm cadastro-card mx-auto">
        <div class="card-body p-4">

            <h3 class="mb-3">Cadastrar Trabalhador</h3>

            <?php if (! empty($erros)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($erros as $erro): ?>
                        <div><?php echo htmlspecialchars($erro) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo URL_BASE ?>/trabalhador/cadastrar" method="post" enctype="multipart/form-data">


                <div class="mb-3">
                    <label for="nome" class="form-label">
                        Nome Completo <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control <?php echo isset($erros['nome']) ? 'is-invalid' : '' ?>"
                        id="nome"
                        name="nome"
                        value="<?php echo htmlspecialchars($usuario['Nome'] ?? '') ?>">

                    <?php if (isset($erros['nome'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($erros['nome']) ?>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="mb-3">
                    <label for="cpf" class="form-label">
                        CPF <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control <?php echo isset($erros['cpf']) ? 'is-invalid' : '' ?>"
                        id="cpf"
                        name="cpf"
                        placeholder="000.000.000-00"
                        maxlength="14"
                        value="<?php echo htmlspecialchars($usuario['CPF'] ?? '') ?>">

                    <?php if (isset($erros['cpf'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($erros['cpf']) ?>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="mb-3">
                    <label for="email" class="form-label">
                        E-mail <span class="text-danger">*</span>
                    </label>

                    <input
                        type="email"
                        class="form-control <?php echo isset($erros['email']) ? 'is-invalid' : '' ?>"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars($usuario['Email'] ?? '') ?>">

                    <?php if (isset($erros['email'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($erros['email']) ?>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="mb-3">
                    <label for="dataNascimento" class="form-label">
                        Data de Nascimento
                    </label>

                    <input
                        type="date"
                        class="form-control <?php echo isset($erros['dataNascimento']) ? 'is-invalid' : '' ?>"
                        id="dataNascimento"
                        name="dataNascimento"
                        value="<?php echo htmlspecialchars($usuario['DataNascimento'] ?? '') ?>">

                    <?php if (isset($erros['dataNascimento'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($erros['dataNascimento']) ?>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="mb-3">
                    <label for="genero" class="form-label">
                        Gênero
                    </label>

                    <select
                        class="form-select <?php echo isset($erros['genero']) ? 'is-invalid' : '' ?>"
                        id="genero"
                        name="genero">

                        <option value="">Selecione</option>

                        <option
                            value="M"
                            <?php echo (($usuario['Genero'] ?? '') === 'M') ? 'selected' : '' ?>>
                            Masculino
                        </option>

                        <option
                            value="F"
                            <?php echo (($usuario['Genero'] ?? '') === 'F') ? 'selected' : '' ?>>
                            Feminino
                        </option>

                        <option
                            value="O"
                            <?php echo (($usuario['Genero'] ?? '') === 'O') ? 'selected' : '' ?>>
                            Outro
                        </option>

                    </select>

                    <?php if (isset($erros['genero'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($erros['genero']) ?>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="mb-3">
                <!-- Cidade e Estado com API IBGE -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-geo-alt-fill me-1" style="color: #550a16;"></i> Cidade e Estado (Localização) <span class="text-danger">*</span>
                    </label>

                    <div class="row g-2">
                        <div class="col-sm-4">
                            <select id="selectUf" class="form-select <?php echo isset($erros['endereco']) ? 'is-invalid' : '' ?>" onchange="carregarCidades(this.value)">
                                <option value="">UF...</option>
                                <?php
                                    $ufs           = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                                    $enderecoAtual = $usuario['Endereco'] ?? '';
                                    $partesEnd     = explode(',', $enderecoAtual);
                                    $cidadeAtual   = trim($partesEnd[0] ?? '');
                                    $ufAtual       = trim($partesEnd[1] ?? '');
                                foreach ($ufs as $uf): ?>
                                    <option value="<?php echo $uf ?>" <?php echo (strtoupper($ufAtual) === $uf) ? 'selected' : '' ?>><?php echo $uf ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <select id="selectCidade" class="form-select <?php echo isset($erros['endereco']) ? 'is-invalid' : '' ?>" onchange="atualizarEnderecoFinal()">
                                <option value="">Selecione o Estado primeiro...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Input oculto com o formato exigido: "Cidade, UF" -->
                    <input type="hidden" id="endereco" name="endereco" value="<?php echo htmlspecialchars($usuario['Endereco'] ?? '') ?>">
                    <small class="form-text text-muted">A lista de cidades é carregada automaticamente via API oficial do IBGE para garantir precisão.</small>

                    <?php if (isset($erros['endereco'])): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo htmlspecialchars($erros['endereco']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="telefone" class="form-label">
                        Telefone / WhatsApp <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control <?php echo isset($erros['telefone']) ? 'is-invalid' : '' ?>"
                        id="telefone"
                        name="telefone"
                        placeholder="digite seu número de telefone"
                        value="<?php echo htmlspecialchars($usuario['Telefone'] ?? '') ?>">

                    <?php if (isset($erros['telefone'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($erros['telefone']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Foto de Perfil com Upload e Pré-visualização -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-camera-fill me-1" style="color: #550a16;"></i> Foto de Perfil
                    </label>

                    <div class="d-flex align-items-center gap-3 p-3 rounded-4 mb-2" style="background-color: #e8dece;">
                        <img id="previewFoto"
                             src="<?php echo htmlspecialchars(! empty($usuario['FotoPerfil']) ? $usuario['FotoPerfil'] : URL_BASE . '/assets/fantasminha.png') ?>"
                             alt="Pré-visualização"
                             class="rounded-circle border"
                             style="width: 65px; height: 65px; object-fit: cover; background-color: #fff;">
                        <div class="flex-grow-1">
                            <label for="arquivoFotoPerfil" class="form-label mb-1 small fw-bold">Enviar foto do seu computador:</label>
                            <input
                                type="file"
                                class="form-control form-control-sm <?php echo isset($erros['fotoPerfil']) ? 'is-invalid' : '' ?>"
                                id="arquivoFotoPerfil"
                                name="arquivoFotoPerfil"
                                accept="image/jpeg,image/png,image/webp,image/gif"
                                onchange="previewImagem(this)">
                            <small class="form-text text-muted d-block mt-1">Formatos aceitos: JPG, PNG, WEBP ou GIF (máximo 2 MB).</small>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label for="fotoPerfil" class="form-label small text-muted mb-0">Ou cole um link direto da imagem:</label>
                        <input
                            type="url"
                            class="form-control form-control-sm <?php echo isset($erros['fotoPerfil']) ? 'is-invalid' : '' ?>"
                            id="fotoPerfil"
                            name="fotoPerfil"
                            placeholder="https://..."
                            value="<?php echo htmlspecialchars($usuario['FotoPerfil'] ?? '') ?>"
                            oninput="previewUrl(this.value)">
                    </div>

                    <?php if (isset($erros['fotoPerfil'])): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo htmlspecialchars($erros['fotoPerfil']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Senha com Olhinho e Validação Forte -->
                <div class="mb-3">
                    <label for="senha" class="form-label">
                        Senha <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control <?php echo isset($erros['senha']) ? 'is-invalid' : '' ?>"
                            id="senha"
                            name="senha">
                        <button class="btn btn-outline-secondary" type="button" onclick="toggleSenha('senha', 'iconeSenha')" title="Mostrar ou ocultar senha">
                            <i class="bi bi-eye" id="iconeSenha"></i>
                        </button>
                    </div>
                    <small class="form-text text-muted">A senha deve ter no mínimo 6 caracteres, pelo menos uma letra maiúscula e um caractere especial (!@#$%&*).</small>

                    <?php if (isset($erros['senha'])): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo htmlspecialchars($erros['senha']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Confirmar Senha com Olhinho -->
                <div class="mb-3">
                    <label for="confirmarSenha" class="form-label">
                        Confirmar Senha <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control <?php echo isset($erros['confirmarSenha']) ? 'is-invalid' : '' ?>"
                            id="confirmarSenha"
                            name="confirmarSenha">
                        <button class="btn btn-outline-secondary" type="button" onclick="toggleSenha('confirmarSenha', 'iconeConfirmarSenha')" title="Mostrar ou ocultar confirmação de senha">
                            <i class="bi bi-eye" id="iconeConfirmarSenha"></i>
                        </button>
                    </div>

                    <?php if (isset($erros['confirmarSenha'])): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo htmlspecialchars($erros['confirmarSenha']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Ações -->
                <div class="d-flex gap-2 justify-content-end mt-4">

                    <a
                        href="<?php echo (isset($_SESSION['usuario_logado']) && (($_SESSION['usuario_logado']['Tipo'] ?? '') === 'adm' || ($_SESSION['usuario_tipo'] ?? '') === 'administrador')) ? URL_BASE . '/adm' : URL_BASE . '/login' ?>"
                        class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary px-4">
                        Cadastrar
                    </button>

                </div>

            </form>

        </div>
    </div>

    <!-- Scripts para API de Cidades do IBGE, Visualização de Senha e Preview de Imagem -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSenha(inputId, iconeId) {
            const input = document.getElementById(inputId);
            const icone = document.getElementById(iconeId);
            if (input && icone) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icone.className = 'bi bi-eye-slash-fill';
                } else {
                    input.type = 'password';
                    icone.className = 'bi bi-eye';
                }
            }
        }

        function previewImagem(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('previewFoto');
                    if (img) img.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewUrl(url) {
            const img = document.getElementById('previewFoto');
            if (img && url && url.startsWith('http')) {
                img.src = url;
            }
        }

        // Integração oficial com API do IBGE para Carregamento de Cidades
        const cidadeSalva = <?php echo json_encode($cidadeAtual ?? '') ?>;
        const ufSalva = <?php echo json_encode($ufAtual ?? '') ?>;

        function carregarCidades(uf, cidadePreSelecionada = '') {
            const selectCidade = document.getElementById('selectCidade');
            if (!selectCidade) return;

            if (!uf) {
                selectCidade.innerHTML = '<option value="">Selecione o Estado primeiro...</option>';
                atualizarEnderecoFinal();
                return;
            }

            selectCidade.innerHTML = '<option value="">Carregando cidades do IBGE...</option>';

            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios?orderBy=nome`)
                .then(response => response.json())
                .then(municipios => {
                    selectCidade.innerHTML = '<option value="">Selecione a cidade...</option>';
                    municipios.forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m.nome;
                        opt.textContent = m.nome;
                        if (cidadePreSelecionada && (m.nome.toLowerCase() === cidadePreSelecionada.toLowerCase())) {
                            opt.selected = true;
                        }
                        selectCidade.appendChild(opt);
                    });
                    atualizarEnderecoFinal();
                })
                .catch(err => {
                    console.error('Erro ao buscar cidades no IBGE:', err);
                    selectCidade.innerHTML = '<option value="">Erro ao carregar cidades. Digite manualmente se necessário.</option>';
                });
        }

        function atualizarEnderecoFinal() {
            const uf = document.getElementById('selectUf').value;
            const cidade = document.getElementById('selectCidade').value;
            const inputEndereco = document.getElementById('endereco');
            if (inputEndereco) {
                if (cidade && uf) {
                    inputEndereco.value = `${cidade}, ${uf}`;
                } else {
                    inputEndereco.value = '';
                }
            }
        }

        // Se já houver UF salva, carrega as cidades do IBGE na inicialização
        if (ufSalva) {
            carregarCidades(ufSalva, cidadeSalva);
        }
    </script>
</body>

</html>