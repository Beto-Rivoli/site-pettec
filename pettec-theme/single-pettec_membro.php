<?php
/**
 * Single template for Membros e Ex-membros (pettec_membro).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="band band--dark band--hex-grid page-hero">
		<div class="container" style="max-width: 520px;">
			<div style="margin-bottom: 1.5rem;">
				<a href="<?php echo esc_url( home_url( '/membros/' ) ); ?>" style="color: var(--ink-light-muted); font-size: 0.9rem;">&larr; Voltar para Membros e Ex-membros</a>
			</div>
			<?php get_template_part( 'template-parts/card-membro' ); ?>
		</div>
	</section>
<?php endwhile; ?>
<?php
get_footer();
