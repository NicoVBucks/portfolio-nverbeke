<?php
/**
 * Bloc projet, partage entre la page d'accueil et l'archive du CPT.
 *
 * @param int    $args['index']   Numero affiche (01, 02, ...).
 * @param bool   $args['alt']     Inverse la grille : metadonnees a droite.
 * @param bool   $args['compact'] Version courte : titre et numero reduits.
 * @param string $args['niveau']  Niveau du titre (h2 par defaut).
 *
 * @package nverbeke
 */

$nv_index   = isset( $args['index'] ) ? (int) $args['index'] : 0;
$nv_alt     = ! empty( $args['alt'] );
$nv_compact = ! empty( $args['compact'] );
$nv_niveau  = isset( $args['niveau'] ) ? $args['niveau'] : 'h2';

$nv_contexte = get_post_meta( get_the_ID(), 'contexte', true );
$nv_visuel   = has_post_thumbnail();

$nv_classes = array( 'projet' );
if ( $nv_alt ) {
	$nv_classes[] = 'projet--alt';
}
if ( $nv_compact ) {
	$nv_classes[] = 'projet--compact';
}
if ( ! $nv_visuel ) {
	$nv_classes[] = 'projet--sans-visuel';
}
?>

<article <?php post_class( $nv_classes ); ?>>

	<div class="projet__meta">
		<?php if ( $nv_index ) : ?>
			<p class="projet__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $nv_index ) ); ?></p>
		<?php endif; ?>

		<?php if ( $nv_contexte ) : ?>
			<p class="projet__contexte"><?php echo esc_html( $nv_contexte ); ?></p>
		<?php endif; ?>
	</div>

	<div class="projet__corps">
		<<?php echo esc_html( $nv_niveau ); ?> class="projet__titre">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</<?php echo esc_html( $nv_niveau ); ?>>

		<?php if ( $nv_visuel ) : ?>
			<figure class="projet__visuel">
				<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
					<?php
					/*
					 * Nom partage avec la meme image sur la page du projet : les
					 * navigateurs qui gerent View Transitions la font glisser d'une
					 * page a l'autre. Le nom doit etre unique dans la page, d'ou
					 * l'identifiant du projet.
					 */
					the_post_thumbnail(
						'nv_projet',
						array(
							'loading' => 'lazy',
							'style'   => 'view-transition-name: nv-visuel-' . get_the_ID() . ';',
						)
					);
					?>
				</a>
			</figure>
		<?php endif; ?>

		<div class="projet__texte">
			<?php echo wp_kses_post( wpautop( get_the_excerpt() ) ); ?>
		</div>

		<?php
		/*
		 * Les technologies sont ici, et non dans la colonne de metadonnees :
		 * en une seule colonne, elles doivent se lire apres le titre, pas avant.
		 * Deplacer l'ordre en CSS desynchroniserait le focus clavier de
		 * l'affichage, ces badges etant des liens.
		 */
		nv_liste_technologies( get_the_ID() );
		nv_liens_projet( get_the_ID() );
		?>
	</div>

</article>
