<?php
/**
 * Page 404.
 *
 * @package nverbeke
 */

get_header();
?>

<section class="section">
	<div class="wrap wrap--etroit">
		<p class="erreur__code" aria-hidden="true">404</p>
		<h1 class="archive__titre"><?php esc_html_e( 'Cette page n’existe pas', 'nverbeke' ); ?></h1>
		<p><?php esc_html_e( 'L’adresse demandée est introuvable. Le lien est peut-être obsolète.', 'nverbeke' ); ?></p>
		<p class="actions">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'projet' ) ); ?>">
				<?php esc_html_e( 'Voir les projets', 'nverbeke' ); ?>
			</a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Retour à l’accueil', 'nverbeke' ); ?>
			</a>
		</p>
	</div>
</section>

<?php
get_footer();
