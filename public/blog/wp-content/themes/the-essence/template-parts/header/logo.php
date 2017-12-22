<?php

// logo type
$logo_type = the_essence_get_theme_mod( 'logo_type', 'image' );

// if image
if ( $logo_type == 'image' ) {

	// default logo image
	$logo_img_default = get_template_directory_uri() . '/images/logo.png';

	// user defined logo image
	$logo_img_src = the_essence_get_theme_mod( 'logo', $logo_img_default );
	$logo_img_retina_src = the_essence_get_theme_mod( 'logo_retina', '' );

	// logo image class
	$logo_img_class = '';
	if ( $logo_img_retina_src !== '' ) {
		$logo_img_class = 'has-retina-ver';
	}

}

?>
<div id="logo" class="logo-type-<?php echo esc_attr( $logo_type ); ?>" data-mtst-selector="#logo" data-mtst-label="Logo" data-mtst-no-support="typography,border">
	<?php if ( $logo_type == 'image' ) : ?>
		<?php if ( is_singular( array( 'post', 'page' ) ) ) : ?><h2><?php else : ?><h1><?php endif ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="visuallyhidden"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				<img alt="" class="<?php echo esc_attr( $logo_img_class );?>" src="<?php echo esc_attr( $logo_img_src ); ?>" data-retina-ver="<?php echo esc_attr( $logo_img_retina_src ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
			</a>
		<?php if ( is_singular( array( 'post', 'page' ) ) ) : ?></h2><?php else : ?></h1><?php endif ?>
	<?php elseif ( $logo_type == 'text' ) : ?>
		<?php if ( is_singular( array( 'post', 'page' ) ) ) : ?>
			<h1 class="logo-title" data-mtst-selector=".logo-title" data-mtst-label="Logo Title" data-mtst-no-support="border"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a></h1>
			<h2 class="logo-subtitle" data-mtst-selector=".logo-subtitle" data-mtst-label="Logo Subtitle" data-mtst-no-support="border"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></a></h2>
		<?php else : ?>
			<h2 class="logo-title" data-mtst-selector=".logo-title" data-mtst-label="Logo Title" data-mtst-no-support="border"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a></h2>
			<h3 class="logo-subtitle" data-mtst-selector=".logo-subtitle" data-mtst-label="Logo Subtitle" data-mtst-no-support="border"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></a></h3>
		<?php endif; ?>
	<?php endif; ?>
</div><!-- #logo -->