<?php
/**
 * Template Name: Home
 */
?>

<?php get_header(); ?>

<?php
	// homepage style
	$homepage_style = '1_cs_12';
	if ( the_essence_get_post_meta( get_the_ID(), 'homepage_style' ) ) {
		$homepage_style = the_essence_get_post_meta( get_the_ID(), 'homepage_style' );
	}
	$homepage_style_string = $homepage_style;
	$homepage_style = explode( '_', $homepage_style );

	// masonry
	$is_masonry = false;
	if ( the_essence_get_post_meta( get_the_ID(), 'homepage_layout_type' ) == 'masonry' ) {
		$is_masonry = true;
	}

	// has sidebar?
	$has_sidebar = true;
	if ( $homepage_style[1] == 'fc' ) {
		$has_sidebar = false;
	}

	// show excerpt
	$show_excerpt = true;
	if ( $homepage_style[0] == '3' ) { 
		if ( $homepage_style[1] == 'cs' && $homepage_style[2] == '6' ) {
			$show_excerpt_length = 140;
		} elseif ( $homepage_style[1] == 'cs' && $homepage_style[2] == '4' ) {
			$show_excerpt_length = 95;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '6' ) {
			$show_excerpt_length = 210;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '4' ) {
			$show_excerpt_length = 140;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '3' ) {
			$show_excerpt_length = 100;
		}
	} elseif ( $homepage_style[0] == '4' ) { 
		if ( $homepage_style[1] == 'cs' && $homepage_style[2] == '6' ) {
			$show_excerpt_length = 140;
		} elseif ( $homepage_style[1] == 'cs' && $homepage_style[2] == '4' ) {
			$show_excerpt_length = 95;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '6' ) {
			$show_excerpt_length = 210;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '4' ) {
			$show_excerpt_length = 140;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '3' ) {
			$show_excerpt_length = 100;
		}
	} elseif ( $homepage_style[0] == '7' ) { 
		if ( $homepage_style[1] == 'cs' && $homepage_style[2] == '6' ) {
			$show_excerpt_length = 120;
		} elseif ( $homepage_style[1] == 'cs' && $homepage_style[2] == '4' ) {
			$show_excerpt_length = 95;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '6' ) {
			$show_excerpt_length = 190;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '4' ) {
			$show_excerpt_length = 120;
		} elseif ( $homepage_style[1] == 'fc' && $homepage_style[2] == '3' ) {
			$show_excerpt_length = 75;
		}
	}

?>

	<div id="content" class="<?php if ( $has_sidebar ) echo 'col col-8'; ?>">

		<?php 

			// post style
			$post_style = $homepage_style[0];

			// post columns
			$post_columns = $homepage_style[2];

			// current page
			if ( is_numeric( get_query_var( 'page' ) ) ) { $paged = get_query_var( 'page' ); } elseif ( is_numeric( get_query_var( 'paged' ) ) ) { $paged = get_query_var( 'paged' ); } else { $paged = 1; }

			// query args
			$args = array(
				'posts_per_page' => 2,
				'paged' => $paged,
			);			

			// query args ( posts per page )
			if ( the_essence_get_post_meta( get_the_ID(), 'query_posts_per_page' ) ) {
				$args['posts_per_page'] = the_essence_get_post_meta( get_the_ID(), 'query_posts_per_page' );
			}

			// query posts
			$the_essence_query = new WP_Query( $args );

			// if there are posts
			if ( $the_essence_query->have_posts() ) :

				// count
				$count = 0;
				$real_count = 0;

				// amount of pages
				$num_pages = $the_essence_query->max_num_pages;

				?><div class="blog-posts-listing blog-posts-listing-<?php echo $homepage_style_string; ?>"><?php

					?><div class="blog-posts-listing-inner clearfix <?php if ( $is_masonry ) echo 'masonry-init'; ?>"><?php

						// loop posts
						while ( $the_essence_query->have_posts() ) : $the_essence_query->the_post(); $count++; $real_count++;

							include( locate_template( 'template-parts/listing/blog-post-s' . $post_style . '.php' ) );

						endwhile;

					?></div><!-- .blog-posts-listing-inner --><?php

					// post navigation
					the_essence_posts_pagination( array( 'pages' => $num_pages, 'type' => the_essence_get_theme_mod( 'pagination_type', 'loadmore' ) ) );

				?></div><!-- .blog-posts-listing --><?php

			// if no posts found
			else :

				// output from template
				get_template_part( 'template-parts/content', 'none' );

			// finish if statement
			endif; 

			// reset query
			wp_reset_postdata();

		?>

	</div><!-- #content -->

	<?php if ( $has_sidebar ) get_sidebar(); ?>

	<?php $show_excerpt = false; ?>

<?php get_footer(); ?>