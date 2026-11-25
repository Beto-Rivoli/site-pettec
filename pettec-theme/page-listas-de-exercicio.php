<?php
/**
 * Template Name: Listas de Exercícios
 * Aggregated lists from Mini Cursos + standalone Lista de Exercícios documents.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
$all_lists = pettec_get_all_exercise_lists();
$has_any   = ! empty( $all_lists['cursos'] ) || ! empty( $all_lists['avulsas'] );
?>
<section class="band band--dark band--hex-grid page-hero">
	<div class="container">
		<span class="eyebrow">Prática &amp; Estudo</span>
		<h1 class="display-mixed display-mixed--xl">LISTAS DE <em>Exercícios</em></h1>
		<p class="lead" style="margin-top: 1rem;">
			Todas as listas de exercícios dos nossos Mini Cursos reunidas em um só lugar, junto com listas avulsas preparadas pelo PETTEC.
		</p>
	</div>
</section>

<section class="band band--light">
	<div class="container">
		<?php if ( ! $has_any ) : ?>
			<div class="empty-state">
				Nenhuma lista de exercícios cadastrada ainda. Adicione listas dentro de um <strong>Mini Curso</strong> ou em <strong>Documentos</strong> com o tipo <em>Lista de Exercícios</em>.
			</div>
		<?php else : ?>
			<?php if ( ! empty( $all_lists['cursos'] ) ) : ?>
				<div style="margin-bottom: 3.5rem;">
					<h2 class="display-mixed display-mixed--md" style="margin-bottom: 1.5rem;">POR MINI <em>Curso</em></h2>
					<?php foreach ( $all_lists['cursos'] as $grupo ) : ?>
						<div style="margin-bottom: 2rem;">
							<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;margin-bottom:0.85rem;">
								<h3 style="margin:0;"><?php echo esc_html( $grupo['course_title'] ); ?></h3>
								<a href="<?php echo esc_url( $grupo['course_url'] ); ?>" style="color: var(--accent-red); font-weight: 700; font-size: 0.9rem;">Ver mini curso &rarr;</a>
							</div>
							<div class="doc-list">
								<?php foreach ( $grupo['listas'] as $lista ) : ?>
									<div class="doc-row reveal">
										<div style="font-weight: 700;"><?php echo esc_html( $lista['titulo'] ); ?></div>
										<a class="btn-primary" style="padding: 0.55rem 1.05rem; font-size: 0.85rem;" href="<?php echo esc_url( $lista['url'] ); ?>" target="_blank" rel="noopener noreferrer">Baixar Lista &nearr;</a>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $all_lists['avulsas'] ) ) : ?>
				<div>
					<h2 class="display-mixed display-mixed--md" style="margin-bottom: 1.5rem;">LISTAS <em>Avulsas</em></h2>
					<div class="doc-list">
						<?php foreach ( $all_lists['avulsas'] as $avulsa ) : ?>
							<div class="doc-row reveal">
								<div>
									<?php if ( ! empty( $avulsa['ano'] ) ) : ?>
										<span class="tag" style="margin-bottom: 0.35rem;"><?php echo esc_html( $avulsa['ano'] ); ?></span>
									<?php endif; ?>
									<div style="font-weight: 700;"><?php echo esc_html( $avulsa['titulo'] ); ?></div>
									<?php if ( ! empty( $avulsa['resumo'] ) ) : ?>
										<p class="muted" style="margin: 0.25rem 0 0; font-size: 0.9rem;"><?php echo esc_html( $avulsa['resumo'] ); ?></p>
									<?php endif; ?>
								</div>
								<a class="btn-primary" style="padding: 0.55rem 1.05rem; font-size: 0.85rem;" href="<?php echo esc_url( $avulsa['url'] ); ?>" target="_blank" rel="noopener noreferrer">Baixar Lista &nearr;</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
