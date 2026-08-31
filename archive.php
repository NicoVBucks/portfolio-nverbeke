<?php
/**
 * Archives : liste des projets, et filtrage par technologie.
 *
 * Un seul fichier couvre les deux cas grace a la hierarchie de
 * templates WordPress : archive-projet.php et taxonomy-technologie.php
 * retombent tous deux sur archive.php.
 *
 * @package nverbeke
 */

get_header();
?>

<section class="section">
	<div class="wrap">
		<h1 class="archive__titre"><?php the_archive_title(); ?></h1>

		<?php if ( is_tax( 'technologie' ) ) : ?>
			<p class="section__chapo">
				<?php esc_html_e( 'Projets dans lesquels cette technologie a été employée.', 'nverbeke' ); ?>
			</p>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<div class="projets">
				<?php
				$nv_i = 0;
				while ( have_posts() ) :
					the_post();
					++$nv_i;

					get_template_part(
						'template-parts/projet',
						'bloc',
						array(
							'index'   => $nv_i,
							'alt'     => ( 0 === $nv_i % 2 ),
							'compact' => false,
							'niveau'  => 'h2',
						)
					);
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => __( 'Précédent', 'nverbeke' ),
					'next_text' => __( 'Suivant', 'nverbeke' ),
				)
			);
			?>

		<?php else : ?>

			<p class="vide"><?php esc_html_e( 'Aucun projet ne correspond à cette sélection.', 'nverbeke' ); ?></p>
			<p>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'projet' ) ); ?>">
					<?php esc_html_e( 'Voir tous les projets', 'nverbeke' ); ?>
				</a>
			</p>

		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
