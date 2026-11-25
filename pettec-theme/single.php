<?php
/**
 * Single Blog Post Template.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
while ( have_posts() ) :
	the_post();
	$cats      = get_the_category();
	$read_time = pettec_estimate_reading_time( (string) get_the_content() );
	?>
	<section class="band band--dark band--hex-grid page-hero">
		<div class="container" style="max-width: 900px;">
			<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" style="color: var(--ink-light-muted); font-size: 0.9rem;">&larr; Voltar para o Blog</a>
			<div style="margin-top: 1rem; display: flex; gap: 0.6rem; flex-wrap: wrap; align-items: center;">
				<?php if ( ! empty( $cats ) ) : ?>
					<span class="tag"><?php echo esc_html( $cats[0]->name ); ?></span>
				<?php endif; ?>
				<span class="muted" style="font-size: 0.88rem;"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></span>
				<span class="muted" style="font-size: 0.88rem;">&bull; <?php echo esc_html( $read_time ); ?> min de leitura</span>
			</div>
			<h1 class="display-mixed display-mixed--lg" style="margin-top: 1rem;"><?php the_title(); ?></h1>
		</div>
	</section>

	<article class="band band--white">
		<div class="container" style="max-width: 820px;">
			<?php if ( has_post_thumbnail() ) : ?>
				<div style="margin-bottom: 2rem; border-radius: var(--radius-md); overflow: hidden;">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>
			<div class="entry-content" style="font-size: 1.06rem; line-height: 1.8;">
				<?php the_content(); ?>
			</div>

			<div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
				<span class="muted" style="font-size: 0.9rem;">Publicado por <strong><?php the_author(); ?></strong></span>
				<a class="btn-outline" style="padding: 0.55rem 1rem; font-size: 0.85rem;" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">&larr; Mais artigos</a>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php
get_footer();
