<?php
	// Default logo image
	$logo_img_default = false;

	// User defined logo image
	$logo_img_src = the_essence_get_theme_mod( 'logo_panel', $logo_img_default );

	// Logo image class
	$logo_img_class = '';

?>
<div id="panel-overlay"></div>
<div id="panel">

	<div id="panel-inner">

		<?php if ( $logo_img_src ) : ?>
			<div id="panel-logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="<?php echo esc_attr( $logo_img_class );?>" src="<?php echo esc_attr( $logo_img_src ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" /></a>
			</div><!-- #panel-logo -->
		<?php endif; ?>

		<div id="panel-navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'panel', 'menu_id' => 'panel-menu', 'fallback_cb' => false ) ); ?>
		</div><!-- #panel-navigation -->

		<?php if ( is_active_sidebar( 'sidebar-panel' ) ) : ?>
			<div id="panel-widgets">
				<?php dynamic_sidebar( 'sidebar-panel' ); ?>
			</div><!-- #panel-widgets -->
		<?php endif; ?>

	</div><!-- #panel-inner -->

	<span id="panel-close"><span class="fa fa-close"></span></span>

</div><!-- #panel -->