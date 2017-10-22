<?php
/**
 * Table of Contents
 *
 * the_essence_posts_pagination ( Outputs post pagination )
 * the_essence_comment_layout ( Template for comments and pingbacks )
 * the_essence_related_posts ( Output related posts )
 * the_essence_social_share_links ( outputs social sharing links )
 */

if ( ! function_exists( 'the_essence_posts_pagination' ) ) : 

	/**
	 * Output post pagination
	 *
	 * @since 1.0
	 */
	function the_essence_posts_pagination( $atts = false ) {

		// The output will be stored here
		$output = '';		

		if ( is_numeric( get_query_var( 'page' ) ) ) { $paged = get_query_var( 'page' ); } elseif ( is_numeric( get_query_var( 'paged' ) ) ) { $paged = get_query_var( 'paged' ); } else { $paged = 1; }

		if ( ! isset( $atts['force_number'] ) ) $force_number = false; else $force_number = $atts['force_number'];
		if ( ! isset( $atts['pages'] ) ) $pages = false; else $pages = $atts['pages'];
		if ( ! isset( $atts['type'] ) ) $type = 'loadmore'; else $type = $atts['type'];
		
		// auto load more
		$loadmore_auto = false;
		if ( $type == 'loadmore_auto' ) {
			$type = 'loadmore';
			$loadmore_auto = true;
		}

		$range = 2;

		$showitems = ($range * 2)+1;  

		if ( empty ( $paged ) ) { $paged = 1; }

		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( ! $pages ) {
				$pages = 1;
			}
		}

		if( 1 != $pages ) {

			?>
			<div class="pagination pagination-type-<?php echo esc_attr( $type ); if ( $loadmore_auto ) echo ' pagination-type-load-more-auto'; ?>">
				<ul class="clearfix">
					<?php

						if ( $type == 'numbered' ) {

							if($paged > 2 && $paged > $range+1 && $showitems < $pages) { echo "<li class='inactive'><a href='".get_pagenum_link(1)."'>&laquo;</a></li>"; }
							if($paged > 1 && $showitems < $pages) { echo "<li class='inactive'><a href='".get_pagenum_link($paged - 1)."' >&lsaquo;</a></li>"; }

							for ($i=1; $i <= $pages; $i++){
								if (1 != $pages &&(!($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems)){
									echo ($paged == $i)? "<li class='active'><a href='".get_pagenum_link($i)."'>".$i."</a></li>":"<li class='inactive'><a class='inactive' href='".get_pagenum_link($i)."'>".$i."</a></li>";
								}
							}

							if ($paged < $pages && $showitems < $pages) { echo "<li class='inactive'><a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a></li>"; } 
							if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) { echo "<li class='inactive'><a href='".get_pagenum_link($pages)."'>&raquo;</a></li>"; }

						} elseif ( $type == 'prevnext' ) {

							if($paged > 1 ) { echo "<li class='inactive float-left'><a href='".get_pagenum_link($paged - 1)."' >" . esc_html__( 'Newer', 'the-essence' ) . "</a></li>"; }
							if ($paged < $pages ) { echo "<li class='inactive float-right'><a href='".get_pagenum_link($paged + 1)."'>" . esc_html__( 'Older', 'the-essence' ) . "</a></li>"; } 

						} elseif ( $type == 'default' ) {

							posts_nav_link();

						}

						if ( $type == 'loadmore' ) {
							if ($paged < $pages ) { 
								echo "<li class='pagination-load-more active' data-mtst-selector='.pagination-load-more a' data-mtst-label='Pagination'><span class='pagination-load-more-line'></span><a href='".get_pagenum_link($paged + 1)."'><span class='fa fa-refresh'></span>" . esc_html__( 'LOAD MORE POSTS', 'the-essence' ) . "</a></li>";
							} else {
								echo "<li class='pagination-load-more inactive'><span class='pagination-load-more-line'></span><a href='#'>" . esc_html__( 'NO MORE POSTS', 'the-essence' ) . "</a></li>";
							}
						}
						
					?>
				</ul>

				<?php if ( $type == 'loadmore' ) : ?>
					<div class="load-more-temp"></div>
				<?php endif; ?>

			</div><!-- .pagination --><?php
		}

	}

endif;  // End if mdrt_posts_slider exists

if ( ! function_exists( 'the_essence_comment_layout' ) ) :

	/**
	 * Template for comments and pingbacks.
	 *
	 * @since 1.0
	 */
	function the_essence_comment_layout( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		
		switch ( $comment->comment_type ) :
			
			case 'pingback' :
			case 'trackback' :
				?>
				<li class="comments-pingback">
					<p><?php esc_html_e( 'Pingback:', 'the-essence' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'the-essence' ), ' ' ); ?></p>
				<?php
			break;
			default :

				if ( $comment->comment_approved == '1' ) :

					?>

					<li <?php comment_class( 'comment' ); ?> id="comment-<?php comment_ID(); ?>">

						<div class="comment-inner">

							<span class="comment-author-avatar"><?php echo get_avatar( $comment, 60 ); ?></span>

							<div class="comment-info clearfix">

								<ul class="comment-meta clearfix">
									<li class="comment-meta-author"><?php echo get_comment_author_link(); ?></li>
									<li class="comment-meta-date"><?php echo get_comment_date(); ?></li>
								</ul>

								<span class="comment-reply">
									<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
								</span>

							</div><!-- .comment-info -->

							<div class="comment-main clearfix">
								
								<?php if ( $comment->comment_approved == '0' ) : ?>
									<p><em><?php esc_html_e( 'Your comment is awaiting moderation.', 'the-essence' ); ?></em></p>
								<?php endif; ?>
								<?php comment_text(); ?>

							</div><!-- .comment-main -->

						</div><!-- .comment-inner -->

					<?php

				endif;

				break;
		endswitch;

	}

endif; // End if the_essence_comment_layout

function the_essence_related_posts() {

	// current post id
	$current_post_id = get_the_ID();

	// current post categories
	$current_post_categories = wp_get_post_categories( $current_post_id );

	$related_posts = get_posts( array(
		'numberposts'		=>	3,
		'category'			=>	$current_post_categories,
		'orderby'			=>	'rand',
		'exclude'			=>	$current_post_id,
	));

	$count = 0;

	if ( is_array( $related_posts ) && count( $related_posts ) >= 1 ) : ?>
		
		<div class="related-posts-wrapper">

			<h2 class="section-heading pink"><?php esc_html_e( 'Related Posts', 'the-essence' ); ?></h2>

			<div class="related-posts clearfix">

				<?php foreach ( $related_posts as $related_post ) : $count++ ?>

					<?php 
						$author_id = $related_post->post_author;
						$author_nicename = get_userdata( $author_id );
						$author_nicename = $author_nicename->display_name;
					?>

					<div class="col col-4 <?php if ( $count == 3 ) echo 'col-last'; ?> blog-post-s4 not-masonry-item">
						
						<?php if ( has_post_thumbnail( $related_post->ID ) ) : ?>
							<div class="blog-post-s4-thumb">
								<a href="<?php echo get_permalink( $related_post->ID ); ?>"><?php echo get_the_post_thumbnail( $related_post->ID, 'the-essence-s4-4' ); ?></a>
							</div><!-- .blog-post-s4-thumb -->
						<?php endif; ?>

						<div class="blog-post-s4-main">

							<div class="blog-post-s4-meta">
								<span class="blog-post-s4-meta-author" data-mtst-selector=".related-posts .blog-post-s4-meta-author a" data-mtst-label="Related Post - Author" data-mtst-no-support="background,border,spacing"><a href="<?php echo get_author_posts_url( $author_id, $author_nicename ); ?>"><?php echo $author_nicename; ?></a></span>
								<span class="blog-post-s4-meta-separator"></span>
								<span class="blog-post-s4-meta-date" data-mtst-selector=".related-posts .blog-post-s4-meta-date" data-mtst-label="Related Post - Date" data-mtst-no-support="background,border,spacing"><?php 
									if ( the_essence_get_theme_mod( 'date_format', 'timeago' ) == 'timeago' ) {
										printf( esc_html__( '%1$s ago', 'the-essence' ), human_time_diff( get_the_time( 'U', $related_post->ID ), current_time('timestamp') ) ); 
									} else {
										the_time( get_option( 'date_format' ) );
									}
								?></span>
							</div><!-- .blog-post-s4-meta -->

							<h4 class="blog-post-s4-title" data-mtst-selector=".related-posts .blog-post-s4-title" data-mtst-label="Related Post - Title" data-mtst-no-support="background,border,spacing"><a href="<?php echo get_permalink( $related_post->ID ); ?>"><?php echo get_the_title( $related_post->ID ); ?></a></h4>

						</div><!-- .blog-post-s4-main -->

					</div><!-- .blog-post-s4 -->

				<?php endforeach; ?>

			</div><!-- .related-posts -->

		</div><!-- .related-posts-wrapper -->

	<?php endif; ?>

	<?php

}

if ( ! function_exists( 'the_essence_social_share_links' ) ) : 

	/**
	 * output social sharing links
	 *
	 * @since 1.0
	 */
	function the_essence_social_share_links( $post_id ) {
		
		// vars
		$post_permalink = get_permalink( $post_id );
		$post_title = str_replace( '&#038;', '', get_the_title( $post_id ) );
		$post_img = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );

		?>
		<a href="#" class="social-link-facebook" target="_blank" onClick="return the_essence_social_share(400, 300, 'http://www.facebook.com/share.php?u=<?php echo esc_html( $post_permalink ); ?>')"><span class="fa fa-facebook-square"></span></a>
		<a href="#" class="social-link-pinterest" onClick="return the_essence_social_share(400, 300, 'https://pinterest.com/pin/create/button/?url=<?php echo esc_html( $post_permalink ); ?>&amp;media=<?php echo esc_html( $post_img ); ?>')"><span class="fa fa-pinterest-square"></span></a>
		<a href="#" class="social-link-twitter" onClick="return the_essence_social_share(400, 300, 'https://twitter.com/home?status=<?php echo esc_html( $post_title . ' ' . $post_permalink ); ?>')" ><span class="fa fa-twitter"></span></a>
		<a href="#" class="social-link-google-plus" onClick="return the_essence_social_share(400, 300, 'https://plus.google.com/share?url=<?php echo esc_html( $post_permalink ); ?>')" ><span class="fa fa-google-plus"></span></a>
		<a href="#" class="social-link-linkedin" onClick="return the_essence_social_share(400, 300, 'https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_html( $post_permalink ); ?>&amp;title=<?php echo esc_html( $post_title ); ?>')" ><span class="fa fa-linkedin"></span></a>
		<a href="mailto:someone@example.com?subject=<?php echo rawurlencode( $post_title ); ?>&amp;body=<?php echo rawurlencode( $post_title . ' ' . $post_permalink ); ?>" class="social-link-email"><span class="fa fa-envelope-o"></span></a>
		<?php

	}

endif; // end if statement for function exists

if ( ! function_exists( 'the_essence_mobile_nav' ) ) :

	/**
	 * Handles output of mobile nav
	 *
	 * @since 1.0
	 */
	function the_essence_mobile_nav() {

		$mobile_nav_output = '';
		if( has_nav_menu('primary') ) {
			
			$locations = get_nav_menu_locations();
			$menu = wp_get_nav_menu_object($locations['primary']);
			$menu_items = wp_get_nav_menu_items($menu->term_id);
			$mobile_nav_output = '';
			
			?>

			<select>
				<option><?php esc_html_e( '- Select Page -', 'the-essence' ); ?></option>
				<?php foreach ( $menu_items as $key => $menu_item ) : ?>
					<?php
						$title = $menu_item->title;
						$url = $menu_item->url;
						$nav_selected = '';
						//if($menu_item->object_id == get_the_ID()){ $nav_selected = 'selected="selected"'; }
					?>
					<?php if($menu_item->post_parent !== 0) : ?>
						<option value="<?php echo esc_url( $url ); ?>"> - <?php echo esc_html( $title ); ?></option>
					<?php else : ?>
						<option value="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			<?php

		}

	}

endif; // End if the_essence_mobile_nav