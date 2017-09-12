<div class="share-post-wrapper">

	<h4 class="section-heading"><?php esc_html_e( 'Share this post', 'the-essence' ); ?></h4>

	<div class="share-post">

		<div class="share-post-count">
			<?php $share_info = the_essence_get_social_count(); ?>
			<?php $total_shares = $share_info['total']; ?>
			<span class="share-post-count-num"><?php echo $share_info['total']; ?></span>
			<span class="share-post-count-text"><?php echo _n( 'share', 'shares', $total_shares, 'the-essence' ); ?></span>
		</div><!-- .share-posot-count -->

		<div class="share-post-links">
			<?php the_essence_social_share_links( get_the_ID() ); ?>
		</div><!-- .share-post-links -->

	</div><!-- .share-post -->

</div><!-- .share-post-wrapper -->