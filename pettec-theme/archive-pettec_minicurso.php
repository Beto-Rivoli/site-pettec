<?php
/**
 * Archive template for Mini Cursos (pettec_minicurso).
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
		<span class="eyebrow">Ensino Aberto</span>
		<h1 class="display-mixed display-mixed--xl">MINI <em>Cursos</em></h1>
		<p class="lead" style="margin-top: 1rem;">
			Videoaulas gravadas no YouTube, apostilas, slides e listas de exercícios preparados pelo PETTEC para a comunidade acadêmica.
		</p>
		<div class="hero-actions">
			<a class="btn-primary" href="<?php echo esc_url( home_url( '/listas-de-exercicio/' ) ); ?>">Ver todas as Listas de Exercícios &rarr;</a>
		</div>
	</div>
</section>

<section class="band band--light">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="grid grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card-minicurso' );
				endwhile;
				?>
			</div>
		<?php else : ?>
			<div class="empty-state">Nenhum mini curso publicado ainda.</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
