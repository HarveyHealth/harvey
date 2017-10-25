<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<h1 class="page-title"><?php the_title(); ?></h1>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="page-thumb">
			<?php the_post_thumbnail( 'large' ); ?>
		</div><!-- .page-thumb -->
	<?php endif; ?>

	<div class="page-content single-content">
		<?php the_content(); ?>
	</div><!-- .page-content -->

</article><!-- #post-## -->
