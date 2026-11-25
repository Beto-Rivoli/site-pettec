<?php
/**
 * Reusable editorial card for Blog posts (native WP post).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pettec_cats  = get_the_category();
$pettec_read  = pettec_estimate_reading_time( (string) get_the_content() );
?>
<article class="card reveal">
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="card-media" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="card-body">
		<div class="card-meta">
			<?php if ( ! empty( $pettec_cats ) ) : ?>
				<span class="tag"><?php echo esc_html( $pettec_cats[0]->name ); ?></span>
			<?php endif; ?>
			<span><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></span>
			<span>&bull; <?php echo esc_html( $pettec_read ); ?> min de leitura</span>
		</div>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="muted" style="margin: 0; font-size: 0.94rem;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
		<div style="margin-top: auto; padding-top: 0.75rem;">
			<a href="<?php the_permalink(); ?>" style="font-weight: 700; color: var(--accent-red); font-size: 0.9rem;">Ler artigo &rarr;</a>
		</div>
	</div>
</article>
