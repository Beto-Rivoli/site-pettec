<?php
/**
 * Custom branded 404 page for PETTEC UNIFEI.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="band band--dark band--hex-grid page-hero" style="min-height: 72vh; display: flex; align-items: center;">
	<div class="container">
		<span class="eyebrow">Erro 404</span>
		<h1 class="display-mixed display-mixed--xl">
			PÁGINA NÃO <em>encontrada</em>
		</h1>
		<p class="lead" style="margin-top: 1.25rem;">
			O endereço que você tentou acessar pode ter sido movido ou atualizado na nova versão do site do PETTEC — UNIFEI. Use os atalhos abaixo para continuar navegando.
		</p>
		<div class="hero-actions">
			<a class="btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Voltar para o Início</a>
			<a class="btn-outline" href="<?php echo esc_url( home_url( '/documentos/' ) ); ?>">Documentos e Editais</a>
			<a class="btn-outline" href="<?php echo esc_url( home_url( '/mini-cursos/' ) ); ?>">Mini Cursos</a>
			<a class="btn-outline" href="<?php echo esc_url( home_url( '/contato/' ) ); ?>">Contato</a>
		</div>
	</div>
</section>
<?php
get_footer();
