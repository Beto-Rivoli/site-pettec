<?php
/**
 * Pure helper functions for PETTEC UNIFEI theme.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Parse a YouTube video or playlist URL into a safe privacy-enhanced embed descriptor.
 *
 * Supports youtube.com/watch?v=, youtu.be/, youtube.com/embed/, youtube.com/shorts/,
 * and youtube.com/playlist?list=. Returns null for invalid or non-YouTube URLs.
 *
 * @param string $url Raw URL.
 * @return array{type: string, id: string, embed_url: string}|null
 */
function pettec_parse_youtube_url( string $url ): ?array {
	$url = trim( $url );
	if ( '' === $url ) {
		return null;
	}

	$parts = wp_parse_url( $url );
	if ( empty( $parts['host'] ) ) {
		return null;
	}

	$host = strtolower( preg_replace( '/^www\./', '', (string) $parts['host'] ) );
	$allowed_hosts = array( 'youtube.com', 'm.youtube.com', 'youtu.be', 'youtube-nocookie.com' );
	if ( ! in_array( $host, $allowed_hosts, true ) ) {
		return null;
	}

	$path  = isset( $parts['path'] ) ? trim( (string) $parts['path'], '/' ) : '';
	$query = array();
	if ( ! empty( $parts['query'] ) ) {
		parse_str( (string) $parts['query'], $query );
	}

	// Playlist URL: youtube.com/playlist?list=PL... or youtube.com/embed/videoseries?list=PL...
	if ( ( 'playlist' === $path || 'embed/videoseries' === $path ) && ! empty( $query['list'] ) && preg_match( '/^[a-zA-Z0-9_-]{10,64}$/', (string) $query['list'] ) ) {
		$list_id = (string) $query['list'];
		return array(
			'type'      => 'playlist',
			'id'        => $list_id,
			'embed_url' => 'https://www.youtube-nocookie.com/embed/videoseries?list=' . rawurlencode( $list_id ),
		);
	}

	$video_id = '';
	if ( 'youtu.be' === $host && '' !== $path ) {
		$video_id = explode( '/', $path )[0];
	} elseif ( ! empty( $query['v'] ) ) {
		$video_id = (string) $query['v'];
	} elseif ( preg_match( '#^(?:embed|shorts|live)/([a-zA-Z0-9_-]{11})#', $path, $matches ) ) {
		$video_id = $matches[1];
	}

	if ( ! preg_match( '/^[a-zA-Z0-9_-]{11}$/', $video_id ) ) {
		return null;
	}

	$embed = 'https://www.youtube-nocookie.com/embed/' . rawurlencode( $video_id );
	if ( ! empty( $query['list'] ) && preg_match( '/^[a-zA-Z0-9_-]{10,64}$/', (string) $query['list'] ) ) {
		$embed .= '?list=' . rawurlencode( (string) $query['list'] );
	}

	return array(
		'type'      => 'video',
		'id'        => $video_id,
		'embed_url' => $embed,
	);
}

/**
 * Clean an external profile/resource URL, rejecting empty strings, "#", and unsafe schemes.
 *
 * @param string $url Raw URL.
 * @return string Sanitized http/https URL or empty string.
 */
function pettec_clean_external_url( string $url ): string {
	$url = trim( $url );
	if ( '' === $url || '#' === $url ) {
		return '';
	}
	if ( ! preg_match( '#^https?://#i', $url ) ) {
		return '';
	}
	$clean = esc_url_raw( $url, array( 'http', 'https' ) );
	return is_string( $clean ) ? $clean : '';
}

/**
 * Estimate reading time in minutes (minimum 1 min) from HTML/plain content.
 *
 * @param string $content Post content.
 * @return int
 */
function pettec_estimate_reading_time( string $content ): int {
	$words = str_word_count( wp_strip_all_tags( $content ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Safely decode repeater meta stored as array or JSON string.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @return array<int, array<string, string>>
 */
function pettec_get_repeater_meta( int $post_id, string $meta_key ): array {
	$raw = get_post_meta( $post_id, $meta_key, true );
	if ( is_string( $raw ) && '' !== trim( $raw ) ) {
		$decoded = json_decode( $raw, true );
		if ( is_array( $decoded ) ) {
			return $decoded;
		}
	}
	return is_array( $raw ) ? $raw : array();
}

/**
 * Aggregate all Exercise Lists from Mini Cursos plus standalone Lista de Exercícios documents.
 *
 * @return array{cursos: array<int, array<string, mixed>>, avulsas: array<int, array<string, string>>}
 */
function pettec_get_all_exercise_lists(): array {
	$result = array(
		'cursos'  => array(),
		'avulsas' => array(),
	);

	// 1. Lists attached inside Mini Cursos.
	$courses = get_posts(
		array(
			'post_type'      => 'pettec_minicurso',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	foreach ( $courses as $course ) {
		$materials = pettec_get_repeater_meta( (int) $course->ID, '_pettec_materiais' );
		$lists     = array();
		foreach ( $materials as $item ) {
			$tipo = isset( $item['tipo'] ) ? (string) $item['tipo'] : '';
			$url  = isset( $item['url'] ) ? pettec_clean_external_url( (string) $item['url'] ) : '';
			if ( 'Lista de Exercícios' === $tipo && '' !== $url ) {
				$lists[] = array(
					'titulo' => ! empty( $item['titulo'] ) ? (string) $item['titulo'] : 'Lista de Exercícios',
					'url'    => $url,
				);
			}
		}

		if ( ! empty( $lists ) ) {
			$result['cursos'][] = array(
				'course_id'    => (int) $course->ID,
				'course_title' => get_the_title( $course ),
				'course_url'   => get_permalink( $course ),
				'listas'       => $lists,
			);
		}
	}

	// 2. Standalone exercise lists registered in Documentos under tipo 'lista-de-exercicios'.
	$standalone_docs = get_posts(
		array(
			'post_type'      => 'pettec_documento',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'tax_query'      => array(
				array(
					'taxonomy' => 'pettec_doc_tipo',
					'field'    => 'slug',
					'terms'    => array( 'lista-de-exercicios' ),
				),
			),
		)
	);

	foreach ( $standalone_docs as $doc ) {
		$doc_url = pettec_clean_external_url( (string) get_post_meta( (int) $doc->ID, '_pettec_doc_url', true ) );
		if ( '' !== $doc_url ) {
			$result['avulsas'][] = array(
				'titulo' => get_the_title( $doc ),
				'ano'    => (string) get_post_meta( (int) $doc->ID, '_pettec_doc_ano', true ),
				'resumo' => wp_strip_all_tags( (string) $doc->post_excerpt ),
				'url'    => $doc_url,
			);
		}
	}

	return $result;
}
