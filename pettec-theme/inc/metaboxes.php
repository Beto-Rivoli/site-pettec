<?php
/**
 * Native WordPress metaboxes (including repeatable lessons & materials) for PETTEC CPTs.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue WP Media Library and repeater JS only on PETTEC CPT admin screens.
 *
 * @param string $hook Current admin hook.
 */
function pettec_admin_metabox_assets( string $hook ): void {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'pettec_minicurso', 'pettec_documento' ), true ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'pettec-admin-repeaters',
		PETTEC_THEME_URI . '/assets/js/admin-repeaters.js',
		array( 'jquery' ),
		PETTEC_THEME_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'pettec_admin_metabox_assets' );

/**
 * Register metaboxes for all 4 CPTs.
 */
function pettec_add_metaboxes(): void {
	add_meta_box( 'pettec_atividade_meta', 'Detalhes da Atividade', 'pettec_render_atividade_metabox', 'pettec_atividade', 'normal', 'high' );
	add_meta_box( 'pettec_minicurso_meta', 'Aulas (YouTube), Materiais e Listas de Exercícios', 'pettec_render_minicurso_metabox', 'pettec_minicurso', 'normal', 'high' );
	add_meta_box( 'pettec_documento_meta', 'Arquivo PDF e Metadados do Documento', 'pettec_render_documento_metabox', 'pettec_documento', 'normal', 'high' );
	add_meta_box( 'pettec_membro_meta', 'Informações do Membro / Ex-membro', 'pettec_render_membro_metabox', 'pettec_membro', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'pettec_add_metaboxes' );

function pettec_render_atividade_metabox( WP_Post $post ): void {
	wp_nonce_field( 'pettec_save_meta', 'pettec_meta_nonce' );
	$periodo = (string) get_post_meta( $post->ID, '_pettec_periodo', true );
	$status  = (string) get_post_meta( $post->ID, '_pettec_status', true );
	$link    = (string) get_post_meta( $post->ID, '_pettec_link_externo', true );
	?>
	<p>
		<label><strong>Período / Ano:</strong></label><br>
		<input type="text" name="_pettec_periodo" value="<?php echo esc_attr( $periodo ); ?>" class="widefat" placeholder="Ex.: 2025/2026 ou Contínuo">
	</p>
	<p>
		<label><strong>Status:</strong></label><br>
		<select name="_pettec_status" class="widefat">
			<option value="Em andamento" <?php selected( $status, 'Em andamento' ); ?>>Em andamento</option>
			<option value="Concluída" <?php selected( $status, 'Concluída' ); ?>>Concluída</option>
		</select>
	</p>
	<p>
		<label><strong>Link externo (opcional — inscrição, repositório ou relatório):</strong></label><br>
		<input type="url" name="_pettec_link_externo" value="<?php echo esc_attr( $link ); ?>" class="widefat" placeholder="https://...">
	</p>
	<?php
}

function pettec_render_minicurso_metabox( WP_Post $post ): void {
	wp_nonce_field( 'pettec_save_meta', 'pettec_meta_nonce' );
	$instrutor = (string) get_post_meta( $post->ID, '_pettec_instrutor', true );
	$carga     = (string) get_post_meta( $post->ID, '_pettec_carga_horaria', true );
	$aulas     = pettec_get_repeater_meta( $post->ID, '_pettec_aulas' );
	$materiais = pettec_get_repeater_meta( $post->ID, '_pettec_materiais' );
	?>
	<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
		<div>
			<label><strong>Instrutor(es):</strong></label><br>
			<input type="text" name="_pettec_instrutor" value="<?php echo esc_attr( $instrutor ); ?>" class="widefat" placeholder="Ex.: Equipe PETTEC / Prof. Fulano">
		</div>
		<div>
			<label><strong>Carga horária:</strong></label><br>
			<input type="text" name="_pettec_carga_horaria" value="<?php echo esc_attr( $carga ); ?>" class="widefat" placeholder="Ex.: 8 horas">
		</div>
	</div>

	<hr>
	<h4>Aulas / Vídeos do YouTube</h4>
	<p class="description">Adicione quantas aulas ou playlists do YouTube este mini curso tiver.</p>
	<div class="pettec-repeater" data-repeater="aulas">
		<div class="pettec-repeater-rows">
			<?php foreach ( $aulas as $aula ) : ?>
				<div class="pettec-repeater-row" style="display:flex;gap:8px;margin-bottom:8px;">
					<input type="text" name="pettec_aulas_titulo[]" value="<?php echo esc_attr( $aula['titulo'] ?? '' ); ?>" placeholder="Título da aula (ex.: Aula 01 - Introdução)" style="flex:1;">
					<input type="url" name="pettec_aulas_url[]" value="<?php echo esc_attr( $aula['url'] ?? '' ); ?>" placeholder="URL do YouTube (vídeo ou playlist)" style="flex:1.5;">
					<button type="button" class="button pettec-remove-row">Remover</button>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-secondary pettec-add-row" data-template="aula">+ Adicionar Aula / Vídeo</button>
	</div>

	<hr style="margin:18px 0;">
	<h4>Arquivos e Listas de Exercícios</h4>
	<p class="description">Itens marcados como <strong>Lista de Exercícios</strong> aparecem automaticamente na página <code>/listas-de-exercicio/</code>.</p>
	<div class="pettec-repeater" data-repeater="materiais">
		<div class="pettec-repeater-rows">
			<?php foreach ( $materiais as $mat ) : ?>
				<div class="pettec-repeater-row" style="display:flex;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
					<input type="text" name="pettec_materiais_titulo[]" value="<?php echo esc_attr( $mat['titulo'] ?? '' ); ?>" placeholder="Título (ex.: Lista 01 - Ponteiros)" style="flex:1.2;">
					<select name="pettec_materiais_tipo[]" style="flex:0.8;">
						<?php foreach ( array( 'Lista de Exercícios', 'Slide', 'Apostila', 'Código' ) as $opt ) : ?>
							<option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $mat['tipo'] ?? '', $opt ); ?>><?php echo esc_html( $opt ); ?></option>
						<?php endforeach; ?>
					</select>
					<input type="url" name="pettec_materiais_url[]" value="<?php echo esc_attr( $mat['url'] ?? '' ); ?>" placeholder="URL do PDF ou link" class="pettec-media-input" style="flex:1.4;">
					<button type="button" class="button pettec-media-upload">Enviar PDF</button>
					<button type="button" class="button pettec-remove-row">Remover</button>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-secondary pettec-add-row" data-template="material">+ Adicionar Arquivo / Lista</button>
	</div>
	<?php
}

function pettec_render_documento_metabox( WP_Post $post ): void {
	wp_nonce_field( 'pettec_save_meta', 'pettec_meta_nonce' );
	$url    = (string) get_post_meta( $post->ID, '_pettec_doc_url', true );
	$ano    = (string) get_post_meta( $post->ID, '_pettec_doc_ano', true );
	$codigo = (string) get_post_meta( $post->ID, '_pettec_doc_codigo', true );
	?>
	<p>
		<label><strong>Arquivo PDF / Link do Documento:</strong></label><br>
		<span style="display:flex;gap:8px;">
			<input type="url" name="_pettec_doc_url" value="<?php echo esc_attr( $url ); ?>" class="widefat pettec-media-input" placeholder="https://...">
			<button type="button" class="button button-secondary pettec-media-upload">Selecionar PDF</button>
		</span>
	</p>
	<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
		<div>
			<label><strong>Ano de referência:</strong></label><br>
			<input type="text" name="_pettec_doc_ano" value="<?php echo esc_attr( $ano ? $ano : wp_date( 'Y' ) ); ?>" class="widefat" placeholder="Ex.: 2026">
		</div>
		<div>
			<label><strong>Código / Número (opcional):</strong></label><br>
			<input type="text" name="_pettec_doc_codigo" value="<?php echo esc_attr( $codigo ); ?>" class="widefat" placeholder="Ex.: Edital nº 01/2026">
		</div>
	</div>
	<?php
}

function pettec_render_membro_metabox( WP_Post $post ): void {
	wp_nonce_field( 'pettec_save_meta', 'pettec_meta_nonce' );
	$papel    = (string) get_post_meta( $post->ID, '_pettec_membro_papel', true );
	$curso    = (string) get_post_meta( $post->ID, '_pettec_membro_curso', true );
	$periodo  = (string) get_post_meta( $post->ID, '_pettec_membro_periodo', true );
	$linkedin = (string) get_post_meta( $post->ID, '_pettec_membro_linkedin', true );
	$lattes   = (string) get_post_meta( $post->ID, '_pettec_membro_lattes', true );
	$github   = (string) get_post_meta( $post->ID, '_pettec_membro_github', true );
	?>
	<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
		<div>
			<label><strong>Categoria:</strong></label><br>
			<select name="_pettec_membro_papel" class="widefat">
				<option value="membro" <?php selected( $papel ? $papel : 'membro', 'membro' ); ?>>Membro Atual</option>
				<option value="tutor" <?php selected( $papel, 'tutor' ); ?>>Tutor(a)</option>
				<option value="ex-membro" <?php selected( $papel, 'ex-membro' ); ?>>Ex-membro (Egresso)</option>
			</select>
		</div>
		<div>
			<label><strong>Curso / Cargo na UNIFEI:</strong></label><br>
			<input type="text" name="_pettec_membro_curso" value="<?php echo esc_attr( $curso ); ?>" class="widefat" placeholder="Ex.: Engenharia de Computação">
		</div>
		<div>
			<label><strong>Período no PETTEC:</strong></label><br>
			<input type="text" name="_pettec_membro_periodo" value="<?php echo esc_attr( $periodo ); ?>" class="widefat" placeholder="Ex.: 2024 – Atual">
		</div>
	</div>
	<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-top:12px;">
		<div>
			<label><strong>LinkedIn (URL completa):</strong></label><br>
			<input type="url" name="_pettec_membro_linkedin" value="<?php echo esc_attr( $linkedin ); ?>" class="widefat" placeholder="https://linkedin.com/in/...">
		</div>
		<div>
			<label><strong>Currículo Lattes (URL):</strong></label><br>
			<input type="url" name="_pettec_membro_lattes" value="<?php echo esc_attr( $lattes ); ?>" class="widefat" placeholder="http://lattes.cnpq.br/...">
		</div>
		<div>
			<label><strong>GitHub (URL):</strong></label><br>
			<input type="url" name="_pettec_membro_github" value="<?php echo esc_attr( $github ); ?>" class="widefat" placeholder="https://github.com/...">
		</div>
	</div>
	<?php
}

/**
 * Save handler for all PETTEC metaboxes with nonce, autosave, and capability checks.
 *
 * @param int $post_id Post ID.
 */
function pettec_save_metaboxes( int $post_id ): void {
	if ( ! isset( $_POST['pettec_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pettec_meta_nonce'] ) ), 'pettec_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );

	if ( 'pettec_atividade' === $post_type ) {
		update_post_meta( $post_id, '_pettec_periodo', sanitize_text_field( wp_unslash( $_POST['_pettec_periodo'] ?? '' ) ) );
		update_post_meta( $post_id, '_pettec_status', sanitize_text_field( wp_unslash( $_POST['_pettec_status'] ?? 'Em andamento' ) ) );
		update_post_meta( $post_id, '_pettec_link_externo', pettec_clean_external_url( (string) wp_unslash( $_POST['_pettec_link_externo'] ?? '' ) ) );
	}

	if ( 'pettec_minicurso' === $post_type ) {
		update_post_meta( $post_id, '_pettec_instrutor', sanitize_text_field( wp_unslash( $_POST['_pettec_instrutor'] ?? '' ) ) );
		update_post_meta( $post_id, '_pettec_carga_horaria', sanitize_text_field( wp_unslash( $_POST['_pettec_carga_horaria'] ?? '' ) ) );

		$aulas       = array();
		$aula_titles = isset( $_POST['pettec_aulas_titulo'] ) && is_array( $_POST['pettec_aulas_titulo'] ) ? wp_unslash( $_POST['pettec_aulas_titulo'] ) : array();
		$aula_urls   = isset( $_POST['pettec_aulas_url'] ) && is_array( $_POST['pettec_aulas_url'] ) ? wp_unslash( $_POST['pettec_aulas_url'] ) : array();
		$count       = max( count( $aula_titles ), count( $aula_urls ) );
		for ( $i = 0; $i < $count; $i++ ) {
			$t = sanitize_text_field( (string) ( $aula_titles[ $i ] ?? '' ) );
			$u = pettec_clean_external_url( (string) ( $aula_urls[ $i ] ?? '' ) );
			if ( '' !== $t || '' !== $u ) {
				$aulas[] = array( 'titulo' => $t, 'url' => $u );
			}
		}
		update_post_meta( $post_id, '_pettec_aulas', wp_slash( wp_json_encode( $aulas, JSON_UNESCAPED_UNICODE ) ) );

		$materiais   = array();
		$mat_titles  = isset( $_POST['pettec_materiais_titulo'] ) && is_array( $_POST['pettec_materiais_titulo'] ) ? wp_unslash( $_POST['pettec_materiais_titulo'] ) : array();
		$mat_tipos   = isset( $_POST['pettec_materiais_tipo'] ) && is_array( $_POST['pettec_materiais_tipo'] ) ? wp_unslash( $_POST['pettec_materiais_tipo'] ) : array();
		$mat_urls    = isset( $_POST['pettec_materiais_url'] ) && is_array( $_POST['pettec_materiais_url'] ) ? wp_unslash( $_POST['pettec_materiais_url'] ) : array();
		$mcount      = max( count( $mat_titles ), count( $mat_urls ) );
		$valid_tipos = array( 'Lista de Exercícios', 'Slide', 'Apostila', 'Código' );
		for ( $i = 0; $i < $mcount; $i++ ) {
			$t    = sanitize_text_field( (string) ( $mat_titles[ $i ] ?? '' ) );
			$tipo = sanitize_text_field( (string) ( $mat_tipos[ $i ] ?? 'Lista de Exercícios' ) );
			$tipo = in_array( $tipo, $valid_tipos, true ) ? $tipo : 'Lista de Exercícios';
			$u    = pettec_clean_external_url( (string) ( $mat_urls[ $i ] ?? '' ) );
			if ( '' !== $t || '' !== $u ) {
				$materiais[] = array( 'titulo' => $t, 'tipo' => $tipo, 'url' => $u );
			}
		}
		update_post_meta( $post_id, '_pettec_materiais', wp_slash( wp_json_encode( $materiais, JSON_UNESCAPED_UNICODE ) ) );
	}

	if ( 'pettec_documento' === $post_type ) {
		update_post_meta( $post_id, '_pettec_doc_url', pettec_clean_external_url( (string) wp_unslash( $_POST['_pettec_doc_url'] ?? '' ) ) );
		update_post_meta( $post_id, '_pettec_doc_ano', sanitize_text_field( wp_unslash( $_POST['_pettec_doc_ano'] ?? '' ) ) );
		update_post_meta( $post_id, '_pettec_doc_codigo', sanitize_text_field( wp_unslash( $_POST['_pettec_doc_codigo'] ?? '' ) ) );
	}

	if ( 'pettec_membro' === $post_type ) {
		$papel = sanitize_text_field( wp_unslash( $_POST['_pettec_membro_papel'] ?? 'membro' ) );
		if ( ! in_array( $papel, array( 'tutor', 'membro', 'ex-membro' ), true ) ) {
			$papel = 'membro';
		}
		update_post_meta( $post_id, '_pettec_membro_papel', $papel );
		update_post_meta( $post_id, '_pettec_membro_curso', sanitize_text_field( wp_unslash( $_POST['_pettec_membro_curso'] ?? '' ) ) );
		update_post_meta( $post_id, '_pettec_membro_periodo', sanitize_text_field( wp_unslash( $_POST['_pettec_membro_periodo'] ?? '' ) ) );
		update_post_meta( $post_id, '_pettec_membro_linkedin', pettec_clean_external_url( (string) wp_unslash( $_POST['_pettec_membro_linkedin'] ?? '' ) ) );
		update_post_meta( $post_id, '_pettec_membro_lattes', pettec_clean_external_url( (string) wp_unslash( $_POST['_pettec_membro_lattes'] ?? '' ) ) );
		update_post_meta( $post_id, '_pettec_membro_github', pettec_clean_external_url( (string) wp_unslash( $_POST['_pettec_membro_github'] ?? '' ) ) );
	}
}
add_action( 'save_post', 'pettec_save_metaboxes' );
