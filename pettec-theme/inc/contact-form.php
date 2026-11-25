<?php
/**
 * Native CSRF + Honeypot + Header-Injection-Safe Contact Form Handler.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Strip CR/LF control characters to prevent email header injection.
 *
 * @param string $value Raw string.
 * @return string
 */
function pettec_strip_header_newlines( string $value ): string {
	return trim( (string) preg_replace( '/[\r\n]+/', ' ', $value ) );
}

/**
 * Process POST contact submission safely.
 *
 * @return array{status: string, message: string, values: array<string, string>}
 */
function pettec_handle_contact_submission(): array {
	$values = array(
		'nome'     => '',
		'email'    => '',
		'assunto'  => '',
		'mensagem' => '',
	);

	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || ! isset( $_POST['pettec_contact_submit'] ) ) {
		return array( 'status' => 'idle', 'message' => '', 'values' => $values );
	}

	// Verify CSRF nonce.
	$nonce = isset( $_POST['pettec_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['pettec_contact_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'pettec_contact_action' ) ) {
		return array(
			'status'  => 'error',
			'message' => 'Sessão expirada ou requisição inválida. Recarregue a página e tente novamente.',
			'values'  => $values,
		);
	}

	// Honeypot check (bots fill hidden field).
	$honeypot = isset( $_POST['pettec_website'] ) ? trim( (string) wp_unslash( $_POST['pettec_website'] ) ) : '';
	if ( '' !== $honeypot ) {
		return array(
			'status'  => 'success',
			'message' => 'Mensagem enviada com sucesso! Em breve a equipe do PETTEC entrará em contato.',
			'values'  => $values,
		);
	}

	$values['nome']     = pettec_strip_header_newlines( sanitize_text_field( (string) wp_unslash( $_POST['pettec_nome'] ?? '' ) ) );
	$values['email']    = pettec_strip_header_newlines( sanitize_email( (string) wp_unslash( $_POST['pettec_email'] ?? '' ) ) );
	$values['assunto']  = pettec_strip_header_newlines( sanitize_text_field( (string) wp_unslash( $_POST['pettec_assunto'] ?? '' ) ) );
	$values['mensagem'] = sanitize_textarea_field( (string) wp_unslash( $_POST['pettec_mensagem'] ?? '' ) );

	if ( '' === $values['nome'] || '' === $values['assunto'] || '' === $values['mensagem'] ) {
		return array(
			'status'  => 'error',
			'message' => 'Por favor, preencha todos os campos obrigatórios.',
			'values'  => $values,
		);
	}

	if ( ! is_email( $values['email'] ) ) {
		return array(
			'status'  => 'error',
			'message' => 'Informe um endereço de e-mail válido.',
			'values'  => $values,
		);
	}

	$to      = sanitize_email( pettec_get_option( 'contact_email', 'pettec@unifei.edu.br' ) );
	$subject = '[Site PETTEC] ' . $values['assunto'];
	$body    = "Nova mensagem enviada pelo formulário do site PETTEC — UNIFEI:\n\n"
		. "Nome: {$values['nome']}\n"
		. "E-mail: {$values['email']}\n"
		. "Assunto: {$values['assunto']}\n\n"
		. "Mensagem:\n{$values['mensagem']}\n";
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $values['nome'] . ' <' . $values['email'] . '>',
	);

	$sent = wp_mail( $to, $subject, $body, $headers );
	if ( ! $sent ) {
		return array(
			'status'  => 'error',
			'message' => 'Não foi possível enviar automaticamente pelo servidor agora. Por favor, escreva diretamente para ' . $to . '.',
			'values'  => $values,
		);
	}

	return array(
		'status'  => 'success',
		'message' => 'Mensagem enviada com sucesso! Em breve a equipe do PETTEC entrará em contato.',
		'values'  => array( 'nome' => '', 'email' => '', 'assunto' => '', 'mensagem' => '' ),
	);
}
