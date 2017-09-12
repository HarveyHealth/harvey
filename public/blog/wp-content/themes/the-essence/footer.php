			</div><!-- .wrapper -->

		</div><!-- #main -->

		<?php get_template_part( 'template-parts/footer/posts' ); ?>

		<footer id="footer" class="site-footer">

			<?php get_template_part( 'template-parts/footer/widgets' ); ?>

			<?php get_template_part( 'template-parts/footer/instagram' ); ?>

			<?php get_template_part( 'template-parts/footer/bottom' ); ?>

		</footer><!-- #footer -->

	</div><!-- #page -->

	<?php get_template_part( 'template-parts/mobile-share' ); ?>

	<?php 
		if ( the_essence_get_theme_mod( 'side_panel_state', 'enabled' ) == 'enabled' )
			get_template_part( 'template-parts/side-panel' ); 
	?>

	<?php wp_footer(); ?>

</body>
</html>
