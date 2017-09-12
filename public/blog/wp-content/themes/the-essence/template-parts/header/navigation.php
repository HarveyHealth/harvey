<?php
	$navigation_class = '';
	if ( the_essence_get_theme_mod( 'navigation_sticky_state', 'disabled' ) == 'enabled' )
			$navigation_class = 'init-sticky';
?>
<nav id="navigation" class="<?php echo esc_attr( $navigation_class ); ?>">
	
	<?php if ( the_essence_get_theme_mod( 'side_panel_state', 'enabled' ) == 'enabled' ) : ?>
		<div id="navigation-panel-hook"><span class="fa fa-align-left"></span></div>
	<?php else : ?>
		<div id="mobile-navigation">
			<span class="mobile-navigation-hook"><span class="fa fa-reorder"></span></span>
			<?php the_essence_mobile_nav(); ?>
		</div><!-- #mobile-navigation -->
	<?php endif; ?>
	
	<div id="navigation-main" data-mtst-selector="#navigation .menu > li > a" data-mtst-label="Navigation Items" data-mtst-no-support="background,border">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'fallback_cb' => false ) ); ?>
	</div><!-- #navigation-main -->

	<div id="navigation-search-form">
		<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input name="s" type="text" placeholder="<?php esc_attr_e( 'Enter search term and hit enter', 'the-essence' ); ?>" />
		</form>
	</div><!-- .navigation-search-form -->
	
	<div id="navigation-search-hook"><span class="fa fa-search"></span><span class="fa fa-close"></span></div>

</nav><!-- #navigation -->