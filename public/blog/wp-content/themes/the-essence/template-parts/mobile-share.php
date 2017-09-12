<?php if ( is_singular( 'post' ) ) : ?>
	<div class="blog-post-share-mobile">

		<div class="blog-post-share-mobile-count">
			<?php $share_info = the_essence_get_social_count(); ?>
			<?php $total_shares = $share_info['total']; ?>
			<span class="blog-post-share-mobile-count-num"><?php echo $share_info['total']; ?></span>
			<span class="blog-post-share-mobile-count-text"><?php echo _n( 'share', 'shares', $total_shares, 'the-essence' ); ?></span>
		</div><!-- .share-posot-count -->

		<div class="blog-post-share-mobile-links">
			<?php the_essence_social_share_links( get_the_ID() ); ?>
		</div><!-- .share-post-links -->

	</div><!-- .blog-post-share-mobile -->
<?php endif; ?>