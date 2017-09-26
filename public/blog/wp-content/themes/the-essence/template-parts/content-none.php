<?php if ( is_search() ) : ?>

	<h2><?php esc_html_e( 'Sorry, Nothing Found!', 'the-essence' ); ?></h2>
	<p><?php esc_html_e( 'Nothing matched your search terms. Please try again with some different keywords.', 'the-essence' ); ?></p>
	<?php get_search_form(); ?>

<?php else : ?>

	<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'the-essence' ); ?></p>
	<?php get_search_form(); ?>

<?php endif; ?>