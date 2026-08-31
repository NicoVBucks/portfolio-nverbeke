<?php
/**
 * Champs personnalises du CPT "projet", en meta boxes natives.
 *
 * Trois champs : contexte (texte court), lien_code (URL), lien_site (URL).
 * Nettoyage a l'enregistrement, echappement a l'affichage.
 *
 * @package nverbeke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Definition des champs. Le tableau sert a la fois a l'affichage
 * du formulaire et a l'enregistrement : une seule source de verite.
 */
function nv_champs_projet() {
	return array(
		'contexte'  => array(
			'label'       => __( 'Contexte', 'nverbeke' ),
			'type'        => 'text',
			'description' => __( 'Une ligne : cadre du projet, commanditaire, année.', 'nverbeke' ),
		),
		'lien_code' => array(
			'label'       => __( 'Lien vers le code', 'nverbeke' ),
			'type'        => 'url',
			'description' => __( 'URL du dépôt. Laisser vide si le code n’est pas public.', 'nverbeke' ),
		),
		'lien_site' => array(
			'label'       => __( 'Lien vers le site en ligne', 'nverbeke' ),
			'type'        => 'url',
			'description' => __( 'URL de la démonstration. Laisser vide si le projet n’est pas déployé.', 'nverbeke' ),
		),
	);
}

/**
 * Ajoute la meta box sur l'ecran d'edition d'un projet.
 */
function nv_add_meta_box_projet() {
	add_meta_box(
		'nv_details_projet',
		__( 'Détails du projet', 'nverbeke' ),
		'nv_render_meta_box_projet',
		'projet',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'nv_add_meta_box_projet' );

/**
 * Affiche le formulaire de la meta box.
 *
 * @param WP_Post $post Projet en cours d'edition.
 */
function nv_render_meta_box_projet( $post ) {
	wp_nonce_field( 'nv_save_projet', 'nv_projet_nonce' );

	foreach ( nv_champs_projet() as $cle => $champ ) {
		$valeur = get_post_meta( $post->ID, $cle, true );
		$id     = 'nv_' . $cle;
		?>
		<p>
			<label for="<?php echo esc_attr( $id ); ?>">
				<strong><?php echo esc_html( $champ['label'] ); ?></strong>
			</label><br>
			<input
				type="<?php echo esc_attr( $champ['type'] ); ?>"
				id="<?php echo esc_attr( $id ); ?>"
				name="<?php echo esc_attr( $cle ); ?>"
				value="<?php echo esc_attr( $valeur ); ?>"
				class="widefat"
				<?php echo 'url' === $champ['type'] ? 'placeholder="https://"' : ''; ?>
			>
			<span class="description"><?php echo esc_html( $champ['description'] ); ?></span>
		</p>
		<?php
	}
}

/**
 * Enregistre les champs personnalises.
 *
 * @param int     $post_id Identifiant du projet.
 * @param WP_Post $post    Objet projet.
 */
function nv_save_meta_projet( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'projet' !== $post->post_type ) {
		return;
	}

	if ( ! isset( $_POST['nv_projet_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nv_projet_nonce'] ) ), 'nv_save_projet' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( nv_champs_projet() as $cle => $champ ) {
		if ( ! isset( $_POST[ $cle ] ) ) {
			continue;
		}

		$brut = wp_unslash( $_POST[ $cle ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- nettoye juste apres.

		$valeur = 'url' === $champ['type']
			? esc_url_raw( trim( $brut ) )
			: sanitize_text_field( $brut );

		if ( '' === $valeur ) {
			delete_post_meta( $post_id, $cle );
		} else {
			update_post_meta( $post_id, $cle, $valeur );
		}
	}
}
add_action( 'save_post', 'nv_save_meta_projet', 10, 2 );
