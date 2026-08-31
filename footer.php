<?php
/**
 * Pied de page : contact et mentions.
 *
 * Le bloc contact vit ici plutot que dans la page d'accueil : il est
 * identique sur toutes les pages, l'ancre #contact y renvoie partout.
 *
 * @package nverbeke
 */

$nv_mentions = get_page_by_path( 'mentions-legales' );
?>
</main>

<footer id="contact" class="pied">
	<div class="wrap">
		<h2 class="etiquette"><?php esc_html_e( 'Contact', 'nverbeke' ); ?></h2>

		<div class="pied__grille">
			<p class="pied__phrase">
				<?php esc_html_e( 'Disponible pour un poste en CDI ou CDD, à Paris et en Île-de-France.', 'nverbeke' ); ?>
			</p>

			<ul class="pied__liens">
				<li>
					<a href="mailto:nico.verbeke91@gmail.com">nico.verbeke91@gmail.com</a>
				</li>
				<li>
					<a class="lien-ext" href="https://github.com/NicoVBucks" target="_blank" rel="noopener noreferrer">
						github.com/NicoVBucks<span class="sr-only"> <?php esc_html_e( '(nouvelle fenêtre)', 'nverbeke' ); ?></span>
					</a>
				</li>
				<li>
					<a class="lien-ext" href="https://www.linkedin.com/in/verbeke-nicolas/" target="_blank" rel="noopener noreferrer">
						linkedin.com/in/verbeke-nicolas<span class="sr-only"> <?php esc_html_e( '(nouvelle fenêtre)', 'nverbeke' ); ?></span>
					</a>
				</li>
			</ul>
		</div>

		<p class="pied__legal">
			<?php
			printf(
				/* translators: %s: annee en cours. */
				esc_html__( '© %s Nicolas Verbeke', 'nverbeke' ),
				esc_html( wp_date( 'Y' ) )
			);
			?>
			<?php if ( $nv_mentions ) : ?>
				<a class="pied__legal-lien" href="<?php echo esc_url( get_permalink( $nv_mentions ) ); ?>">
					<?php esc_html_e( 'Mentions légales', 'nverbeke' ); ?>
				</a>
			<?php endif; ?>
		</p>

		<p class="pied__legal">
			<?php esc_html_e( 'Ce site ne dépose aucun cookie et n’utilise aucun outil de mesure d’audience.', 'nverbeke' ); ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
