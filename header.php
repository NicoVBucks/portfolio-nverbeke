<?php
/**
 * En-tete du document.
 *
 * @package nverbeke
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip" href="#contenu"><?php esc_html_e( 'Aller au contenu', 'nverbeke' ); ?></a>

<header class="entete">
	<div class="wrap entete__inner">
		<p class="entete__nom">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		</p>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'nverbeke' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'principal',
					'container'      => false,
					'menu_class'     => 'nav__liste',
					'depth'          => 1,
					'fallback_cb'    => 'nv_menu_fallback',
				)
			);
			?>
		</nav>
	</div>
</header>

<main id="contenu">
