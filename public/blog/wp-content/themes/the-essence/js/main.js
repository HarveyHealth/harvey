"use strict";

/**
 * Table Of Contents
 *
 * the_essence_masonry ( masonry )
 * the_essence_social_share ( social sharing )
 * the_essence_carousel ( carousel )
 * the_essence_retina_img_replace ( retina images )
 * the_essence_first_letter_cap ( first letter into big cap )
 * the_essence_mobile_images ( replace smaller images with bigger versions )
 * the_essence_center_top_carousel ( centers the text in the top carousel )
 * document ready
 * window load
 */

/**
 * initiate masonry
 */
function the_essence_masonry() {

	if ( jQuery(window).width() > 760 ) {		

		var gutter = parseInt( jQuery('.masonry-init').width() / 100 * 2.76 );

		jQuery('.masonry-init').masonry({
			itemSelector: '.post',
			columnWidth: '.masonry-init .post',
			gutter: gutter,
			percentPosition: true,
		});

	}

}

/**
 * social sharing
 */
function the_essence_social_share( width, height, url ) {

	// vars
	var leftPosition, topPosition, u, t, windowFeatures;

	// positions
	leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
	topPosition = (window.screen.height / 2) - ((height / 2) + 50);
	
	// window features
	windowFeatures = "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";

	// other
	u=location.href;
	t=document.title;

	// open up new window
	window.open(url,'sharer', windowFeatures);

	// return
	return false;

}

/**
 * carousel
 */
function the_essence_carousel() {
	
	// vars
	var pagination,
	items;

	// each carousel
	jQuery('.carousel').each(function(){

		jQuery(this).show();

		// pagination
		if ( jQuery(this).data('pagination') == true ) {
			pagination = true;
		} else {
			pagination = false;
		}

		// items
		if ( jQuery(this).data('items') ) {
			items = jQuery(this).data('items');
		} else {
			items = 3;
		}

		// vars
		var firstItem = jQuery(this).find('.carousel-item:first'),
		slider = jQuery(this),
		spacing = jQuery('.wrapper').width() / 100 * 2.76 / 2;

		// no spacing
		if ( slider.closest('.no-col-spacing').length ) {
			spacing = 0;
		}

		// apply sizes
		slider.find('.carousel-item').css({ 'padding-left' : spacing, 'padding-right' : spacing });
		slider.css({ 'margin-left' : spacing * -1, 'width' : jQuery('.wrapper').width() + spacing * 2 });

		// initiate carousel
		jQuery(this).owlCarousel({
			slideSpeed : 500,
			mouseDrag : true,
			pagination : pagination,
			scrollPerPage: true,
			items: items,
			itemsDesktop : [ 1500, items ],
			itemsDesktopSmall : [ 1280, items ],
			itemsTablet : [1024,items],
			itemsMobile : [767,1],
			afterAction: function( carousel ){
				var visible_items = this.owl.visibleItems;
				carousel.find('.carousel-item-visible').removeClass('carousel-item-visible');
				carousel.find('.owl-item').filter(function(index) {
					return visible_items.indexOf(index) > -1;
				}).addClass('carousel-item-visible');				
			},
		});

	});

	// prev
	jQuery(document).on( 'click', '.carousel-nav-prev', function() {
		var carousel = jQuery(this).closest('.carousel-wrapper').find('.carousel');		
		carousel.trigger('owl.prev');
	});

	// next
	jQuery(document).on( 'click', '.carousel-nav-next', function() {
		var carousel = jQuery(this).closest('.carousel-wrapper').find('.carousel');
		carousel.trigger('owl.next');
	});

}

/**
 * retina images
 */
function the_essence_retina_img_replace() {

	// each image with retina
	jQuery('img.has-retina-ver').each(function(){

		// replace img source
		jQuery(this)
			.css({ height : jQuery(this).height(), width : jQuery(this).width() })
			.attr( 'src', jQuery(this).data('retina-ver') );		

	});

}

/**
 * first leter into big cap
 */
function the_essence_first_letter_cap() {

	if ( jQuery('body').hasClass('body-capitalize-letter-enabled') ) {

		jQuery('.page-content:not(:has(.blog-post-excerpt-big-cap)) > p:first-child, .blog-post-excerpt:not(:has(.blog-post-excerpt-big-cap)) > p:first-child, .blog-post-single-content:not(:has(.blog-post-excerpt-big-cap)) > p:first-of-type').html(function (i, html) {
			
			var firstLetter = html.charAt(0);
			if ( firstLetter !== '<' ) {
				return html.replace( firstLetter, '<span class="blog-post-excerpt-big-cap">' + firstLetter + '</span>');
			}

		});

	}

}

/**
 * replace smaller images with bigger versions
 */
function the_essence_mobile_images() {

	if ( jQuery(window).width() <= 767 ) {

		var images = jQuery('img[data-mobile-version]'),
		mobileVersion = '';

		images.each(function(){

			mobileVersion = jQuery(this).data('mobile-version');

			if ( mobileVersion !== '' ) {
				jQuery(this).attr( 'src', mobileVersion );
			}

		});

	}

	if ( jQuery(window).width() <= 1023 && jQuery(window).width() > 767 ) {

		var images = jQuery('img[data-tablet-version]'),
		tabletVersion = '';

		images.each(function(){

			tabletVersion = jQuery(this).data('tablet-version');

			if ( tabletVersion !== '' ) {
				jQuery(this).attr( 'src', tabletVersion );
			}

		});

	}

}

/**
 * centers the text in the top carousel 
 */
function the_essence_center_top_carousel() {

	if ( jQuery('.horizontal-carousel').length ) {

		var carousel = jQuery('.horizontal-carousel'),
		items = carousel.find('.blog-post-s2'),
		itemTitle,
		offset;

		items.each(function(){

			itemTitle = jQuery(this).find('.blog-post-s2-title');

			if ( jQuery(window).width() <= 1023 && jQuery(window).width() >= 768 ) {
				itemTitle.css({ paddingTop : '13px' });
			} else {
				offset = jQuery(this).height() / 2 - itemTitle.height() / 2;
				itemTitle.css({ paddingTop : offset });
			}

		});

	}

}

/**
 * maybe trigger load more
 */
function the_essence_maybe_trigger_load_more() {

	// if there is a load more button
	if ( jQuery('.pagination-type-load-more-auto').length ) {		

		var loadMoreButton = jQuery('.pagination-type-load-more-auto a'),
		loadMoreOffset = loadMoreButton.offset().top,
		currentOffset = loadMoreOffset - jQuery(window).scrollTop() - jQuery(window).height();

		if ( currentOffset < 300 ) {
			loadMoreButton.trigger('click');
		}

	}

}

/**
 * document ready
 */
jQuery(document).ready(function($){

	the_essence_first_letter_cap();

	the_essence_mobile_images();

	// sticky nav
	$('#navigation.init-sticky').sticky();

	// mobile navigation
	$('#mobile-navigation select').change(function() { window.location = $(this).val(); });	
	
	/**
	 * navigation panel hook
	 */
	 $(document).on( 'click', '#navigation-panel-hook, #panel-close, #panel-overlay', function(){
	 	$('body').toggleClass('panel-active');
	 });

	/**
	 * navigation search
	 */

	 $(document).on( 'click', '#navigation-search-hook', function(){

	 	if ( $(this).hasClass('active') ) {

		 	$(this).removeClass('active');
		 	$('#navigation-search-form').hide();
		 	$('#navigation-main').css({ opacity : 1 });
		 	$('#navigation-search-hook .fa-close').hide();
		 	$('#navigation-search-hook .fa-search').show();

		 } else {

		 	$(this).addClass('active');
		 	$('#navigation-main').css({ opacity : 0 });
		 	$('#navigation-search-form').show();
		 	$('#navigation-search-form input[type=text]').focus();
		 	$('#navigation-search-hook .fa-search').hide();
		 	$('#navigation-search-hook .fa-close').show();

		 }

	 });

	/**
	 * navigationa arrows
	 */

	$('#navigation #primary-menu > li:has(ul) > a').append('<span class="fa fa-chevron-down"></span>');

	/**
	 * mobile navigation add arrows
	 */
	$('#panel-navigation li:has(ul)').append('<span class="fa fa-plus"></span>');

	/**
	 * mobile navigation expand submenu
	 */
	$(document).on( 'click', '#panel-navigation li .fa', function(e){		

		// stop the anchor click event
		e.stopPropagation();

		// toggle class
		$(this).closest('li').toggleClass( 'expand' );

	});

	/**
	 * load more posts
	 */

	$(document).on( 'click', '.pagination-load-more a', function(e) {

		if ( $('body').hasClass('mtst-active') )
			return false;

		e.preventDefault();

		if ( $(this).parent().hasClass('active') && ! jQuery('body').hasClass( 'load-more-in-progress' ) ) {

			jQuery('body').addClass( 'load-more-in-progress' );

			var _this = $(this),
			module = $(this).closest('.blog-posts-listing'),
			pagination = module.find('.pagination'),
			postsContainer = module.find('.blog-posts-listing-inner'),
			moduleID = module.attr('id'),
			pagLink = _this.attr('href'),
			tempHolder = module.find('.load-more-temp');

			_this.find('.fa').addClass('fa-spin');

			tempHolder.load( pagLink + ' .blog-posts-listing', function(){

				if ( postsContainer.hasClass('masonry-init') ) {

					// get new content
					var content = jQuery( tempHolder.find('.blog-posts-listing-inner .post') );

					// add new content
					postsContainer.append( content );

					// wait for all images to load
					postsContainer.waitForImages(function() {					

						// reorder
						postsContainer.masonry( 'appended', content );

						// change pagination HTML
						module.find('.pagination').html( tempHolder.find('.pagination').html() );

						// replace pagination HTML
						pagination.replaceWith( tempHolder.find('.pagination') );

						// remove temporary holder HTML
						tempHolder.html('');

						// remove in progress class
						$('body').removeClass('load-more-in-progress');

					});

				} else {

					postsContainer.append( tempHolder.find('.blog-posts-listing-inner').html() );

					module.find('.pagination').html( tempHolder.find('.pagination').html() );

					pagination.replaceWith( tempHolder.find('.pagination') );

					tempHolder.html('');

					the_essence_first_letter_cap();

					jQuery('body').removeClass( 'load-more-in-progress' );

				}

			});
		}	

	});

	/**
	 * mobile Nav
	 */

	$('.header-search-mobile-nav-hook select').change(function() {
		window.location = $(this).val();
	});	

	/**
	 * lightbox 
	 */

	 $('.hidden-lightbox-gallery').magnificPopup({
		delegate: 'a',
		gallery: {
			enabled: true
		},
		type: 'image'
	});

});

/**
 * window load
 */
jQuery(window).load(function(){

	// masonry 
	the_essence_masonry();

	// init carousel
	the_essence_carousel();

	// center top carousel titles
	the_essence_center_top_carousel();

	// is it retina?
	var retina = window.devicePixelRatio > 1;
	if ( retina ) {
		jQuery('body').addClass('retina');
		the_essence_retina_img_replace();
	} else {
		jQuery('body').addClass('not-retina');		
	}

	// blog post share follow
	if ( jQuery('.blog-post-share-aside').length ) {

		var element = jQuery('.blog-post-share-aside'),
		originalY = element.offset().top,
		maximum = jQuery('#main').offset().top + jQuery('#main').outerHeight() - element.outerHeight() - originalY - parseInt( jQuery('#main').css( 'padding-bottom' ) );

		jQuery(window).on('scroll', function(event) {

			var scrollTop = jQuery(window).scrollTop();

			if ( scrollTop > originalY ) {
				var top = scrollTop - originalY;
				if ( top >= maximum ) {
					top = maximum;
				}
			} else {
				var top = 0;
			}

			element.css({ top: top });

		});
	
	}

	/**
	 * page reload during resize
	 */

	jQuery('#page').data( 'start-width', jQuery(window).width() );

	jQuery(window).resize(function(){

		// center top carousel titles
		the_essence_center_top_carousel();

		if ( jQuery('#page.reloading').length < 1 ) {

			var startWidth = jQuery('#page').data('start-width');
			var currentWidth = jQuery(window).width();
			var startID;

			if ( startWidth < 480 )
				startID = 'portrait';
			else if ( startWidth < 768 )
				startID = 'landscape';
			else if ( startWidth < 959 )
				startID = 'tablet';
			else if ( startWidth < 1200 )
				startID = 'monitor';
			else if ( startWidth < 1425 )
				startID = 'monitor-standard';
			else
				startID = 'big';

			if ( ! jQuery('body').hasClass('single-post') ) {

				if ( startID == 'big' && currentWidth < 1425 ) {
					location.reload();
					jQuery('#page').addClass('reloading');
				} else if ( startID == 'monitor-standard' && currentWidth < 1200 ) {
					location.reload();
					jQuery('#page').addClass('reloading');
				} else if ( startID == 'monitor' && ( currentWidth < 959 || currentWidth > 1200 ) ) {
					location.reload();
					jQuery('#page').addClass('reloading');
				} else if ( startID == 'tablet' && ( currentWidth < 768 || currentWidth > 959 ) ) {
					location.reload();
					jQuery('#page').addClass('reloading');
				} else if ( startID == 'landscape' && ( currentWidth < 480 || currentWidth > 768 ) ) {
					location.reload();			
					jQuery('#page').addClass('reloading');
				} else if ( startID == 'portrait' && ( currentWidth > 479 ) ) {
					location.reload();
					jQuery('#page').addClass('reloading');
				}

			}

		}

	});

});

jQuery(window).scroll(function(){

	// trigger load more if needed
	the_essence_maybe_trigger_load_more();

});