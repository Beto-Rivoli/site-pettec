<?php
/**
 * PETTEC UNIFEI theme bootstrap, assets, menus, and legacy redirects.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PETTEC_THEME_VERSION', '1.3.3' );
define( 'PETTEC_THEME_DIR', get_template_directory() );
define( 'PETTEC_THEME_URI', get_template_directory_uri() );

/**
 * Load modular theme includes.
 */
$pettec_includes = array(
	'/inc/https-defense.php',
	'/inc/helpers.php',
	'/inc/cpts.php',
	'/inc/metaboxes.php',
	'/inc/customizer.php',
	'/inc/contact-form.php',
);

foreach ( $pettec_includes as $pettec_file ) {
	$pettec_path = PETTEC_THEME_DIR . $pettec_file;
	if ( file_exists( $pettec_path ) ) {
		require_once $pettec_path;
	}
}

/**
 * Theme supports and navigation menus.
 */
function pettec_theme_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu Principal (Cabeçalho)', 'pettec' ),
			'footer'  => __( 'Menu do Rodapé', 'pettec' ),
		)
	);
}
add_action( 'after_setup_theme', 'pettec_theme_setup' );

/**
 * Enqueue Google Fonts, modular CSS, and lightweight parallax/UI JavaScript.
 */
function pettec_enqueue_assets(): void {
	wp_enqueue_style(
		'pettec-google-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	$css_files = array(
		'pettec-tokens'     => '/assets/css/tokens.css',
		'pettec-base'       => '/assets/css/base.css',
		'pettec-layout'     => '/assets/css/layout.css',
		'pettec-components' => '/assets/css/components.css',
		'pettec-motion'     => '/assets/css/motion.css',
	);

	$prev_handle = 'pettec-google-fonts';
	foreach ( $css_files as $handle => $rel_path ) {
		if ( file_exists( PETTEC_THEME_DIR . $rel_path ) ) {
			wp_enqueue_style(
				$handle,
				PETTEC_THEME_URI . $rel_path,
				array( $prev_handle ),
				PETTEC_THEME_VERSION
			);
			$prev_handle = $handle;
		}
	}

	if ( file_exists( PETTEC_THEME_DIR . '/assets/js/main.js' ) ) {
		wp_enqueue_script(
			'pettec-main',
			PETTEC_THEME_URI . '/assets/js/main.js',
			array(),
			PETTEC_THEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'pettec_enqueue_assets' );

/**
 * Return the PETTEC logo URL (custom-logo if configured, otherwise bundled logo).
 *
 * @return string
 */
function pettec_get_logo_url(): string {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$logo_src = wp_get_attachment_image_url( $custom_logo_id, 'full' );
		if ( $logo_src ) {
			return (string) $logo_src;
		}
	}
	return PETTEC_THEME_URI . '/assets/images/logo-pettec.png';
}

/**
 * Redirect legacy broken /contact/ route to /contato/ with HTTP 301.
 */
function pettec_legacy_redirects(): void {
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
	$path        = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );

	if ( 'contact' === strtolower( $path ) ) {
		wp_safe_redirect( home_url( '/contato/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'pettec_legacy_redirects' );
