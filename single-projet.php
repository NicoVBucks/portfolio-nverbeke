<?php
/**
 * Page d'un projet.
 *
 * @package nverbeke
 */

get_header();

while ( have_posts() ) :
	the_post();

	$nv_contexte = get_post_meta( get_the_ID(), 'contexte', true );
	?>

	<article class="fiche-projet">
		<header class="fiche-projet__entete">
			<div class="wrap fiche-projet__grille">
				<div class="fiche-projet__meta">
					<p class="fiche-projet__retour">
						<a href="<?php echo esc_url( get_post_type_archive_link( 'projet' ) ); ?>">
							<?php esc_html_e( 'Tous les projets', 'nverbeke' ); ?>
						</a>
					</p>

					<?php if ( $nv_contexte ) : ?>
						<p class="projet__contexte"><?php echo esc_html( $nv_contexte ); ?></p>
					<?php endif; ?>
				</div>

				<div class="fiche-projet__titrage">
					<h1 class="fiche-projet__titre"><?php the_title(); ?></h1>
					<?php
					nv_liste_technologies( get_the_ID() );
					nv_liens_projet( get_the_ID() );
					?>
				</div>
			</div>
		</header>

		<?php
		// L'image a la une, puis les autres images televersees sur ce projet.
		// get_attached_media() suffit : WordPress rattache deja une image au
		// projet depuis lequel elle a ete televersee, aucun champ a ajouter.
		$nv_id_une  = get_post_thumbnail_id();
		$nv_visuels = array_unique(
			array_filter(
				array_merge(
					array( $nv_id_une ),
					array_keys( get_attached_media( 'image', get_the_ID() ) )
				)
			)
		);

		if ( $nv_visuels ) :
			?>
			<div class="fiche-projet__visuel">
				<div class="wrap visuels">
					<?php foreach ( $nv_visuels as $nv_visuel_id ) : ?>
						<figure class="visuels__carte">
							<?php
							echo wp_get_attachment_image(
								$nv_visuel_id,
								'nv_projet',
								false,
								// Meme nom que sur la liste : l'image a la une se
								// deplace d'une page a l'autre au lieu de disparaitre.
								$nv_visuel_id === $nv_id_une
									? array( 'style' => 'view-transition-name: nv-visuel-' . get_the_ID() . ';' )
									: array()
							);
							?>
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		endif;
		?>

		<div class="wrap">
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</article>

	<?php
	$nv_precedent = get_previous_post();
	$nv_suivant   = get_next_post();

	if ( $nv_precedent || $nv_suivant ) :
		?>
		<nav class="pagination-projets" aria-label="<?php esc_attr_e( 'Autres projets', 'nverbeke' ); ?>">
			<div class="wrap pagination-projets__inner">
				<?php if ( $nv_precedent ) : ?>
					<a class="pagination-projets__lien" href="<?php echo esc_url( get_permalink( $nv_precedent ) ); ?>">
						<span class="etiquette"><?php esc_html_e( 'Projet précédent', 'nverbeke' ); ?></span>
						<span class="pagination-projets__titre"><?php echo esc_html( get_the_title( $nv_precedent ) ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( $nv_suivant ) : ?>
					<a class="pagination-projets__lien pagination-projets__lien--suivant" href="<?php echo esc_url( get_permalink( $nv_suivant ) ); ?>">
						<span class="etiquette"><?php esc_html_e( 'Projet suivant', 'nverbeke' ); ?></span>
						<span class="pagination-projets__titre"><?php echo esc_html( get_the_title( $nv_suivant ) ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</nav>
		<?php
	endif;

endwhile;

get_footer();
