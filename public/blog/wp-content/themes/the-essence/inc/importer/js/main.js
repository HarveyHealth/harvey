jQuery(document).ready(function($){

	function initiateImport( progressItem, all ) {

		all = typeof all !== 'undefined' ? all : false;

		funcName = 'mt-ajax-' + progressItem.data('mt-func-name');

		jQuery('.mt-importer-row').show();
		progressItem.show();
		progressItem.find('span').show();

		jQuery.post(

			MTImporterAjax.ajaxurl,
			{
				action : funcName,
			},
			function( response ) {

				if ( response.status == 'success' ) {
					progressItem.find('strong').show();
				} else {
					alert( 'Something went wrong, please try again.' );
				}

				if ( progressItem.next('.mt-importer-progress-item').length ) {
					initiateImport( progressItem.next('.mt-importer-progress-item'), all );
				} else {
					progressItem.after('<hr><strong>All Finished</strong>');
					progressItem.closest('.mt-importer-row').find('.mt-importer-hook').text('Installed');
					if ( all ) {
						if ( progressItem.closest('.mt-importer-row').next('.mt-importer-row').length ) {
							initiateImport( progressItem.closest('.mt-importer-row').next('.mt-importer-row').find('.mt-importer-progress-item:first-child'), all );
						}
					}
				}

			}

		);

	}

	function initiateImportAll() {

		initiateImport( jQuery('.mt-importer-row .mt-importer-progress-item:first-child'), true );

	}

	jQuery(document).on( 'click', '.mt-importer-hook:not(.button-disabled)', function(e){

		e.preventDefault();

		var _this = jQuery(this),
		progress = _this.closest('.mt-importer-row').find('.mt-importer-progress'),
		funcName,
		progressItem;

		// Disable button
		_this.hide();

		// Initiate Import
		initiateImport( progress.find('.mt-importer-progress-item:first-child'), false );

	});

	jQuery(document).on( 'click', '.mt-importer-all-hook:not(.button-disabled)', function(e){

		e.preventDefault();

		var _this = jQuery(this);

		// Disable button
		_this.hide();

		// Initiate Import
		initiateImportAll();

	});

	jQuery(document).on( 'click', '.mt-importer-close-hook', function(e){

		e.preventDefault();		

		jQuery.post(

			MTImporterAjax.ajaxurl,
			{
				action : 'mt-ajax-close-installer'
			},
			function( response ) {

				if ( response.status == 'success' ) {
					jQuery('.mt-importer').slideUp( 200 );
				}

			}

		);

	});
	

});