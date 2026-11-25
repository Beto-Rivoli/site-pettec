<?php
/**
 * Archive template for Membros e Ex-membros (pettec_membro) with role tabs & circular portraits.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$all_members = new WP_Query(
	array(
		'post_type'      => 'pettec_membro',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);
?>
<section class="band band--dark band--hex-grid page-hero">
	<div class="container">
		<span class="eyebrow">Pessoas do PETTEC</span>
		<h1 class="display-mixed display-mixed--xl">MEMBROS <em>&amp; Ex-membros</em></h1>
		<p class="lead" style="margin-top: 1rem;">
			Conheça a tutoria, os petianos atuais e os egressos que ajudaram a construir a história do PETTEC na UNIFEI.
		</p>
	</div>
</section>

<section class="band band--dark" style="padding-top: 2rem;">
	<div class="container" data-filter-group="membros">
		<div class="filter-bar" role="group" aria-label="Filtrar por categoria">
			<button type="button" class="filter-pill is-active" data-filter-key="papel" data-filter-value="all">Todos</button>
			<button type="button" class="filter-pill" data-filter-key="papel" data-filter-value="tutor">Tutoria</button>
			<button type="button" class="filter-pill" data-filter-key="papel" data-filter-value="membro">Membros Atuais</button>
			<button type="button" class="filter-pill" data-filter-key="papel" data-filter-value="ex-membro">Ex-membros</button>
		</div>

		<?php if ( $all_members->have_posts() ) : ?>
			<div class="grid grid--4">
				<?php
				while ( $all_members->have_posts() ) :
					$all_members->the_post();
					get_template_part( 'template-parts/card-membro' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<div class="empty-state is-hidden" data-filter-empty>Nenhum integrante encontrado nesta categoria.</div>
		<?php else : ?>
			<div class="empty-state">Nenhum membro cadastrado ainda.</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
