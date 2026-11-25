import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const THEME = path.join(process.cwd(), 'pettec-theme');

test('cpts.php registers all 4 CPTs with exact archive slugs and 3 taxonomies', () => {
  const filePath = path.join(THEME, 'inc', 'cpts.php');
  assert.equal(fs.existsSync(filePath), true, 'inc/cpts.php must exist');
  const content = fs.readFileSync(filePath, 'utf8');

  for (const cpt of ['pettec_atividade', 'pettec_minicurso', 'pettec_documento', 'pettec_membro']) {
    assert.match(content, new RegExp(`'${cpt}'`), `must register CPT ${cpt}`);
  }
  for (const slug of ['atividades', 'mini-cursos', 'documentos', 'membros']) {
    assert.match(content, new RegExp(`'slug'\\s*=>\\s*'${slug}'`), `must configure slug ${slug}`);
  }
  for (const tax of ['pettec_pilar', 'pettec_curso_area', 'pettec_doc_tipo']) {
    assert.match(content, new RegExp(`'${tax}'`), `must register taxonomy ${tax}`);
  }
  assert.match(content, /after_switch_theme/);
  assert.match(content, /Lista de Exercícios/);
});

test('helpers.php implements YouTube parser, safe external URL cleaner, reading time, and exercise list aggregator', () => {
  const filePath = path.join(THEME, 'inc', 'helpers.php');
  assert.equal(fs.existsSync(filePath), true, 'inc/helpers.php must exist');
  const content = fs.readFileSync(filePath, 'utf8');

  assert.match(content, /function\s+pettec_parse_youtube_url\s*\(/);
  assert.match(content, /youtube-nocookie\.com\/embed/);
  assert.match(content, /videoseries\?list=/);
  assert.match(content, /function\s+pettec_clean_external_url\s*\(/);
  assert.match(content, /function\s+pettec_estimate_reading_time\s*\(/);
  assert.match(content, /function\s+pettec_get_all_exercise_lists\s*\(/);
  assert.match(content, /lista-de-exercicios/);
});

test('metaboxes.php enforces nonce, autosave, capability checks, and supports repeatable lessons/materials', () => {
  const filePath = path.join(THEME, 'inc', 'metaboxes.php');
  assert.equal(fs.existsSync(filePath), true, 'inc/metaboxes.php must exist');
  const content = fs.readFileSync(filePath, 'utf8');

  assert.match(content, /wp_nonce_field/);
  assert.match(content, /wp_verify_nonce/);
  assert.match(content, /DOING_AUTOSAVE/);
  assert.match(content, /current_user_can/);
  assert.match(content, /_pettec_aulas/);
  assert.match(content, /_pettec_materiais/);
  assert.match(content, /_pettec_doc_url/);
  assert.match(content, /_pettec_membro_papel/);
  assert.match(content, /wp_enqueue_media/);
});

test('admin-repeaters.js handles dynamic rows and WP Media Library file selection', () => {
  const filePath = path.join(THEME, 'assets', 'js', 'admin-repeaters.js');
  assert.equal(fs.existsSync(filePath), true, 'assets/js/admin-repeaters.js must exist');
  const content = fs.readFileSync(filePath, 'utf8');

  assert.match(content, /wp\.media/);
  assert.match(content, /pettec-add-row/);
  assert.match(content, /pettec-remove-row/);
});
