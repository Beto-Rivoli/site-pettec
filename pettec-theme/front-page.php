<?php
/**
 * Front Page — Harbour.Space + Extrafazant + Two26 Selected Works & Stacked Testimonials + Onlines Kinetic Team.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- 1. HERO (Dark + Geometric Hex Grid + Parallax + Rotating Scroll Badge + 4 Metrics) -->
<section class="band band--dark band--hex-grid hero">
	<div class="container">
		<div class="hero-layout">
			<div data-parallax-speed="-0.08">
				<div class="hero-unifei-lockup">
					<img src="<?php echo esc_url( PETTEC_THEME_URI . '/assets/images/logo-unifei-neg.png' ); ?>" alt="UNIFEI — Universidade Federal de Itajubá">
					<span><?php echo esc_html( pettec_get_option( 'hero_eyebrow' ) ); ?></span>
				</div>
				<h1 class="display-mixed display-mixed--xl" data-float-headline>
					<?php echo esc_html( pettec_get_option( 'hero_title_sans' ) ); ?>
					<em><?php echo esc_html( pettec_get_option( 'hero_title_serif' ) ); ?></em>
				</h1>
				<p class="lead" style="margin-top: 1.5rem;">
					<?php echo esc_html( pettec_get_option( 'hero_subtitle' ) ); ?>
				</p>
				<div class="hero-actions">
					<a class="btn-primary" href="#quem-somos">Conheça o PETTEC &darr;</a>
					<a class="btn-outline" href="<?php echo esc_url( home_url( '/mini-cursos/' ) ); ?>">Ver Mini Cursos</a>
					<a class="btn-outline" href="<?php echo esc_url( home_url( '/documentos/' ) ); ?>">Editais e Documentos</a>
				</div>
			</div>

			<div class="hero-badge-wrap" data-parallax-speed="0.14">
				<a href="#quem-somos" class="scroll-badge" aria-label="Explorar o site">
					<svg viewBox="0 0 180 180" data-scroll-rotate="0.22" aria-hidden="true">
						<defs>
							<path id="pettecCirclePath" d="M 90, 90 m -66, 0 a 66,66 0 1,1 132,0 a 66,66 0 1,1 -132,0" />
						</defs>
						<text fill="#F8F8F6" font-size="11" font-weight="700" letter-spacing="2.2">
							<textPath href="#pettecCirclePath">PETTEC • UNIFEI • ENSINO • PESQUISA • EXTENSÃO • </textPath>
						</text>
					</svg>
					<span class="scroll-badge-center" aria-hidden="true">&darr;</span>
				</a>
			</div>
		</div>

		<div class="metrics-strip reveal">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<div class="metric-item">
					<strong><?php echo esc_html( pettec_get_option( "metric_{$i}_num" ) ); ?></strong>
					<span><?php echo esc_html( pettec_get_option( "metric_{$i}_label" ) ); ?></span>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>

<!-- 1.5. CURSOS INTEGRANTES DO PETTEC (Eletrônica & Computação — Campo de Força + Parallax) -->
<section class="courses-band" aria-label="Cursos que integram o PETTEC">
	<div class="container">
		<div class="courses-stage" data-force-stage>
			<span class="eyebrow" style="margin-bottom: 0.5rem;">Cursos Integrantes do PETTEC • UNIFEI</span>
			<p class="force-word" data-force-word="ELETRÔNICA">ELETRÔNICA</p>
			<span class="courses-amp" aria-hidden="true">&amp;</span>
			<p class="force-word" data-force-word="COMPUTAÇÃO">COMPUTAÇÃO</p>
			<p class="courses-caption">
				O PETTEC conecta estudantes de <strong>Engenharia Eletrônica</strong> e <strong>Engenharia de Computação</strong> da UNIFEI.
			</p>
		</div>
	</div>
</section>

<!-- 2. QUEM SOMOS & 3 PILARES (Light + Tactile Cards) -->
<section id="quem-somos" class="band band--light">
	<div class="container">
		<div class="section-head reveal">
			<div>
				<span class="eyebrow">Quem somos &amp; O que fazemos</span>
				<h2 class="display-mixed display-mixed--lg">
					TRÍADE UNIVERSITÁRIA <em>na prática</em>
				</h2>
			</div>
			<p class="lead" style="margin: 0;">
				O PETTEC reúne estudantes e tutoria na UNIFEI para desenvolver projetos reais, compartilhar conhecimento aberto e aproximar a universidade da comunidade.
			</p>
		</div>

		<div class="grid grid--3">
			<div class="pillar-card reveal">
				<div class="pillar-num">01</div>
				<h3 style="margin: 0 0 0.6rem; font-size: 1.35rem;">Ensino</h3>
				<p class="muted" style="margin: 0;">
					Mini cursos abertos, listas de exercícios comentadas, oficinas de programação, eletrônica e ferramentas de engenharia para fortalecer a formação acadêmica.
				</p>
			</div>
			<div class="pillar-card reveal">
				<div class="pillar-num">02</div>
				<h3 style="margin: 0 0 0.6rem; font-size: 1.35rem;">Pesquisa</h3>
				<p class="muted" style="margin: 0;">
					Iniciação científica coletiva, publicações técnicas, desenvolvimento de protótipos e investigação de soluções tecnológicas para desafios reais.
				</p>
			</div>
			<div class="pillar-card reveal">
				<div class="pillar-num">03</div>
				<h3 style="margin: 0 0 0.6rem; font-size: 1.35rem;">Extensão</h3>
				<p class="muted" style="margin: 0;">
					Eventos abertos, impacto social em escolas e projetos comunitários levando ciência e tecnologia da UNIFEI para além do campus.
				</p>
			</div>
		</div>
	</div>
</section>

<!-- 3. FEATURED PROJECTS & SELECTED WORKS (Two26 Studio style — Mini Cursos & Atividades) -->
<section class="band band--dark band--hex-grid">
	<div class="container">
		<div class="section-head reveal" style="justify-content: center; text-align: center; flex-direction: column; align-items: center;">
			<span class="eyebrow">Projetos em Destaque</span>
			<h2 class="display-mixed display-mixed--xl">
				SELECTED WORKS <em>Mini Cursos &amp; Atividades</em>
			</h2>
		</div>

		<?php
		$pettec_selected_courses = get_posts(
			array(
				'post_type'      => 'pettec_minicurso',
				'post_status'    => 'publish',
				'posts_per_page' => 4,
			)
		);
		$pettec_selected_atividades = get_posts(
			array(
				'post_type'      => 'pettec_atividade',
				'post_status'    => 'publish',
				'posts_per_page' => 2,
			)
		);
		$pettec_works = array_merge( $pettec_selected_courses, $pettec_selected_atividades );
		if ( ! empty( $pettec_works ) ) :
			?>
			<div class="work-grid">
				<?php foreach ( $pettec_works as $idx => $work ) : ?>
					<?php
					$is_course = 'pettec_minicurso' === $work->post_type;
					$badge     = $is_course ? 'MC' : 'AT';
					$category  = $is_course ? 'Mini Curso • YouTube & Materiais' : 'Atividade • PETTEC UNIFEI';
					$thumb_url = get_the_post_thumbnail_url( $work, 'large' );
					if ( ! $thumb_url && $is_course ) {
						$aulas = pettec_get_repeater_meta( (int) $work->ID, '_pettec_aulas' );
						if ( ! empty( $aulas[0]['url'] ) ) {
							$parsed = pettec_parse_youtube_url( (string) $aulas[0]['url'] );
							if ( $parsed && 'video' === $parsed['type'] ) {
								$thumb_url = 'https://i.ytimg.com/vi/' . rawurlencode( $parsed['id'] ) . '/hqdefault.jpg';
							}
						}
					}
					?>
					<a class="work-card work-card-clip" href="<?php echo esc_url( get_permalink( $work ) ); ?>">
						<div class="work-card-media">
							<?php if ( $thumb_url ) : ?>
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title( $work ) ); ?>" loading="lazy">
							<?php else : ?>
								<div class="work-card-placeholder">
									<span class="tag" style="align-self: flex-start; margin-bottom: 0.75rem;"><?php echo esc_html( $is_course ? 'Mini Curso' : 'Atividade' ); ?></span>
									<span class="display-mixed display-mixed--md" style="color: #fff;"><?php echo esc_html( get_the_title( $work ) ); ?></span>
								</div>
							<?php endif; ?>
							<svg class="work-card-svg-accent" viewBox="0 0 400 300" preserveAspectRatio="none" aria-hidden="true">
								<path d="M 18 282 Q 110 210 205 245 T 382 18" />
							</svg>
						</div>
						<div class="work-card-footer">
							<div class="work-card-id">
								<span class="work-badge" aria-hidden="true"><?php echo esc_html( $badge ); ?></span>
								<div>
									<h3 class="work-card-title"><?php echo esc_html( get_the_title( $work ) ); ?></h3>
									<span class="work-card-cat"><?php echo esc_html( $category ); ?></span>
								</div>
							</div>
							<span class="work-arrow" aria-hidden="true">&nearr;</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="works-outro reveal">
			<h3 class="display-mixed display-mixed--lg">
				TEM MUITO MAIS <em>para explorar</em>
			</h3>
			<div class="works-outro-pills" aria-hidden="true">
				<span>100% Gratuito</span>
				<span>Ensino, Pesquisa &amp; Extensão</span>
				<span>Materiais &amp; Listas</span>
			</div>
			<div style="display: flex; justify-content: center; gap: 0.85rem; flex-wrap: wrap;">
				<a class="btn-primary" href="<?php echo esc_url( home_url( '/mini-cursos/' ) ); ?>">Ver todos os Mini Cursos &nearr;</a>
				<a class="btn-outline" href="<?php echo esc_url( home_url( '/atividades/' ) ); ?>">Ver todas as Atividades &nearr;</a>
			</div>
		</div>
	</div>
</section>

<!-- 4. DEPOIMENTOS — FALAS DOS ANTIGOS PETIANOS (Two26 Studio Interactive Stacked Deck) -->
<section class="band band--light">
	<div class="container">
		<div class="section-head reveal" style="justify-content: center; text-align: center; flex-direction: column; align-items: center;">
			<span class="eyebrow">Depoimentos</span>
			<h2 class="display-mixed display-mixed--lg">
				FALAS DOS <em>Antigos Petianos</em>
			</h2>
		</div>

		<?php get_template_part( 'template-parts/testimonials-deck' ); ?>
	</div>
</section>

<!-- 5. BLOG & ACESSO RÁPIDO A EDITAIS/DOCUMENTOS (White) -->
<section class="band band--white">
	<div class="container">
		<div class="grid grid--2" style="align-items: start; gap: 3rem;">
			<div>
				<div class="section-head reveal" style="margin-bottom: 1.75rem;">
					<div>
						<span class="eyebrow">Blog PETTEC</span>
						<h2 class="display-mixed display-mixed--md">ÚLTIMAS <em>publicações</em></h2>
					</div>
					<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" style="font-weight: 700; color: var(--accent-red);">Ver blog &rarr;</a>
				</div>
				<?php
				$pettec_blog_q = new WP_Query(
					array(
						'post_type'      => 'post',
						'posts_per_page' => 2,
					)
				);
				if ( $pettec_blog_q->have_posts() ) :
					echo '<div class="grid" style="gap: 1.25rem;">';
					while ( $pettec_blog_q->have_posts() ) :
						$pettec_blog_q->the_post();
						get_template_part( 'template-parts/card-post' );
					endwhile;
					echo '</div>';
					wp_reset_postdata();
				else :
					?>
					<div class="empty-state">Em breve novos artigos e novidades do PETTEC por aqui.</div>
				<?php endif; ?>
			</div>

			<div>
				<div class="section-head reveal" style="margin-bottom: 1.75rem;">
					<div>
						<span class="eyebrow">Transparência</span>
						<h2 class="display-mixed display-mixed--md">EDITAIS <em>&amp; Documentos</em></h2>
					</div>
					<a href="<?php echo esc_url( home_url( '/documentos/' ) ); ?>" style="font-weight: 700; color: var(--accent-red);">Ver todos &rarr;</a>
				</div>
				<?php
				$pettec_docs_q = new WP_Query(
					array(
						'post_type'      => 'pettec_documento',
						'posts_per_page' => 5,
					)
				);
				if ( $pettec_docs_q->have_posts() ) :
					echo '<div class="doc-list">';
					while ( $pettec_docs_q->have_posts() ) :
						$pettec_docs_q->the_post();
						$doc_url = pettec_clean_external_url( (string) get_post_meta( get_the_ID(), '_pettec_doc_url', true ) );
						$doc_ano = (string) get_post_meta( get_the_ID(), '_pettec_doc_ano', true );
						?>
						<div class="doc-row reveal">
							<div>
								<?php if ( $doc_ano ) : ?>
									<span class="tag" style="margin-bottom: 0.35rem;"><?php echo esc_html( $doc_ano ); ?></span>
								<?php endif; ?>
								<div style="font-weight: 700;"><?php the_title(); ?></div>
							</div>
							<?php if ( $doc_url ) : ?>
								<a class="btn-outline" style="padding: 0.55rem 1rem; font-size: 0.84rem;" href="<?php echo esc_url( $doc_url ); ?>" target="_blank" rel="noopener noreferrer">PDF &nearr;</a>
							<?php endif; ?>
						</div>
						<?php
					endwhile;
					echo '</div>';
					wp_reset_postdata();
				else :
					?>
					<div class="empty-state">Nenhum documento publicado ainda.</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<!-- 6. PRÉVIA DA EQUIPE (Dark + Onlines Cell Matrix + Kinetic Tilt Cards) -->
<section class="band band--dark cell-matrix-wrap">
	<div class="cell-matrix" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head reveal">
			<div>
				<span class="eyebrow">Quem faz acontecer</span>
				<h2 class="display-mixed display-mixed--lg">
					NOSSA
					<span class="selection-box">
						<span class="selection-handle selection-handle--tl" aria-hidden="true"></span>
						<span class="selection-handle selection-handle--tr" aria-hidden="true"></span>
						<span class="selection-handle selection-handle--bl" aria-hidden="true"></span>
						<span class="selection-handle selection-handle--br" aria-hidden="true"></span>
						<em>Equipe</em>
					</span>
				</h2>
			</div>
			<a class="btn-outline" href="<?php echo esc_url( home_url( '/membros/' ) ); ?>">Ver todos os membros e ex-membros &rarr;</a>
		</div>

		<?php
		$pettec_members_q = new WP_Query(
			array(
				'post_type'      => 'pettec_membro',
				'posts_per_page' => 8,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'meta_query'     => array(
					array(
						'key'   => '_pettec_membro_papel',
						'value' => 'membro',
					),
				),
			)
		);
		if ( $pettec_members_q->have_posts() ) :
			?>
			<div class="grid grid--4" data-parallax-speed="0.05">
				<?php
				while ( $pettec_members_q->have_posts() ) :
					$pettec_members_q->the_post();
					get_template_part( 'template-parts/card-membro' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
