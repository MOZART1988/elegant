jQuery( function( $ ) {

	// wc_single_product_params is required to continue, ensure the object exists
	if ( typeof wc_single_product_params === 'undefined' ) {
		return false;
	}

	// Tabs
	$( '.woocommerce-tabs .panel' ).hide();

	$( '.woocommerce-tabs ul.tabs li a' ).click( function() {

		var $tab = $( this ),
			$tabs_wrapper = $tab.closest( '.woocommerce-tabs' );

		$( 'ul.tabs li', $tabs_wrapper ).removeClass( 'active' );
		$( 'div.panel', $tabs_wrapper ).hide();
		$( 'div' + $tab.attr( 'href' ), $tabs_wrapper).show();
		$tab.parent().addClass( 'active' );

		return false;
	});

	$( '.woocommerce-tabs' ).each( function() {
		var hash	= window.location.hash,
			url		= window.location.href,
			tabs	= $( this );

		if ( hash.toLowerCase().indexOf( "comment-" ) >= 0 || hash == '#reviews' ) {
			$('ul.tabs li.reviews_tab a', tabs ).click();

		} else if ( url.indexOf( "comment-page-" ) > 0 || url.indexOf( "cpage=" ) > 0 ) {
			$( 'ul.tabs li.reviews_tab a', $( this ) ).click();

		} else {
			$( 'ul.tabs li:first a', tabs ).click();
		}
	});

	$( 'a.woocommerce-review-link' ).click( function() {
		$( '.reviews_tab a' ).click();
		return true;
	});

	// Star ratings for comments
	$( '#rating' ).hide().before( '<p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>' );

	$( 'body' )
		.on( 'click', '#respond p.stars a', function() {
			var $star   = $( this ),
				$rating = $( this ).closest( '#respond' ).find( '#rating' );

			$rating.val( $star.text() );
			$star.siblings( 'a' ).removeClass( 'active' );
			$star.addClass( 'active' );

			return false;
		})
		.on( 'click', '#respond #submit', function() {
			var $rating = $( this ).closest( '#respond' ).find( '#rating' ),
				rating  = $rating.val();

			if ( $rating.size() > 0 && ! rating && wc_single_product_params.review_rating_required === 'yes' ) {
				alert( wc_single_product_params.i18n_required_rating_text );

				return false;
			}
		});

	// prevent double form submission
	$( 'form.cart' ).submit( function() {
		$( this ).find( ':submit' ).attr( 'disabled','disabled' );
	});

	$(document).on('click touchstart', function(){
		$('#header').find('#cart_block').slideUp(),
			$('#header').find('#search_block_top').slideUp(),
			$('#search_but_id').removeClass('hover-search'),
			$('.header-button').find('ul').slideUp(),
			$('#header_user').removeClass('close-cart'),
			$('.header-button').find('.icon_wrapp').removeClass('active')
	});
	console.log("HELLO!");
	$(".content_social").click(function(){
		$(this).animate({height: "300px"}, 600).addClass("click_close_social");
		$(".text_surprise").addClass('js-surprise-opened');
		$(".text_surprise span.strong").animate({top: 0}, 600);
		$(".text_surprise span.h3").animate({top: 0}, 600);
		console.log('123');
	});
	$(".click_close_social").click(function(){
		$(this).animate({height: "100px"}, 600);
		$(".text_surprise").removeClass('js-surprise-opened');
		$(".text_surprise span.strong").animate({top: "-4em"}, 600);
		$(".text_surprise span.h3").animate({top: "-2em", marginBottom: 0}, 600);
	});
});