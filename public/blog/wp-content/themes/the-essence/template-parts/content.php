<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post' ); ?>>

	<?php if ( has_category() && ! is_archive() ) : ?>
		<div class="blog-post-cats">
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
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	</div><!-- .blog-post-title -->			

	<div class="blog-post-meta clearfix">
		
		<div class="blog-post-meta-author clearfix">
			<div class="blog-post-meta-author-avatar"><?php echo get_avatar( get_the_author_meta('email'), '40' ); ?></div>
			<div class="blog-post-meta-author-main">
				<div class="blog-post-meta-author-name"><?php echo esc_html_e( 'by', 'the-essence' ); ?> <?php the_author_posts_link(); ?></div>
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
		
		<div class="blog-post-meta-comments-count">
			<span class="fa fa-commenting-o"></span>
			<span><a href="<?php comments_link(); ?>"><?php comments_number( esc_html__( 'No comments', 'the-essence' ), esc_html__( 'One comment', 'the-essence' ), esc_html__( '% comments', 'the-essence' ) ); ?></a></span>
		</div><!-- .blog-post-meta-comments-count -->

		<div class="blog-post-meta-separator"></div>

		<div class="blog-post-meta-share-count">
			<?php $share_info = the_essence_get_social_count(); ?>
			<?php $total_shares = $share_info['total']; ?>
			<span class="blog-post-meta-share-count-num"><?php echo $share_info['total']; ?></span>
			<span class="blog-post-meta-share-count-text"><?php echo _n( 'share', 'shares', $total_shares, 'the-essence' ); ?></span>
		</div><!-- .blog-post-meta-share-count -->
		
		<div class="blog-post-meta-separator"></div>

		<div class="blog-post-meta-share">
			<?php
				$post_img = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
				$share_status = get_the_title( get_the_ID() ) . ' ' . get_permalink( get_the_ID() );
				$share_status = esc_attr( str_replace( '&#038;', '', $share_status ) );
				$share_title = get_the_title( get_the_ID() );
				$share_title = esc_attr( str_replace( '&#038;', '', $share_title ) );
			?>
			<a href="#" class="social-link-facebook" target="_blank" onClick="return the_essence_social_share(400, 300, 'http://www.facebook.com/share.php?u=<?php echo get_permalink( get_the_ID() ); ?>')"><span class="fa fa-facebook"></span></a>
			<a href="#" class="social-link-pinterest" onClick="return the_essence_social_share(400, 300, 'https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo esc_html( $post_img ); ?>')"><span class="fa fa-pinterest"></span></a>
			<a href="#" class="social-link-twitter" onClick="return the_essence_social_share(400, 300, 'https://twitter.com/home?status=<?php echo esc_html( $share_status ); ?>')" ><span class="fa fa-twitter"></span></a>
			<a href="#" class="social-link-google-plus" onClick="return the_essence_social_share(400, 300, 'https://plus.google.com/share?url=<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>')" ><span class="fa fa-google-plus"></span></a>
			<a href="#" class="social-link-linkedin" onClick="return the_essence_social_share(400, 300, 'https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_html( $post_permalink ); ?>&amp;title=<?php echo esc_html( $post_title ); ?>')" ><span class="fa fa-linkedin"></span></a>
			<a href="mailto:someone@example.com?subject=<?php echo esc_attr( $share_title ); ?>&amp;body=<?php echo esc_attr( $share_status ); ?>" class="social-link-email"><span class="fa fa-envelope-o"></span></a>
		</div><!-- .blog-post-meta-share -->

	</div><!-- .blog-post-meta -->

	<?php if ( has_post_thumbnail() ) : ?>

		<?php
			$gallery_images_arr = the_essence_get_post_meta( get_the_ID(), 'gallery_images' );
		?>

		<?php if ( shortcode_exists('the_essence_gallery') && $gallery_images_arr && count( $gallery_images_arr ) > 1 ) : ?>

			<?php echo do_shortcode( '[the_essence_gallery]' ); ?>

		<?php else : ?>
		
			<div class="blog-post-thumb">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'the-essece-with-sidebar' ); ?></a>
			</div><!-- .blog-post-thumb -->

		<?php endif; ?>

	<?php endif; ?>

	<div class="blog-post-excerpt">
		<?php the_excerpt(); ?>
	</div><!-- .blog-post-excerpt -->

	<div class="blog-post-read-more">
		<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'CONTINUE READING', 'the-essence' ); ?></a>
	</div><!-- .blog-post-read-more -->

	<?php 
		if ( ! is_archive() ) {
			the_essence_related_posts(); 
		}
	?>

</article><!-- .blog-post -->
