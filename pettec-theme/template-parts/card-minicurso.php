<?php
/**
 * Reusable card for Mini Cursos (pettec_minicurso).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pettec_dark      = ! empty( $args['dark'] );
$pettec_instrutor = (string) get_post_meta( get_the_ID(), '_pettec_instrutor', true );
$pettec_carga     = (string) get_post_meta( get_the_ID(), '_pettec_carga_horaria', true );
$pettec_aulas     = pettec_get_repeater_meta( get_the_ID(), '_pettec_aulas' );
$pettec_materiais = pettec_get_repeater_meta( get_the_ID(), '_pettec_materiais' );
$pettec_thumb_url = '';

if ( ! has_post_thumbnail() && ! empty( $pettec_aulas[0]['url'] ) ) {
	$parsed = pettec_parse_youtube_url( (string) $pettec_aulas[0]['url'] );
	if ( $parsed && 'video' === $parsed['type'] ) {
		$pettec_thumb_url = 'https://i.ytimg.com/vi/' . rawurlencode( $parsed['id'] ) . '/hqdefault.jpg';
	}
}
?>
<article class="card <?php echo $pettec_dark ? 'card--dark' : ''; ?> reveal">
	<a class="card-media" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		<?php elseif ( $pettec_thumb_url ) : ?>
			<img src="<?php echo esc_url( $pettec_thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
		<?php else : ?>
			<div style="width:100%;height:100%;display:grid;place-items:center;color:#fff;font-family:var(--font-display);font-size:1.6rem;letter-spacing:0.06em;">MINI CURSO</div>
		<?php endif; ?>
	</a>
	<div class="card-body">
		<div class="card-meta">
			<span class="tag">Mini Curso</span>
			<?php if ( count( $pettec_aulas ) > 0 ) : ?>
				<span><?php echo esc_html( count( $pettec_aulas ) . ( 1 === count( $pettec_aulas ) ? ' vídeo' : ' vídeos' ) ); ?></span>
			<?php endif; ?>
			<?php if ( count( $pettec_materiais ) > 0 ) : ?>
				<span>&bull; <?php echo esc_html( count( $pettec_materiais ) . ( 1 === count( $pettec_materiais ) ? ' arquivo' : ' arquivos' ) ); ?></span>
			<?php endif; ?>
			<?php if ( $pettec_carga ) : ?>
				<span>&bull; <?php echo esc_html( $pettec_carga ); ?></span>
			<?php endif; ?>
		</div>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $pettec_instrutor ) : ?>
			<p class="muted" style="margin: 0; font-size: 0.86rem;">Instrutor(es): <?php echo esc_html( $pettec_instrutor ); ?></p>
		<?php endif; ?>
		<p class="muted" style="margin: 0; font-size: 0.93rem;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
		<div style="margin-top: auto; padding-top: 0.75rem;">
			<a href="<?php the_permalink(); ?>" style="font-weight: 700; color: var(--accent-red); font-size: 0.9rem;">Acessar aulas e materiais &rarr;</a>
		</div>
	</div>
</article>
