<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<aside id="sidebar" class="col col-4 col-last">
		<div id="sidebar-inner">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #sidebar-inner -->
	</aside><!-- #sidebar -->
<?php endif; ?>
