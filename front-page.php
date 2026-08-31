<?php
/**
 * Page d'accueil.
 *
 * @package nverbeke
 */

get_header();

$nv_projets = new WP_Query(
	array(
		'post_type'      => 'projet',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	)
);
?>

<section class="hero">
	<div class="wrap hero__inner">
		<div class="hero__principal">
			<h1 class="hero__titre">
				<?php bloginfo( 'name' ); ?>
				<span class="hero__role"><?php esc_html_e( 'Développeur web', 'nverbeke' ); ?></span>
			</h1>

			<p class="hero__intro">
				<?php esc_html_e( 'J’écris des interfaces et des thèmes WordPress à la main : HTML sémantique, CSS, JavaScript sans bibliothèque, PHP. Ce site en est un exemple, développé sans extension ni constructeur de page.', 'nverbeke' ); ?>
			</p>
		</div>

		<dl class="fiche">
			<div class="fiche__ligne">
				<dt><?php esc_html_e( 'Certification', 'nverbeke' ); ?></dt>
				<dd>RNCP38145, <?php esc_html_e( 'niveau 5', 'nverbeke' ); ?></dd>
			</div>
			<div class="fiche__ligne">
				<dt><?php esc_html_e( 'Statut', 'nverbeke' ); ?></dt>
				<dd><?php esc_html_e( 'Formation terminée', 'nverbeke' ); ?></dd>
			</div>
			<div class="fiche__ligne">
				<dt><?php esc_html_e( 'Secteur', 'nverbeke' ); ?></dt>
				<dd><?php esc_html_e( 'Paris / Île-de-France', 'nverbeke' ); ?></dd>
			</div>
			<div class="fiche__ligne">
				<dt><?php esc_html_e( 'Recherche', 'nverbeke' ); ?></dt>
				<dd><?php esc_html_e( 'CDI ou CDD', 'nverbeke' ); ?></dd>
			</div>
		</dl>
	</div>
</section>

<section id="projets" class="section">
	<div class="wrap">
		<h2 class="etiquette"><?php esc_html_e( 'Projets', 'nverbeke' ); ?></h2>

		<?php if ( $nv_projets->have_posts() ) : ?>

			<div class="projets">
				<?php
				$nv_i = 0;
				while ( $nv_projets->have_posts() ) :
					$nv_projets->the_post();
					++$nv_i;

					/*
					 * Les deux premiers projets occupent un bloc pleine largeur,
					 * les suivants un bloc court. L'ordre se regle dans l'admin
					 * avec le champ "Ordre" de chaque projet.
					 */
					get_template_part(
						'template-parts/projet',
						'bloc',
						array(
							'index'   => $nv_i,
							'alt'     => ( 0 === $nv_i % 2 ),
							'compact' => ( $nv_i > 2 ),
							'niveau'  => 'h3',
						)
					);
				endwhile;
				wp_reset_postdata();
				?>
			</div>

		<?php else : ?>

			<p class="vide"><?php esc_html_e( 'Aucun projet publié pour le moment.', 'nverbeke' ); ?></p>

		<?php endif; ?>
	</div>
</section>

<section id="parcours" class="section section--alt">
	<div class="wrap">
		<h2 class="etiquette"><?php esc_html_e( 'Parcours', 'nverbeke' ); ?></h2>

		<div class="parcours">
			<div class="parcours__intro">
				<h3 class="parcours__diplome"><?php esc_html_e( 'Développeur Informatique', 'nverbeke' ); ?></h3>
				<p class="parcours__source">
					<?php esc_html_e( 'OpenClassrooms, parcours Développeur WordPress. Formation terminée.', 'nverbeke' ); ?>
				</p>
				<p class="parcours__rncp">
					<?php esc_html_e( 'Titre RNCP38145, niveau 5, enregistré au Répertoire national des certifications professionnelles le 18 octobre 2023 (codes NSF 326, 326t).', 'nverbeke' ); ?>
				</p>
			</div>

			<ol class="blocs">
				<li class="bloc">
					<p class="bloc__code">BC01</p>
					<p class="bloc__intitule"><?php esc_html_e( 'Participer à la mise en œuvre d’un projet de développement de solution informatique', 'nverbeke' ); ?></p>
					<p class="bloc__preuve"><?php esc_html_e( 'Ce site : conception, architecture du thème, modèle de contenu.', 'nverbeke' ); ?></p>
				</li>
				<li class="bloc">
					<p class="bloc__code">BC02</p>
					<p class="bloc__intitule"><?php esc_html_e( 'Développer les fonctionnalités front-end et back-end d’une solution informatique', 'nverbeke' ); ?></p>
					<p class="bloc__preuve"><?php esc_html_e( 'TSG Fleet Planner, Booki, Riding Cities, ce site.', 'nverbeke' ); ?></p>
				</li>
				<li class="bloc">
					<p class="bloc__code">BC03</p>
					<p class="bloc__intitule"><?php esc_html_e( 'Tester et publier une solution informatique', 'nverbeke' ); ?></p>
					<p class="bloc__preuve"><?php esc_html_e( 'TSG Fleet Planner, déployé et maintenu en production sur Cloudflare Workers.', 'nverbeke' ); ?></p>
				</li>
			</ol>
		</div>
	</div>
</section>

<section id="competences" class="section">
	<div class="wrap">
		<h2 class="etiquette"><?php esc_html_e( 'Compétences', 'nverbeke' ); ?></h2>

		<?php
		$nv_technos = get_terms(
			array(
				'taxonomy'   => 'technologie',
				'hide_empty' => true,
				'orderby'    => 'count',
				'order'      => 'DESC',
			)
		);
		?>

		<p class="section__chapo">
			<?php esc_html_e( 'Cette liste est construite à partir des technologies réellement employées dans les projets ci-dessus. Chaque entrée mène aux projets concernés.', 'nverbeke' ); ?>
		</p>

		<?php if ( ! empty( $nv_technos ) && ! is_wp_error( $nv_technos ) ) : ?>
			<ul class="competences">
				<?php foreach ( $nv_technos as $nv_terme ) : ?>
					<li class="competence">
						<a href="<?php echo esc_url( get_term_link( $nv_terme ) ); ?>">
							<span class="competence__nom"><?php echo esc_html( $nv_terme->name ); ?></span>
							<span class="competence__compte">
								<?php
								printf(
									/* translators: %d: nombre de projets. */
									esc_html( _n( '%d projet', '%d projets', $nv_terme->count, 'nverbeke' ) ),
									absint( $nv_terme->count )
								);
								?>
							</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p class="vide"><?php esc_html_e( 'Aucune technologie renseignée pour le moment.', 'nverbeke' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section id="ce-site" class="section section--alt">
	<div class="wrap">
		<h2 class="etiquette"><?php esc_html_e( 'Ce site', 'nverbeke' ); ?></h2>

		<div class="cesite">
			<p class="cesite__chapo">
				<?php esc_html_e( 'Thème WordPress écrit intégralement à la main. Aucune extension, aucun constructeur de page, aucune bibliothèque tierce.', 'nverbeke' ); ?>
			</p>

			<ul class="cesite__liste">
				<li><?php esc_html_e( 'Type de contenu « projet » et taxonomie « technologie » enregistrés en PHP.', 'nverbeke' ); ?></li>
				<li><?php esc_html_e( 'Champs personnalisés en meta boxes natives : nettoyage à l’enregistrement, échappement à l’affichage.', 'nverbeke' ); ?></li>
				<li><?php esc_html_e( 'Aucun JavaScript : navigation, ancres et états sont gérés en CSS.', 'nverbeke' ); ?></li>
				<li><?php esc_html_e( 'Polices auto-hébergées en trois fichiers woff2, environ 95 Ko, sans requête vers un service tiers.', 'nverbeke' ); ?></li>
				<li><?php esc_html_e( 'Aucun traceur, aucun cookie de mesure d’audience.', 'nverbeke' ); ?></li>
			</ul>
		</div>
	</div>
</section>

<?php
get_footer();
