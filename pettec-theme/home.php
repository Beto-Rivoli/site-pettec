<?php
/**
 * Blog Index Template (/blog/ — native WordPress posts).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
$categories = get_categories( array( 'hide_empty' => true ) );
?>
<section class="band band--dark band--hex-grid page-hero">
	<div class="container">
		<span class="eyebrow">Artigos &amp; Novidades</span>
		<h1 class="display-mixed display-mixed--xl">BLOG <em>PETTEC</em></h1>
		<p class="lead" style="margin-top: 1rem;">
			Tutoriais técnicos, relatos de projetos, eventos na UNIFEI e novidades produzidas pelos integrantes do grupo.
		</p>
	</div>
</section>

<section class="band band--light">
	<div class="container">
		<?php if ( ! empty( $categories ) ) : ?>
			<div class="filter-bar" role="navigation" aria-label="Categorias do Blog">
				<a class="filter-pill is-active" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Todos</a>
				<?php foreach ( $categories as $cat ) : ?>
					<a class="filter-pill" href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
						<?php echo esc_html( $cat->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="grid grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card-post' );
				endwhile;
				?>
			</div>
			<div style="margin-top: 2.5rem;">
				<?php the_posts_pagination( array( 'prev_text' => '&larr; Anteriores', 'next_text' => 'Próximos &rarr;' ) ); ?>
			</div>
		<?php else : ?>
			<div class="empty-state">Nenhum artigo publicado ainda. Crie o primeiro em <strong>Posts &rarr; Adicionar Novo</strong> no painel do WordPress.</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
