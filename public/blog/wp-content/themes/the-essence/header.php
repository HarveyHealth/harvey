<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	
	<!-- Meta -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Link -->
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!-- WP Head -->
	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

	<div id="page" class="site">

		<?php get_template_part( 'template-parts/header/top-bar' ); ?>

		<header id="header" class="site-header">
			
			<div class="wrapper clearfix">

				<?php get_template_part( 'template-parts/header/logo' ); ?>

				<?php get_template_part( 'template-parts/header/navigation' ); ?>

			</div><!-- .wrapper -->
			
		</header><!-- #header -->

		<?php get_template_part( 'template-parts/header/tagline' ); ?>

		<?php get_template_part( 'template-parts/header/horizontal-carousel' ); ?>

		<?php get_template_part( 'template-parts/header/vertical-carousel' ); ?>

		<?php get_template_part( 'template-parts/header/promo-boxes' ); ?>

		<div id="main" class="site-content">

			<div class="wrapper clearfix">