<div class="blog-post-single-nav">
	<span class="blog-post-single-nav-line"></span>
	<?php
		$prev_post = get_adjacent_post( false, '', true );
		if ( is_a( $prev_post, 'WP_Post' ) ) {
			?><a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="blog-post-single-nav-prev"><span class="fa fa-angle-left"></span><?php esc_html_e( 'Previous Post', 'the-essence' ); ?></a><?php
		}
	?>
	<?php
		$next_post = get_adjacent_post( false, '', false );
		if ( is_a( $next_post, 'WP_Post' ) ) {
			?><a href="<?php echo get_permalink( $next_post->ID ); ?>" class="blog-post-single-nav-next"><?php esc_html_e( 'Next Post', 'the-essence' ); ?><span class="fa fa-angle-right"></span></a><?php
		}
	?>
</div><!-- .blog-post-single-nav -->	