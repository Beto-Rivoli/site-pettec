<?php
/**
 * WordPress Customizer settings for PETTEC UNIFEI.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default theme option values.
 *
 * @return array<string, string>
 */
function pettec_default_options(): array {
	return array(
		'hero_eyebrow'      => 'Programa de Educação Tutorial • UNIFEI',
		'hero_title_sans'   => 'CONSTRUINDO TECNOLOGIA',
		'hero_title_serif'  => '& Conhecimento',
		'hero_subtitle'     => 'Grupo PET focado em ensino, pesquisa e extensão na Universidade Federal de Itajubá — formando profissionais críticos através de projetos práticos, minicursos e inovação.',
		'metric_1_num'      => '15+',
		'metric_1_label'    => 'Anos de história',
		'metric_2_num'      => '30+',
		'metric_2_label'    => 'Mini cursos e oficinas',
		'metric_3_num'      => '17',
		'metric_3_label'    => 'Membros ativos',
		'metric_4_num'      => '100%',
		'metric_4_label'    => 'Ensino, Pesquisa e Extensão',
		'contact_email'     => 'pettec@unifei.edu.br',
		'contact_address'   => 'Av. B P S, 1303 - Bloco I2 - Sala I.2.1.06 - Pinheirinho, Itajubá - MG, 37500-015 (UNIFEI)',
		'contact_map_embed' => 'https://maps.google.com/maps?q=Iesti%2C%20unifei%2C%20itajuba&t=m&z=18&output=embed&iwloc=near',
		'social_instagram'  => 'https://www.instagram.com/pettec.unifei/',
		'social_linkedin'   => 'https://www.linkedin.com/company/pettec-unifei/',
		'social_youtube'    => 'https://www.youtube.com/@pettecunifei',
		'social_github'     => 'https://github.com/pettec-unifei',
	);
}

/**
 * Retrieve a PETTEC Customizer option with fallback to defaults.
 *
 * @param string $key     Option key.
 * @param string $default Optional override default.
 * @return string
 */
function pettec_get_option( string $key, string $default = '' ): string {
	$defaults = pettec_default_options();
	$fallback = '' !== $default ? $default : ( $defaults[ $key ] ?? '' );
	$value    = get_theme_mod( 'pettec_' . $key, $fallback );
	return is_string( $value ) && '' !== trim( $value ) ? $value : $fallback;
}

/**
 * Register PETTEC Customizer panel, sections, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function pettec_customize_register( WP_Customize_Manager $wp_customize ): void {
	$defaults = pettec_default_options();

	$wp_customize->add_panel(
		'pettec_panel',
		array(
			'title'    => __( 'PETTEC — Configurações do Site', 'pettec' ),
			'priority' => 25,
		)
	);

	// Section: Hero.
	$wp_customize->add_section(
		'pettec_hero_section',
		array(
			'title' => __( 'Início (Hero e Métricas)', 'pettec' ),
			'panel' => 'pettec_panel',
		)
	);

	$hero_fields = array(
		'hero_eyebrow'     => array( 'Texto superior (eyebrow)', 'text' ),
		'hero_title_sans'  => array( 'Título principal (caixa alta)', 'text' ),
		'hero_title_serif' => array( 'Título destaque (itálico serifado)', 'text' ),
		'hero_subtitle'    => array( 'Subtítulo institucional', 'textarea' ),
		'metric_1_num'     => array( 'Métrica 1 — Número', 'text' ),
		'metric_1_label'   => array( 'Métrica 1 — Legenda', 'text' ),
		'metric_2_num'     => array( 'Métrica 2 — Número', 'text' ),
		'metric_2_label'   => array( 'Métrica 2 — Legenda', 'text' ),
		'metric_3_num'     => array( 'Métrica 3 — Número', 'text' ),
		'metric_3_label'   => array( 'Métrica 3 — Legenda', 'text' ),
		'metric_4_num'     => array( 'Métrica 4 — Número', 'text' ),
		'metric_4_label'   => array( 'Métrica 4 — Legenda', 'text' ),
	);

	foreach ( $hero_fields as $key => $meta ) {
		$wp_customize->add_setting(
			'pettec_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'textarea' === $meta[1] ? 'sanitize_textarea_field' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'pettec_' . $key,
			array(
				'label'   => $meta[0],
				'section' => 'pettec_hero_section',
				'type'    => $meta[1],
			)
		);
	}

	// Section: Contato e Redes Sociais.
	$wp_customize->add_section(
		'pettec_contact_section',
		array(
			'title' => __( 'Contato, Mapa e Redes Sociais', 'pettec' ),
			'panel' => 'pettec_panel',
		)
	);

	$contact_fields = array(
		'contact_email'     => array( 'E-mail oficial (destino do formulário)', 'email', 'sanitize_email' ),
		'contact_address'   => array( 'Endereço institucional UNIFEI', 'textarea', 'sanitize_textarea_field' ),
		'contact_map_embed' => array( 'URL do iframe Google Maps', 'url', 'esc_url_raw' ),
		'social_instagram'  => array( 'Instagram (URL)', 'url', 'esc_url_raw' ),
		'social_linkedin'   => array( 'LinkedIn (URL)', 'url', 'esc_url_raw' ),
		'social_youtube'    => array( 'YouTube (URL)', 'url', 'esc_url_raw' ),
		'social_github'     => array( 'GitHub (URL)', 'url', 'esc_url_raw' ),
	);

	foreach ( $contact_fields as $key => $meta ) {
		$wp_customize->add_setting(
			'pettec_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => $meta[2],
			)
		);
		$wp_customize->add_control(
			'pettec_' . $key,
			array(
				'label'   => $meta[0],
				'section' => 'pettec_contact_section',
				'type'    => $meta[1],
			)
		);
	}
}
add_action( 'customize_register', 'pettec_customize_register' );
