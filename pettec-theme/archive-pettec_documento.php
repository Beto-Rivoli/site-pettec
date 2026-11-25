<?php
/**
 * Archive template for Documentos e Editais (pettec_documento) with instant Year & Type filters.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$all_docs = get_posts(
	array(
		'post_type'      => 'pettec_documento',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

$anos  = array();
$tipos = get_terms( array( 'taxonomy' => 'pettec_doc_tipo', 'hide_empty' => false ) );

foreach ( $all_docs as $d ) {
	$a = trim( (string) get_post_meta( (int) $d->ID, '_pettec_doc_ano', true ) );
	if ( '' !== $a ) {
		$anos[ $a ] = $a;
	}
}
krsort( $anos );
?>
<section class="band band--dark band--hex-grid page-hero">
	<div class="container">
		<span class="eyebrow">Transparência &amp; Editais</span>
		<h1 class="display-mixed display-mixed--xl">DOCUMENTOS <em>&amp; Editais</em></h1>
		<p class="lead" style="margin-top: 1rem;">
			Editais de processo seletivo, planejamentos anuais, relatórios de atividades e documentos oficiais do PETTEC — UNIFEI.
		</p>
	</div>
</section>

<section class="band band--light">
	<div class="container" data-filter-group="documentos">
		<?php if ( ! empty( $anos ) ) : ?>
			<div style="margin-bottom: 0.6rem; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink-muted);">Filtrar por Ano</div>
			<div class="filter-bar" role="group" aria-label="Filtrar documentos por ano">
				<button type="button" class="filter-pill is-active" data-filter-key="ano" data-filter-value="all">Todos os anos</button>
				<?php foreach ( $anos as $ano ) : ?>
					<button type="button" class="filter-pill" data-filter-key="ano" data-filter-value="<?php echo esc_attr( $ano ); ?>"><?php echo esc_html( $ano ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( is_array( $tipos ) && ! empty( $tipos ) ) : ?>
			<div style="margin-bottom: 0.6rem; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink-muted);">Filtrar por Tipo</div>
			<div class="filter-bar" role="group" aria-label="Filtrar documentos por tipo">
				<button type="button" class="filter-pill is-active" data-filter-key="tipo" data-filter-value="all">Todos os tipos</button>
				<?php foreach ( $tipos as $tipo ) : ?>
					<button type="button" class="filter-pill" data-filter-key="tipo" data-filter-value="<?php echo esc_attr( $tipo->slug ); ?>"><?php echo esc_html( $tipo->name ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $all_docs ) ) : ?>
			<div class="doc-list">
				<?php foreach ( $all_docs as $doc ) : ?>
					<?php
					$doc_url    = pettec_clean_external_url( (string) get_post_meta( (int) $doc->ID, '_pettec_doc_url', true ) );
					$doc_ano    = trim( (string) get_post_meta( (int) $doc->ID, '_pettec_doc_ano', true ) );
					$doc_codigo = trim( (string) get_post_meta( (int) $doc->ID, '_pettec_doc_codigo', true ) );
					$doc_terms  = get_the_terms( (int) $doc->ID, 'pettec_doc_tipo' );
					$tipo_slugs = array();
					if ( is_array( $doc_terms ) ) {
						foreach ( $doc_terms as $t ) {
							$tipo_slugs[] = $t->slug;
						}
					}
					?>
					<div class="doc-row reveal" data-filter-item data-ano="<?php echo esc_attr( $doc_ano ); ?>" data-tipo="<?php echo esc_attr( implode( ',', $tipo_slugs ) ); ?>">
						<div>
							<div style="display: flex; flex-wrap: wrap; gap: 0.45rem; margin-bottom: 0.4rem;">
								<?php if ( $doc_ano ) : ?>
									<span class="tag"><?php echo esc_html( $doc_ano ); ?></span>
								<?php endif; ?>
								<?php if ( is_array( $doc_terms ) ) : ?>
									<?php foreach ( $doc_terms as $t ) : ?>
										<span class="tag" style="background: rgba(20,22,25,0.08); color: var(--ink-dark);"><?php echo esc_html( $t->name ); ?></span>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php if ( $doc_codigo ) : ?>
									<span class="muted" style="font-size: 0.82rem;"><?php echo esc_html( $doc_codigo ); ?></span>
								<?php endif; ?>
							</div>
							<div style="font-weight: 700; font-size: 1.05rem;"><?php echo esc_html( get_the_title( $doc ) ); ?></div>
							<?php if ( ! empty( $doc->post_excerpt ) ) : ?>
								<p class="muted" style="margin: 0.25rem 0 0; font-size: 0.9rem;"><?php echo esc_html( wp_strip_all_tags( (string) $doc->post_excerpt ) ); ?></p>
							<?php endif; ?>
						</div>
						<?php if ( $doc_url ) : ?>
							<a class="btn-primary" style="padding: 0.6rem 1.15rem; font-size: 0.85rem;" href="<?php echo esc_url( $doc_url ); ?>" target="_blank" rel="noopener noreferrer">Baixar PDF &nearr;</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="empty-state is-hidden" data-filter-empty>Nenhum documento encontrado com os filtros selecionados.</div>
		<?php else : ?>
			<div class="empty-state">Nenhum documento publicado ainda.</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
