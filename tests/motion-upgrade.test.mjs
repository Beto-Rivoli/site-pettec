import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const ROOT = process.cwd();
const THEME = path.join(ROOT, 'pettec-theme');

test('Extrafazant-style page transition curtain exists in header.php, motion.css, and main.js with bfcache guard', () => {
  const header = fs.readFileSync(path.join(THEME, 'header.php'), 'utf8');
  const motion = fs.readFileSync(path.join(THEME, 'assets', 'css', 'motion.css'), 'utf8');
  const js = fs.readFileSync(path.join(THEME, 'assets', 'js', 'main.js'), 'utf8');

  assert.match(header, /pettec-page-transition/);
  assert.match(motion, /\.page-transition/);
  assert.match(motion, /\.page-transition\.is-leaving/);
  assert.match(js, /pettec-page-transition/);
  assert.match(js, /pageshow/);
});

test('pettec_depoimento CPT, metabox, archive template, and 4 real alumni testimonials exist', () => {
  const cpts = fs.readFileSync(path.join(THEME, 'inc', 'cpts.php'), 'utf8');
  const metaboxes = fs.readFileSync(path.join(THEME, 'inc', 'metaboxes.php'), 'utf8');
  const wxr = fs.readFileSync(path.join(ROOT, 'data', 'pettec-content-seed.xml'), 'utf8');
  const archivePath = path.join(THEME, 'archive-pettec_depoimento.php');

  assert.match(cpts, /'pettec_depoimento'/);
  assert.match(cpts, /'slug'\s*=>\s*'depoimentos'/);
  assert.match(metaboxes, /_pettec_dep_destaque/);
  assert.match(metaboxes, /_pettec_dep_subtitulo/);
  assert.equal(fs.existsSync(archivePath), true, 'archive-pettec_depoimento.php must exist');

  for (const name of ['Vinícius Monteglione', 'Leandro Moraes Barbosa', 'Leonardo Vieira Franzini', 'Enzo Carvalho Silva']) {
    assert.match(wxr, new RegExp(name), `WXR seed must include real alumni testimonial by ${name}`);
  }
});

test('Homepage implements Two26-style Selected Works 2-col showcase and stacked Testimonials deck', () => {
  const front = fs.readFileSync(path.join(THEME, 'front-page.php'), 'utf8');
  const deck = fs.readFileSync(path.join(THEME, 'template-parts', 'testimonials-deck.php'), 'utf8');
  const js = fs.readFileSync(path.join(THEME, 'assets', 'js', 'main.js'), 'utf8');
  const css = fs.readFileSync(path.join(THEME, 'assets', 'css', 'components.css'), 'utf8');

  assert.match(front, /work-grid/);
  assert.match(front, /work-card/);
  assert.match(front, /template-parts\/testimonials-deck/);
  assert.match(deck, /testimonials-stage/);
  assert.match(deck, /data-testimonial-deck/);
  assert.match(deck, /deck-ticks/);
  assert.match(js, /data-testimonial-deck/);
  assert.match(css, /\.testimonials-stage/);
  assert.match(css, /\.work-card/);
});

test('Members section uses Onlines-inspired interactive cell grid, selection box, and kinetic tilt cards', () => {
  const archive = fs.readFileSync(path.join(THEME, 'archive-pettec_membro.php'), 'utf8');
  const card = fs.readFileSync(path.join(THEME, 'template-parts', 'card-membro.php'), 'utf8');
  const js = fs.readFileSync(path.join(THEME, 'assets', 'js', 'main.js'), 'utf8');
  const css = fs.readFileSync(path.join(THEME, 'assets', 'css', 'components.css'), 'utf8');

  assert.match(archive, /cell-matrix/);
  assert.match(archive, /selection-box/);
  assert.match(card, /data-tilt-card/);
  assert.match(card, /member-card--kinetic/);
  assert.match(js, /data-tilt-card/);
  assert.match(js, /cell-matrix/);
  assert.match(css, /\.selection-box/);
  assert.match(css, /\.member-card--kinetic/);
});
