<?php
/**
 * Single template for Atividades (pettec_atividade).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
while ( have_posts() ) :
	the_post();
	$pilares = get_the_terms( get_the_ID(), 'pettec_pilar' );
	$periodo = (string) get_post_meta( get_the_ID(), '_pettec_periodo', true );
	$status  = (string) get_post_meta( get_the_ID(), '_pettec_status', true );
	$link    = pettec_clean_external_url( (string) get_post_meta( get_the_ID(), '_pettec_link_externo', true ) );
	?>
	<section class="band band--dark band--hex-grid page-hero">
		<div class="container">
			<a href="<?php echo esc_url( home_url( '/atividades/' ) ); ?>" style="color: var(--ink-light-muted); font-size: 0.9rem;">&larr; Voltar para Atividades</a>
			<div style="margin-top: 1rem; display: flex; gap: 0.6rem; flex-wrap: wrap;">
				<?php if ( is_array( $pilares ) ) : ?>
					<?php foreach ( $pilares as $term ) : ?>
						<span class="tag"><?php echo esc_html( $term->name ); ?></span>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if ( $periodo ) : ?><span class="tag" style="background: rgba(255,255,255,0.12); color: #fff;"><?php echo esc_html( $periodo ); ?></span><?php endif; ?>
				<?php if ( $status ) : ?><span class="tag" style="background: rgba(255,255,255,0.12); color: #fff;"><?php echo esc_html( $status ); ?></span><?php endif; ?>
			</div>
			<h1 class="display-mixed display-mixed--lg" style="margin-top: 1rem;"><?php the_title(); ?></h1>
			<?php if ( $link ) : ?>
				<div style="margin-top: 1.5rem;">
					<a class="btn-primary" href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer">Acessar link da atividade &nearr;</a>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="band band--white">
		<div class="container" style="max-width: 820px;">
			<?php if ( has_post_thumbnail() ) : ?>
				<div style="margin-bottom: 2rem; border-radius: var(--radius-md); overflow: hidden;">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
<?php endwhile; ?>
<?php
get_footer();
