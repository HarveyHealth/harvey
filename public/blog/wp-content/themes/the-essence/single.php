<?php get_header(); ?>
	
	<?php
		// post layout
		$layout = 'content_sidebar';
		if ( the_essence_get_post_meta( get_the_ID(), 'post_layout' ) ) {
			$layout = the_essence_get_post_meta( get_the_ID(), 'post_layout' );
		}

		// content class
		if ( $layout == 'content_sidebar' ) {
			$content_class = 'col col-8';
		} else {
			$content_class = '';
		}
	?>

	<div id="content" class="<?php echo esc_attr( $content_class ); ?>">

		<?php

			// loop posts
			while ( have_posts() ) : the_post();

				// main content
				get_template_part( 'template-parts/content-single', get_post_format() );

				// related posts
				if ( the_essence_get_theme_mod( 'blog_single_related_posts_section', 'enabled' ) == 'enabled' )
					get_template_part( 'template-parts/main/related-posts' );

				// about author
				if ( the_essence_get_theme_mod( 'blog_single_about_author_section', 'enabled' ) == 'enabled' )
					get_template_part( 'template-parts/main/about-author' );

				// previous and next post
				get_template_part( 'template-parts/main/prev-next-posts' );

				// comments
				if ( comments_open() || get_comments_number() ) { comments_template(); }

			// end loop
			endwhile;
			
		?>

	</div><!-- #content -->

	<?php if ( $layout == 'content_sidebar' ) get_sidebar(); ?>

<?php get_footer();
