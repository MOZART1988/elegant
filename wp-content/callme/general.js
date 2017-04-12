function show(){
     $.ajax({
       type: "GET",
       url: "/wp-content/callme/index.php",
       data: {cphone: $("#cphone").val(), cname: $("#cname").val(), csubj: $("#csubj").val()},
       success: function(html){
           $("#callme_result").html(html);
           setTimeout( function(){ $("#callme_result").slideToggle("fast");, 2000);
       }
     });
}

jQuery(document).ready(function($){

	// Fix dropdowns in Android
	if ( /Android/i.test( navigator.userAgent ) && jQuery( window ).width() > 769 ) {
		$( '.nav li:has(ul)' ).doubleTapToGo();
	}

	// Table alt row styling
	jQuery( '.entry table tr:odd' ).addClass( 'alt-table-row' );

	// FitVids - Responsive Videos
	jQuery( ".post, .widget, .panel" ).fitVids();

	// Add class to parent menu items with JS until WP does this natively
	jQuery("ul.sub-menu").parents('li').addClass('parent');


	// Responsive Navigation (switch top drop down for select)
	jQuery('ul#top-nav').mobileMenu({
		switchWidth: 767,                   //width (in px to switch at)
		topOptionText: 'Select a page',     //first option text
		indentString: '&nbsp;&nbsp;&nbsp;'  //string for indenting nested items
	});



  	// Show/hide the main navigation
  	jQuery('.nav-toggle').click(function() {
	  jQuery('#navigation').slideToggle('fast', function() {
	  	return false;
	    // Animation complete.
	  });
	});

	// Stop the navigation link moving to the anchor (Still need the anchor for semantic markup)
	jQuery('.nav-toggle a').click(function(e) {
        e.preventDefault();
    });

    // Add parent class to nav parents
	jQuery("ul.sub-menu, ul.children").parents().addClass('parent');

	/*Custom js*/

	$("span#search_btn").on('click', function() { 
	    // Отображаем скрытый блок 
	    $("#search-form").fadeIn(200); // fadeIn - плавное появление

	    $(document).click( function(event){
	      if( $(event.target).closest("#searchform").length ) 
	        return;
	      $("#search-form").fadeOut("slow");
	      event.stopPropagation();
	    });
	});


	$("div#sub").click(function() { 
	   $('#searchsubmit').click();
	});

    $("#woocommerce_product_categories-2 h4").click(function(){
        //slide up all the link lists
        $("#woocommerce_product_categories-2 ul ul").slideUp();
        $("#woocommerce_product_categories-2 h4").removeClass('minus');
        
        //slide down the link list below the h3 clicked - only if its closed
        if(!$(this).parent().next().is(":visible"))
        {
            $(this).parent().next().slideDown();
            $(this).addClass('minus');
        }
    });

    /*custom lightbox*/
 //    var pos = $('.lightb').siblings('a').children('img').position();
	// var elem_top = pos.top + $('.lightb').siblings('a').children('img').height();
	// $('.lightb').css({
	// 	'top' : elem_top,
	// });


    $('.lightb').on('click', function(){
    	$('#box').remove();
    	var box = $('<div/>', {
		    id: 'box',
		});

		box.append('<div class="modal-box"><a class="close"><img src="/wp-content/themes/elegant-life/images/close.png"/></a><span></span><img src=""></div><div class="over close"></div>');
		
		box.children('.modal-box').children('img').attr('src', $(this).siblings('a').children('.img-holder').children('.img-wrapper').children('img').attr('src'));

		box.children('.modal-box').children('span').html($(this).siblings('a').children('.name-wrapper').children('.product-title').html());
		$('body').append(box);
		$('.modal-box').hide().fadeIn();
		function popup_position(){
	         var my_popup = $('.modal-box'), // наш попап
                 my_popup_w = my_popup.width(), // ширина попапа
                 my_popup_h = my_popup.height(), // высота попапа
                 popup_half_w = my_popup_w/2, // половина ширины попапа
                 popup_half_h = my_popup_h/2, // половина высоты попапа
                 win_w = $(window).width(), // ширина окна
                 win_h = $(window).height(); // высота окна
 
		         if ( win_w > my_popup_w ) { // если ширина окна больше ширины попапа
		                 my_popup.css({'margin-left':-popup_half_w, 'left': '50%'});
		         }
		         if ( win_w < my_popup_w ) { // если ширина окна меньше ширины попапа                  
		                 my_popup.css({'margin-left': 5, 'left': '0'});
		         }
		         if ( win_h > my_popup_h ) { // если высота окна больше ширины попапа
		                 my_popup.css({'margin-top': -popup_half_h + $(document).scrollTop(), 'top':'50%'});
		         }
		         if ( win_h < my_popup_h ) { // если высота окна меньше ширины попапа
		                 my_popup.css({'margin-top': 5, 'top': '0'});
		         }
		 };

		popup_position();

		$('.close').on('click', function () {
			$('#box').fadeOut('fast');
			setTimeout(function () {
				$('#box').remove();
			}, 1000);
		});

		$(window).resize(function(){	
			popup_position();
		});
    });

	/******/
	$('ul.parent > li.cat-item > div > .dropdown').each(function(){
		if (!($(this).parent().siblings().hasClass('children'))) {
			$(this).css('display', 'none');
		};
	});
	/******/
		$('.breadcrumb-trail').children('a:contains("Товары") + span').hide();
		$('.breadcrumb-trail').children('a:contains("Товары")').hide();
	/******/

	/*******/
	// $('.input-text', '.shop_table.cart .quantity').on('keyup', function() {

	// 	$('input[name="update_cart"]').click();
	// });


	/*******/

	/******/

	$("img").attr("title", '');
	$("img").attr("alt", '');

	/*******/

	$('.callme-link').on('click', function(){
		$('.call-overlay').fadeIn();
		return false;
	});

	$('.call-close').on('click', function(){
		$('.call-overlay').fadeOut();
		return false;
	});

	

    $(".callme_submit").click(function(){
        show();
    });

	// $('#copyright').children('p').text('Elegant-life © 2012 - 2015.')



});




