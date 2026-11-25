import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const ROOT = process.cwd();
const THEME = path.join(ROOT, 'pettec-theme');

test('style.css declares PETTEC UNIFEI theme metadata', () => {
  const stylePath = path.join(THEME, 'style.css');
  assert.equal(fs.existsSync(stylePath), true, 'style.css must exist');
  const content = fs.readFileSync(stylePath, 'utf8');
  assert.match(content, /Theme Name:\s*PETTEC UNIFEI/);
  assert.match(content, /Text Domain:\s*pettec/);
  assert.match(content, /Requires PHP:\s*7\.4/);
});

test('official PETTEC logo asset is bundled and non-empty', () => {
  const logoPath = path.join(THEME, 'assets', 'images', 'logo-pettec.png');
  assert.equal(fs.existsSync(logoPath), true, 'logo-pettec.png must exist');
  assert.ok(fs.statSync(logoPath).size > 1000, 'logo-pettec.png must be non-empty');
});

test('https-defense.php implements proxy-aware HTTPS normalization without breaking localhost', () => {
  const filePath = path.join(THEME, 'inc', 'https-defense.php');
  assert.equal(fs.existsSync(filePath), true, 'inc/https-defense.php must exist');
  const content = fs.readFileSync(filePath, 'utf8');
  assert.match(content, /function\s+pettec_is_https_request\s*\(/);
  assert.match(content, /function\s+pettec_normalize_asset_url\s*\(/);
  assert.match(content, /HTTP_X_FORWARDED_PROTO/);
  assert.match(content, /127\.0\.0\.1|localhost/);
  assert.match(content, /style_loader_src/);
  assert.match(content, /script_loader_src/);
  assert.match(content, /wp_get_attachment_url/);
});

test('customizer.php exposes pettec_get_option and registers hero, metrics, contact, and social settings', () => {
  const filePath = path.join(THEME, 'inc', 'customizer.php');
  assert.equal(fs.existsSync(filePath), true, 'inc/customizer.php must exist');
  const content = fs.readFileSync(filePath, 'utf8');
  assert.match(content, /function\s+pettec_get_option\s*\(/);
  assert.match(content, /hero_title_sans/);
  assert.match(content, /metric_1_num/);
  assert.match(content, /contact_email/);
  assert.match(content, /pettec@unifei\.edu\.br/);
  assert.match(content, /social_instagram/);
});

test('functions.php sets up theme supports, menus, fonts, and /contact -> /contato/ 301 redirect', () => {
  const filePath = path.join(THEME, 'functions.php');
  assert.equal(fs.existsSync(filePath), true, 'functions.php must exist');
  const content = fs.readFileSync(filePath, 'utf8');
  assert.match(content, /PETTEC_THEME_VERSION/);
  assert.match(content, /add_theme_support\s*\(\s*'custom-logo'/);
  assert.match(content, /add_theme_support\s*\(\s*'post-thumbnails'/);
  assert.match(content, /add_theme_support\s*\(\s*'title-tag'/);
  assert.match(content, /'primary'/);
  assert.match(content, /'footer'/);
  assert.match(content, /Barlow\+Condensed/);
  assert.match(content, /Instrument\+Serif/);
  assert.match(content, /Plus\+Jakarta\+Sans/);
  assert.match(content, /\/contact/);
  assert.match(content, /301/);
});

test('WXR seed, Playground blueprint, migration guide, and forward-slash theme zip are valid', () => {
  const wxrPath = path.join(ROOT, 'data', 'pettec-content-seed.xml');
  const bpPath = path.join(ROOT, 'playground', 'blueprint.json');
  const guidePath = path.join(ROOT, 'docs', 'guia-migracao-wp-admin.md');
  const zipPath = path.join(ROOT, 'dist', 'pettec-theme.zip');

  assert.equal(fs.existsSync(wxrPath), true, 'data/pettec-content-seed.xml must exist');
  assert.equal(fs.existsSync(bpPath), true, 'playground/blueprint.json must exist');
  assert.equal(fs.existsSync(guidePath), true, 'docs/guia-migracao-wp-admin.md must exist');
  assert.equal(fs.existsSync(zipPath), true, 'dist/pettec-theme.zip must exist');

  const wxr = fs.readFileSync(wxrPath, 'utf8');
  assert.match(wxr, /<wp:post_type><!\[CDATA\[pettec_documento\]\]><\/wp:post_type>/);
  assert.match(wxr, /<wp:post_type><!\[CDATA\[pettec_minicurso\]\]><\/wp:post_type>/);
  assert.match(wxr, /<wp:post_type><!\[CDATA\[pettec_membro\]\]><\/wp:post_type>/);
  assert.match(wxr, /<wp:post_type><!\[CDATA\[pettec_atividade\]\]><\/wp:post_type>/);
  assert.doesNotMatch(wxr, /http:\/\/pettec\.unifei\.edu\.br/, 'WXR seed must not contain http:// mixed-content URLs');

  const bp = JSON.parse(fs.readFileSync(bpPath, 'utf8'));
  assert.ok(Array.isArray(bp.steps), 'blueprint.json must have steps array');
  assert.match(JSON.stringify(bp), /pettec-theme/);

  const zipBuf = fs.readFileSync(zipPath);
  const zipLatin1 = zipBuf.toString('latin1');
  assert.ok(zipBuf.length > 5000, 'zip archive must be non-empty');
  assert.ok(zipLatin1.includes('pettec-theme/style.css'), 'zip must contain pettec-theme/style.css at top level');
  assert.ok(!zipLatin1.includes('pettec-theme\\style.css'), 'zip must use forward slashes only');
  assert.ok(!zipLatin1.includes('node_modules/'), 'zip must exclude node_modules');
  assert.ok(!zipLatin1.includes('.git/'), 'zip must exclude .git');
});
