<?php
/**
 * Main fallback template.
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
		<h1 class="display-mixed display-mixed--lg"><?php is_singular() ? the_title() : bloginfo( 'name' ); ?></h1>
	</div>
</section>

<section class="band band--light">
	<div class="container" style="max-width: 860px;">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article style="margin-bottom: 2.5rem;">
					<?php if ( ! is_singular() ) : ?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>
					<div><?php the_content(); ?></div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="empty-state">Conteúdo não encontrado.</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
