import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const THEME = path.join(process.cwd(), 'pettec-theme');

test('Critical #1: metaboxes.php slashes JSON with JSON_UNESCAPED_UNICODE before update_post_meta', () => {
  const content = fs.readFileSync(path.join(THEME, 'inc', 'metaboxes.php'), 'utf8');
  assert.match(content, /wp_slash\s*\(\s*wp_json_encode\s*\(\s*\$aulas\s*,\s*JSON_UNESCAPED_UNICODE\s*\)\s*\)/);
  assert.match(content, /wp_slash\s*\(\s*wp_json_encode\s*\(\s*\$materiais\s*,\s*JSON_UNESCAPED_UNICODE\s*\)\s*\)/);
});

test('Important #1: https-defense.php also normalizes template_directory_uri and wp_calculate_image_srcset', () => {
  const content = fs.readFileSync(path.join(THEME, 'inc', 'https-defense.php'), 'utf8');
  assert.match(content, /template_directory_uri/);
  assert.match(content, /wp_calculate_image_srcset/);
});

test('Important #2: archives include pagination or full query so items 11+ are never unreachable', () => {
  const atividades = fs.readFileSync(path.join(THEME, 'archive-pettec_atividade.php'), 'utf8');
  const minicursos = fs.readFileSync(path.join(THEME, 'archive-pettec_minicurso.php'), 'utf8');
  const archive = fs.readFileSync(path.join(THEME, 'archive.php'), 'utf8');

  assert.match(atividades, /posts_per_page.*-1|the_posts_pagination/s);
  assert.match(minicursos, /the_posts_pagination/);
  assert.match(archive, /the_posts_pagination/);
});

test('Important #3: single-pettec_documento.php and single-pettec_membro.php exist so CPT singles never fall back to Blog single.php', () => {
  assert.equal(fs.existsSync(path.join(THEME, 'single-pettec_documento.php')), true);
  assert.equal(fs.existsSync(path.join(THEME, 'single-pettec_membro.php')), true);
});

test('Important #4: base.css sets height: auto on img to prevent mobile stretching', () => {
  const css = fs.readFileSync(path.join(THEME, 'assets', 'css', 'base.css'), 'utf8');
  assert.match(css, /img\s*\{[^}]*height:\s*auto/s);
});
