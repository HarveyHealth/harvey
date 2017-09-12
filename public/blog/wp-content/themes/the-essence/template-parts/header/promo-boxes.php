<?php

	$is_home = false;

	// if the regular homepage
	if ( is_home() ) {
		$is_home = true;
	}

	// if homepage template
	if ( strpos( get_page_template_slug( get_the_ID() ), 'template-home' ) !== false ) {
		$is_home = true;
	}

	// is enabled
	$is_enabled = false;
	if ( the_essence_get_theme_mod( 'promo_boxes', 'enabled' ) == 'enabled' ) {
		$is_enabled = true;
	}

	// proceed if "home"
	if ( $is_home && $is_enabled ) {

		/**
		 * promo box 1
		 */

		// promo box 1 URL
		$promo_box_1_url = the_essence_get_theme_mod( 'promo_box_1_url', '#' );

		// promo box 1 bg image
		$promo_box_1_bg_image = the_essence_get_theme_mod( 'promo_box_1_bg_image', false );

		// promo box 1 title
		$promo_box_1_title = the_essence_get_theme_mod( 'promo_box_1_title', 'About Myself' );

		// promo box 1 subtitle
		$promo_box_1_subtitle = the_essence_get_theme_mod( 'promo_box_1_subtitle', 'Learn More' );

		/**
		 * promo box 2
		 */

		// promo box 2 URL
		$promo_box_2_url = the_essence_get_theme_mod( 'promo_box_2_url', '#' );

		// promo box 2 bg image
		$promo_box_2_bg_image = the_essence_get_theme_mod( 'promo_box_2_bg_image', false );

		// promo box 2 title
		$promo_box_2_title = the_essence_get_theme_mod( 'promo_box_2_title', 'On Instagram' );

		// promo box 2 subtitle
		$promo_box_2_subtitle = the_essence_get_theme_mod( 'promo_box_2_subtitle', 'Follow Me' );

		/**
		 * promo box 3
		 */

		// promo box 3 URL
		$promo_box_3_url = the_essence_get_theme_mod( 'promo_box_3_url', '#' );

		// promo box 3 bg image
		$promo_box_3_bg_image = the_essence_get_theme_mod( 'promo_box_3_bg_image', false );

		// promo box 3 title
		$promo_box_3_title = the_essence_get_theme_mod( 'promo_box_3_title', 'My Travels' );

		// promo box 3 subtitle
		$promo_box_3_subtitle = the_essence_get_theme_mod( 'promo_box_3_subtitle', 'Discover' );

		/**
		 * promo box 4
		 */

		// promo box 4 URL
		$promo_box_4_url = the_essence_get_theme_mod( 'promo_box_4_url', '#' );

		// promo box 4 bg image
		$promo_box_4_bg_image = the_essence_get_theme_mod( 'promo_box_4_bg_image', false );

		// promo box 4 title
		$promo_box_4_title = the_essence_get_theme_mod( 'promo_box_4_title', '' );

		// promo box 4 subtitle
		$promo_box_4_subtitle = the_essence_get_theme_mod( 'promo_box_4_subtitle', '' );

		/**
		 * promo box 5
		 */

		// promo box 5 URL
		$promo_box_5_url = the_essence_get_theme_mod( 'promo_box_5_url', '#' );

		// promo box 5 bg image
		$promo_box_5_bg_image = the_essence_get_theme_mod( 'promo_box_5_bg_image', false );

		// promo box 5 title
		$promo_box_5_title = the_essence_get_theme_mod( 'promo_box_5_title', '' );

		// promo box 5 subtitle
		$promo_box_5_subtitle = the_essence_get_theme_mod( 'promo_box_5_subtitle', '' );

		/**
		 * promo box 6
		 */

		// promo box 6 URL
		$promo_box_6_url = the_essence_get_theme_mod( 'promo_box_6_url', '#' );

		// promo box 6 bg image
		$promo_box_6_bg_image = the_essence_get_theme_mod( 'promo_box_6_bg_image', false );

		// promo box 6 title
		$promo_box_6_title = the_essence_get_theme_mod( 'promo_box_6_title', '' );

		// promo box 6 subtitle
		$promo_box_6_subtitle = the_essence_get_theme_mod( 'promo_box_6_subtitle', '' );

	}

?>

<?php if ( $is_home ) : ?>

	<div class="promo-boxes clearfix">

		<div class="wrapper">

			<?php if ( $promo_box_1_title || $promo_box_1_subtitle ) : ?>

				<div class="promo-box promo-box-1 col col-4" <?php if ( $promo_box_1_bg_image ) echo 'style="background-image:url(' . $promo_box_1_bg_image . ')"'; ?> data-mtst-selector=".promo-box" data-mtst-label="Promo Box" data-mtst-no-support="typography">

					<div class="promo-box-inner" data-mtst-selector=".promo-box-inner" data-mtst-label="Promo Box - Inner" data-mtst-no-support="typography">

						<?php if ( $promo_box_1_subtitle ) : ?>
							<div class="promo-box-secondary" data-mtst-selector=".promo-box-secondary" data-mtst-label="Promo Box - Subtitle" data-mtst-no-support="background,border"><?php echo $promo_box_1_subtitle; ?></div>
						<?php endif; ?>

						<?php if ( $promo_box_1_title ) : ?>
							<h2 class="promo-box-primary" data-mtst-selector=".promo-box-primary" data-mtst-label="Promo Box - Title" data-mtst-no-support="background,border"><?php echo $promo_box_1_title; ?></h2>
						<?php endif; ?>

					</div><!-- .promo-box-inner -->

					<?php if ( $promo_box_1_url ) : ?>
						<a href="<?php echo $promo_box_1_url; ?>" class="promo-box-link"></a>
					<?php endif; ?>

				</div><!-- .promo-box -->

			<?php endif; ?>

			<?php if ( $promo_box_2_title || $promo_box_2_subtitle ) : ?>

				<div class="promo-box promo-box-2 col col-4" <?php if ( $promo_box_2_bg_image ) echo 'style="background-image:url(' . $promo_box_2_bg_image . ')"'; ?>>

					<div class="promo-box-inner">

						<?php if ( $promo_box_2_subtitle ) : ?>
							<div class="promo-box-secondary"><?php echo $promo_box_2_subtitle; ?></div>
						<?php endif; ?>

						<?php if ( $promo_box_2_title ) : ?>
							<h2 class="promo-box-primary"><?php echo $promo_box_2_title; ?></h2>
						<?php endif; ?>

					</div><!-- .promo-box-inner -->

					<?php if ( $promo_box_2_url ) : ?>
						<a href="<?php echo $promo_box_2_url; ?>" class="promo-box-link"></a>
					<?php endif; ?>

				</div><!-- .promo-box -->

			<?php endif; ?>

			<?php if ( $promo_box_3_title || $promo_box_3_subtitle ) : ?>

				<div class="promo-box promo-box-3 col col-4 col-last" <?php if ( $promo_box_3_bg_image ) echo 'style="background-image:url(' . $promo_box_3_bg_image . ')"'; ?>>

					<div class="promo-box-inner">

						<?php if ( $promo_box_3_subtitle ) : ?>
							<div class="promo-box-secondary"><?php echo $promo_box_3_subtitle; ?></div>
						<?php endif; ?>

						<?php if ( $promo_box_3_title ) : ?>
							<h2 class="promo-box-primary"><?php echo $promo_box_3_title; ?></h2>
						<?php endif; ?>

					</div><!-- .promo-box-inner -->

					<?php if ( $promo_box_3_url ) : ?>
						<a href="<?php echo $promo_box_3_url; ?>" class="promo-box-link"></a>
					<?php endif; ?>

				</div><!-- .promo-box -->

			<?php endif; ?>

			<?php if ( $promo_box_4_title || $promo_box_4_subtitle ) : ?>

				<div class="promo-box promo-box-4 col col-4" <?php if ( $promo_box_4_bg_image ) echo 'style="background-image:url(' . $promo_box_4_bg_image . ')"'; ?> data-mtst-selector=".promo-box" data-mtst-label="Promo Box" data-mtst-no-support="typography">

					<div class="promo-box-inner" data-mtst-selector=".promo-box-inner" data-mtst-label="Promo Box - Inner" data-mtst-no-support="typography">

						<?php if ( $promo_box_4_subtitle ) : ?>
							<div class="promo-box-secondary" data-mtst-selector=".promo-box-secondary" data-mtst-label="Promo Box - Subtitle" data-mtst-no-support="background,border"><?php echo $promo_box_4_subtitle; ?></div>
						<?php endif; ?>

						<?php if ( $promo_box_4_title ) : ?>
							<h2 class="promo-box-primary" data-mtst-selector=".promo-box-primary" data-mtst-label="Promo Box - Title" data-mtst-no-support="background,border"><?php echo $promo_box_4_title; ?></h2>
						<?php endif; ?>

					</div><!-- .promo-box-inner -->

					<?php if ( $promo_box_4_url ) : ?>
						<a href="<?php echo $promo_box_4_url; ?>" class="promo-box-link"></a>
					<?php endif; ?>

				</div><!-- .promo-box -->

			<?php endif; ?>

			<?php if ( $promo_box_5_title || $promo_box_5_subtitle ) : ?>

				<div class="promo-box promo-box-5 col col-4" <?php if ( $promo_box_5_bg_image ) echo 'style="background-image:url(' . $promo_box_5_bg_image . ')"'; ?> data-mtst-selector=".promo-box" data-mtst-label="Promo Box" data-mtst-no-support="typography">

					<div class="promo-box-inner" data-mtst-selector=".promo-box-inner" data-mtst-label="Promo Box - Inner" data-mtst-no-support="typography">

						<?php if ( $promo_box_5_subtitle ) : ?>
							<div class="promo-box-secondary" data-mtst-selector=".promo-box-secondary" data-mtst-label="Promo Box - Subtitle" data-mtst-no-support="background,border"><?php echo $promo_box_5_subtitle; ?></div>
						<?php endif; ?>

						<?php if ( $promo_box_5_title ) : ?>
							<h2 class="promo-box-primary" data-mtst-selector=".promo-box-primary" data-mtst-label="Promo Box - Title" data-mtst-no-support="background,border"><?php echo $promo_box_5_title; ?></h2>
						<?php endif; ?>

					</div><!-- .promo-box-inner -->

					<?php if ( $promo_box_5_url ) : ?>
						<a href="<?php echo $promo_box_5_url; ?>" class="promo-box-link"></a>
					<?php endif; ?>

				</div><!-- .promo-box -->

			<?php endif; ?>

			<?php if ( $promo_box_6_title || $promo_box_6_subtitle ) : ?>

				<div class="promo-box promo-box-6 col col-4 col-last" <?php if ( $promo_box_6_bg_image ) echo 'style="background-image:url(' . $promo_box_6_bg_image . ')"'; ?> data-mtst-selector=".promo-box" data-mtst-label="Promo Box" data-mtst-no-support="typography">

					<div class="promo-box-inner" data-mtst-selector=".promo-box-inner" data-mtst-label="Promo Box - Inner" data-mtst-no-support="typography">

						<?php if ( $promo_box_6_subtitle ) : ?>
							<div class="promo-box-secondary" data-mtst-selector=".promo-box-secondary" data-mtst-label="Promo Box - Subtitle" data-mtst-no-support="background,border"><?php echo $promo_box_6_subtitle; ?></div>
						<?php endif; ?>

						<?php if ( $promo_box_6_title ) : ?>
							<h2 class="promo-box-primary" data-mtst-selector=".promo-box-primary" data-mtst-label="Promo Box - Title" data-mtst-no-support="background,border"><?php echo $promo_box_6_title; ?></h2>
						<?php endif; ?>

					</div><!-- .promo-box-inner -->

					<?php if ( $promo_box_6_url ) : ?>
						<a href="<?php echo $promo_box_6_url; ?>" class="promo-box-link"></a>
					<?php endif; ?>

				</div><!-- .promo-box -->

			<?php endif; ?>

		</div><!-- .wrapper -->

	</div><!-- .promo-boxes -->

<?php endif; ?>