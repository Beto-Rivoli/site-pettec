<?php
/**
 * Generic Archive Template (Categories, Tags, Dates).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="band band--dark band--hex-grid page-hero">
	<div class="container">
		<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" style="color: var(--ink-light-muted); font-size: 0.9rem;">&larr; Voltar para o Blog</a>
		<h1 class="display-mixed display-mixed--lg" style="margin-top: 0.75rem;"><?php the_archive_title(); ?></h1>
		<?php if ( get_the_archive_description() ) : ?>
			<div class="lead" style="margin-top: 0.75rem;"><?php the_archive_description(); ?></div>
		<?php endif; ?>
	</div>
</section>

<section class="band band--light">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="grid grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card-post' );
				endwhile;
				?>
			</div>
		<?php else : ?>
			<div class="empty-state">Nenhuma publicação encontrada neste arquivo.</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
