# Tema WordPress Institucional PETTEC (UNIFEI) — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use `superpowers:subagent-driven-development` (recommended) or `superpowers:executing-plans` to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build `pettec-theme`, a self-contained, zero-builder WordPress theme for PETTEC (UNIFEI) with Harbour.Space structure, Extrafazant editorial energy + parallax, native CPTs/metaboxes, pre-loaded real public site data, WordPress Playground local dev, and a production-ready `.zip` + `/wp-admin` migration guide.

**Architecture:** Classic/hybrid PHP WordPress theme (`pettec-theme/`) with zero external plugin dependencies. Structured content (Atividades, Mini Cursos with repeatable YouTube lessons & materials, Documentos/Editais, Membros/Ex-membros) is managed via native WordPress Custom Post Types, taxonomies, and nonce-protected metaboxes; Blog uses native `post`; Contact uses a native CSRF + honeypot `wp_mail()` handler. Local execution runs on Node.js via `@wp-playground/cli` with a blueprint that auto-imports real public PETTEC content from `data/pettec-content-seed.xml`.

**Tech Stack:** WordPress 6.x (PHP 7.4+ / 8.x), Vanilla CSS3 (modular custom properties, fluid typography, responsive grid), Vanilla ES6 JS (`requestAnimationFrame` parallax, `IntersectionObserver` reveals, client-side archive filters, admin repeater UI), Node.js (`node:test`, `@wp-playground/cli`, zip packager).

**Spec:** `docs/superpowers/specs/2026-11-25-pettec-wordpress-theme-design.md`

---

## Global Constraints

- WordPress 6.x and PHP 7.4+ / 8.x compatibility; zero dependency on Elementor, ACF, or paid/heavy plugins.
- Deployable exclusively via `/wp-admin` (`Aparência → Temas → Adicionar novo` uploading `dist/pettec-theme.zip`).
- Official PETTEC brand palette from logo: dark `#141619` / `#1A1D21`, primary accent `#D61A1F` (hover `#B51419`, tint `#FDF0F0`), light `#F4F4F2` / `#FFFFFF`.
- Logo bundled at `pettec-theme/assets/images/logo-pettec.png` and displayed on a clean light pill/surface over dark backgrounds.
- All parallax/motion must respect `prefers-reduced-motion: reduce` and avoid horizontal overflow or jank on mobile (`375px` viewport).
- All user-facing copy in Brazilian Portuguese (`pt-BR`).
- Local environment runs on Windows with Node.js + Git only (no Docker, XAMPP, or local PHP binary required).

## Review Focus

1. **YouTube URL parsing edge cases (`pettec_parse_youtube_url`):** must handle `youtube.com/watch?v=`, `youtu.be/`, `youtube.com/embed/`, `youtube.com/playlist?list=`, and reject invalid/non-YouTube URLs safely without broken iframes or XSS (tested in Task 2).
2. **Defensive HTTPS behind reverse proxy vs. local HTTP Playground (`pettec_normalize_asset_url`):** must upgrade `http://` to `https://` when `HTTP_X_FORWARDED_PROTO === 'https'` or `WP_HOME` is `https://`, while leaving `http://127.0.0.1` untouched in local Playground (tested in Task 1).
3. **Empty/dead metadata handling (e.g., `#` LinkedIn links, courses with 0 lists/videos, empty `/listas-de-exercicio/`):** must strip/ignore `#` links and render clean empty states with zero PHP warnings (tested in Task 2 & Task 5).
4. **Contact form validation & abuse protection:** must reject missing/invalid nonces, filled honeypot fields, invalid emails, and CRLF header injection, and show a friendly `pt-BR` error with `mailto:pettec@unifei.edu.br` fallback if `wp_mail()` returns `false` (tested in Task 6).
5. **Cross-platform `.zip` packaging (`dist/pettec-theme.zip`):** archive entries must use forward slashes (`/`), place `pettec-theme/style.css` at the top level, and exclude dev files (`node_modules`, `.git`, `tests`, `docs`) so Linux WordPress servers at UNIFEI extract cleanly (tested in Task 7).

---

## File Structure Map

```text
c:\Users\Junin\Desktop\SitePet\
├── .gitignore
├── package.json
├── scripts/
│   └── build-zip.mjs                    # Packages pettec-theme/ -> dist/pettec-theme.zip
├── playground/
│   └── blueprint.json                   # WordPress Playground setup (theme, pages, logo, WXR seed)
├── data/
│   └── pettec-content-seed.xml          # WordPress WXR with real public PETTEC content
├── docs/
│   ├── guia-migracao-wp-admin.md        # Step-by-step backup, HTTPS fix, upload & cutover guide
│   └── superpowers/
│       ├── specs/2026-11-25-pettec-wordpress-theme-design.md
│       └── plans/2026-11-25-pettec-wordpress-theme.md
├── tests/
│   ├── theme-core.test.mjs              # Verifies theme structure, HTTPS defense, zip integrity
│   ├── helpers-and-cpts.test.mjs        # Verifies YouTube parser, repeater sanitizer, CPT registrations
│   └── templates-and-contact.test.mjs   # Verifies templates, contact security, a11y/reduced-motion rules
└── pettec-theme/
    ├── style.css                        # WordPress theme header + base imports
    ├── functions.php                    # Theme setup, enqueues, 301 /contact/ -> /contato/, includes
    ├── header.php                       # Floating header with logo pill, nav, Contato pill, mobile drawer
    ├── footer.php                       # Dark footer with CTA, 4-column grid, logo pill, UNIFEI credit
    ├── front-page.php                   # Alternating dark/light parallax homepage
    ├── home.php                         # Blog archive listing (native WP posts)
    ├── archive.php                      # Category/tag blog archive
    ├── single.php                       # Single blog post editorial view
    ├── index.php                        # Fallback template
    ├── 404.php                          # Custom branded 404 page
    ├── archive-pettec_atividade.php     # Atividades filterable by Pilar
    ├── single-pettec_atividade.php      # Single atividade detail
    ├── archive-pettec_minicurso.php     # Mini Cursos grid
    ├── single-pettec_minicurso.php      # Mini Curso detail (multiple YouTube embeds + materials/lists)
    ├── archive-pettec_documento.php     # Documentos/Editais filterable by Year & Type
    ├── archive-pettec_membro.php        # Membros/Tutor/Ex-membros tabbed circular grid
    ├── page-listas-de-exercicio.php     # Aggregated Mini Curso lists + standalone lists
    ├── page-contato.php                 # Native contact form + UNIFEI info + map
    ├── inc/
    │   ├── https-defense.php            # Reverse-proxy HTTPS asset URL normalization
    │   ├── helpers.php                  # YouTube parser, repeater sanitizer, reading time, list aggregator
    │   ├── cpts.php                     # CPT & taxonomy registrations
    │   ├── metaboxes.php                # Nonce-protected native metaboxes + repeatable fields
    │   ├── customizer.php               # Hero, 4 metrics, contact/map/social Customizer settings
    │   └── contact-form.php             # CSRF + honeypot + header-safe wp_mail() handler
    ├── template-parts/
    │   ├── card-atividade.php
    │   ├── card-minicurso.php
    │   ├── card-membro.php
    │   └── card-post.php
    └── assets/
        ├── images/
        │   └── logo-pettec.png          # Official PETTEC logo
        ├── css/
        │   ├── tokens.css               # Palette, typography, spacing, z-index tokens
        │   ├── base.css                 # Reset, mixed display typography, hex-circuit SVG motif, buttons
        │   ├── layout.css               # Floating header, drawer, dark/light bands, footer, grids
        │   ├── components.css           # Cards, circular portraits, filter pills, tables, forms, embeds
        │   └── motion.css               # Parallax transforms, rotating badge, fade-up, reduced-motion
        └── js/
            ├── main.js                  # Parallax rAF engine, scroll badge, reveals, drawer, filters
            └── admin-repeaters.js       # Admin metabox repeatable rows + WP Media Library picker
```

---

### Task 1: Project Scaffolding, Logo Asset, Theme Core & Defensive HTTPS

**Files:**
- Create: `.gitignore`
- Create: `package.json`
- Create: `pettec-theme/assets/images/logo-pettec.png` (copied from uploaded logo)
- Create: `pettec-theme/style.css`
- Create: `pettec-theme/functions.php`
- Create: `pettec-theme/inc/https-defense.php`
- Create: `pettec-theme/inc/customizer.php`
- Test: `tests/theme-core.test.mjs`

**Interfaces:**
- Consumes: none
- Produces:
  - `PETTEC_THEME_VERSION` constant (`'1.0.0'`) and `PETTEC_THEME_DIR` / `PETTEC_THEME_URI`
  - `pettec_is_https_request(array $server, string $site_url): bool` and `pettec_normalize_asset_url(string $url, bool $force_https): string` in `pettec-theme/inc/https-defense.php`
  - `pettec_get_option(string $key, string $default = ''): string` in `pettec-theme/inc/customizer.php` (keys: `hero_eyebrow`, `hero_title_sans`, `hero_title_serif`, `hero_subtitle`, `metric_1_num`..`metric_4_label`, `contact_email`, `contact_address`, `contact_map_embed`, `social_instagram`, `social_linkedin`, `social_youtube`, `social_github`)
  - Nav menu locations: `'primary'`, `'footer'`

- [ ] **Step 1: Initialize Git repo, `.gitignore`, and `package.json`**

Create `.gitignore` (ignoring `node_modules/`, `dist/`, `.DS_Store`, `*.log`) and `package.json` with `"type": "module"` and scripts:
- `"test": "node --test tests/*.test.mjs"`
- `"dev": "npx --yes @wp-playground/cli server --blueprint=playground/blueprint.json --mount=./pettec-theme:/wordpress/wp-content/themes/pettec-theme --port=9400"`
- `"zip": "node scripts/build-zip.mjs"`

Initialize git with `git init`.

- [ ] **Step 2: Copy official PETTEC logo into theme assets**

Copy `C:/Users/Junin/.gemini/antigravity/brain/882ab38c-85df-47f6-9605-2f2dc2591105/.user_uploaded/media_1795579081368_84a66065.png` to `pettec-theme/assets/images/logo-pettec.png`.

- [ ] **Step 3: Write failing test `tests/theme-core.test.mjs`**

Assert:
- `pettec-theme/style.css` exists and declares `Theme Name: PETTEC UNIFEI` and `Text Domain: pettec`.
- `pettec-theme/assets/images/logo-pettec.png` exists and is non-empty.
- `pettec-theme/inc/https-defense.php` defines `pettec_is_https_request` and `pettec_normalize_asset_url`, checks `HTTP_X_FORWARDED_PROTO`, and filters `style_loader_src`, `script_loader_src`, and `wp_get_attachment_url`.
- `pettec-theme/functions.php` registers `'primary'` and `'footer'` menus, enables `custom-logo`, `post-thumbnails`, `title-tag`, and redirects `/contact` → `/contato/` with HTTP 301.
- `pettec-theme/inc/customizer.php` defines `pettec_get_option` and registers hero, metrics, contact, and social settings.

- [ ] **Step 4: Run test to verify it fails**

Run: `npm test`
Expected: FAIL (missing theme files).

- [ ] **Step 5: Implement `style.css`, `inc/https-defense.php`, `inc/customizer.php`, and `functions.php`**

- `pettec-theme/style.css`: WordPress theme metadata header (`Theme Name: PETTEC UNIFEI`, `Version: 1.0.0`, `Requires at least: 6.0`, `Requires PHP: 7.4`, `Text Domain: pettec`).
- `pettec-theme/inc/https-defense.php`: implement `pettec_is_https_request()` (returns `true` if `HTTPS === 'on'`, `SERVER_PORT == 443`, `HTTP_X_FORWARDED_PROTO === 'https'`, or `site_url` starts with `https://` AND host is not `127.0.0.1`/`localhost`) and `pettec_normalize_asset_url()` (upgrades `http://` same-host URLs to `https://` when forced). Hook into `style_loader_src`, `script_loader_src`, `wp_get_attachment_url`.
- `pettec-theme/inc/customizer.php`: register `pettec_panel` with sections for Hero, Métricas, Contato e Mapa, e Redes Sociais, with sensible PETTEC UNIFEI defaults (e.g., `contact_email` = `pettec@unifei.edu.br`).
- `pettec-theme/functions.php`: define constants, load `inc/*.php` (guarding optional files with `file_exists` until later tasks add them), `after_setup_theme` (title-tag, post-thumbnails, custom-logo, html5, nav menus), enqueue Google Fonts (`Barlow Condensed`, `Instrument Serif`, `Plus Jakarta Sans`) + modular CSS + `assets/js/main.js`, and `template_redirect` 301 from `/contact` to `/contato/`.

- [ ] **Step 6: Run test to verify it passes**

Run: `npm test`
Expected: PASS.

- [ ] **Step 7: Commit**

```bash
git add .gitignore package.json pettec-theme/ tests/theme-core.test.mjs
git commit -m "feat: scaffold pettec-theme core, customizer, logo, and defensive HTTPS"
```

---

### Task 2: Helpers, Custom Post Types & Repeatable Native Metaboxes

**Files:**
- Create: `pettec-theme/inc/helpers.php`
- Create: `pettec-theme/inc/cpts.php`
- Create: `pettec-theme/inc/metaboxes.php`
- Create: `pettec-theme/assets/js/admin-repeaters.js`
- Test: `tests/helpers-and-cpts.test.mjs`

**Interfaces:**
- Consumes: `PETTEC_THEME_URI`, `PETTEC_THEME_VERSION` from Task 1
- Produces:
  - CPTs: `pettec_atividade` (`atividades`), `pettec_minicurso` (`mini-cursos`), `pettec_documento` (`documentos`), `pettec_membro` (`membros`)
  - Taxonomies: `pettec_pilar` (`Ensino`, `Pesquisa`, `Extensão`), `pettec_curso_area`, `pettec_doc_tipo` (`Edital`, `Planejamento`, `Relatório`, `Lista de Exercícios`, `Outros`)
  - Post meta keys:
    - `pettec_atividade`: `_pettec_periodo`, `_pettec_status`, `_pettec_link_externo`
    - `pettec_minicurso`: `_pettec_instrutor`, `_pettec_carga_horaria`, `_pettec_aulas` (JSON/array of `['titulo' => string, 'url' => string]`), `_pettec_materiais` (JSON/array of `['titulo' => string, 'tipo' => string, 'url' => string]`)
    - `pettec_documento`: `_pettec_doc_url`, `_pettec_doc_ano`, `_pettec_doc_codigo`
    - `pettec_membro`: `_pettec_membro_papel` (`tutor` | `membro` | `ex-membro`), `_pettec_membro_curso`, `_pettec_membro_periodo`, `_pettec_membro_linkedin`, `_pettec_membro_lattes`, `_pettec_membro_github`
  - Helpers in `inc/helpers.php`:
    - `pettec_parse_youtube_url(string $url): ?array` → `['type' => 'video'|'playlist', 'id' => string, 'embed_url' => string]` or `null`
    - `pettec_clean_external_url(string $url): string` → returns `''` for empty, `#`, or invalid scheme; sanitized URL otherwise
    - `pettec_estimate_reading_time(string $content): int`
    - `pettec_get_all_exercise_lists(): array` → `['cursos' => [...], 'avulsas' => [...]]`

- [ ] **Step 1: Write failing test `tests/helpers-and-cpts.test.mjs`**

Assert:
- `pettec-theme/inc/cpts.php` registers all 4 CPTs with exact archive slugs (`atividades`, `mini-cursos`, `documentos`, `membros`) and all 3 taxonomies, with default Portuguese terms seeded on theme activation (`after_switch_theme`).
- `pettec-theme/inc/helpers.php` defines `pettec_parse_youtube_url`, `pettec_clean_external_url`, `pettec_estimate_reading_time`, and `pettec_get_all_exercise_lists`.
- `pettec-theme/inc/metaboxes.php` uses `wp_nonce_field` and `wp_verify_nonce`, checks `DOING_AUTOSAVE` and `current_user_can`, and handles repeatable `_pettec_aulas` and `_pettec_materiais` plus document PDF media picker.
- `pettec-theme/assets/js/admin-repeaters.js` supports adding/removing repeater rows and invoking `wp.media` uploader.

- [ ] **Step 2: Run test to verify it fails**

Run: `npm test`
Expected: FAIL.

- [ ] **Step 3: Implement `inc/helpers.php`, `inc/cpts.php`, `inc/metaboxes.php`, and `assets/js/admin-repeaters.js`**

- `inc/helpers.php`:
  - `pettec_parse_youtube_url`: regex matching `youtube.com/watch?v=`, `youtu.be/`, `youtube.com/embed/`, `youtube.com/shorts/`, and `youtube.com/playlist?list=`; returns `https://www.youtube-nocookie.com/embed/...` or `null` on non-YouTube URLs.
  - `pettec_clean_external_url`: trims, rejects `#` or `javascript:`, validates `http://`/`https://`, returns `esc_url_raw`.
  - `pettec_get_all_exercise_lists`: queries published `pettec_minicurso` items collecting `_pettec_materiais` where `tipo === 'Lista de Exercícios'`, plus published `pettec_documento` items in taxonomy `pettec_doc_tipo` slug `lista-de-exercicios`.
- `inc/cpts.php`: register CPTs with Portuguese labels, Dashicons (`dashicons-hammer`, `dashicons-welcome-learn-more`, `dashicons-media-document`, `dashicons-groups`), `show_in_rest => true` (so Gutenberg gallery/blocks work inside Mini Cursos & Atividades), and seed default terms (`Ensino`, `Pesquisa`, `Extensão`; `Edital`, `Planejamento`, `Relatório`, `Lista de Exercícios`, `Outros`).
- `inc/metaboxes.php` & `assets/js/admin-repeaters.js`: clean admin UI for all 4 CPTs, enqueue `wp_enqueue_media()` and `admin-repeaters.js` only on CPT edit screens.

- [ ] **Step 4: Run test to verify it passes**

Run: `npm test`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add pettec-theme/inc/ pettec-theme/assets/js/admin-repeaters.js tests/helpers-and-cpts.test.mjs
git commit -m "feat: add CPTs, repeatable metaboxes, and YouTube/list helpers"
```

---

### Task 3: Design System CSS, Floating Header, Footer, Parallax Engine & 404

**Files:**
- Create: `pettec-theme/assets/css/tokens.css`
- Create: `pettec-theme/assets/css/base.css`
- Create: `pettec-theme/assets/css/layout.css`
- Create: `pettec-theme/assets/css/components.css`
- Create: `pettec-theme/assets/css/motion.css`
- Create: `pettec-theme/assets/js/main.js`
- Create: `pettec-theme/header.php`
- Create: `pettec-theme/footer.php`
- Create: `pettec-theme/404.php`
- Test: `tests/templates-and-contact.test.mjs` (initial assertions for design tokens, reduced-motion, header/footer/404)

**Interfaces:**
- Consumes: `pettec_get_option`, `PETTEC_THEME_URI`, nav menu `'primary'` / `'footer'`
- Produces:
  - CSS design tokens (`--bg-dark: #141619`, `--bg-graphite: #1A1D21`, `--bg-light: #F4F4F2`, `--surface-white: #FFFFFF`, `--accent-red: #D61A1F`, `--accent-red-hover: #B51419`, `--accent-red-tint: #FDF0F0`)
  - Reusable layout classes: `.band--dark`, `.band--light`, `.band--hex-grid`, `.container`, `.display-mixed` (condensed uppercase sans + `<em>` editorial serif), `.btn-pill`, `.btn-primary`, `.card`, `.portrait-circle`, `.filter-pills`, `.reveal`, `[data-parallax-speed]`
  - JS behaviors in `assets/js/main.js`: rAF parallax loop (respecting `prefers-reduced-motion: reduce` and `window.innerWidth >= 768`), rotating scroll badge (`[data-scroll-rotate]`), `IntersectionObserver` `.reveal` → `.is-visible`, sticky header `.is-scrolled` toggle, accessible mobile drawer (`aria-expanded`, Escape key close), and declarative `[data-filter-group]` client-side filtering.

- [ ] **Step 1: Write failing assertions in `tests/templates-and-contact.test.mjs`**

Assert:
- `tokens.css` defines `#141619`, `#1A1D21`, `#D61A1F`, `#F4F4F2`.
- `motion.css` includes `@media (prefers-reduced-motion: reduce)`.
- `main.js` checks `prefers-reduced-motion: reduce`, uses `requestAnimationFrame` and `IntersectionObserver`, and handles mobile drawer + `[data-filter-group]`.
- `header.php` renders the PETTEC logo inside a light pill container, primary nav, and `• Contato` pill CTA linking to `/contato/`.
- `footer.php` renders dark closing CTA, logo pill, 4-column links, and UNIFEI credit.
- `404.php` renders Portuguese 404 copy with links to Home, Documentos, Mini Cursos, and Contato.

- [ ] **Step 2: Run test to verify it fails**

Run: `npm test`
Expected: FAIL.

- [ ] **Step 3: Implement CSS modules, `main.js`, `header.php`, `footer.php`, and `404.php`**

Build clean, responsive CSS with zero horizontal overflow at `375px`, subtle inline SVG hex/circuit background pattern for `.band--hex-grid`, Harbour.Space disciplined grids + Extrafazant mixed typography & tactile hover states.

- [ ] **Step 4: Run test to verify it passes**

Run: `npm test`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add pettec-theme/assets/css/ pettec-theme/assets/js/main.js pettec-theme/header.php pettec-theme/footer.php pettec-theme/404.php tests/templates-and-contact.test.mjs
git commit -m "feat: add design system, parallax engine, header, footer, and 404"
```

---

### Task 4: Reusable Card Parts & Alternating Parallax Homepage (`front-page.php`)

**Files:**
- Create: `pettec-theme/template-parts/card-atividade.php`
- Create: `pettec-theme/template-parts/card-minicurso.php`
- Create: `pettec-theme/template-parts/card-membro.php`
- Create: `pettec-theme/template-parts/card-post.php`
- Create: `pettec-theme/front-page.php`
- Modify: `tests/templates-and-contact.test.mjs`

**Interfaces:**
- Consumes: `pettec_get_option`, CPTs from Task 2, CSS classes & parallax attributes from Task 3
- Produces:
  - Template parts accepting `$args` via `get_template_part('template-parts/card-*', null, $args)`
  - Complete `front-page.php` alternating 5 bands: Hero (dark + hex-grid + parallax + rotating SVG badge + 4 metrics) → Quem Somos / 3 Pilares (light + tactile cards) → Atividades & Mini Cursos em Destaque (dark) → Últimos do Blog + Editais Recentes (light) → Prévia da Equipe (dark, circular portraits with subtle parallax drift) + CTA Contato.

- [ ] **Step 1: Add failing assertions to `tests/templates-and-contact.test.mjs`**

Assert `front-page.php` includes all 5 alternating bands (`band--dark` / `band--light`), `data-parallax-speed`, `data-scroll-rotate`, and queries `pettec_atividade`, `pettec_minicurso`, `pettec_documento`, `pettec_membro`, and `post`. Assert `card-membro.php` uses `pettec_clean_external_url` so `#` links are never rendered.

- [ ] **Step 2: Run test to verify it fails**

Run: `npm test`
Expected: FAIL.

- [ ] **Step 3: Implement the 4 card template parts and `front-page.php`**

- [ ] **Step 4: Run test to verify it passes**

Run: `npm test`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add pettec-theme/template-parts/ pettec-theme/front-page.php tests/templates-and-contact.test.mjs
git commit -m "feat: add reusable card template parts and parallax homepage"
```

---

### Task 5: Institutional Archive & Single Templates (Atividades, Mini Cursos + Listas, Documentos, Membros)

**Files:**
- Create: `pettec-theme/archive-pettec_atividade.php`
- Create: `pettec-theme/single-pettec_atividade.php`
- Create: `pettec-theme/archive-pettec_minicurso.php`
- Create: `pettec-theme/single-pettec_minicurso.php`
- Create: `pettec-theme/page-listas-de-exercicio.php`
- Create: `pettec-theme/archive-pettec_documento.php`
- Create: `pettec-theme/archive-pettec_membro.php`
- Modify: `tests/templates-and-contact.test.mjs`

**Interfaces:**
- Consumes: CPTs & meta keys from Task 2, `pettec_parse_youtube_url`, `pettec_clean_external_url`, `pettec_get_all_exercise_lists`, card template parts from Task 4, `[data-filter-group]` from Task 3
- Produces: All 5 institutional section routes (`/atividades/`, `/mini-cursos/`, `/listas-de-exercicio/`, `/documentos/`, `/membros/`) with zero PHP warnings on empty meta and clean empty states.

- [ ] **Step 1: Add failing assertions to `tests/templates-and-contact.test.mjs`**

Assert all 7 template files exist, `single-pettec_minicurso.php` renders multiple YouTube embeds via `pettec_parse_youtube_url` and loops `_pettec_materiais`, `page-listas-de-exercicio.php` calls `pettec_get_all_exercise_lists()`, `archive-pettec_documento.php` provides Year + Type filters, and `archive-pettec_membro.php` separates `tutor`, `membro`, and `ex-membro`.

- [ ] **Step 2: Run test to verify it fails**

Run: `npm test`
Expected: FAIL.

- [ ] **Step 3: Implement all 7 institutional templates**

- `archive-pettec_atividade.php` / `single-pettec_atividade.php`: filter pills by Pilar (`Todos`, `Ensino`, `Pesquisa`, `Extensão`), rich single view.
- `archive-pettec_minicurso.php` / `single-pettec_minicurso.php`: course cards with lesson/material counts; single view with responsive 16:9 YouTube embeds for each lesson, rich WP content/gallery, and categorized download cards for Lists/Slides/Apostilas/Código.
- `page-listas-de-exercicio.php`: Page Template (`Template Name: Listas de Exercícios`) + slug match; displays lists grouped by Mini Curso + standalone lists section, with friendly empty state if none exist.
- `archive-pettec_documento.php`: editorial table/list with instant Year and Type filter pills + direct `Baixar PDF ↗` button (opens in new tab with `rel="noopener noreferrer"`).
- `archive-pettec_membro.php`: tabs/filters for `Todos`, `Tutoria`, `Membros Atuais`, `Ex-membros`, using circular portraits and conditional social links.

- [ ] **Step 4: Run test to verify it passes**

Run: `npm test`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add pettec-theme/archive-*.php pettec-theme/single-pettec_*.php pettec-theme/page-listas-de-exercicio.php tests/templates-and-contact.test.mjs
git commit -m "feat: add Atividades, Mini Cursos, Listas, Documentos, and Membros templates"
```

---

### Task 6: Blog Templates & Native Contact Form (`inc/contact-form.php`, `page-contato.php`)

**Files:**
- Create: `pettec-theme/home.php`
- Create: `pettec-theme/archive.php`
- Create: `pettec-theme/single.php`
- Create: `pettec-theme/index.php`
- Create: `pettec-theme/inc/contact-form.php`
- Create: `pettec-theme/page-contato.php`
- Modify: `tests/templates-and-contact.test.mjs`

**Interfaces:**
- Consumes: `pettec_get_option`, `pettec_estimate_reading_time`, `template-parts/card-post.php`
- Produces:
  - `pettec_handle_contact_submission(): array` returning `['status' => 'idle'|'success'|'error', 'message' => string, 'values' => array]`
  - Complete Blog (`/blog/` + single + category archives) and Contact (`/contato/`) experience.

- [ ] **Step 1: Add failing assertions to `tests/templates-and-contact.test.mjs`**

Assert:
- `inc/contact-form.php` verifies `wp_verify_nonce`, checks a hidden honeypot field (`pettec_website`), strips CR/LF (`[\r\n]`) from email/subject headers, validates email with `is_email`, calls `wp_mail`, and provides a friendly fallback message referencing `pettec@unifei.edu.br` when `wp_mail` fails.
- `home.php`, `archive.php`, `single.php`, `index.php`, and `page-contato.php` exist and use proper escaping (`esc_html`, `esc_attr`, `esc_url`).

- [ ] **Step 2: Run test to verify it fails**

Run: `npm test`
Expected: FAIL.

- [ ] **Step 3: Implement Blog templates, `inc/contact-form.php`, and `page-contato.php`**

- [ ] **Step 4: Run test to verify it passes**

Run: `npm test`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add pettec-theme/home.php pettec-theme/archive.php pettec-theme/single.php pettec-theme/index.php pettec-theme/inc/contact-form.php pettec-theme/page-contato.php tests/templates-and-contact.test.mjs
git commit -m "feat: add Blog templates and CSRF/honeypot-protected Contact page"
```

---

### Task 7: Real Content WXR Seed, WordPress Playground Blueprint, Zip Packager, Migration Guide & Live Verification

**Files:**
- Create: `data/pettec-content-seed.xml`
- Create: `playground/blueprint.json`
- Create: `scripts/build-zip.mjs`
- Create: `docs/guia-migracao-wp-admin.md`
- Modify: `tests/theme-core.test.mjs` (add zip integrity assertions)

**Interfaces:**
- Consumes: Entire `pettec-theme/` from Tasks 1–6
- Produces:
  - `data/pettec-content-seed.xml`: valid WordPress WXR 1.2 file containing real public data from `https://pettec.unifei.edu.br/` (2025/2026 editais & planejamentos, 5 YouTube mini cursos, 17 members, UNIFEI address/contact, sample Atividades & inaugural Blog post).
  - `playground/blueprint.json`: boots WordPress with `pettec-theme` active, sets `pt_BR`, sets permalinks to `/%postname%/`, creates required pages (`Início`, `Blog`, `Listas de Exercício`, `Contato`), imports `data/pettec-content-seed.xml`, and configures `show_on_front = page`.
  - `dist/pettec-theme.zip`: clean, forward-slash zip package ready for `/wp-admin` upload.
  - `docs/guia-migracao-wp-admin.md`: step-by-step Portuguese guide covering WPvivid backup, HTTPS fix, `.zip` upload, moving old Elementor pages to Draft/Trash, saving Permalinks, disabling conflicting plugins, and importing `pettec-content-seed.xml`.

- [ ] **Step 1: Fetch real public content from `https://pettec.unifei.edu.br/` and generate `data/pettec-content-seed.xml`**

Extract the real public lists from `/documentos/`, `/mini-cursos/`, `/membros/`, and `/contato/` on `https://pettec.unifei.edu.br/` and encode them into valid WordPress WXR 1.2 XML with exact CPT slugs and meta keys from Task 2.

- [ ] **Step 2: Create `playground/blueprint.json`, `scripts/build-zip.mjs`, and `docs/guia-migracao-wp-admin.md`**

- `scripts/build-zip.mjs`: creates `dist/pettec-theme.zip` with `pettec-theme/` as the single top-level directory and forward-slash entry paths (cross-platform safe for Linux servers at UNIFEI).

- [ ] **Step 3: Add failing zip & blueprint assertions to `tests/theme-core.test.mjs` and run `npm run zip` + `npm test`**

Assert:
- `playground/blueprint.json` is valid JSON referencing `pettec-theme`.
- `data/pettec-content-seed.xml` is well-formed XML containing `<wp:post_type>pettec_documento</wp:post_type>`, `pettec_minicurso`, `pettec_membro`, and `pettec_atividade`.
- `dist/pettec-theme.zip` exists, contains `pettec-theme/style.css`, uses no backslashes, and contains no `node_modules` or `.git` entries.

Run: `npm run zip && npm test`
Expected: PASS.

- [ ] **Step 4: Live end-to-end verification in WordPress Playground**

Start `npm run dev` (WordPress Playground on `http://127.0.0.1:9400`) and verify in the browser:
- Homepage (`/`) renders with PETTEC logo pill, dark/light bands, parallax, metrics, and 0 console/PHP/mixed-content errors on desktop (`1280×800`) and mobile (`375×812`, no horizontal overflow).
- `/atividades/`, `/mini-cursos/` (+ single course with YouTube embeds & downloads), `/listas-de-exercicio/`, `/documentos/` (Year/Type filters), `/membros/` (Tutor/Membros/Ex-membros tabs), `/blog/` (+ single post), `/contato/` (form renders & validates), `/contact/` (301 → `/contato/`), and `/rota-inexistente/` (branded 404).

- [ ] **Step 5: Commit**

```bash
git add data/ playground/ scripts/ docs/guia-migracao-wp-admin.md tests/theme-core.test.mjs
git commit -m "feat: add real content WXR seed, Playground blueprint, zip packager, and migration guide"
```
