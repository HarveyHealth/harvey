<?php if ( is_active_sidebar( 'sidebar-2' ) && the_essence_get_theme_mod( 'footer_widgets', 'enabled' ) == 'enabled' ) : ?>

	<div id="footer-widgets">
		
		<div class="wrapper clearfix">

			<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
				<div class="footer-widgets-1 col col-4">
					<?php dynamic_sidebar( 'sidebar-2' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
				<div class="footer-widgets-2 col col-4">
					<?php dynamic_sidebar( 'sidebar-3' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
				<div class="footer-widgets-3 col col-4 col-last">
					<?php dynamic_sidebar( 'sidebar-4' ); ?>
				</div>
			<?php endif; ?>

		</div><!-- .wrapper -->

	</div><!-- #footer-widgets -->

<?php endif; ?>