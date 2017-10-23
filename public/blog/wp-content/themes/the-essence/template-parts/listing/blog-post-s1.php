<?php
	
	// default real count
	if ( ! isset( $real_count ) ) 
		$real_count = 0;

	// default count
	if ( ! isset( $count ) )
		$count = '';

	// default post class append
	if ( ! isset( $post_class_append ) )
		$post_class_append = '';

	// default columns
	if ( ! isset( $post_columns ) ) {
		$post_columns = 12;
	}

	// column class
	$column_class = 'col col-' . $post_columns . ' ';
	$count_max = 12 / $post_columns;
	if ( $count == 1 ) {
		$column_class .= 'col-first ';
	}
	if ( $count >= $count_max ) {
		$count = 0;
		$column_class .= 'col-last ';
	}

	// if sidebar is set ( it's a homepage listing )
	$image_size = 'the-essence-s1-s-12';
	if ( isset( $has_sidebar ) ) {

		// thumbnail width
		if ( ! $has_sidebar ) {
			if ( $post_columns == '12' )
				$image_size = 'the-essence-s1-12';
			elseif ( $post_columns == '6' )
				$image_size = 'the-essence-s1-6';
			elseif ( $post_columns == '4' )
				$image_size = 'the-essence-s1-4';
			else
				$image_size = 'the-essence-s1-3';
		} else {
			if ( $post_columns == '12' )
				$image_size = 'the-essence-s1-s-12';
			elseif ( $post_columns == '6' )
				$image_size = 'the-essence-s1-s-6';
			elseif ( $post_columns == '4' )
				$image_size = 'the-essence-s1-s-4';
			else
				$image_size = 'the-essence-s1-s-3';
		}
	}	

	// meta elements
	$meta_elements = the_essence_get_theme_mod( 'blog_listing_meta_elements', array( 'author', 'comments', 'shares_count', 'shares_actions' ) );
	$meta_elements = !is_array( $meta_elements ) ? explode( ',', $meta_elements ) : $meta_elements;

?>
<div <?php post_class( 'blog-post clearfix ' . $column_class . $post_class_append ); ?>>

	<?php if ( has_category() && ! is_archive() ) : ?>
		<div class="blog-post-cats" data-mtst-selector=".blog-post-cats" data-mtst-label="Blog Post S1 - Category">
			<?php 
				if ( the_essence_get_theme_mod( 'show_one_cat_in_listings', 'disabled' ) == 'enabled' ) {
					$cats = get_the_category( get_the_ID() );
					if ( is_array( $cats ) ) {
						?><a href="<?php echo get_term_link( $cats[0], 'category' ) ?>"><?php echo esc_html( $cats[0]->cat_name ); ?></a><?php
					}
				} else {
					the_category( ' ' );
				}
			?>
		</div><!-- .blog-post-cats -->
	<?php endif; ?>

	<div class="blog-post-title">
		<h2 data-mtst-selector=".blog-post-title h2" data-mtst-label="Blog Post S1 - Title" data-mtst-no-support="spacing,border"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	</div><!-- .blog-post-title -->			

	<div class="blog-post-meta clearfix">
		
		<?php if ( in_array( 'author', $meta_elements ) ) : ?>
			<div class="blog-post-meta-author clearfix">
				<div class="blog-post-meta-author-avatar"><?php echo get_avatar( get_the_author_meta('email'), '40' ); ?></div>
				<div class="blog-post-meta-author-main">
					<div class="blog-post-meta-author-name" data-mtst-selector=".blog-post-meta-author-name" data-mtst-label="Blog S1 - Author" data-mtst-no-support="border,background"><?php echo esc_html_e( 'by', 'the-essence' ); ?> <?php the_author_posts_link(); ?></div>
					<div class="blog-post-meta-author-date" data-mtst-selector=".blog-post-meta-author-date" data-mtst-label="Blog S1 - Date" data-mtst-no-support="border,background"><?php 
						if ( the_essence_get_theme_mod( 'date_format', 'timeago' ) == 'timeago' ) {
							printf( esc_html__( '%1$s ago', 'the-essence' ), human_time_diff( get_the_time('U'), current_time('timestamp') ) ); 
						} else {
							the_time( get_option( 'date_format' ) );
						}
					?></div>
				</div><!-- .blog-post-meta-author-main -->
			</div><!-- .blog-post-meta-author -->
			
			<div class="blog-post-meta-separator"></div>
		<?php endif; ?>

	</div><!-- .blog-post-meta -->

	<?php if ( has_post_thumbnail() ) : ?>

		<div class="blog-post-thumb">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $image_size ); ?></a>
		</div><!-- .blog-post-thumb -->

	<?php endif; ?>

	<div class="blog-post-excerpt" data-mtst-selector=".blog-post-excerpt" data-mtst-label="Blog Post S1 - Excerpt">
		<?php the_excerpt(); ?>
	</div><!-- .blog-post-excerpt -->

	<div class="blog-post-read-more">
		<a data-mtst-selector=".blog-post-read-more a" data-mtst-label="Blog Post S1 - Button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'CONTINUE READING', 'the-essence' ); ?></a>
	</div><!-- .blog-post-read-more -->

	<?php 
		if ( ! is_archive() && ! is_search() ) {
			the_essence_related_posts(); 
		}
	?>

</div><!-- .blog-post -->
