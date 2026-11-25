<?php
/**
 * Archive template for Depoimentos (Falas dos antigos petianos — /depoimentos/).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="band band--dark band--hex-grid page-hero">
	<div class="container">
		<span class="eyebrow">Histórias Reais</span>
		<h1 class="display-mixed display-mixed--xl">FALAS DOS <em>Antigos Petianos</em></h1>
		<p class="lead" style="margin-top: 1rem;">
			O que ex-membros do PETTEC — UNIFEI levam na bagagem após anos de projetos, mini cursos, pesquisa e trabalho em equipe.
		</p>
	</div>
</section>

<section class="band band--light">
	<div class="container">
		<?php get_template_part( 'template-parts/testimonials-deck' ); ?>
	</div>
</section>
<?php
get_footer();
