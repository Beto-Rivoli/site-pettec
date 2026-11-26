<?php
/**
 * Site Footer — Dark closing CTA, 4-column navigation, logo pill & UNIFEI info.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pettec_email     = pettec_get_option( 'contact_email' );
$pettec_address   = pettec_get_option( 'contact_address' );
$pettec_instagram = function_exists( 'pettec_clean_external_url' ) ? pettec_clean_external_url( pettec_get_option( 'social_instagram' ) ) : '';
$pettec_linkedin  = function_exists( 'pettec_clean_external_url' ) ? pettec_clean_external_url( pettec_get_option( 'social_linkedin' ) ) : '';
$pettec_youtube   = function_exists( 'pettec_clean_external_url' ) ? pettec_clean_external_url( pettec_get_option( 'social_youtube' ) ) : '';
$pettec_github    = function_exists( 'pettec_clean_external_url' ) ? pettec_clean_external_url( pettec_get_option( 'social_github' ) ) : '';
?>
</main>

<footer class="site-footer" role="contentinfo">
	<div class="container">
		<div class="footer-cta reveal">
			<span class="eyebrow">Vamos conversar</span>
			<h2 class="display-mixed display-mixed--lg">
				QUER FAZER PARTE OU <em>propor uma parceria?</em>
			</h2>
			<div style="margin-top: 1.75rem;">
				<a class="btn-primary" href="<?php echo esc_url( home_url( '/contato/' ) ); ?>">
					Fale com o PETTEC &nearr;
				</a>
			</div>
		</div>

		<div class="footer-grid">
			<div class="footer-col">
				<div class="footer-brand-lockup">
					<a class="logo-pill" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo esc_url( pettec_get_logo_url() ); ?>" alt="PETTEC UNIFEI">
					</a>
					<span class="footer-brand-divider" aria-hidden="true"></span>
					<a href="https://unifei.edu.br/" target="_blank" rel="noopener noreferrer" aria-label="Universidade Federal de Itajubá — UNIFEI">
						<img class="footer-unifei-logo" src="<?php echo esc_url( PETTEC_THEME_URI . '/assets/images/logo-unifei-compl-neg.png' ); ?>" alt="UNIFEI — Universidade Federal de Itajubá">
					</a>
				</div>
				<p class="muted" style="font-size: 0.9rem; margin: 0.75rem 0 0;">
					Programa de Educação Tutorial em Tecnologia — Engenharia Eletrônica e Engenharia de Computação (UNIFEI).
				</p>
			</div>

			<div class="footer-col">
				<h4>Navegação</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Início</a></li>
					<li><a href="<?php echo esc_url( home_url( '/atividades/' ) ); ?>">Atividades</a></li>
					<li><a href="<?php echo esc_url( home_url( '/mini-cursos/' ) ); ?>">Mini Cursos</a></li>
					<li><a href="<?php echo esc_url( home_url( '/listas-de-exercicio/' ) ); ?>">Listas de Exercício</a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h4>Institucional</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/documentos/' ) ); ?>">Documentos e Editais</a></li>
					<li><a href="<?php echo esc_url( home_url( '/membros/' ) ); ?>">Membros e Ex-membros</a></li>
					<li><a href="<?php echo esc_url( home_url( '/depoimentos/' ) ); ?>">Depoimentos</a></li>
					<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contato/' ) ); ?>">Contato</a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h4>Contato &amp; Redes</h4>
				<ul>
					<li><a href="mailto:<?php echo esc_attr( $pettec_email ); ?>"><?php echo esc_html( $pettec_email ); ?></a></li>
					<?php if ( $pettec_instagram ) : ?>
						<li><a href="<?php echo esc_url( $pettec_instagram ); ?>" target="_blank" rel="noopener noreferrer">Instagram &nearr;</a></li>
					<?php endif; ?>
					<?php if ( $pettec_linkedin ) : ?>
						<li><a href="<?php echo esc_url( $pettec_linkedin ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn &nearr;</a></li>
					<?php endif; ?>
					<?php if ( $pettec_youtube ) : ?>
						<li><a href="<?php echo esc_url( $pettec_youtube ); ?>" target="_blank" rel="noopener noreferrer">YouTube &nearr;</a></li>
					<?php endif; ?>
					<?php if ( $pettec_github ) : ?>
						<li><a href="<?php echo esc_url( $pettec_github ); ?>" target="_blank" rel="noopener noreferrer">GitHub &nearr;</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>

		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> PETTEC — UNIFEI. Todos os direitos reservados.</span>
			<span><?php echo esc_html( $pettec_address ); ?></span>
			<span>Desenvolvido por <a href="https://github.com/Beto-Rivoli" target="_blank" rel="noopener noreferrer" style="color: var(--ink-light); font-weight: 600; text-decoration: underline; text-underline-offset: 3px;">Roberto Rivoli</a></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
