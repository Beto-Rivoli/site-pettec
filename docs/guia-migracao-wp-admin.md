# Guia de Backup e Migração Segura — PETTEC (UNIFEI) via `/wp-admin`

Este guia detalha o passo a passo para substituir o tema atual (Twenty Twenty-Four + Elementor + addons conflitantes) pelo novo tema **`pettec-theme`** usando **apenas o `/wp-admin`** de `https://pettec.unifei.edu.br/`.

---

## Etapa 1 — Backup Completo Obrigatório (Antes de Qualquer Alteração)

1. Acesse `https://pettec.unifei.edu.br/wp-admin`.
2. Vá em **Plugins → Adicionar novo**, pesquise por **WPvivid Backup & Migration**, instale e ative.
3. No menu lateral, clique em **WPvivid Backup → Backup & Restore**.
4. Selecione **Database + Files (WordPress Files)** e clique em **Backup Now**.
5. Quando terminar, clique em **Download** em todos os arquivos gerados e salve em uma pasta segura no seu computador (ou Google Drive do PETTEC).

> **Regra de ouro:** não avance para a Etapa 2 sem ter o backup completo salvo fora do servidor.

---

## Etapa 2 — Corrigir o Mixed Content (`http://` → `https://`)

O diagnóstico mostrou que o site atual bloqueava 44 de 50 arquivos por carregar recursos em `http://` dentro de `https://`.

1. Vá em **Configurações → Geral**.
2. Verifique se **Endereço do WordPress (URL)** e **Endereço do site (URL)** estão ambos como:
   `https://pettec.unifei.edu.br` (com `https://`). Salve se alterou.
3. *(Opcional, recomendado se ainda houver links antigos no banco)* Instale o plugin **Better Search Replace**, vá em **Ferramentas → Better Search Replace**, selecione todas as tabelas e substitua:
   - Procurar por: `http://pettec.unifei.edu.br`
   - Substituir por: `https://pettec.unifei.edu.br`
   - Desmarque "Executar como simulação?" após conferir a prévia e rode.
4. O tema `pettec-theme` também inclui defesa automática de HTTPS (`inc/https-defense.php`) para servidores atrás de proxy reverso.

---

## Etapa 3 — Instalar e Ativar o Novo Tema (`pettec-theme.zip`)

1. No seu PC (dentro da pasta `SitePet`), gere o pacote atualizado com:
   ```bash
   npm run zip
   ```
   O arquivo será salvo em `dist/pettec-theme.zip`.
2. No `/wp-admin`, vá em **Aparência → Temas → Adicionar novo → Enviar tema**.
3. Escolha `dist/pettec-theme.zip`, clique em **Instalar agora** e depois em **Ativar**.

---

## Etapa 4 — Evitar Colisão de Rotas (Páginas Antigas do Elementor)

O novo tema usa menus dedicados (Custom Post Types) com rotas limpas `/atividades/`, `/mini-cursos/`, `/documentos/` e `/membros/`.

1. Vá em **Páginas → Todas as páginas**.
2. Mova para **Rascunho** (ou Lixeira) as páginas antigas do Elementor que tenham os slugs:
   - `atividades`
   - `mini-cursos`
   - `documentos`
   - `membros`
   *(Elas continuam no seu backup da Etapa 1, e deixá-las em Rascunho evita que sobrescrevam os novos arquivos do tema.)*
3. Garanta que existam 4 páginas publicadas com os templates corretos:
   - **Início** (slug `inicio`)
   - **Blog** (slug `blog`)
   - **Listas de Exercício** (slug `listas-de-exercicio` — Modelo: *Listas de Exercícios*)
   - **Contato** (slug `contato` — Modelo: *Contato PETTEC*)
4. Vá em **Configurações → Leitura** → marque **Uma página estática**:
   - Página inicial: **Início**
   - Página de posts: **Blog**
5. Vá em **Configurações → Links Permanentes**, selecione **Nome do post** (`/%postname%/`) e clique em **Salvar alterações** (obrigatório para ativar as novas rotas!).

---

## Etapa 5 — Importar Conteúdo Estruturado (`data/pettec-content-seed.xml`)

1. Vá em **Ferramentas → Importar** → clique em **Instalar agora** no **WordPress** e depois em **Rodar importador**.
2. Selecione `data/pettec-content-seed.xml` (que já contém os 7 editais/planejamentos 2025/2026 em HTTPS, os 5 mini cursos do YouTube e os 17 membros atuais).
3. Atribua os itens ao seu usuário administrador e conclua a importação.

---

## Etapa 6 — Limpeza dos Plugins Conflitantes

Depois de conferir que todas as páginas (`/`, `/atividades/`, `/mini-cursos/`, `/listas-de-exercicio/`, `/documentos/`, `/membros/`, `/blog/`, `/contato/`) abriram perfeitamente:

1. Vá em **Plugins → Plugins instalados** e desative os plugins antigos que causavam lentidão e erros:
   - `Elementor` e todos os addons (`Essential Addons`, `Royal Elementor Addons`, `Jeg Elementor Kit`, `Header Footer Elementor`, `Testimonials Carousel`)
   - `Max Mega Menu`
   - `Feeds for YouTube` (que gerava erros 404 de CSS)
   - `Instagram Feed`
   - `WP-Optimize` (limpe o cache antes de desativar)
2. **Dica para o formulário de contato:** se o servidor da UNIFEI bloquear a função `mail()` padrão do PHP, basta instalar o plugin gratuito **WP Mail SMTP** e configurar a conta SMTP institucional — o formulário nativo do tema funciona automaticamente com ele.

---

## Como Atualizar o Site no Dia a Dia (Para Futuros Petianos)

- **Novo Post no Blog:** `Posts → Adicionar novo`
- **Novo Edital / Planejamento / Lista Avulsa:** `Documentos e Editais → Adicionar novo` → suba o PDF e informe o ano e o tipo.
- **Novo Mini Curso:** `Mini Cursos → Adicionar novo` → adicione quantas aulas do YouTube e quantos PDFs/Listas quiser (listas marcadas como *Lista de Exercícios* aparecem automaticamente em `/listas-de-exercicio/`).
- **Novo Membro ou Mover para Ex-membro:** `Membros e Ex-membros` → basta trocar a categoria de *Membro Atual* para *Ex-membro (Egresso)*.
- **Textos da Home, Métricas, E-mail e Redes Sociais:** `Aparência → Personalizar → PETTEC — Configurações do Site`.
