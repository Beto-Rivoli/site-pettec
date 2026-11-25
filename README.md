# Site Institucional PETTEC — UNIFEI (Tema WordPress)

Tema WordPress próprio desenvolvido para o **PETTEC — Programa de Educação Tutorial em Tecnologia** da **Universidade Federal de Itajubá (UNIFEI)** ([pettec.unifei.edu.br](https://pettec.unifei.edu.br/)).

O projeto substitui a arquitetura anterior (baseada em Elementor e múltiplos addons conflitantes) por um **tema 100% nativo em PHP, CSS e JavaScript**, focado em velocidade, facilidade de atualização por futuras gerações de petianos e zero dependência de plugins de construtor.

---

## Principais Funcionalidades

- **Início (`front-page.php`):** alternância de faixas claras e escuras na paleta oficial da logo do PETTEC (`#141619`, `#1A1D21`, `#D61A1F`, `#F4F4F2`), parallax suave, selo circular giratório no scroll, métricas editáveis e vitrine **Selected Works** de Mini Cursos e Atividades.
- **Transição entre páginas:** cortina de transição em tela cheia ao navegar entre rotas internas (respeitando `prefers-reduced-motion`).
- **Atividades (`/atividades/`):** cadastro nativo com filtro por pilar (**Ensino**, **Pesquisa**, **Extensão**).
- **Mini Cursos (`/mini-cursos/`) e Listas de Exercícios (`/listas-de-exercicio/`):** cada mini curso aceita múltiplas playlists/vídeos do YouTube (embed nativo sem plugins externos) e múltiplos arquivos (listas de exercícios, slides, apostilas, código). Todas as listas são reunidas automaticamente em `/listas-de-exercicio/`, junto com listas avulsas.
- **Documentos e Editais (`/documentos/`):** listagem com upload direto de PDF pela Biblioteca de Mídia e filtros instantâneos por **Ano** e **Tipo** (*Edital*, *Planejamento*, *Relatório*, *Lista de Exercícios*, *Outros*).
- **Membros e Ex-membros (`/membros/`):** grade com matriz de fundo interativa, cards cinéticos com inclinação 3D no hover e filtro entre **Tutoria**, **Membros Atuais** e **Ex-membros**.
- **Depoimentos (`/depoimentos/` e Home):** deck empilhado interativo com as falas dos antigos petianos.
- **Blog (`/blog/`):** utiliza os **Posts nativos do WordPress**, com categorias e estimativa de tempo de leitura.
- **Contato (`/contato/`):** formulário nativo protegido contra CSRF e spam (*honeypot*), compatível com `wp_mail()` / WP Mail SMTP, além de endereço da UNIFEI e mapa incorporado (com redirecionamento 301 automático da rota antiga `/contact/`).
- **Defesa automática contra Mixed Content:** normaliza URLs de assets para `https://` atrás de proxy reverso sem interferir no ambiente local `http://127.0.0.1`.

---

## Estrutura do Repositório

```text
├── pettec-theme/                  # Código-fonte completo do tema WordPress
│   ├── assets/                    # CSS modular, JS (parallax, transições, filtros) e logo oficial
│   ├── inc/                       # CPTs, metaboxes nativos, Customizer, formulário e defesa HTTPS
│   └── template-parts/            # Componentes reutilizáveis (cards e deck de depoimentos)
├── data/
│   └── pettec-content-seed.xml    # Arquivo WXR importável com os dados reais do PETTEC
├── playground/
│   └── blueprint.json             # Configuração do WordPress Playground para preview local
├── scripts/
│   └── build-zip.mjs              # Empacotador que gera dist/pettec-theme.zip
└── docs/
    └── guia-migracao-wp-admin.md  # Passo a passo de backup e deploy via /wp-admin
```

---

## Como Rodar Localmente

Pré-requisito: apenas **Node.js** instalado (não precisa de XAMPP, PHP local, MySQL ou Docker).

```bash
npm run dev
```

O comando sobe uma instância local do WordPress via **WordPress Playground** com o tema montado em tempo real e os dados reais pré-carregados:

- **Site local:** `http://127.0.0.1:9400/`
- **Painel `/wp-admin` local:** `http://127.0.0.1:9400/wp-admin/` *(usuário `admin` / senha `password`)*

---

## Como Gerar o `.zip` e Publicar no `/wp-admin`

1. Gere o pacote limpo do tema:
   ```bash
   npm run zip
   ```
   O arquivo será criado em `dist/pettec-theme.zip`.

2. Siga o passo a passo completo de backup obrigatório, correção de HTTPS, envio do tema e importação de conteúdo em [docs/guia-migracao-wp-admin.md](docs/guia-migracao-wp-admin.md).

---

## Manutenção no Dia a Dia (`/wp-admin`)

- **Posts no Blog:** `Posts → Adicionar novo`
- **Atividades:** `Atividades → Adicionar nova`
- **Mini Cursos e Listas:** `Mini Cursos → Adicionar novo` (adicione aulas do YouTube e PDFs/Listas)
- **Editais e Documentos:** `Documentos e Editais → Adicionar novo`
- **Membros e Ex-membros:** `Membros e Ex-membros → Adicionar novo`
- **Depoimentos:** `Depoimentos → Adicionar novo`
- **Textos da Home, Métricas, E-mail e Redes Sociais:** `Aparência → Personalizar → PETTEC — Configurações do Site`
