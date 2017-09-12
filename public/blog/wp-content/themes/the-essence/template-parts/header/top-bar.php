<?php
	
	$is_enabled = false;

	if ( the_essence_get_theme_mod( 'header_top_bar', 'enabled' ) == 'enabled' ) {
		$is_enabled = true;
	}

?>
<?php if ( $is_enabled ) : ?>

	<div id="top-bar" data-mtst-selector="#top-bar" data-mtst-label="Top Bar" data-mtst-no-support="typography,border">
		
		<div class="wrapper clearfix">
			
			<div id="top-bar-navigation" data-mtst-selector="#top-bar-navigation li" data-mtst-label="Top Bar - Navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'top-bar', 'menu_id' => 'top-bar-menu', 'fallback_cb' => false ) ); ?>
			</div><!-- .top-bar-navigation -->

			<div id="top-bar-social" class="clearfix" data-mtst-selector="#top-bar-social a" data-mtst-label="Top Bar - Social" data-mtst-no-support="background,border">
				<?php if ( the_essence_get_theme_mod( 'social_twitter', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_twitter', false ) ); ?>" target="_blank"><span class="fa fa-twitter-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_facebook', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_facebook', false ) ); ?>" target="_blank"><span class="fa fa-facebook-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_youtube', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_youtube', false ) ); ?>" target="_blank"><span class="fa fa-youtube-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_vimeo', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_vimeo', false ) ); ?>" target="_blank"><span class="fa fa-vimeo-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_tumblr', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_tumblr', false ) ); ?>" target="_blank"><span class="fa fa-tumblr-square"></span></a>					
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_pinterest', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_pinterest', false ) ); ?>" target="_blank"><span class="fa fa-pinterest-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_linkedin', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_linkedin', false ) ); ?>" target="_blank"><span class="fa fa-linkedin-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_instagram', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_instagram', false ) ); ?>" target="_blank"><span class="fa fa-instagram"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_github', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_github', false ) ); ?>" target="_blank"><span class="fa fa-github-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_googleplus', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_googleplus', false ) ); ?>" target="_blank"><span class="fa fa-google-plus-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_dribbble', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_dribbble', false ) ); ?>" target="_blank"><span class="fa fa-dribbble"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_dropbox', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_dropbox', false ) ); ?>" target="_blank"><span class="fa fa-dropbox"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_flickr', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_flickr', false ) ); ?>" target="_blank"><span class="fa fa-flickr"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_foursquare', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_foursquare', false ) ); ?>" target="_blank"><span class="fa fa-foursquare"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_behance', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_behance', false ) ); ?>" target="_blank"><span class="fa fa-behance-square"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_vine', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_vine', false ) ); ?>" target="_blank"><span class="fa fa-vine"></span></a>
				<?php endif; ?>
				<?php if ( the_essence_get_theme_mod( 'social_rss', false ) ) : $has_icons = true; ?>
					<a href="<?php echo esc_attr( the_essence_get_theme_mod( 'social_rss', false ) ); ?>" target="_blank"><span class="fa fa-rss-square"></span></a>
				<?php endif; ?>
			</div><!-- .top-bar-social -->

		</div><!-- .wrapper -->

	</div><!-- #top-bar -->

<?php endif; ?>