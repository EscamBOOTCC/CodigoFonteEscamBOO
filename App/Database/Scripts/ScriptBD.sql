-- 1. Tabela Administrador
CREATE TABLE IF NOT EXISTS ADM 
(
    IdAdm INT AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL,
    CPF VARCHAR(14) NOT NULL UNIQUE,
    Genero CHAR(1),
    Email VARCHAR(255) NOT NULL UNIQUE,
    Senha VARCHAR(255) NOT NULL,
    DataNascimento DATE,
    FotoPerfil TEXT,
    Endereco VARCHAR(255),
    Ativo ENUM('ATIVO', 'INATIVO', 'SUSPENSO', 'BANIDO') DEFAULT 'ATIVO' NOT NULL,
    DataExpiraStatus DATETIME DEFAULT NULL,
    PRIMARY KEY (IdAdm)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabela de Grandes Áreas
CREATE TABLE IF NOT EXISTS GrandesAreas 
(
    IdGrandeArea INT AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL UNIQUE,
    PRIMARY KEY (IdGrandeArea)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabela de Profissões
CREATE TABLE IF NOT EXISTS Profissao 
(
    IdProfissao INT AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL UNIQUE,
    IdGrandeArea INT NOT NULL,
    PRIMARY KEY (IdProfissao),
    CONSTRAINT fk_profissao_grandearea 
        FOREIGN KEY (IdGrandeArea) REFERENCES GrandesAreas (IdGrandeArea) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabela Trabalhador
CREATE TABLE IF NOT EXISTS Trabalhador 
(
    IdTrabalhador INT AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL,
    CPF VARCHAR(14) NOT NULL UNIQUE,
    Genero CHAR(1),
    Email VARCHAR(255) NOT NULL UNIQUE,
    Senha VARCHAR(255) NOT NULL,
    DataNascimento DATE,
    FotoPerfil TEXT,
    Endereco VARCHAR(255),
    StatusAcordo BOOLEAN DEFAULT FALSE,
    Ativo ENUM('ATIVO', 'INATIVO', 'SUSPENSO', 'BANIDO') DEFAULT 'ATIVO' NOT NULL,
    DataExpiraStatus DATETIME DEFAULT NULL,
    IdProfissao INT DEFAULT NULL,
    IdGrandeArea INT DEFAULT NULL,
    Biografia TEXT,
    Telefone VARCHAR(30) DEFAULT NULL,
    Instagram VARCHAR(255) DEFAULT NULL,
    Twitter VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (IdTrabalhador),
    CONSTRAINT fk_trabalhador_profissao 
        FOREIGN KEY (IdProfissao) REFERENCES Profissao (IdProfissao) 
        ON DELETE SET NULL,
    CONSTRAINT fk_trabalhador_grandearea 
        FOREIGN KEY (IdGrandeArea) REFERENCES GrandesAreas (IdGrandeArea) 
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4.1. Interesses profissionais selecionados por trabalhador
CREATE TABLE IF NOT EXISTS TrabalhadorInteresseGrandeArea
(
    IdTrabalhador INT NOT NULL,
    IdGrandeArea INT NOT NULL,
    PRIMARY KEY (IdTrabalhador, IdGrandeArea),
    CONSTRAINT fk_interesse_trabalhador
        FOREIGN KEY (IdTrabalhador) REFERENCES Trabalhador (IdTrabalhador)
        ON DELETE CASCADE,
    CONSTRAINT fk_interesse_grandearea
        FOREIGN KEY (IdGrandeArea) REFERENCES GrandesAreas (IdGrandeArea)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabela Servico
CREATE TABLE IF NOT EXISTS Servico 
(
    IdServico INT AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL,
    Descricao TEXT NULL,
    FotoServico TEXT NULL,
    FotoServico2 TEXT NULL,
    IdTrabalhador INT NOT NULL,
    IdProfissao INT NOT NULL,
    PRIMARY KEY (IdServico),
    CONSTRAINT fk_servico_trabalhador 
        FOREIGN KEY (IdTrabalhador) REFERENCES Trabalhador (IdTrabalhador) 
        ON DELETE CASCADE,
    CONSTRAINT fk_servico_profissao 
        FOREIGN KEY (IdProfissao) REFERENCES Profissao (IdProfissao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabela Avaliacao (Estrutura com Tipo, IdAvaliador, IdAvaliado e constraint de unicidade atualizada)
CREATE TABLE IF NOT EXISTS Avaliacao 
(
    IdAvaliacao INT AUTO_INCREMENT,
    Nota FLOAT NOT NULL,
    Data DATE NOT NULL,
    Comentario VARCHAR(255),
    Tipo ENUM('PARCEIRO', 'ESCAMBO') NOT NULL DEFAULT 'PARCEIRO',
    IdServico INT NOT NULL,
    IdAcordo INT NULL,
    IdAvaliador INT NULL,
    IdAvaliado INT NULL,
    PRIMARY KEY (IdAvaliacao),
    UNIQUE KEY uniq_avaliacao_acordo_avaliador_tipo (IdAcordo, IdAvaliador, Tipo),
    CONSTRAINT fk_avaliacao_servico 
        FOREIGN KEY (IdServico) REFERENCES Servico (IdServico) 
        ON DELETE CASCADE,
    CONSTRAINT fk_avaliacao_avaliador 
        FOREIGN KEY (IdAvaliador) REFERENCES Trabalhador (IdTrabalhador) 
        ON DELETE SET NULL,
    CONSTRAINT fk_avaliacao_avaliado 
        FOREIGN KEY (IdAvaliado) REFERENCES Trabalhador (IdTrabalhador) 
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Tabela Denuncia
CREATE TABLE IF NOT EXISTS Denuncia 
(
    IdDenuncia INT AUTO_INCREMENT,
    Motivo VARCHAR(255) NOT NULL,
    Descricao VARCHAR(255),
    Status INT DEFAULT 0, -- 0: Pendente, 1: Procedente (Penalizado), 2: Improcedente (Arquivado)
    Data DATE NOT NULL,
    IdTrabalhador INT NOT NULL,
    IdDenunciante INT NULL,
    PRIMARY KEY (IdDenuncia),
    CONSTRAINT fk_denuncia_trabalhador 
        FOREIGN KEY (IdTrabalhador) REFERENCES Trabalhador (IdTrabalhador) 
        ON DELETE CASCADE,
    CONSTRAINT fk_denuncia_denunciante 
        FOREIGN KEY (IdDenunciante) REFERENCES Trabalhador (IdTrabalhador) 
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Tabela Penalidade
CREATE TABLE IF NOT EXISTS Penalidade 
(
    IdPenalidade INT AUTO_INCREMENT,
    Motivo VARCHAR(255) NOT NULL,
    Tipo INT NOT NULL, -- 1: Suspensão, 2: Banimento, 3: Advertência
    Status VARCHAR(50) DEFAULT 'APLICADA',
    Data DATE NOT NULL,
    IdAdm INT NOT NULL,
    IdTrabalhador INT NOT NULL,
    IdDenuncia INT NULL,
    PRIMARY KEY (IdPenalidade),
    CONSTRAINT fk_penalidade_adm 
        FOREIGN KEY (IdAdm) REFERENCES ADM (IdAdm),
    CONSTRAINT fk_penalidade_trabalhador 
        FOREIGN KEY (IdTrabalhador) REFERENCES Trabalhador (IdTrabalhador) 
        ON DELETE CASCADE,
    CONSTRAINT fk_penalidade_denuncia 
        FOREIGN KEY (IdDenuncia) REFERENCES Denuncia (IdDenuncia) 
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Tabela Bloqueio
CREATE TABLE IF NOT EXISTS Bloqueio 
(
    IdBloqueio INT AUTO_INCREMENT,
    Data DATE NOT NULL,
    IdTrabalhadorBloqueou INT NOT NULL,
    IdTrabalhadorBloqueado INT NOT NULL,
    PRIMARY KEY (IdBloqueio),
    UNIQUE KEY uniq_bloqueio (IdTrabalhadorBloqueou, IdTrabalhadorBloqueado),
    CONSTRAINT fk_bloqueio_trabalhador_bloqueou 
        FOREIGN KEY (IdTrabalhadorBloqueou) REFERENCES Trabalhador (IdTrabalhador) 
        ON DELETE CASCADE,
    CONSTRAINT fk_bloqueio_trabalhador_bloqueado 
        FOREIGN KEY (IdTrabalhadorBloqueado) REFERENCES Trabalhador (IdTrabalhador) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Tabela Match
CREATE TABLE IF NOT EXISTS `Match` 
(
    IdMatch INT AUTO_INCREMENT,
    Data DATE NOT NULL,
    Status INT DEFAULT 0, -- 0: Pendente, 1: Aceito, 2: Recusado
    IdTrabalhadorUm INT NOT NULL,
    IdTrabalhadorDois INT NOT NULL,
    IdServicoOrigem INT NULL,
    IdServicoDestino INT NULL,
    Mensagem TEXT NULL,
    PRIMARY KEY (IdMatch),
    CONSTRAINT fk_match_trabalhador_um 
        FOREIGN KEY (IdTrabalhadorUm) REFERENCES Trabalhador (IdTrabalhador),
    CONSTRAINT fk_match_trabalhador_dois 
        FOREIGN KEY (IdTrabalhadorDois) REFERENCES Trabalhador (IdTrabalhador),
    CONSTRAINT fk_match_servico_origem 
        FOREIGN KEY (IdServicoOrigem) REFERENCES Servico (IdServico) ON DELETE SET NULL,
    CONSTRAINT fk_match_servico_destino 
        FOREIGN KEY (IdServicoDestino) REFERENCES Servico (IdServico) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Tabela Acordo
CREATE TABLE IF NOT EXISTS Acordo 
(
    IdAcordo INT AUTO_INCREMENT,
    Status INT DEFAULT 0, -- 0: Em Andamento, 1: Concluído, 2: Cancelado
    IdMatch INT UNIQUE NOT NULL,
    ConfirmacaoTrabalhadorUm TINYINT(1) DEFAULT 0,
    ConfirmacaoTrabalhadorDois TINYINT(1) DEFAULT 0,
    DataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    DataConclusao DATETIME NULL,
    JustificativaCancelamento TEXT NULL,
    PRIMARY KEY (IdAcordo),
    CONSTRAINT fk_acordo_match 
        FOREIGN KEY (IdMatch) REFERENCES `Match` (IdMatch) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =====================================================================
-- POVOAMENTO DOS DADOS (Grandes Áreas e Profissões)
-- =====================================================================

-- Inserção das 100 Grandes Áreas
INSERT INTO GrandesAreas (Nome) VALUES
('Tecnologia da Informação'), ('Engenharia Civil'), ('Saúde e Medicina'), ('Educação e Docência'), ('Direito e Jurídico'),
('Administração e Gestão'), ('Marketing e Comunicação'), ('Finanças e Contabilidade'), ('Artes e Design'), ('Ciências Biológicas'),
('Agronomia e Agropecuária'), ('Psicologia e Saúde Mental'), ('Gastronomia e Culinária'), ('Turismo e Hotelaria'), ('Arquitetura e Urbanismo'),
('Logística e Transporte'), ('Segurança do Trabalho'), ('Recursos Humanos'), ('Jornalismo e Mídia'), ('Moda e Estilo'),
('Esportes e Educação Física'), ('Veterinária e Zootecnia'), ('Química e Processos Industriais'), ('Física e Astronomia'), ('Matemática e Estatística'),
('Cinema e Audiovisual'), ('Música e Artes Cênicas'), ('Vendas e Comercial'), ('Atendimento ao Cliente'), ('Segurança Pública e Privada'),
('Beleza e Estética'), ('Meio Ambiente e Ecologia'), ('Serviço Social'), ('Farmácia e Biomedicina'), ('Odontologia'),
('Fisioterapia e Terapia Ocupacional'), ('Nutrição'), ('Engenharia Elétrica'), ('Engenharia Mecânica'), ('Engenharia de Produção'),
('Engenharia Química'), ('Engenharia de Computação'), ('Engenharia Ambiental'), ('Engenharia Mecatrônica'), ('Engenharia Aeronáutica'),
('Mineração e Geologia'), ('Biotecnologia'), ('Ciências Sociais e Política'), ('Filosofia e Sociologia'), ('História e Geografia'),
('Idiomas e Tradução'), ('Literatura e Edição'), ('Relações Internacionais'), ('Relações Públicas'), ('Publicidade e Propaganda'),
('Design de Interiores'), ('Design Gráfico'), ('UX/UI Design'), ('Games e Animação'), ('Fotografia'),
('Eventos e Recreação'), ('Manutenção e Reparos'), ('Construção Civil (Operacional)'), ('Metalurgia e Soldagem'), ('Marcenaria e Carpintaria'),
('Eletricidade e Eletrônica'), ('Encanamento e Hidráulica'), ('Pintura e Acabamento'), ('Telecomunicações'), ('Automação Industrial'),
('Petróleo e Gás'), ('Energias Renováveis'), ('Contabilidade Tributária'), ('Auditoria e Compliance'), ('Investimentos e Mercado Financeiro'),
('Seguros e Previdência'), ('Comércio Exterior'), ('E-commerce e Vendas Online'), ('Gestão de Projetos'), ('Estratégia e Consultoria'),
('Pedagogia e Licenciaturas'), ('Educação Especial'), ('Pesquisa e Acadêmica'), ('Arquivologia e Biblioteconomia'), ('Teologia e Ciências Religiosas'),
('Arqueologia e Antropologia'), ('Meteorologia e Climatologia'), ('Oceanografia e Ciências do Mar'), ('Pesca e Aquicultura'), ('Silvicultura e Manejo Florestal'),
('Cuidado de Animais'), ('Serviços Domésticos'), ('Cuidados de Idosos e Acompanhamento'), ('Serviços Funerários'), ('Limpeza e Conservação'),
('Jardinagem e Paisagismo'), ('Moda e Costura'), ('Joalheria e Relojoaria'), ('Artesanato e Restauro'), ('Piloto e Aviação Civil');

-- Inserção das Profissões associadas
INSERT INTO Profissao (Nome, IdGrandeArea) VALUES
-- Área 1 a 10
('Desenvolvedor Software', 1), ('DBA', 1), ('Analista de Cibersegurança', 1), ('Outros (Tecnologia da Informação)', 1),
('Engenheiro Calculista', 2), ('Engenheiro de Estruturas', 2), ('Gerente de Obras', 2), ('Outros (Engenharia Civil)', 2),
('Médico Generalista', 3), ('Enfermeiro', 3), ('Cirurgião Geral', 3), ('Outros (Saúde e Medicina)', 3),
('Professor Fundamental', 4), ('Professor Universitário', 4), ('Coordenador Pedagógico', 4), ('Outros (Educação e Docência)', 4),
('Advogado Trabalhista', 5), ('Advogado Criminalista', 5), ('Consultor Jurídico', 5), ('Outros (Direito e Jurídico)', 5),
('Administrador Hospitalar', 6), ('Gerente Administrativo', 6), ('Analista de Processos', 6), ('Outros (Administração e Gestão)', 6),
('Analista de Marketing Digital', 7), ('Gestor de Tráfego', 7), ('Copywriter', 7), ('Outros (Marketing e Comunicação)', 7),
('Contador', 8), ('Analista Financeiro', 8), ('Perito Contábil', 8), ('Outros (Finanças e Contabilidade)', 8),
('Ilustrador', 9), ('Diretor de Arte', 9), ('Escultor', 9), ('Outros (Artes e Design)', 9),
('Biólogo Marinho', 10), ('Microbiologista', 10), ('Genetista', 10), ('Outros (Ciências Biológicas)', 10),

-- Área 11 a 20
('Engenheiro Agrônomo', 11), ('Zootecnista', 11), ('Técnico Agrícola', 11), ('Outros (Agronomia e Agropecuária)', 11),
('Psicólogo Clínico', 12), ('Psicólogo Organizacional', 12), ('Psicopedagogo', 12), ('Outros (Psicologia e Saúde Mental)', 12),
('Chef de Cozinha', 13), ('Subchef', 13), ('Padeiro Confeiteiro', 13), ('Outros (Gastronomia e Culinária)', 13),
('Guia de Turismo', 14), ('Gerente de Hotel', 14), ('Agente de Viagens', 14), ('Outros (Turismo e Hotelaria)', 14),
('Arquiteto Urbanista', 15), ('Paisagista', 15), ('Arquiteto de Restauro', 15), ('Outros (Arquitetura e Urbanismo)', 15),
('Analista de Logística', 16), ('Gerente de Supply Chain', 16), ('Despachante Operacional', 16), ('Outros (Logística e Transporte)', 16),
('Técnico em Segurança do Trabalho', 17), ('Engenheiro de Segurança do Trabalho', 17), ('Higienista Ocupacional', 17), ('Outros (Segurança do Trabalho)', 17),
('Analista de R&S', 18), ('Business Partner', 18), ('Analista de DP', 18), ('Outros (Recursos Humanos)', 18),
('Jornalista Investigativo', 19), ('Repórter de Campo', 19), ('Editor de Texto', 19), ('Outros (Jornalismo e Mídia)', 19),
('Estilista', 20), ('Personal Stylist', 20), ('Modelista', 20), ('Outros (Moda e Estilo)', 20),

-- Área 21 a 30
('Personal Trainer', 21), ('Preparador Físico', 21), ('Técnico Esportivo', 21), ('Outros (Esportes e Educação Física)', 21),
('Veterinário de Pequenos Animais', 22), ('Veterinário de Grandes Animais', 22), ('Patologista Veterinário', 22), ('Outros (Veterinária e Zootecnia)', 22),
('Químico Industrial', 23), ('Analista de Controle de Qualidade', 23), ('Engenheiro de Processos Químicos', 23), ('Outros (Química e Processos Industriais)', 23),
('Físico Médico', 24), ('Astrofísico', 24), ('Pesquisador Quântico', 24), ('Outros (Física e Astronomia)', 24),
('Estatístico', 25), ('Cientista de Dados', 25), ('Atuária', 25), ('Outros (Matemática e Estatística)', 25),
('Cinegrafista', 26), ('Editor de Vídeo', 26), ('Diretor de Cinema', 26), ('Outros (Cinema e Audiovisual)', 26),
('Músico Instrumentista', 27), ('Ator', 27), ('Produtor Musical', 27), ('Outros (Música e Artes Cênicas)', 27),
('Executivo de Vendas', 28), ('Vendedor Interno', 28), ('Gerente Comercial', 28), ('Outros (Vendas e Comercial)', 28),
('Operador de Telemarketing', 29), ('Atendente de SAC', 29), ('Líder de Atendimento', 29), ('Outros (Atendimento ao Cliente)', 29),
('Vigilante Patrimonial', 30), ('Policial Civil', 30), ('Guarda Municipal', 30), ('Outros (Segurança Pública e Privada)', 30),

-- Área 31 a 40
('Cabeleireiro', 31), ('Esteticista', 31), ('Maquiador Profissional', 31), ('Outros (Beleza e Estética)', 31),
('Consultor Ambiental', 32), ('Gestor Ambiental', 32), ('Técnico em Meio Ambiente', 32), ('Outros (Meio Ambiente e Ecologia)', 32),
('Assistente Social', 33), ('Mediador Comunitário', 33), ('Agente Social', 33), ('Outros (Serviço Social)', 33),
('Farmacêutico Hospitalar', 34), ('Biomédico', 34), ('Farmacêutico Bioquímico', 34), ('Outros (Farmácia e Biomedicina)', 34),
('Dentista Ortodontista', 35), ('Cirurgião Bucomaxilofacial', 35), ('Odontopediatra', 35), ('Outros (Odontologia)', 35),
('Fisioterapeuta Esportivo', 36), ('Terapeuta Ocupacional', 36), ('Fisioterapeuta Respiratório', 36), ('Outros (Fisioterapia e Terapia Ocupacional)', 36),
('Nutricionista Clínico', 37), ('Nutricionista Esportivo', 37), ('Nutricionista de Produção', 37), ('Outros (Nutrição)', 37),
('Engenheiro de Potência', 38), ('Engenheiro de Subestações', 38), ('Engenheiro Eletrônico', 38), ('Outros (Engenharia Elétrica)', 38),
('Engenheiro Automotivo', 39), ('Engenheiro HVAC', 39), ('Engenheiro de Manutenção', 39), ('Outros (Engenharia Mecânica)', 39),
('Engenheiro PCP', 40), ('Analista de Qualidade Industrial', 40), ('Gerente de Planta', 40), ('Outros (Engenharia de Produção)', 40),

-- Área 41 a 50
('Engenheiro Petroquímico', 41), ('Engenheiro de Polímeros', 41), ('Engenheiro de Tintas', 41), ('Outros (Engenharia Química)', 41),
('Engenheiro de Firmware', 42), ('Engenheiro de Hardware', 42), ('Arquiteto de Sistemas', 42), ('Outros (Engenharia de Computação)', 42),
('Engenheiro Sanitário', 43), ('Engenheiro de Recursos Hídricos', 43), ('Consultor de Licenciamento', 43), ('Outros (Engenharia Ambiental)', 43),
('Engenheiro de Robótica', 44), ('Especialista em Embarcados', 44), ('Engenheiro de Controle', 44), ('Outros (Engenharia Mecatrônica)', 44),
('Engenheiro Aeronáutico', 45), ('Engenheiro Aerodinâmico', 45), ('Engenheiro de Manutenção Aeronáutica', 45), ('Outros (Engenharia Aeronáutica)', 45),
('Engenheiro de Minas', 46), ('Geólogo de Exploração', 46), ('Técnico em Mineração', 46), ('Outros (Mineração e Geologia)', 46),
('Biotecnologista', 47), ('Engenheiro de Bioprocessos', 47), ('Pesquisador de Genômica', 47), ('Outros (Biotecnologia)', 47),
('Cientista Político', 48), ('Sociólogo', 48), ('Pesquisador de Opinião Pública', 48), ('Outros (Ciências Sociais e Política)', 48),
('Professor de Filosofia', 49), ('Consultor Ético', 49), ('Escritor Filosófico', 49), ('Outros (Filosofia e Sociologia)', 49),
('Historiador', 50), ('Geógrafo', 50), ('Cartógrafo', 50), ('Outros (História e Geografia)', 50),

-- Área 51 a 60
('Tradutor Juramentado', 51), ('Intérprete Simultâneo', 51), ('Professor de Idiomas', 51), ('Outros (Idiomas e Tradução)', 51),
('Editor de Livros', 52), ('Revisor de Texto', 52), ('Crítico Literário', 52), ('Outros (Literatura e Edição)', 52),
('Analista de RI', 53), ('Diplomata', 53), ('Consultor Internacional', 53), ('Outros (Relações Internacionais)', 53),
('Relações Públicas', 54), ('Assessor de Imprensa', 54), ('Gestor de Crise', 54), ('Outros (Relações Públicas)', 54),
('Publicitário', 55), ('Redator Publicitário', 55), ('Mídia Planner', 55), ('Outros (Publicidade e Propaganda)', 55),
('Designer de Interiores', 56), ('Decorador de Ambientes', 56), ('Consultor de Iluminação', 56), ('Outros (Design de Interiores)', 56),
('Designer Gráfico', 57), ('Designer de Embalagens', 57), ('Designer Editorial', 57), ('Outros (Design Gráfico)', 57),
('UX Designer', 58), ('UI Designer', 58), ('UX Researcher', 58), ('Outros (UX/UI Design)', 58),
('Game Designer', 59), ('Animador 3D', 59), ('Roteirista de Jogos', 59), ('Outros (Games e Animação)', 59),
('Fotógrafo Social', 60), ('Fotógrafo de Produtos', 60), ('Fotojornalista', 60), ('Outros (Fotografia)', 60),

-- Área 61 a 70
('Organizador de Eventos', 61), ('Cerimonialista', 61), ('Recreador Infantil', 61), ('Outros (Eventos e Recreação)', 61),
('Técnico de Manutenção Predial', 62), ('Eletromecânico', 62), ('Mecânico Industrial', 62), ('Outros (Manutenção e Reparos)', 62),
('Pedreiro', 63), ('Mestre de Obras', 63), ('Servente de Pedreiro', 63), ('Outros (Construção Civil Operacional)', 63),
('Soldador', 64), ('Caldeireiro', 64), ('Torneiro Mecânico', 64), ('Outros (Metalurgia e Soldagem)', 64),
('Marceneiro', 65), ('Carpinteiro', 65), ('Lustrador de Móveis', 65), ('Outros (Marcenaria e Carpintaria)', 65),
('Eletricista Residencial', 66), ('Eletricista Industrial', 66), ('Técnico em Eletrônica', 66), ('Outros (Eletricidade e Eletrônica)', 66),
('Encanador', 67), ('Gasista', 67), ('Desentupidor', 67), ('Outros (Encanamento e Hidráulica)', 67),
('Pintor Residencial', 68), ('Pintor Industrial', 68), ('Gesseiro', 68), ('Outros (Pintura e Acabamento)', 68),
('Técnico de Redes Telecom', 69), ('Instalador de Fibra Óptica', 69), ('Engenheiro de Telecom', 69), ('Outros (Telecomunicações)', 69),
('Técnico em Automação', 70), ('Programador de PLC', 70), ('Engenheiro de Automação', 70), ('Outros (Automação Industrial)', 70),

-- Área 71 a 80
('Engenheiro de Petróleo', 71), ('Técnico em Sondagem', 71), ('Operador de Plataforma', 71), ('Outros (Petróleo e Gás)', 71),
('Instalador Solar', 72), ('Engenheiro Eólico', 72), ('Consultor de Eficiência Energética', 72), ('Outros (Energias Renováveis)', 72),
('Analista Tributário', 73), ('Consultor Fiscal', 73), ('Planejador Tributário', 73), ('Outros (Contabilidade Tributária)', 73),
('Auditor Interno', 74), ('Oficial de Compliance', 74), ('Auditor de Riscos', 74), ('Outros (Auditoria e Compliance)', 74),
('Analista de Investimentos', 75), ('Corretor de Valores', 75), ('Trader', 75), ('Outros (Investimentos e Mercado Financeiro)', 75),
('Corretor de Seguros', 76), ('Analista de Sinistros', 76), ('Atuário de Seguros', 76), ('Outros (Seguros e Previdência)', 76),
('Analista de Importação', 77), ('Analista de Exportação', 77), ('Despachante Aduaneiro', 77), ('Outros (Comércio Exterior)', 77),
('Gerente de E-commerce', 78), ('Analista de Marketplace', 78), ('Especialista em SEO', 78), ('Outros (E-commerce e Vendas Online)', 78),
('Scrum Master', 79), ('Agile Coach', 79), ('Product Owner', 79), ('Outros (Gestão de Projetos)', 79),
('Consultor de Negócios', 80), ('Consultor de Estratégia', 80), ('Analista de M&A', 80), ('Outros (Estratégia e Consultoria)', 80),

-- Área 81 a 90
('Pedagogo', 81), ('Orientador Educacional', 81), ('Supervisor Escolar', 81), ('Outros (Pedagogia e Licenciaturas)', 81),
('Professor de Libras', 82), ('Especialista em Braille', 82), ('Acompanhante Terapêutico', 82), ('Outros (Educação Especial)', 82),
('Pesquisador Acadêmico', 83), ('Cientista de Laboratório', 83), ('Estatístico Acadêmico', 83), ('Outros (Pesquisa e Acadêmica)', 83),
('Arquivista', 84), ('Bibliotecário', 84), ('Documentalista', 84), ('Outros (Arquivologia e Biblioteconomia)', 84),
('Teólogo', 85), ('Capelão', 85), ('Pesquisador de Religião', 85), ('Outros (Teologia e Ciências Religiosas)', 85),
('Arqueólogo', 86), ('Antropólogo', 86), ('Etnógrafo', 86), ('Outros (Arqueologia e Antropologia)', 86),
('Meteorologista', 87), ('Climatologista', 87), ('Previsor do Tempo', 87), ('Outros (Meteorologia e Climatologia)', 87),
('Oceanógrafo', 88), ('Pesquisador Marinho', 88), ('Hidrógrafo', 88), ('Outros (Oceanografia e Ciências do Mar)', 88),
('Engenheiro de Pesca', 89), ('Técnico em Aquicultura', 89), ('Mestre de Embarcação', 89), ('Outros (Pesca e Aquicultura)', 89),
('Engenheiro Florestal', 90), ('Técnico Florestal', 90), ('Manejador Florestal', 90), ('Outros (Silvicultura e Manejo Florestal)', 90),

-- Área 91 a 100
('Groomer Tosador', 91), ('Adestrador de Cães', 91), ('Pet Sitter', 91), ('Outros (Cuidado de Animais)', 91),
('Empregada Doméstica', 92), ('Cozinheira Residencial', 92), ('Governanta', 92), ('Outros (Serviços Domésticos)', 92),
('Cuidador de Idosos', 93), ('Acompanhante Hospitalar', 93), ('Gerontólogo', 93), ('Outros (Cuidados de Idosos e Acompanhamento)', 93),
('Tanatopraxista', 94), ('Agente Funerário', 94), ('Necromaquiador', 94), ('Outros (Serviços Funerários)', 94),
('Auxiliar de Limpeza', 95), ('Encarregado de Serviços Gerais', 95), ('Limpador de Vidros em Altura', 95), ('Outros (Limpeza e Conservação)', 95),
('Jardineiro', 96), ('Podador de Árvores', 96), ('Manutencista de Gramados', 96), ('Outros (Jardinagem e Paisagismo)', 96),
('Costureira', 97), ('Alfaiate', 97), ('Corte e Costura Industrial', 97), ('Outros (Moda e Costura)', 97),
('Ourives', 98), ('Relojoeiro', 98), ('Gemólogo', 98), ('Outros (Joalheria e Relojoaria)', 98),
('Restaurador de Obras de Arte', 99), ('Restaurador de Móveis', 99), ('Artesão', 99), ('Outros (Artesanato e Restauro)', 99),
('Piloto Comercial', 100), ('Copiloto', 100), ('Comissário de Bordo', 100), ('Outros (Piloto e Aviação Civil)', 100);