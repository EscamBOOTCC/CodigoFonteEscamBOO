# EscamBOO

Plataforma web de economia colaborativa para conectar pessoas interessadas em oferecer e solicitar serviços por meio de trocas. Este projeto foi desenvolvido como Trabalho de Conclusão de Curso (Projeto Integrador) apresentado ao **Curso Técnico Integrado em Desenvolvimento de Sistemas** do **Instituto Federal do Paraná (IFPR) – Campus Foz do Iguaçu (2026)**.

**Orientadores:** Ana Carla Arruda de Holanda e Jefferson de Oliveira Chaves.

---

## Sobre o sistema

O EscamBOO permite que trabalhadores cadastrem habilidades e serviços, encontrem ofertas de outras pessoas e iniciem propostas de troca por meio de um mecanismo de compatibilidade (*match*) baseado em interesse mútuo. Quando uma proposta é aceita, o sistema cria um acordo formal que pode ser acompanhado até a conclusão ou cancelamento.

### Funcionalidades

**Trabalhadores**
- Criar conta, configurar perfil, profissão e áreas de interesse.
- Cadastrar, editar, visualizar e excluir serviços, incluindo imagens.
- Buscar e filtrar serviços por texto, grande área, profissão e localização.
- Enviar, aceitar, recusar, editar mensagem e acompanhar propostas de troca (*matches*).
- Consultar acordos, acessar contato externo via WhatsApp para alinhamento operacional, confirmar conclusão mútua e avaliar a experiência.
- Bloquear e desbloquear usuários, além de enviar e acompanhar denúncias.

**Administradores**
- Consultar e gerenciar usuários (ativar, suspender, banir) e cadastrar novos administradores.
- Gerenciar estruturas de grandes áreas de atuação e profissões derivadas.
- Analisar denúncias e aplicar penalidades (advertência, suspensão temporária ou banimento).
- Consultar acordos cancelados e auditar suas justificativas.

> A recuperação de senha não está disponível nesta versão.

---

## Principais regras de negócio

- **Elegibilidade e unicidade:** cadastro permitido apenas para maiores de 18 anos e com CPF único.
- **Pré-requisito para troca:** o trabalhador precisa ter ao menos um serviço cadastrado para enviar propostas de *match*.
- **Acordo único:** cada trabalhador pode manter apenas um acordo ativo por vez; durante a vigência, seu status de disponibilidade muda automaticamente para indisponível.
- **Conclusão e reputação:** o acordo só é considerado concluído após confirmação de ambas as partes, liberando avaliações imutáveis (nota e comentário) para o serviço e para o trabalhador.
- **Moderação:** cancelamentos exigem justificativa obrigatória e podem ser auditados pelos administradores, assim como denúncias registradas na plataforma.

---

## Prévia do sistema

| Tela de Login | Feed e Match |
| :---: | :---: |
| ![Login](./docs/prints/login.png) | ![Feed](./docs/prints/feed.png) |

| Acordo Formalizado | Painel Administrativo |
| :---: | :---: |
| ![Acordo](./docs/prints/acordo.png) | ![Admin](./docs/prints/admin.png) |

> **Nota:** Ajuste os caminhos das imagens acima conforme a pasta onde salvar os prints no repositório.

---

## Tecnologias

- PHP 8.0+ nativo com orientação a objetos e arquitetura inspirada em MVC.
- MySQL ou MariaDB com PDO.
- HTML5, CSS3 e JavaScript.
- Bootstrap 5 e Bootstrap Icons carregados por CDN.
- Criptografia de senhas com `Bcrypt` (`password_hash`).

Não há dependências instaladas via Composer ou `npm` neste projeto.

---

## Requisitos

- PHP 8.0 ou superior.
- MySQL ou MariaDB em execução.
- Extensão PHP `pdo_mysql` habilitada.
- Extensão PHP `fileinfo` habilitada para validar imagens enviadas.
- Apache com `mod_rewrite` habilitado, configurado para usar `Public/` como raiz pública.

---

## Como executar localmente

### 1. Configurar o banco de dados

Edite as constantes em [`App/Config/Config.php`](./App/Config/Config.php) para corresponder ao seu ambiente:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'EscamBOO');
define('DB_USER', 'root');
define('DB_PASS', '');
```

O endereço base usado pela aplicação também está nesse arquivo:

```php
define('URL_BASE', 'http://localhost:8080');
```

Configure o Apache para servir o diretório `Public/` nesse endereço. As rotas da aplicação precisam ser reescritas para `Public/index.php`. Com `mod_rewrite` habilitado e `AllowOverride All`, crie `Public/.htaccess` com:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

Se usar outro domínio, porta ou caminho público, ajuste `URL_BASE` para refletir o endereço acessado no navegador.

### 2. Inicializar o esquema

Na primeira inicialização, se o banco configurado ainda não existir, a aplicação tenta criá-lo, executar o esquema de [`ScriptBD.sql`](./App/Database/Scripts/ScriptBD.sql) e inserir contas administrativas de demonstração. Para isso, o usuário do banco precisa ter permissão para criar bancos e tabelas.

Se o banco já existir vazio, a inicialização automática não é executada. Nesse caso, importe o script SQL no banco `EscamBOO`. A importação manual cria tabelas e dados de áreas e profissões, mas não executa a etapa de criação das contas administrativas feita pelo inicializador da aplicação.

### 3. Iniciar e acessar

Inicie o Apache e o MySQL/MariaDB e abra o endereço configurado em `URL_BASE` (por padrão, `http://localhost:8080`).

---

## Roteiro de demonstração

1. Crie duas contas de trabalhador usando dados fictícios.
2. Configure profissão e interesses e cadastre ao menos um serviço em cada conta.
3. Envie uma proposta de troca de uma conta para a outra.
4. Entre na conta destinatária e aceite a proposta.
5. Acompanhe o acordo, confirme a conclusão e registre as avaliações.
6. Para demonstrar a área administrativa, acesse uma conta administradora de demonstração e explore o gerenciamento de usuários, denúncias e acordos cancelados.

O sistema inclui credenciais administrativas fixas para demonstração em [`DatabaseInitializer.php`](./App/Database/DatabaseInitializer.php). Elas são apenas para uso local: altere ou remova essas contas antes de disponibilizar uma instalação acessível pela internet. Não use dados pessoais reais na demonstração.

---

## Imagens enviadas

O sistema aceita imagens `JPG`, `PNG`, `WEBP` e `GIF` de até 2 MB por arquivo. As imagens de perfil são armazenadas em `Public/uploads/perfis/` e as imagens de serviços em `Public/uploads/servicos/`. O servidor web precisa ter permissão de escrita nesses diretórios.

Serviços podem ter até duas imagens. Elas podem ser enviadas como arquivo ou informadas por URL.

---

## Estrutura do projeto

```text
App/
  Config/         Configurações da aplicação e do banco
  Controllers/    Recebem as requisições e coordenam os fluxos
  Core/           Roteador, controlador base e autoload
  Database/       Conexão, inicialização e script SQL
  Helpers/        Validação e armazenamento de imagens
  Models/         Entidades do sistema
  Repositories/   Consultas e persistência de dados
  Services/       Regras e operações da aplicação
  Views/          Telas PHP/HTML
Public/
  assets/         Arquivos estáticos
  uploads/        Imagens enviadas pelos usuários
  index.php       Ponto de entrada e definição das rotas
```

---

## Trabalhos futuros

- [ ] Canal de comunicação interno na plataforma para alinhamento de serviços.
- [ ] Filtros por geolocalização e raio de proximidade.
- [ ] Sistema gamificado de níveis e classes para prestadores de serviços.
- [ ] Mecanismo inteligente de recomendação de *matches* baseado em histórico e reputação.
- [ ] Fluxo de recuperação de senha e reativação de conta verificada.
- [ ] Versão mobile dedicada da plataforma.

---

## Observações para publicação

- `DEV_ENVIRONMENT` está habilitado em [`Config.php`](./App/Config/Config.php) para desenvolvimento local. Desabilite a exibição de erros antes de publicar uma instalação.
- Use credenciais próprias e restritas para o banco; não publique senhas ou configurações locais.
- As contas administrativas de demonstração têm credenciais fixas no código. Não as mantenha em uma instalação pública.
- O repositório não define uma licença de uso. Os autores devem escolher e adicionar uma licença antes de conceder permissões explícitas de reutilização ou distribuição.

---

## Integrantes

- **Evillyn Regina dos Santos** — [GitHub](https://github.com/htttpsviiiih)
- **Maria Luiza Augusto dos Santos** — [GitHub](https://github.com/mlads15)
- **Rafael Vitor da Cunha** — [GitHub](https://github.com/RafaVitorCunha)
- **Sarah Elizabete Giembra** — [GitHub](https://github.com/SarahElizabeteTDS)