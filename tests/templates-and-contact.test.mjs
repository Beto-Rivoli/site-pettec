import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const THEME = path.join(process.cwd(), 'pettec-theme');

test('tokens.css defines official PETTEC brand palette', () => {
  const filePath = path.join(THEME, 'assets', 'css', 'tokens.css');
  assert.equal(fs.existsSync(filePath), true, 'tokens.css must exist');
  const content = fs.readFileSync(filePath, 'utf8');
  assert.match(content, /#141619/i);
  assert.match(content, /#1A1D21/i);
  assert.match(content, /#D61A1F/i);
  assert.match(content, /#F4F4F2/i);
});

test('motion.css and main.js respect prefers-reduced-motion and implement rAF parallax + filters', () => {
  const motionPath = path.join(THEME, 'assets', 'css', 'motion.css');
  const jsPath = path.join(THEME, 'assets', 'js', 'main.js');
  assert.equal(fs.existsSync(motionPath), true, 'motion.css must exist');
  assert.equal(fs.existsSync(jsPath), true, 'main.js must exist');

  const css = fs.readFileSync(motionPath, 'utf8');
  const js = fs.readFileSync(jsPath, 'utf8');
  assert.match(css, /prefers-reduced-motion:\s*reduce/);
  assert.match(js, /prefers-reduced-motion:\s*reduce/);
  assert.match(js, /requestAnimationFrame/);
  assert.match(js, /IntersectionObserver/);
  assert.match(js, /data-parallax-speed/);
  assert.match(js, /data-filter-group/);
});

test('header.php, footer.php, and 404.php implement PETTEC floating nav, logo pill, and branded 404', () => {
  const headerPath = path.join(THEME, 'header.php');
  const footerPath = path.join(THEME, 'footer.php');
  const notFoundPath = path.join(THEME, '404.php');
  assert.equal(fs.existsSync(headerPath), true, 'header.php must exist');
  assert.equal(fs.existsSync(footerPath), true, 'footer.php must exist');
  assert.equal(fs.existsSync(notFoundPath), true, '404.php must exist');

  const header = fs.readFileSync(headerPath, 'utf8');
  const footer = fs.readFileSync(footerPath, 'utf8');
  const notFound = fs.readFileSync(notFoundPath, 'utf8');

  assert.match(header, /pettec_get_logo_url/);
  assert.match(header, /logo-pill/);
  assert.match(header, /\/contato\//);
  assert.match(header, /aria-expanded/);
  assert.match(footer, /pettec_get_logo_url/);
  assert.match(footer, /UNIFEI/);
  assert.match(notFound, /404/);
  assert.match(notFound, /\/documentos\//);
});

test('front-page.php renders alternating dark/light bands with parallax, rotating badge, and all CPT queries', () => {
  const filePath = path.join(THEME, 'front-page.php');
  assert.equal(fs.existsSync(filePath), true, 'front-page.php must exist');
  const content = fs.readFileSync(filePath, 'utf8');
  assert.match(content, /band--dark/);
  assert.match(content, /band--light/);
  assert.match(content, /data-parallax-speed/);
  assert.match(content, /data-scroll-rotate/);
  for (const cpt of ['pettec_atividade', 'pettec_minicurso', 'pettec_documento', 'pettec_membro']) {
    assert.match(content, new RegExp(cpt), `front-page.php must query ${cpt}`);
  }
});

test('reusable card template parts exist and card-membro strips dead # links', () => {
  for (const name of ['card-atividade.php', 'card-minicurso.php', 'card-membro.php', 'card-post.php']) {
    assert.equal(fs.existsSync(path.join(THEME, 'template-parts', name)), true, `${name} must exist`);
  }
  const memberCard = fs.readFileSync(path.join(THEME, 'template-parts', 'card-membro.php'), 'utf8');
  assert.match(memberCard, /pettec_clean_external_url/);
  assert.match(memberCard, /portrait-circle/);
});
