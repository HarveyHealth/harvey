<?php

	include get_template_directory() . '/inc/importer/ajax.php';

	function the_essence_importer_scripts( $hook ) {

		if ( $hook == 'appearance_page_the-essence-getting-started' ) {

			wp_enqueue_style( 'the-essence-importer-style', get_template_directory_uri() . '/inc/importer/css/main.css', array(), '1.0' );
			wp_enqueue_script( 'the-essence-importer-js', get_template_directory_uri() . '/inc/importer/js/main.js', array(), '1.0' );			
			wp_localize_script( 'the-essence-importer-js', 'MTImporterAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		}

	} add_action( 'admin_enqueue_scripts', 'the_essence_importer_scripts' );

	function the_essence_importer_notification() {

		?>

		<div class="mt-importer">
			
			<div class="mt-importer-inner">

				<div class="mt-importer-row">
					<div class="mt-importer-progress">
						<div class="mt-importer-progress-item" data-mt-func-name="install-nav-menus"><span>Setting up navigation menus...</span> <strong>done</strong></div>					
						<div class="mt-importer-progress-item" data-mt-func-name="install-home-page"><span>Setting Up Home Page...</span> <strong>done</strong></div>
						<div class="mt-importer-progress-item" data-mt-func-name="install-contact-page"><span>Setting Up Contact Page...</span> <strong>done</strong></div>
						<div class="mt-importer-progress-item" data-mt-func-name="install-blog-posts"><span>adding blog posts...</span> <strong>done</strong></div>
					</div><!-- .mt-importer-progress -->
				</div><!-- .mt-importer-row -->

				<div class="mt-importer-all" style="clear:both;">
					<div class="mt-importer-button-all">
						<a href="#" class="button button-primary mt-importer-all-hook">OK, Import Demo Content</a>
					</div><!-- .mt-importer-button -->
				</div><!-- .mt-importer-all -->

			</div><!-- .mt-importer-inner -->

		</div>

		<?php

	}