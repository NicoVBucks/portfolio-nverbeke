<?php
/**
 * Modele de repli exige par WordPress.
 *
 * @package nverbeke
 */

get_header();
?>

<section class="section">
	<div class="wrap wrap--etroit">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<h2 class="archive__titre">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<div class="prose"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>

			<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
		<?php else : ?>
			<p class="vide"><?php esc_html_e( 'Aucun contenu à afficher.', 'nverbeke' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
