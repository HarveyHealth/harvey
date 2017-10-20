<?php
	
	// not enabled by default
	$is_enabled = false;

	// if on a single blog post page enable
	if ( is_singular( 'post' ) ) {
		$is_enabled = true;
	}

?>

<?php if ( $is_enabled ) : ?>

	<?php $meta_elements = the_essence_get_theme_mod( 'blog_single_meta_elements', array( 'author', 'category', 'comments', 'shares' ) ); ?>
	<?php $meta_elements = !is_array( $meta_elements ) ? explode( ',', $meta_elements ) : $meta_elements; ?>

	<div id="tagline">

		<div class="wrapper">

			<h1 data-mtst-selector="#tagline h1" data-mtst-label="Tagline - Title" data-mtst-no-support="background,border"><?php echo get_the_title( get_the_ID() ); ?></h1>

			<?php
				global $post;				
				$user_data = get_userdata( $post->post_author );
				$user_email = $user_data->user_email;
			?>

			<div class="blog-post-meta clearfix">
				
				<?php if ( in_array( 'author', $meta_elements ) ) : ?>
					<div class="blog-post-meta-author clearfix">
						<div class="blog-post-meta-author-avatar"><?php echo get_avatar( $user_email, '40' ); ?></div>
						<div class="blog-post-meta-author-main">
							<div class="blog-post-meta-author-name"><?php echo esc_html_e( 'by', 'the-essence' ); ?> <a href="<?php echo get_author_posts_url( $post->post_author, $user_data->user_nicename ); ?>"><?php echo $user_data->display_name; ?></a></div>
							<div class="blog-post-meta-author-date"><?php 
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

				<?php if ( in_array( 'category', $meta_elements ) ) : ?>
					<div class="blog-post-meta-cats">
						<?php the_category( ' ' ); ?>
					</div><!-- .blog-post-meta-cats -->

					<div class="blog-post-meta-separator"></div>
				<?php endif; ?>

			</div><!-- .blog-post-meta -->

		</div><!-- .wrapper -->

	</div><!-- #tagline -->

<?php endif; ?>