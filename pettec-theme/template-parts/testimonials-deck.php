<?php
/**
 * Reusable Two26-style Interactive Stacked Testimonials Deck.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pettec_dep_q = new WP_Query(
	array(
		'post_type'      => 'pettec_depoimento',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

if ( ! $pettec_dep_q->have_posts() ) :
	?>
	<div class="empty-state">Nenhum depoimento publicado ainda. Adicione em <strong>Depoimentos &rarr; Adicionar Novo</strong> no painel.</div>
	<?php
	return;
endif;

$pettec_deps = $pettec_dep_q->posts;
?>
<div class="testimonials-stage reveal" data-testimonial-deck tabindex="0" role="region" aria-label="Depoimentos de antigos petianos — clique para avançar">
	<div class="testimonials-hint">Clique no card para ver o próximo depoimento &rarr;</div>

	<div class="deck-stack">
		<?php foreach ( $pettec_deps as $idx => $dep ) : ?>
			<?php
			$author_name = get_the_title( $dep );
			$destaque    = trim( (string) get_post_meta( (int) $dep->ID, '_pettec_dep_destaque', true ) );
			$subtitulo   = trim( (string) get_post_meta( (int) $dep->ID, '_pettec_dep_subtitulo', true ) );
			$body_text   = trim( wp_strip_all_tags( (string) $dep->post_content ) );
			if ( '' === $destaque ) {
				$destaque = wp_trim_words( $body_text, 14 );
			}
			if ( '' === $subtitulo ) {
				$subtitulo = 'Ex-membro PETTEC • UNIFEI';
			}
			$words    = preg_split( '/\s+/', trim( $author_name ) );
			$initials = mb_strtoupper( mb_substr( $words[0] ?? 'P', 0, 1 ) . mb_substr( $words[ count( $words ) - 1 ] ?? '', 0, 1 ) );
			?>
			<article class="deck-card" data-pos="<?php echo esc_attr( $idx <= 3 ? (string) $idx : 'back' ); ?>">
				<blockquote class="deck-quote">&ldquo;<?php echo esc_html( $destaque ); ?>&rdquo;</blockquote>
				<div class="deck-bottom">
					<div class="deck-author">
						<div class="deck-avatar">
							<?php if ( has_post_thumbnail( $dep ) ) : ?>
								<?php echo get_the_post_thumbnail( $dep, 'thumbnail' ); ?>
							<?php else : ?>
								<span aria-hidden="true"><?php echo esc_html( $initials ); ?></span>
							<?php endif; ?>
						</div>
						<div>
							<strong><?php echo esc_html( $author_name ); ?></strong>
							<span><?php echo esc_html( $subtitulo ); ?></span>
						</div>
					</div>
					<p class="deck-body"><?php echo esc_html( $body_text ); ?></p>
				</div>
			</article>
		<?php endforeach; ?>
	</div>

	<div class="deck-ticks" role="tablist" aria-label="Selecionar depoimento">
		<?php foreach ( $pettec_deps as $idx => $dep ) : ?>
			<button
				type="button"
				class="deck-tick <?php echo 0 === $idx ? 'is-active' : ''; ?>"
				data-tick-index="<?php echo esc_attr( (string) $idx ); ?>"
				aria-label="Ver depoimento de <?php echo esc_attr( get_the_title( $dep ) ); ?>">
			</button>
		<?php endforeach; ?>
	</div>
</div>
<?php
wp_reset_postdata();
