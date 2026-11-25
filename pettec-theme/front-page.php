<?php
/**
 * Front Page — Alternating dark/light parallax homepage (Harbour.Space + Extrafazant).
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
				<span class="eyebrow"><?php echo esc_html( pettec_get_option( 'hero_eyebrow' ) ); ?></span>
				<h1 class="display-mixed display-mixed--xl">
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

<!-- 3. ATIVIDADES & MINI CURSOS EM DESTAQUE (Dark + Parallax) -->
<section class="band band--dark band--hex-grid">
	<div class="container">
		<div class="section-head reveal">
			<div>
				<span class="eyebrow">Projetos &amp; Capacitação</span>
				<h2 class="display-mixed display-mixed--lg">
					MINI CURSOS <em>&amp; Atividades</em>
				</h2>
			</div>
			<div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
				<a class="btn-outline" href="<?php echo esc_url( home_url( '/mini-cursos/' ) ); ?>">Todos os Mini Cursos</a>
				<a class="btn-outline" href="<?php echo esc_url( home_url( '/atividades/' ) ); ?>">Todas as Atividades</a>
			</div>
		</div>

		<?php
		$pettec_featured_courses = new WP_Query(
			array(
				'post_type'      => 'pettec_minicurso',
				'posts_per_page' => 3,
			)
		);
		if ( $pettec_featured_courses->have_posts() ) :
			?>
			<div class="grid grid--3" data-parallax-speed="-0.05">
				<?php
				while ( $pettec_featured_courses->have_posts() ) :
					$pettec_featured_courses->the_post();
					get_template_part( 'template-parts/card-minicurso', null, array( 'dark' => true ) );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>

		<?php
		$pettec_featured_atividades = new WP_Query(
			array(
				'post_type'      => 'pettec_atividade',
				'posts_per_page' => 3,
			)
		);
		if ( $pettec_featured_atividades->have_posts() ) :
			?>
			<div style="margin-top: 3rem;">
				<h3 class="display-mixed display-mixed--md" style="margin-bottom: 1.5rem;">ATIVIDADES <em>recentes</em></h3>
				<div class="grid grid--3">
					<?php
					while ( $pettec_featured_atividades->have_posts() ) :
						$pettec_featured_atividades->the_post();
						get_template_part( 'template-parts/card-atividade', null, array( 'dark' => true ) );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- 4. BLOG & ACESSO RÁPIDO A EDITAIS/DOCUMENTOS (Light) -->
<section class="band band--light">
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

<!-- 5. PRÉVIA DA EQUIPE (Dark + Circular Portraits + Parallax) -->
<section class="band band--dark">
	<div class="container">
		<div class="section-head reveal">
			<div>
				<span class="eyebrow">Quem faz acontecer</span>
				<h2 class="display-mixed display-mixed--lg">NOSSA <em>Equipe</em></h2>
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
