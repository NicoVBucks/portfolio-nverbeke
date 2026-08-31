<?php
/**
 * Fonctions du theme.
 *
 * @package nverbeke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NV_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/cpt-projet.php';
require_once get_template_directory() . '/inc/meta-boxes.php';

/**
 * Declaration des fonctionnalites supportees par le theme.
 */
function nv_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	// Format des visuels de projet : 16/10, recadrage force pour un rythme regulier.
	add_image_size( 'nv_projet', 1600, 1000, true );

	register_nav_menus(
		array( 'principal' => __( 'Navigation principale', 'nverbeke' ) )
	);

	load_theme_textdomain( 'nverbeke', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'nv_setup' );

/**
 * Chargement des styles. Le theme ne charge aucun JavaScript.
 */
function nv_assets() {
	wp_enqueue_style( 'nv-style', get_stylesheet_uri(), array(), NV_VERSION );
}
add_action( 'wp_enqueue_scripts', 'nv_assets' );

/**
 * Precharge la police des titres : elle est utilisee des le premier ecran.
 */
function nv_preload_fonts() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_template_directory_uri() . '/assets/fonts/fraunces-variable-latin.woff2' )
	);
}
add_action( 'wp_head', 'nv_preload_fonts', 1 );

/**
 * WordPress injecte par defaut un script de compatibilite emoji sur chaque page.
 * On le retire pour tenir la promesse "aucun JavaScript" du theme.
 */
function nv_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'nv_disable_emojis' );

/**
 * Menu de repli : si aucun menu n'est assigne dans l'admin, on affiche
 * les ancres de la page d'accueil pour que la navigation fonctionne toujours.
 */
function nv_menu_fallback() {
	$accueil = home_url( '/' );
	$liens   = array(
		'#projets'     => __( 'Projets', 'nverbeke' ),
		'#parcours'    => __( 'Parcours', 'nverbeke' ),
		'#competences' => __( 'Compétences', 'nverbeke' ),
		'#contact'     => __( 'Contact', 'nverbeke' ),
	);

	echo '<ul class="nav__liste">';
	foreach ( $liens as $ancre => $libelle ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( is_front_page() ? $ancre : $accueil . $ancre ),
			esc_html( $libelle )
		);
	}
	echo '</ul>';
}

/**
 * Retourne les technologies d'un projet sous forme de liste de badges.
 *
 * @param int $post_id Identifiant du projet.
 */
function nv_liste_technologies( $post_id ) {
	$termes = get_the_terms( $post_id, 'technologie' );

	if ( empty( $termes ) || is_wp_error( $termes ) ) {
		return;
	}

	echo '<ul class="techs">';
	foreach ( $termes as $terme ) {
		printf(
			'<li class="tech"><a href="%s">%s</a></li>',
			esc_url( get_term_link( $terme ) ),
			esc_html( $terme->name )
		);
	}
	echo '</ul>';
}

/**
 * Affiche les liens externes d'un projet (code, site en ligne).
 * Un lien vide n'affiche rien : pas de bouton inactif.
 *
 * @param int $post_id Identifiant du projet.
 */
function nv_liens_projet( $post_id ) {
	$liens = array(
		'lien_site' => __( 'Ouvrir le site', 'nverbeke' ),
		'lien_code' => __( 'Voir le code', 'nverbeke' ),
	);

	$sortie = '';
	foreach ( $liens as $cle => $libelle ) {
		$url = get_post_meta( $post_id, $cle, true );

		if ( ! $url ) {
			continue;
		}

		$sortie .= sprintf(
			'<a class="lien-ext" href="%s" target="_blank" rel="noopener noreferrer">%s<span class="sr-only"> %s</span></a>',
			esc_url( $url ),
			esc_html( $libelle ),
			esc_html__( '(nouvelle fenêtre)', 'nverbeke' )
		);
	}

	if ( $sortie ) {
		echo '<p class="actions">' . $sortie . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput -- contenu deja echappe ci-dessus.
	}
}

/**
 * Description de la page courante, pour la meta description et Open Graph.
 *
 * WordPress ne genere aucune meta description nativement : sans elle, un
 * moteur de recherche fabrique lui-meme un extrait dans la page.
 *
 * @return string Description en texte brut, tronquee a 160 caracteres.
 */
function nv_description_page() {
	if ( is_singular() ) {
		$texte = get_the_excerpt();
	} elseif ( is_tax( 'technologie' ) ) {
		$terme = get_queried_object();
		/* translators: %s : nom d'une technologie. */
		$texte = sprintf( __( 'Projets réalisés avec %s.', 'nverbeke' ), $terme->name );
	} elseif ( is_post_type_archive( 'projet' ) ) {
		$texte = __( 'Les projets de Nicolas Verbeke, développeur web.', 'nverbeke' );
	} else {
		// Le slogan se renseigne dans Reglages > General. Sans lui, ce repli.
		$texte = get_bloginfo( 'description' );

		if ( ! $texte ) {
			$texte = __( 'Portfolio de Nicolas Verbeke, développeur web : thèmes WordPress sur-mesure, intégration HTML et CSS, JavaScript sans framework.', 'nverbeke' );
		}
	}

	return wp_html_excerpt( wp_strip_all_tags( $texte ), 160, '…' );
}

/**
 * Meta description, Open Graph et carte Twitter.
 *
 * Sans ces balises, un lien vers le site partage sur LinkedIn ou dans une
 * messagerie s'affiche nu : ni titre, ni resume, ni visuel.
 */
function nv_meta_sociales() {
	$description = nv_description_page();

	if ( is_singular() ) {
		$url   = get_permalink();
		$titre = get_the_title();
		$type  = 'article';
	} elseif ( is_tax( 'technologie' ) ) {
		$terme = get_queried_object();
		$url   = get_term_link( $terme );
		$titre = $terme->name;
		$type  = 'website';
	} elseif ( is_post_type_archive( 'projet' ) ) {
		$url   = get_post_type_archive_link( 'projet' );
		$titre = post_type_archive_title( '', false );
		$type  = 'website';
	} else {
		$url   = home_url( '/' );
		$titre = get_bloginfo( 'name' );
		$type  = 'website';
	}

	// L'image a la une du contenu, sinon le visuel de partage du theme.
	$image = is_singular() && has_post_thumbnail()
		? get_the_post_thumbnail_url( null, 'nv_projet' )
		: get_template_directory_uri() . '/assets/og-image.png';

	$balises = array(
		'name'     => array(
			'description'  => $description,
			'twitter:card' => 'summary_large_image',
		),
		'property' => array(
			'og:type'        => $type,
			'og:site_name'   => get_bloginfo( 'name' ),
			'og:locale'      => get_locale(),
			'og:title'       => $titre,
			'og:description' => $description,
			'og:url'         => $url,
			'og:image'       => $image,
		),
	);

	foreach ( $balises as $attribut => $paires ) {
		foreach ( $paires as $cle => $valeur ) {
			if ( ! $valeur ) {
				continue;
			}

			printf(
				'<meta %s="%s" content="%s">' . "\n",
				esc_attr( $attribut ),
				esc_attr( $cle ),
				esc_attr( $valeur )
			);
		}
	}
}
add_action( 'wp_head', 'nv_meta_sociales', 2 );

/**
 * Icone du site, en repli.
 *
 * Si une icone est definie dans Reglages > General, WordPress emet deja ses
 * propres balises et on ne fait rien : deux jeux de declarations se
 * contrediraient.
 */
function nv_favicon() {
	if ( has_site_icon() ) {
		return;
	}

	$icone = esc_url( get_template_directory_uri() . '/assets/favicon.png' );

	printf( '<link rel="icon" href="%s" sizes="any">' . "\n", $icone );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", $icone );
}
add_action( 'wp_head', 'nv_favicon', 2 );
