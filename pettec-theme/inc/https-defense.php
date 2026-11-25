<?php
/**
 * Defensive HTTPS asset URL normalization for reverse-proxy university hosting.
 *
 * Ensures theme CSS, JS, and uploaded media URLs are served over HTTPS when
 * WordPress sits behind an SSL-terminating proxy (e.g., UNIFEI infrastructure)
 * while preserving HTTP on local development hosts (127.0.0.1 / localhost).
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the current request should force HTTPS for same-host assets.
 *
 * @param array  $server   Server superglobal array.
 * @param string $site_url Configured WordPress site URL.
 * @return bool
 */
function pettec_is_https_request( array $server, string $site_url ): bool {
	$host = '';
	if ( ! empty( $server['HTTP_HOST'] ) ) {
		$host = strtolower( (string) $server['HTTP_HOST'] );
	} elseif ( $site_url ) {
		$parsed_host = wp_parse_url( $site_url, PHP_URL_HOST );
		$host        = $parsed_host ? strtolower( (string) $parsed_host ) : '';
	}

	// Never force HTTPS on local development environments.
	if ( preg_match( '/^(localhost|127\.0\.0\.1)(:\d+)?$/', $host ) ) {
		return false;
	}

	if ( ! empty( $server['HTTPS'] ) && 'off' !== strtolower( (string) $server['HTTPS'] ) ) {
		return true;
	}

	if ( isset( $server['SERVER_PORT'] ) && '443' === (string) $server['SERVER_PORT'] ) {
		return true;
	}

	if ( ! empty( $server['HTTP_X_FORWARDED_PROTO'] ) ) {
		$proto = strtolower( trim( explode( ',', (string) $server['HTTP_X_FORWARDED_PROTO'] )[0] ) );
		if ( 'https' === $proto ) {
			return true;
		}
	}

	if ( 0 === strpos( strtolower( $site_url ), 'https://' ) ) {
		return true;
	}

	return false;
}

/**
 * Upgrade same-host http:// asset URLs to https:// when HTTPS is active.
 *
 * @param string $url         Original asset URL.
 * @param bool   $force_https Whether HTTPS should be enforced.
 * @return string
 */
function pettec_normalize_asset_url( string $url, bool $force_https ): string {
	if ( ! $force_https || '' === $url || 0 !== strpos( $url, 'http://' ) ) {
		return $url;
	}

	$asset_host = wp_parse_url( $url, PHP_URL_HOST );
	$site_host  = wp_parse_url( home_url(), PHP_URL_HOST );

	if ( $asset_host && $site_host && strtolower( $asset_host ) === strtolower( $site_host ) ) {
		return 'https://' . substr( $url, 7 );
	}

	return $url;
}

/**
 * Filter callback for WordPress style, script, and media attachment URLs.
 *
 * @param string $url Asset URL.
 * @return string
 */
function pettec_filter_https_url( $url ) {
	if ( ! is_string( $url ) ) {
		return $url;
	}
	$force = pettec_is_https_request( $_SERVER, (string) home_url() );
	return pettec_normalize_asset_url( $url, $force );
}

add_filter( 'style_loader_src', 'pettec_filter_https_url', 99 );
add_filter( 'script_loader_src', 'pettec_filter_https_url', 99 );
add_filter( 'wp_get_attachment_url', 'pettec_filter_https_url', 99 );
