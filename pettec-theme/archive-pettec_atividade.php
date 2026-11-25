<?php
/**
 * Archive template for Atividades (pettec_atividade).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
$pilares = get_terms( array( 'taxonomy' => 'pettec_pilar', 'hide_empty' => false ) );
?>
<section class="band band--dark band--hex-grid page-hero">
	<div class="container">
		<span class="eyebrow">Projetos do PETTEC</span>
		<h1 class="display-mixed display-mixed--xl">NOSSAS <em>Atividades</em></h1>
		<p class="lead" style="margin-top: 1rem;">
			Projetos de ensino, pesquisa e extensão conduzidos pelos membros e tutoria do PETTEC na UNIFEI.
		</p>
	</div>
</section>

<section class="band band--light">
	<div class="container" data-filter-group="atividades">
		<div class="filter-bar" role="group" aria-label="Filtrar por pilar">
			<button type="button" class="filter-pill is-active" data-filter-key="pilar" data-filter-value="all">Todos</button>
			<?php if ( is_array( $pilares ) ) : ?>
				<?php foreach ( $pilares as $pilar ) : ?>
					<button type="button" class="filter-pill" data-filter-key="pilar" data-filter-value="<?php echo esc_attr( $pilar->slug ); ?>">
						<?php echo esc_html( $pilar->name ); ?>
					</button>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<?php
		$all_atividades = new WP_Query(
			array(
				'post_type'      => 'pettec_atividade',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		if ( $all_atividades->have_posts() ) :
			?>
			<div class="grid grid--3">
				<?php
				while ( $all_atividades->have_posts() ) :
					$all_atividades->the_post();
					get_template_part( 'template-parts/card-atividade' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<div class="empty-state is-hidden" data-filter-empty>Nenhuma atividade encontrada para este pilar.</div>
		<?php else : ?>
			<div class="empty-state">Nenhuma atividade cadastrada ainda. Adicione em <strong>Atividades &rarr; Adicionar Nova</strong> no painel.</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
