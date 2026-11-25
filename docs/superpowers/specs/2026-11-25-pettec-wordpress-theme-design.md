# Design Specification — Tema WordPress Institucional PETTEC (UNIFEI)

**Data:** 2026-11-25  
**Projeto:** Reconstrução do site institucional do PETTEC — Programa de Educação Tutorial (UNIFEI) (`https://pettec.unifei.edu.br/`)  
**Workspace:** `c:\Users\Junin\Desktop\SitePet`  
**Status:** Pronto para aprovação do usuário (revisado via Builder Check)

---

## 1. Intenção e Motivação (O Porquê)

O site atual do PETTEC (`https://pettec.unifei.edu.br/`) sofre de três problemas estruturais confirmados por diagnóstico técnico:

1. **Mixed Content massivo (`http://` em páginas `https://`):** 44/50 requisições na Home e 49/50 em `/documentos/` são bloqueadas pelo navegador, deixando páginas inteiras sem CSS/JS e com imagens quebradas.
2. **Conflito e peso excessivo de plugins:** o tema Twenty Twenty-Four está misturado com Elementor + 6 addons concorrentes (`essential-addons`, `royal-elementor-addons`, `jeg-elementor-kit`, `header-footer-elementor`, `testimonials-carousel-elementor`, `megamenu`), além de `feeds-for-youtube` (gerando 404s malformados), `instagram-feed` e cache quebrado do `wp-optimize`. Isso causa lentidão (8–15s), cabeçalho ocluído atrás do hero e sobreposições visuais.
3. **Rotas quebradas e conteúdo ausente:** `/atividades/`, `/?page_id=30` e `/contact/` retornam 404; `/listas-de-exercicio/` está vazia; `/contato/` não possui formulário; `/membros/` tem links de LinkedIn quebrados (`#`) sem separação clara de ex-membros; e a área de **Blog** nunca foi implementada.

Como o PETTEC é um grupo estudantil universitário onde os membros se renovam anualmente, o site precisa ser **rápido, visualmente marcante e impossível de quebrar** quando novos petianos precisarem publicar um edital, cadastrar um membro/ex-membro, divulgar um minicurso ou escrever um post no blog pelo `/wp-admin`.

---

## 2. Objetivos, Não-Objetivos e Anti-Objetivos

### Objetivos
- Entregar um **tema WordPress próprio (`pettec-theme`)**, versionado em Git na pasta `SitePet`, empacotável em um único `.zip` instalável via `/wp-admin` (`Aparência → Temas → Adicionar novo`).
- Cobrir integralmente as 7 áreas institucionais:
  1. **Início** (quem somos, o que fazemos — Ensino, Pesquisa e Extensão — métricas, destaques e equipe)
  2. **Atividades** (projetos e ações do grupo)
  3. **Mini Cursos** (aulas/vídeos do YouTube + **Listas de Exercícios** integradas e/ou em página dedicada)
  4. **Documentos / Editais** (PDFs filtráveis por ano e categoria, como Editais, Planejamentos, Relatórios)
  5. **Membros e Ex-membros** (separação clara entre membros atuais, tutor(a) e egressos/ex-membros, com foto, cargo/período e LinkedIn/Lattes)
  6. **Blog** (usando os Posts nativos do WordPress, com categorias, busca e leitura confortável)
  7. **Contato** (formulário funcional + informações institucionais da UNIFEI + mapa)
- Adotar uma direção visual moderna com **parallax suave**, unindo a clareza acadêmica do **Harbour.Space** com o contraste e a energia editorial do **Extrafazant**, usando a paleta oficial da logo do PETTEC.
- Permitir desenvolvimento e testes locais completos sem instalar XAMPP ou Docker, usando **WordPress Playground** via Node.js.
- Fornecer um guia seguro de **backup via `/wp-admin`** e transição em produção (incluindo correção de `http://` → `https://` e desativação segura dos plugins conflitantes).

### Não-Objetivos
- Não construir um e-commerce, área de login para alunos ou sistema de inscrição/avaliação online (inscrições de editais/minicursos continuam via link externo, ex.: Google Forms ou SIGAA, configurável por item).
- Não depender de acesso SSH, FTP ou cPanel do servidor da UNIFEI (todo o deploy precisa funcionar exclusivamente pelo `/wp-admin`).
- Não migrar para fora do WordPress.

### Anti-Objetivos (O que caracterizaria fracasso)
- Exigir Elementor ou plugins pagos/pesados para o tema funcionar.
- Permitir que uma edição de conteúdo (ex.: adicionar um novo edital ou membro) desalinhe o layout ou quebre o parallax.
- Efeitos de parallax exagerados que causem travamento (*jank*), problemas no mobile ou falta de legibilidade em páginas de leitura (Blog e Documentos).
- Deixar a página 404 genérica ou links hardcoded em `http://`.

---

## 3. Restrições

- **Hospedagem UNIFEI:** acesso exclusivo via `/wp-admin`, com **permissões confirmadas** pelo usuário para enviar temas `.zip` (`Aparência → Temas → Adicionar novo`) e instalar plugins (`Plugins → Adicionar novo`). Não há acesso SSH/FTP/cPanel.
- **Proxy reverso / HTTPS (Decisão técnica do arquiteto):** como servidores universitários frequentemente terminam SSL em proxy reverso (onde `is_ssl()` pode retornar `false`), o tema incluirá **normalização defensiva de URLs para HTTPS** (respeitando `HTTP_X_FORWARDED_PROTO === 'https'` e `WP_HOME`) nos assets enfileirados, garantindo que o tema nunca sofra *Mixed Content* mesmo antes de ajustar o `wp-config.php`.
- **Ambiente local do usuário:** Windows com `Node.js`, `npm` e `Git` instalados (sem Docker, PHP local, MySQL ou XAMPP).
- **Segurança de produção:** nenhuma alteração deve ser feita no site em produção antes de realizar um backup completo pelo `/wp-admin` (ex.: plugin WPvivid ou UpdraftPlus).
- **Compatibilidade WordPress:** compatível com WordPress 6.x e PHP 7.4+ / 8.x (padrão de hospedagens universitárias).
- **Identidade visual fixa:** a logo oficial do PETTEC (fornecida pelo usuário — hexágono com circuito + engrenagem em grafite/preto `#1A1D21` e vermelho `#D61A1F`) será empacotada em `pettec-theme/assets/images/logo-pettec.png` como padrão do `custom-logo`. *(Decisão técnica do arquiteto: sobre fundos escuros `#141619`, a logo será exibida dentro de um container/pill branco limpo — seguindo a estética do Extrafazant — para preservar 100% da legibilidade do grafite `#1A1D21` sem distorcer as cores oficiais da marca.)*

---

## 4. Abordagens Consideradas

| Abordagem | Resumo | Trade-offs | Decisão |
| :--- | :--- | :--- | :--- |
| **A — Tema WordPress próprio 100% nativo** (`pettec-theme` em PHP/CSS/JS com CPTs e metaboxes nativos) | Tudo em código no Git; menus dedicados no `/wp-admin` para Atividades, Mini Cursos, Documentos e Membros; Blog nativo; zero dependência de plugins de construtor. | Máxima performance, fidelidade ao parallax e proteção contra quebras de layout; mudanças estruturais exigem editar o código e reenviar `.zip`. | **Escolhida pelo usuário** |
| **B — Tema de Blocos (Gutenberg / FSE)** | Usa `theme.json` e Block Patterns do editor visual nativo do WordPress. | Maior liberdade visual no painel, porém mais frágil a edições acidentais de espaçamento/parallax e menos natural para filtros estruturados sem blocos React customizados. | Descartada |
| **C — Tema Próprio + Plugin ACF gratuito** | Mesmos templates da Abordagem A, delegando os campos extras ao plugin ACF. | Facilita criar campos sem PHP, mas cria dependência de plugin externo que pode quebrar a exibição se desativado no `/wp-admin`. | Descartada |

---

## 5. Direção Visual, Paleta e Sistema de Parallax

### Paleta de Cores (derivada da logo oficial do PETTEC)
- **Escuro principal (seções dark / texto primário):** `#141619` (carvão profundo) e `#1A1D21` (grafite da logo)
- **Claro principal (seções light):** `#F4F4F2` (off-white quente, inspirado no Extrafazant) e `#FFFFFF` (cards e superfícies limpas)
- **Cor de destaque principal:** `#D61A1F` (vermelho PETTEC da logo) com estado hover `#B51419` e tint suave `#FDF0F0` para badges claros
- **Texto secundário / bordas:** `#5A606B` (sobre claro), `#A6ADB8` (sobre escuro), bordas sutis `rgba(26,29,33,0.12)` / `rgba(255,255,255,0.12)`

### Linguagem Visual (Harbour.Space + Extrafazant)
- **Alternância rítmica Claro ↔ Escuro:** seções alternam entre `#141619` e `#F4F4F2` para criar hierarquia clara (ex.: Hero escuro → Quem Somos claro → Atividades/Mini Cursos escuro → Blog & Documentos claro → Membros & CTA Contato escuro).
- **Tipografia Mista Expressiva (inspirada no Extrafazant):**
  - Títulos de destaque combinando **sans-serif condensada/geométrica bold em caixa alta** + **serifada editorial itálica** para palavras-chave de impacto (ex.: `ENSINO, PESQUISA` *& Extensão*).
  - Corpo de texto em sans-serif limpa de alta legibilidade (estilo Harbour.Space) para Blog, Editais e descrições.
- **Motivo geométrico de marca:** grade/constelação sutil em SVG inspirada no hexágono e trilhas de circuito da logo do PETTEC aplicada em baixa opacidade no fundo das seções escuras.
- **Cabeçalho flutuante:** logo do PETTEC à esquerda, barra de navegação central limpa (`Início`, `Atividades`, `Mini Cursos`, `Documentos`, `Membros`, `Blog`), botão pill à direita (`• Contato` com ponto vermelho), adaptando contraste suavemente no scroll e com menu drawer responsivo no mobile.

### Sistema de Parallax e Movimento
- **Parallax leve em CSS/JS (`requestAnimationFrame` + `IntersectionObserver`, sem bibliotecas pesadas):**
  - Camada de fundo geométrica e foto/banner do Hero com deslocamento vertical sutil (`data-parallax-speed`).
  - Selo circular giratório no scroll (ex.: `"PETTEC • UNIFEI • ENSINO • PESQUISA • EXTENSÃO •"`), inspirado no Extrafazant.
  - Deslocamento sutil em retratos/imagens de destaque nas seções da Home e revelação progressiva (*fade-up*) ao entrar na viewport.
- **Acessibilidade e performance:** desativação automática de movimento quando `prefers-reduced-motion: reduce` estiver ativo; intensidade reduzida ou desativada em telas pequenas/mobile para evitar *jank* e overflow horizontal.

---

## 6. Arquitetura de Informação e Páginas

1. **Início (`front-page.php`)**
   - Hero escuro com logo/tag institucional, manchete mista, CTAs (`Conheça o PETTEC`, `Mini Cursos`), selo giratório e faixa de métricas (editáveis no Personalizar do WordPress).
   - Seção **Quem Somos / O que Fazemos** (3 pilares: Ensino, Pesquisa e Extensão, em cards claros com toque tátil).
   - **Destaques de Atividades & Mini Cursos** (últimos itens publicados via CPT).
   - **Últimos Posts do Blog + Acesso Rápido a Editais** (linkando os documentos mais recentes).
   - **Prévia da Equipe** (retratos circulares estilo Harbour.Space) + faixa de CTA para Contato.
2. **Atividades (`archive-pettec_atividade.php` / `single-pettec_atividade.php`)**
   - Grade de cards filtrável por pilar (`Ensino`, `Pesquisa`, `Extensão`) com imagem, resumo, período/status e página de detalhes completa.
3. **Mini Cursos (`archive-pettec_minicurso.php` / `single-pettec_minicurso.php`) + Listas de Exercícios**
   - Cards com miniatura, carga horária/instrutor, contagem de aulas/materiais e acesso à página completa do curso.
   - Página individual do Mini Curso suporta **múltiplas aulas/vídeos do YouTube** (embed nativo responsivo, sem plugin `feeds-for-youtube`), **múltiplos arquivos e listas de exercícios** (PDFs/links com tipo: `Lista de Exercícios`, `Slide`, `Apostila`, `Código`), além do editor completo do WordPress para descrição, cronograma e galeria de fotos do curso.
   - Página dedicada `/listas-de-exercicio/` (template próprio) listando automaticamente todas as listas vinculadas aos Mini Cursos (agrupadas por curso) **mais quaisquer Listas Avulsas** cadastradas sem curso associado (marcadas com o tipo `Lista de Exercícios` em Documentos). *(Decisão técnica do arquiteto: reutilizar a taxonomia `Lista de Exercícios` em Documentos para listas avulsas evita criar um 5º menu redundante no `/wp-admin`.)*
4. **Documentos / Editais (`archive-pettec_documento.php`)**
   - Lista/grade limpa estilo tabela editorial com filtros instantâneos por **Ano** (ex.: 2026, 2025, 2024...) e **Categoria** (`Edital`, `Planejamento`, `Relatório`, `Regimento`), exibindo título, data, descrição curta e botão direto `Baixar PDF ↗`.
5. **Membros e Ex-membros (`archive-pettec_membro.php`)**
   - Filtro/abas claras separando **Tutor(a)**, **Membros Atuais** e **Ex-membros (Egressos)**.
   - Retratos circulares (inspiração Harbour.Space), nome, curso/cargo no PET, período de participação e ícones de LinkedIn / Lattes / GitHub (exibidos apenas quando preenchidos — nunca links `#` mortos).
6. **Blog (`home.php`, `single.php`, `archive.php`)**
   - Usa os **Posts nativos do WordPress** (zero curva de aprendizado no `/wp-admin`).
   - Layout editorial claro, filtro por categorias, tempo estimado de leitura, imagem destacada, barra de compartilhamento e posts relacionados.
7. **Contato (`page-contato.php`)**
   - Formulário de contato nativo do tema (Nome, E-mail, Assunto, Mensagem) com proteção CSRF (`wp_nonce_field`), campo *honeypot* anti-spam e envio via `wp_mail()` (com feedback claro de sucesso/erro e compatibilidade total com plugin WP Mail SMTP caso o servidor da UNIFEI exija SMTP autenticado).
   - Bloco lateral com e-mail oficial (`pettec@unifei.edu.br`), endereço do campus UNIFEI, redes sociais e mapa incorporado.
8. **Página 404 (`404.php`)**
   - Página personalizada com identidade do PETTEC, busca rápida e atalhos para as principais seções (substituindo o template genérico em inglês).

---

## 7. Modelo de Conteúdo no WordPress (`/wp-admin`)

Todo registrado diretamente no tema (sem depender de plugins externos):

| Tipo no `/wp-admin` | Slug | Campos customizados (Metaboxes nativos) | Taxonomias |
| :--- | :--- | :--- | :--- |
| **Atividades** (`pettec_atividade`) | `/atividades/` | Período/Ano, Status (`Em andamento` / `Concluída`), Link externo opcional | Pilar (`Ensino`, `Pesquisa`, `Extensão`) |
| **Mini Cursos** (`pettec_minicurso`) | `/mini-cursos/` | Instrutor(es), Carga horária, **Lista repetível de Aulas/Vídeos** (título + URL do YouTube por aula/playlist), **Lista repetível de Arquivos/Listas** (título, categoria `Lista de Exercícios`/`Slide`/`Apostila`/`Código`, URL/PDF integrado à Biblioteca de Mídia) + editor completo (texto e fotos/galeria) | Área/Tag do curso |
| **Documentos** (`pettec_documento`) | `/documentos/` | URL/Upload do PDF (integrado à Biblioteca de Mídia do WP), Ano de referência, Código/Número do Edital | Tipo (`Edital`, `Planejamento`, `Relatório`, `Lista de Exercícios`, `Outros`) |
| **Membros** (`pettec_membro`) | `/membros/` | Função/Cargo (`Tutor`, `Membro`, `Ex-membro`), Curso na UNIFEI, Período (ex.: `2024 – Atual`), LinkedIn, Lattes, GitHub | — |
| **Blog** (`post` nativo) | `/blog/` | Recursos nativos do WordPress (título, conteúdo, imagem destacada, resumo) | Categorias e Tags nativas |

**Personalização global (`Aparência → Personalizar` / Customizer):**
- Logo do PETTEC (upload nativo `custom-logo`, já embarcando a logo enviada como padrão).
- Textos do Hero, 4 métricas da Home, e-mail de destino do formulário de contato, endereço, link do mapa e URLs das redes sociais (Instagram, LinkedIn, YouTube, GitHub).

---

## 8. Fluxo Local, Empacotamento e Migração Segura para a UNIFEI

### Ambiente Local (em `c:\Users\Junin\Desktop\SitePet`)
- Estrutura organizada com o tema em `pettec-theme/`, dados públicos reais extraídos de `https://pettec.unifei.edu.br/` (editais 2025/2026, os 5 mini cursos do YouTube, lista de membros atuais e informações reais de contato/campus UNIFEI) em um pacote importável (`data/pettec-content-seed.xml` em formato WordPress WXR + script de seed), blueprint do **WordPress Playground** (`playground/blueprint.json`) que carrega o tema + logo oficial + dados reais automaticamente, e scripts no `package.json`:
  - `npm run dev`: sobe o WordPress localmente no navegador via `@wp-playground/cli` montando `pettec-theme/` em tempo real com os dados reais pré-carregados.
  - `npm run zip`: gera `dist/pettec-theme.zip` limpo (sem `node_modules` ou arquivos de dev), pronto para upload no `/wp-admin`.

### Guia de Transição Segura no `/wp-admin` da UNIFEI (`docs/guia-migracao-wp-admin.md`)
1. **Backup completo obrigatório** antes de qualquer alteração (instalar WPvivid Backup → gerar backup completo de arquivos + banco e baixar para o PC).
2. **Corrigir Mixed Content na raiz:** verificar em `Configurações → Geral` se *Endereço do WordPress* e *Endereço do site* estão em `https://pettec.unifei.edu.br`, e se necessário rodar substituição segura (`Better Search Replace` de `http://pettec.unifei.edu.br` → `https://pettec.unifei.edu.br`).
3. **Subir e ativar `pettec-theme.zip`** em `Aparência → Temas → Adicionar novo`.
4. **Evitar colisão de slugs com páginas antigas do Elementor (Decisão técnica do arquiteto):** mover as antigas páginas Elementor com slugs `/documentos/`, `/membros/`, `/mini-cursos/` e `/atividades/` para **Rascunho ou Lixeira** para que os arquivos nativos dos CPTs assumam essas URLs sem conflito. *(O tema também incluirá redirecionamento 301 automático de `/contact/` → `/contato/` para links antigos.)*
5. **Atualizar Links Permanentes** (`Configurações → Links Permanentes → Salvar alterações`) para registrar as rotas `/atividades/`, `/mini-cursos/`, `/documentos/`, `/membros/`, `/blog/`.
6. **Desativar gradualmente os plugins conflitantes** (addons redundantes do Elementor, `feeds-for-youtube`, cache antigo do `wp-optimize`) e validar todas as páginas.
7. **Importar conteúdo estruturado:** importar `data/pettec-content-seed.xml` via `Ferramentas → Importar → WordPress` (ou complementar manualmente pelos novos menus do painel) e conferir links de LinkedIn/ex-membros.

---

## 9. Decisões Deixadas para a Implementação (Builder)

- Escolha exata do par de fontes do Google Fonts (ou fontes locais) que melhor reproduza a combinação sans condensada/geométrica + serif editorial do Extrafazant + leitura limpa do Harbour.Space.
- Breakpoints responsivos exatos, escalas fluidas (`clamp()`) e curvas de easing das animações de parallax.
- Organização interna dos arquivos PHP (`inc/cpt-*.php`, `inc/metaboxes.php`, `inc/customizer.php`, `inc/contact-form.php`, `template-parts/`).
- Detalhes técnicos do `blueprint.json` do WordPress Playground e do script de empacotamento `.zip` em Node.js/PowerShell.
