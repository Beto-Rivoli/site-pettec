<?php
/**
 * Reusable kinetic circular portrait card for Membros e Ex-membros (pettec_membro).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pettec_papel    = (string) get_post_meta( get_the_ID(), '_pettec_membro_papel', true );
$pettec_papel    = $pettec_papel ? $pettec_papel : 'membro';
$pettec_curso    = (string) get_post_meta( get_the_ID(), '_pettec_membro_curso', true );
$pettec_periodo  = (string) get_post_meta( get_the_ID(), '_pettec_membro_periodo', true );
$pettec_linkedin = pettec_clean_external_url( (string) get_post_meta( get_the_ID(), '_pettec_membro_linkedin', true ) );
$pettec_lattes   = pettec_clean_external_url( (string) get_post_meta( get_the_ID(), '_pettec_membro_lattes', true ) );
$pettec_github   = pettec_clean_external_url( (string) get_post_meta( get_the_ID(), '_pettec_membro_github', true ) );
$pettec_foto     = sanitize_file_name( (string) get_post_meta( get_the_ID(), '_pettec_membro_foto', true ) );
$pettec_foto_url = ( '' !== $pettec_foto && file_exists( PETTEC_THEME_DIR . '/assets/images/membros/' . $pettec_foto ) )
	? PETTEC_THEME_URI . '/assets/images/membros/' . rawurlencode( $pettec_foto )
	: '';

$pettec_name     = get_the_title();
$pettec_words    = preg_split( '/\s+/', trim( $pettec_name ) );
$pettec_initials = mb_strtoupper( mb_substr( $pettec_words[0] ?? 'P', 0, 1 ) . mb_substr( $pettec_words[ count( $pettec_words ) - 1 ] ?? '', 0, 1 ) );
$pettec_delay    = ( ( get_the_ID() % 6 ) * 0.45 ) . 's';

$pettec_papel_labels = array(
	'tutor'     => 'Tutor(a)',
	'ex-tutor'  => 'Ex-tutor(a)',
	'membro'    => 'Membro Atual',
	'ex-membro' => 'Ex-membro',
);
?>
<article
	class="member-card member-card--kinetic reveal"
	data-tilt-card
	data-filter-item
	data-papel="<?php echo esc_attr( $pettec_papel ); ?>"
	style="--float-delay: <?php echo esc_attr( $pettec_delay ); ?>;">
	<div class="portrait-circle">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
		<?php elseif ( '' !== $pettec_foto_url ) : ?>
			<img src="<?php echo esc_url( $pettec_foto_url ); ?>" alt="<?php echo esc_attr( $pettec_name ); ?>" loading="lazy">
		<?php else : ?>
			<span aria-hidden="true"><?php echo esc_html( $pettec_initials ); ?></span>
		<?php endif; ?>
	</div>
	<span class="tag" style="margin-bottom: 0.5rem;"><?php echo esc_html( $pettec_papel_labels[ $pettec_papel ] ?? 'Membro' ); ?></span>
	<h3 style="margin: 0.25rem 0 0.35rem; font-size: 1.1rem;"><?php echo esc_html( $pettec_name ); ?></h3>
	<?php if ( $pettec_curso ) : ?>
		<p class="muted" style="margin: 0; font-size: 0.86rem;"><?php echo esc_html( $pettec_curso ); ?></p>
	<?php endif; ?>
	<?php if ( $pettec_periodo ) : ?>
		<p class="muted" style="margin: 0.2rem 0 0; font-size: 0.8rem; opacity: 0.8;"><?php echo esc_html( $pettec_periodo ); ?></p>
	<?php endif; ?>

	<?php if ( $pettec_linkedin || $pettec_lattes || $pettec_github ) : ?>
		<div class="member-links">
			<?php if ( $pettec_linkedin ) : ?>
				<a href="<?php echo esc_url( $pettec_linkedin ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
			<?php endif; ?>
			<?php if ( $pettec_lattes ) : ?>
				<a href="<?php echo esc_url( $pettec_lattes ); ?>" target="_blank" rel="noopener noreferrer">Lattes</a>
			<?php endif; ?>
			<?php if ( $pettec_github ) : ?>
				<a href="<?php echo esc_url( $pettec_github ); ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</article>
