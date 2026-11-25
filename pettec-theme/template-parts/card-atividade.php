<?php
/**
 * Reusable card for Atividades (pettec_atividade).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pettec_dark    = ! empty( $args['dark'] );
$pettec_pilares = get_the_terms( get_the_ID(), 'pettec_pilar' );
$pettec_slugs   = array();
if ( is_array( $pettec_pilares ) ) {
	foreach ( $pettec_pilares as $term ) {
		$pettec_slugs[] = $term->slug;
	}
}
$pettec_periodo = (string) get_post_meta( get_the_ID(), '_pettec_periodo', true );
$pettec_status  = (string) get_post_meta( get_the_ID(), '_pettec_status', true );
?>
<article class="card <?php echo $pettec_dark ? 'card--dark' : ''; ?> reveal" data-filter-item data-pilar="<?php echo esc_attr( implode( ',', $pettec_slugs ) ); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="card-media" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="card-body">
		<div class="card-meta">
			<?php if ( is_array( $pettec_pilares ) ) : ?>
				<?php foreach ( $pettec_pilares as $term ) : ?>
					<span class="tag"><?php echo esc_html( $term->name ); ?></span>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( $pettec_periodo ) : ?>
				<span><?php echo esc_html( $pettec_periodo ); ?></span>
			<?php endif; ?>
			<?php if ( $pettec_status ) : ?>
				<span>&bull; <?php echo esc_html( $pettec_status ); ?></span>
			<?php endif; ?>
		</div>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="muted" style="margin: 0; font-size: 0.94rem;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
		<div style="margin-top: auto; padding-top: 0.75rem;">
			<a href="<?php the_permalink(); ?>" style="font-weight: 700; color: var(--accent-red); font-size: 0.9rem;">Ver detalhes &rarr;</a>
		</div>
	</div>
</article>
