<?php get_header(); ?>
	
	<div id="content" class="col col-8">

		<h1 class="section-heading-bg pink"><span><?php esc_html_e( 'Sorry, Nothing Found!', 'the-essence' ); ?></span></h1>
		<p><?php esc_html_e( 'The page you are looking for could not be found. Maybe try the search form below.', 'the-essence' ); ?></p>
		<?php get_search_form(); ?>

	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer();
