<?php
/**
 * Single template for Documentos e Editais (pettec_documento).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
while ( have_posts() ) :
	the_post();
	$doc_url    = pettec_clean_external_url( (string) get_post_meta( get_the_ID(), '_pettec_doc_url', true ) );
	$doc_ano    = (string) get_post_meta( get_the_ID(), '_pettec_doc_ano', true );
	$doc_codigo = (string) get_post_meta( get_the_ID(), '_pettec_doc_codigo', true );
	$doc_terms  = get_the_terms( get_the_ID(), 'pettec_doc_tipo' );
	?>
	<section class="band band--dark band--hex-grid page-hero">
		<div class="container" style="max-width: 860px;">
			<a href="<?php echo esc_url( home_url( '/documentos/' ) ); ?>" style="color: var(--ink-light-muted); font-size: 0.9rem;">&larr; Voltar para Documentos e Editais</a>
			<div style="margin-top: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
				<?php if ( $doc_ano ) : ?><span class="tag"><?php echo esc_html( $doc_ano ); ?></span><?php endif; ?>
				<?php if ( is_array( $doc_terms ) ) : ?>
					<?php foreach ( $doc_terms as $term ) : ?>
						<span class="tag" style="background: rgba(255,255,255,0.12); color: #fff;"><?php echo esc_html( $term->name ); ?></span>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if ( $doc_codigo ) : ?><span class="muted"><?php echo esc_html( $doc_codigo ); ?></span><?php endif; ?>
			</div>
			<h1 class="display-mixed display-mixed--lg" style="margin-top: 1rem;"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="lead" style="margin-top: 1rem;"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<div class="hero-actions">
				<?php if ( $doc_url ) : ?>
					<a class="btn-primary" href="<?php echo esc_url( $doc_url ); ?>" target="_blank" rel="noopener noreferrer">Baixar PDF &nearr;</a>
				<?php endif; ?>
				<a class="btn-outline" href="<?php echo esc_url( home_url( '/documentos/' ) ); ?>">Ver todos os documentos</a>
			</div>
		</div>
	</section>
<?php endwhile; ?>
<?php
get_footer();
