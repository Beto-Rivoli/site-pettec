<?php
/**
 * Site Header — Floating 3-part navigation with PETTEC logo pill & Contato CTA.
 *
 * @package PETTEC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pettec_nav_items = array(
	array( 'label' => 'Início', 'url' => home_url( '/' ) ),
	array( 'label' => 'Atividades', 'url' => home_url( '/atividades/' ) ),
	array( 'label' => 'Mini Cursos', 'url' => home_url( '/mini-cursos/' ) ),
	array( 'label' => 'Listas', 'url' => home_url( '/listas-de-exercicio/' ) ),
	array( 'label' => 'Documentos', 'url' => home_url( '/documentos/' ) ),
	array( 'label' => 'Membros', 'url' => home_url( '/membros/' ) ),
	array( 'label' => 'Depoimentos', 'url' => home_url( '/depoimentos/' ) ),
	array( 'label' => 'Blog', 'url' => home_url( '/blog/' ) ),
);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Extrafazant-style Full-Screen Page Transition Curtain -->
<div id="pettec-page-transition" class="page-transition is-entering" aria-hidden="true">
	<div class="page-transition__brand">
		<img src="<?php echo esc_url( pettec_get_logo_url() ); ?>" alt="">
	</div>
</div>

<header class="site-header" role="banner">
	<div class="container header-inner">
		<a class="logo-pill" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'PETTEC UNIFEI — Página Inicial', 'pettec' ); ?>">
			<img src="<?php echo esc_url( pettec_get_logo_url() ); ?>" alt="PETTEC UNIFEI">
		</a>

		<nav class="nav-box" aria-label="<?php esc_attr_e( 'Navegação Principal', 'pettec' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav-list',
						'depth'          => 1,
					)
				);
			} else {
				echo '<ul class="nav-list">';
				foreach ( $pettec_nav_items as $item ) {
					echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a></li>';
				}
				echo '</ul>';
			}
			?>
		</nav>

		<div class="header-actions">
			<a class="btn-pill header-cta-desktop" href="<?php echo esc_url( home_url( '/contato/' ) ); ?>">
				<span class="dot" aria-hidden="true"></span>
				Contato
			</a>
			<button class="menu-toggle" type="button" aria-label="Abrir menu" aria-expanded="false" aria-controls="pettec-mobile-drawer">
				<span aria-hidden="true">&#9776;</span>
			</button>
		</div>
	</div>
</header>

<aside id="pettec-mobile-drawer" class="mobile-drawer" aria-hidden="true">
	<button class="drawer-close" type="button" aria-label="Fechar menu">&times;</button>
	<?php foreach ( $pettec_nav_items as $item ) : ?>
		<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
	<?php endforeach; ?>
	<a href="<?php echo esc_url( home_url( '/contato/' ) ); ?>" style="color: var(--accent-red);">Contato</a>
</aside>

<main id="main-content">
