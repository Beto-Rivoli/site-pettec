<?php
/**
 * Custom Post Types and Taxonomies for PETTEC UNIFEI.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register institutional CPTs and taxonomies.
 */
function pettec_register_cpts_and_taxonomies(): void {
	// 1. Atividades.
	register_post_type(
		'pettec_atividade',
		array(
			'labels'       => array(
				'name'          => 'Atividades',
				'singular_name' => 'Atividade',
				'add_new_item'  => 'Adicionar Nova Atividade',
				'edit_item'     => 'Editar Atividade',
				'all_items'     => 'Todas as Atividades',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'atividades', 'with_front' => false ),
			'menu_icon'    => 'dashicons-hammer',
			'menu_position'=> 5,
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);

	register_taxonomy(
		'pettec_pilar',
		array( 'pettec_atividade' ),
		array(
			'labels'       => array(
				'name'          => 'Pilares (Ensino/Pesquisa/Extensão)',
				'singular_name' => 'Pilar',
			),
			'public'       => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'pilar' ),
		)
	);

	// 2. Mini Cursos.
	register_post_type(
		'pettec_minicurso',
		array(
			'labels'       => array(
				'name'          => 'Mini Cursos',
				'singular_name' => 'Mini Curso',
				'add_new_item'  => 'Adicionar Novo Mini Curso',
				'edit_item'     => 'Editar Mini Curso',
				'all_items'     => 'Todos os Mini Cursos',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'mini-cursos', 'with_front' => false ),
			'menu_icon'    => 'dashicons-welcome-learn-more',
			'menu_position'=> 6,
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);

	register_taxonomy(
		'pettec_curso_area',
		array( 'pettec_minicurso' ),
		array(
			'labels'       => array(
				'name'          => 'Áreas dos Mini Cursos',
				'singular_name' => 'Área',
			),
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'area-curso' ),
		)
	);

	// 3. Documentos e Editais.
	register_post_type(
		'pettec_documento',
		array(
			'labels'       => array(
				'name'          => 'Documentos e Editais',
				'singular_name' => 'Documento',
				'add_new_item'  => 'Adicionar Novo Documento',
				'edit_item'     => 'Editar Documento',
				'all_items'     => 'Todos os Documentos',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'documentos', 'with_front' => false ),
			'menu_icon'    => 'dashicons-media-document',
			'menu_position'=> 7,
			'show_in_rest' => true,
			'supports'     => array( 'title', 'excerpt' ),
		)
	);

	register_taxonomy(
		'pettec_doc_tipo',
		array( 'pettec_documento' ),
		array(
			'labels'       => array(
				'name'          => 'Tipos de Documento',
				'singular_name' => 'Tipo de Documento',
			),
			'public'       => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'tipo-documento' ),
		)
	);

	// 4. Membros e Ex-membros.
	register_post_type(
		'pettec_membro',
		array(
			'labels'       => array(
				'name'          => 'Membros e Ex-membros',
				'singular_name' => 'Membro',
				'add_new_item'  => 'Adicionar Novo Membro',
				'edit_item'     => 'Editar Membro',
				'all_items'     => 'Todos os Membros',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'membros', 'with_front' => false ),
			'menu_icon'    => 'dashicons-groups',
			'menu_position'=> 8,
			'show_in_rest' => true,
			'supports'     => array( 'title', 'thumbnail', 'excerpt' ),
		)
	);

	// 5. Depoimentos (Falas dos antigos petianos).
	register_post_type(
		'pettec_depoimento',
		array(
			'labels'       => array(
				'name'          => 'Depoimentos',
				'singular_name' => 'Depoimento',
				'add_new_item'  => 'Adicionar Novo Depoimento',
				'edit_item'     => 'Editar Depoimento',
				'all_items'     => 'Todos os Depoimentos',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'depoimentos', 'with_front' => false ),
			'menu_icon'    => 'dashicons-format-quote',
			'menu_position'=> 9,
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
		)
	);
}
add_action( 'init', 'pettec_register_cpts_and_taxonomies' );

/**
 * Seed default taxonomy terms and flush rewrite rules on theme activation.
 */
function pettec_seed_default_terms(): void {
	pettec_register_cpts_and_taxonomies();

	$pilares = array(
		'ensino'   => 'Ensino',
		'pesquisa' => 'Pesquisa',
		'extensao' => 'Extensão',
	);
	foreach ( $pilares as $slug => $name ) {
		if ( ! term_exists( $slug, 'pettec_pilar' ) ) {
			wp_insert_term( $name, 'pettec_pilar', array( 'slug' => $slug ) );
		}
	}

	$doc_tipos = array(
		'edital'              => 'Edital',
		'planejamento'        => 'Planejamento',
		'relatorio'           => 'Relatório',
		'lista-de-exercicios' => 'Lista de Exercícios',
		'outros'              => 'Outros',
	);
	foreach ( $doc_tipos as $slug => $name ) {
		if ( ! term_exists( $slug, 'pettec_doc_tipo' ) ) {
			wp_insert_term( $name, 'pettec_doc_tipo', array( 'slug' => $slug ) );
		}
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'pettec_seed_default_terms' );
