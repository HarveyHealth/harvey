<?php
	/* Outputs on a single post page */
?>

<div class="blog-post-share-aside">
	<?php the_essence_social_share_links( get_the_ID() ); ?>
</div><!-- .blog-post-share-aside -->

<?php if ( has_post_thumbnail() ) : ?>
	<div class="blog-post-single-thumb">
		<?php the_post_thumbnail( 'the-essence-full' ); ?>
	</div><!-- .blog-post-single-thumb -->
<?php endif; ?>

<div class="blog-post-single-content single-content">
	<?php the_content(); ?>
	<div class="blog-post-single-tags">
		<?php the_tags(); ?>
	</div><!-- .blog-post-single-tags -->
</div><!-- .blog-post-single-content -->

<div class="blog-post-single-pagination">
	<?php if ( the_essence_get_theme_mod( 'post_pagination_type', 'number' ) == 'next' ) : ?>
		<?php wp_link_pages( array( 'next_or_number' => 'next', 'before' => '', 'after' => '' ) ); ?>
	<?php else : ?>
		<?php wp_link_pages( array( 'next_or_number' => 'number', 'before' => '', 'after' => '', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
	<?php endif; ?>
</div><!-- .blog-post-single-pagination -->