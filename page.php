<?php
/**
 * Page simple : mentions legales, etc.
 *
 * @package nverbeke
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="section">
		<div class="wrap wrap--etroit">
			<h1 class="archive__titre"><?php the_title(); ?></h1>
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
