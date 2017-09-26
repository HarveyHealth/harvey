<?php
/**
 * Template Name: Full Width Content
 */
?>
<?php get_header(); ?>

	<div id="content">

		<?php

			// loop posts
			while ( have_posts() ) : the_post();

				// output from template
				get_template_part( 'template-parts/content', 'page' );

				// comments
				if ( comments_open() || get_comments_number() ) { comments_template(); }

			endwhile;
			
		?>

	</div><!-- #content -->

<?php get_footer();
