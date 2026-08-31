<?php
/**
 * Custom Post Type "projet" et taxonomie "technologie".
 *
 * @package nverbeke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enregistre le type de contenu "projet".
 *
 * page-attributes est active pour disposer du champ "Ordre" natif :
 * il permet de classer les projets a la main depuis l'admin.
 */
function nv_register_cpt_projet() {
	$labels = array(
		'name'               => __( 'Projets', 'nverbeke' ),
		'singular_name'      => __( 'Projet', 'nverbeke' ),
		'menu_name'          => __( 'Projets', 'nverbeke' ),
		'add_new'            => __( 'Ajouter', 'nverbeke' ),
		'add_new_item'       => __( 'Ajouter un projet', 'nverbeke' ),
		'edit_item'          => __( 'Modifier le projet', 'nverbeke' ),
		'new_item'           => __( 'Nouveau projet', 'nverbeke' ),
		'view_item'          => __( 'Voir le projet', 'nverbeke' ),
		'search_items'       => __( 'Rechercher un projet', 'nverbeke' ),
		'not_found'          => __( 'Aucun projet trouvé', 'nverbeke' ),
		'not_found_in_trash' => __( 'Aucun projet dans la corbeille', 'nverbeke' ),
		'all_items'          => __( 'Tous les projets', 'nverbeke' ),
	);

	register_post_type(
		'projet',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'projets', 'with_front' => false ),
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest'  => true,
			'hierarchical'  => false,
		)
	);
}
add_action( 'init', 'nv_register_cpt_projet' );

/**
 * Enregistre la taxonomie non hierarchique "technologie".
 *
 * Elle sert deux fois : les badges de chaque projet, et la liste
 * de competences de la page d'accueil, qui est donc pilotee par
 * le contenu reel et non par une liste ecrite en dur.
 */
function nv_register_taxonomie_technologie() {
	$labels = array(
		'name'          => __( 'Technologies', 'nverbeke' ),
		'singular_name' => __( 'Technologie', 'nverbeke' ),
		'search_items'  => __( 'Rechercher une technologie', 'nverbeke' ),
		'all_items'     => __( 'Toutes les technologies', 'nverbeke' ),
		'edit_item'     => __( 'Modifier la technologie', 'nverbeke' ),
		'add_new_item'  => __( 'Ajouter une technologie', 'nverbeke' ),
		'not_found'     => __( 'Aucune technologie trouvée', 'nverbeke' ),
	);

	register_taxonomy(
		'technologie',
		'projet',
		array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'technologies', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'nv_register_taxonomie_technologie' );

/**
 * Reecrit les permaliens une seule fois apres l'activation du theme.
 * Evite d'avoir a passer manuellement dans Reglages > Permaliens.
 */
function nv_flush_permaliens() {
	nv_register_cpt_projet();
	nv_register_taxonomie_technologie();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'nv_flush_permaliens' );

/**
 * Classe les projets par le champ "Ordre" sur l'archive du CPT.
 *
 * @param WP_Query $query Requete principale.
 */
function nv_ordre_archive_projets( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'projet' ) || $query->is_tax( 'technologie' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
	}
}
add_action( 'pre_get_posts', 'nv_ordre_archive_projets' );
