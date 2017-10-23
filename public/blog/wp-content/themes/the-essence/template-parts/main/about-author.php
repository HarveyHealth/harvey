<?php
	
	// Get author ID	
	if ( get_the_author_meta( 'ID' ) )
		$author_id = get_the_author_meta( 'ID' );
	else
		$author_id = 1;

?>

<div class="about-author clearfix">
					
	<div class="about-author-sidebar">
		<div class="about-author-avatar">
			<?php echo get_avatar( $author_id , 75 ); ?>
		</div><!-- .about-author-avatar -->
		<div class="about-author-sidebar-main">
			<span class="about-author-name"><?php echo esc_html_e( 'by', 'the-essence' ); ?> <?php the_author_posts_link(); ?></span>
			<span class="about-author-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
		</div><!-- .about-author-sidebar-main -->
	</div><!-- .about-author-sidebar -->

	<div class="about-author-main">

		<div class="about-author-bio">
			<?php echo get_the_author_meta( 'description', $author_id ); ?>
		</div><!-- .about-author-bio -->

		<div class="about-author-social">
			<?php if ( get_the_author_meta( 'the_essence_twitter', $author_id ) ) : ?>
				<a class="social-link-twitter" href="<?php echo get_the_author_meta( 'the_essence_twitter' ); ?>"><span class="fa fa-twitter"></span></a>
			<?php endif; ?>
			<?php if ( get_the_author_meta( 'the_essence_facebook', $author_id ) ) : ?>
				<a class="social-link-facebook" href="<?php echo get_the_author_meta( 'the_essence_facebook' ); ?>"><span class="fa fa-facebook"></span></a>
			<?php endif; ?>
			<?php if ( get_the_author_meta( 'the_essence_instagram', $author_id ) ) : ?>
				<a class="social-link-instagram" href="<?php echo get_the_author_meta( 'the_essence_instagram' ); ?>"><span class="fa fa-instagram"></span></a>
			<?php endif; ?>
			<?php if ( get_the_author_meta( 'the_essence_behance', $author_id ) ) : ?>
				<a class="social-link-behance" href="<?php echo get_the_author_meta( 'the_essence_behance' ); ?>"><span class="fa fa-behance"></span></a>
			<?php endif; ?>
			<?php if ( get_the_author_meta( 'the_essence_dribbble', $author_id ) ) : ?>
				<a class="social-link-dribbble" href="<?php echo get_the_author_meta( 'the_essence_dribbble' ); ?>"><span class="fa fa-dribbble"></span></a>
			<?php endif; ?>
			<?php if ( get_the_author_meta( 'the_essence_vine', $author_id ) ) : ?>
				<a class="social-link-vine" href="<?php echo get_the_author_meta( 'the_essence_dribbble' ); ?>"><span class="fa fa-vine"></span></a>
			<?php endif; ?>
			<?php if ( get_the_author_meta( 'the_essence_linkedin', $author_id ) ) : ?>
				<a class="social-link-linkedin" href="<?php echo get_the_author_meta( 'the_essence_linkedin' ); ?>"><span class="fa fa-linkedin"></span></a>
			<?php endif; ?>
		</div><!-- .about-author-social -->

	</div><!-- .about-author-main -->

</div><!-- .about-author -->