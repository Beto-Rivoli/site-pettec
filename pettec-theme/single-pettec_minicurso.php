<?php
/**
 * Single template for Mini Cursos (multiple YouTube lessons + downloadable materials/lists + rich content).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
while ( have_posts() ) :
	the_post();
	$instrutor = (string) get_post_meta( get_the_ID(), '_pettec_instrutor', true );
	$carga     = (string) get_post_meta( get_the_ID(), '_pettec_carga_horaria', true );
	$aulas     = pettec_get_repeater_meta( get_the_ID(), '_pettec_aulas' );
	$materiais = pettec_get_repeater_meta( get_the_ID(), '_pettec_materiais' );
	?>
	<section class="band band--dark band--hex-grid page-hero">
		<div class="container">
			<a href="<?php echo esc_url( home_url( '/mini-cursos/' ) ); ?>" style="color: var(--ink-light-muted); font-size: 0.9rem;">&larr; Voltar para Mini Cursos</a>
			<div style="margin-top: 1rem; display: flex; gap: 0.6rem; flex-wrap: wrap;">
				<span class="tag">Mini Curso</span>
				<?php if ( $carga ) : ?><span class="tag" style="background: rgba(255,255,255,0.12); color: #fff;"><?php echo esc_html( $carga ); ?></span><?php endif; ?>
				<?php if ( $instrutor ) : ?><span class="tag" style="background: rgba(255,255,255,0.12); color: #fff;">Instrutor(es): <?php echo esc_html( $instrutor ); ?></span><?php endif; ?>
			</div>
			<h1 class="display-mixed display-mixed--lg" style="margin-top: 1rem;"><?php the_title(); ?></h1>
		</div>
	</section>

	<section class="band band--light">
		<div class="container">
			<?php if ( trim( get_the_content() ) !== '' ) : ?>
				<div style="max-width: 820px; margin-bottom: 3rem;">
					<h2 class="display-mixed display-mixed--md" style="margin-bottom: 1rem;">SOBRE O <em>curso</em></h2>
					<div class="entry-content"><?php the_content(); ?></div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $aulas ) ) : ?>
				<div style="margin-bottom: 3.5rem;">
					<h2 class="display-mixed display-mixed--md" style="margin-bottom: 1.5rem;">AULAS <em>em vídeo</em></h2>
					<div class="grid grid--2">
						<?php foreach ( $aulas as $idx => $aula ) : ?>
							<?php
							$aula_url   = isset( $aula['url'] ) ? (string) $aula['url'] : '';
							$aula_title = ! empty( $aula['titulo'] ) ? (string) $aula['titulo'] : 'Aula ' . ( $idx + 1 );
							$parsed     = pettec_parse_youtube_url( $aula_url );
							?>
							<div class="card reveal">
								<?php if ( $parsed ) : ?>
									<div class="video-embed">
										<iframe
											src="<?php echo esc_url( $parsed['embed_url'] ); ?>"
											title="<?php echo esc_attr( $aula_title ); ?>"
											loading="lazy"
											allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
											allowfullscreen>
										</iframe>
									</div>
								<?php endif; ?>
								<div class="card-body">
									<h3><?php echo esc_html( $aula_title ); ?></h3>
									<?php if ( ! $parsed && pettec_clean_external_url( $aula_url ) ) : ?>
										<a href="<?php echo esc_url( pettec_clean_external_url( $aula_url ) ); ?>" target="_blank" rel="noopener noreferrer" style="color: var(--accent-red); font-weight: 700;">Abrir vídeo externo &nearr;</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div>
				<h2 class="display-mixed display-mixed--md" style="margin-bottom: 1.5rem;">MATERIAIS <em>&amp; Listas de Exercícios</em></h2>
				<?php if ( ! empty( $materiais ) ) : ?>
					<div class="doc-list">
						<?php foreach ( $materiais as $mat ) : ?>
							<?php
							$mat_url   = pettec_clean_external_url( (string) ( $mat['url'] ?? '' ) );
							$mat_tipo  = (string) ( $mat['tipo'] ?? 'Material' );
							$mat_title = ! empty( $mat['titulo'] ) ? (string) $mat['titulo'] : $mat_tipo;
							if ( '' === $mat_url ) {
								continue;
							}
							?>
							<div class="doc-row reveal">
								<div>
									<span class="tag" style="margin-bottom: 0.35rem;"><?php echo esc_html( $mat_tipo ); ?></span>
									<div style="font-weight: 700;"><?php echo esc_html( $mat_title ); ?></div>
								</div>
								<a class="btn-primary" style="padding: 0.6rem 1.1rem; font-size: 0.85rem;" href="<?php echo esc_url( $mat_url ); ?>" target="_blank" rel="noopener noreferrer">Baixar / Abrir &nearr;</a>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="empty-state">Nenhum arquivo ou lista anexada a este mini curso ainda.</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endwhile; ?>
<?php
get_footer();
