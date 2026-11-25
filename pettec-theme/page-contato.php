<?php
/**
 * Template Name: Contato PETTEC
 * Native CSRF + Honeypot Contact Page with UNIFEI Info & Map.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pettec_form_state = pettec_handle_contact_submission();
$pettec_email      = pettec_get_option( 'contact_email', 'pettec@unifei.edu.br' );
$pettec_address    = pettec_get_option( 'contact_address' );
$pettec_map_url    = pettec_clean_external_url( pettec_get_option( 'contact_map_embed' ) );

get_header();
?>
<section class="band band--dark band--hex-grid page-hero">
	<div class="container">
		<span class="eyebrow">Fale com o grupo</span>
		<h1 class="display-mixed display-mixed--xl">ENTRE EM <em>Contato</em></h1>
		<p class="lead" style="margin-top: 1rem;">
			Dúvidas sobre editais, mini cursos, parcerias ou projetos? Envie uma mensagem para a equipe do PETTEC — UNIFEI.
		</p>
	</div>
</section>

<section class="band band--light">
	<div class="container">
		<div class="grid grid--2" style="gap: 3rem; align-items: start;">
			<div style="background: var(--surface-white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-light);">
				<h2 class="display-mixed display-mixed--md" style="margin-bottom: 1.25rem;">ENVIE SUA <em>mensagem</em></h2>

				<?php if ( 'success' === $pettec_form_state['status'] ) : ?>
					<div class="alert alert--success" role="status"><?php echo esc_html( $pettec_form_state['message'] ); ?></div>
				<?php elseif ( 'error' === $pettec_form_state['status'] ) : ?>
					<div class="alert alert--error" role="alert"><?php echo esc_html( $pettec_form_state['message'] ); ?></div>
				<?php endif; ?>

				<form class="contact-form" method="post" action="">
					<?php wp_nonce_field( 'pettec_contact_action', 'pettec_contact_nonce' ); ?>

					<!-- Honeypot anti-spam -->
					<div class="sr-only" aria-hidden="true">
						<label for="pettec_website">Não preencha este campo</label>
						<input type="text" id="pettec_website" name="pettec_website" value="" tabindex="-1" autocomplete="off">
					</div>

					<div class="form-field">
						<label for="pettec_nome">Nome completo *</label>
						<input type="text" id="pettec_nome" name="pettec_nome" required value="<?php echo esc_attr( $pettec_form_state['values']['nome'] ); ?>" placeholder="Seu nome">
					</div>

					<div class="form-field">
						<label for="pettec_email">E-mail *</label>
						<input type="email" id="pettec_email" name="pettec_email" required value="<?php echo esc_attr( $pettec_form_state['values']['email'] ); ?>" placeholder="voce@exemplo.com">
					</div>

					<div class="form-field">
						<label for="pettec_assunto">Assunto *</label>
						<input type="text" id="pettec_assunto" name="pettec_assunto" required value="<?php echo esc_attr( $pettec_form_state['values']['assunto'] ); ?>" placeholder="Ex.: Processo seletivo, Mini curso, Parceria">
					</div>

					<div class="form-field">
						<label for="pettec_mensagem">Mensagem *</label>
						<textarea id="pettec_mensagem" name="pettec_mensagem" rows="5" required placeholder="Escreva sua mensagem..."><?php echo esc_textarea( $pettec_form_state['values']['mensagem'] ); ?></textarea>
					</div>

					<div>
						<button type="submit" name="pettec_contact_submit" value="1" class="btn-primary">Enviar mensagem &rarr;</button>
					</div>
				</form>
			</div>

			<div style="display: grid; gap: 1.5rem;">
				<div style="background: var(--surface-white); padding: 1.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-light);">
					<span class="eyebrow" style="margin-bottom: 0.5rem;">E-mail oficial</span>
					<p style="margin: 0; font-size: 1.15rem; font-weight: 700;">
						<a href="mailto:<?php echo esc_attr( $pettec_email ); ?>" style="color: var(--accent-red);"><?php echo esc_html( $pettec_email ); ?></a>
					</p>
				</div>

				<div style="background: var(--surface-white); padding: 1.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-light);">
					<span class="eyebrow" style="margin-bottom: 0.5rem;">Localização</span>
					<p class="muted" style="margin: 0;"><?php echo esc_html( $pettec_address ); ?></p>
				</div>

				<?php if ( $pettec_map_url ) : ?>
					<div style="border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-light); height: 300px;">
						<iframe
							src="<?php echo esc_url( $pettec_map_url ); ?>"
							title="Localização da UNIFEI"
							width="100%"
							height="100%"
							style="border: 0;"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade">
						</iframe>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
