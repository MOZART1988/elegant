function show() { $.ajax({ type: "GET", url: "/wp-content/callme/index.php", data: { cphone: $("#cphone").val(), cname: $("#cname").val(), csubj: $("#csubj").val() }, success: function(html) { $("#callme_result").html(html);
            setTimeout(function() { $('.c_error').parent("#callme_result").slideToggle("slow"); }, 3000);
            setTimeout(function() { $('.c_success').parent("#callme_result").slideToggle("slow", function() { setTimeout(function() { $(".c_success").closest('.call-overlay').fadeOut('slow'); }, 1000); }); }, 1500); } }); }
jQuery(document).ready(function($) {

    $('.lightb').html('Быстрый просмотр');
    function addToCard(form){
        form.on('submit', function() {
            var product = $(this).find('input').val();
            var parent = $(this).parents(".product-container");
            var img = parent.find(".img-wrapper");
            var cart = $(".cart-tab a.cart-parent");
            img.clone().appendTo($("body")).css({ display: "block", position: "absolute", left: img.offset().left, top: img.offset().top, width: img.innerWidth(), height: img.innerHeight(), opacity: .5, "z-index": 9999 }).animate({ top: cart.offset().top, left: cart.offset().left }, 1000, 'linear', function() { $(this).remove(); });
            $.ajax({ url: '/', data: { 'add-to-cart': product }, success: function(data) {
                var newcart = $(data).find("li.cart").html();
                $("li.cart").html(newcart).addClass('animated shake');
                $("li.cart").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() { $(this).removeClass('animated shake'); }); } })
            return false;
        });
    }

    if (/Android/i.test(navigator.userAgent) && jQuery(window).width() > 769) { $('.nav li:has(ul)').doubleTapToGo(); }
    jQuery('.entry table tr:odd').addClass('alt-table-row');
    jQuery(".post, .widget, .panel").fitVids();
    jQuery("ul.sub-menu").parents('li').addClass('parent');
    jQuery('ul#top-nav').mobileMenu({ switchWidth: 767, topOptionText: 'Select a page', indentString: '&nbsp;&nbsp;&nbsp;' });
    jQuery('.nav-toggle').click(function() { jQuery('#navigation').slideToggle('fast', function() {
            return false; }); });
    jQuery('.nav-toggle a').click(function(e) { e.preventDefault(); });
    jQuery("ul.sub-menu, ul.children").parents().addClass('parent');
    $("#woocommerce_product_categories-2 .current-cat-parent ul.children").slideDown();
    $("#woocommerce_product_categories-2 .current-cat-parent h4").addClass('minus');

    addToCard($('.products form.cart'));

    $("span#search_btn").on('click', function() { $("#search-form").fadeIn(200);
        $(document).click(function(event) {
            if ($(event.target).closest("#searchform").length) return;
            $("#search-form").fadeOut("slow");
            event.stopPropagation(); }); });
    $("div#sub").click(function() { $('#searchsubmit').click(); });
    $("#woocommerce_product_categories-2 h4").click(function() { $("#woocommerce_product_categories-2 ul ul").slideUp();
        $("#woocommerce_product_categories-2 h4").removeClass('minus');
        if (!$(this).parent().next().is(":visible")) { $(this).parent().next().slideDown();
            $(this).addClass('minus'); } });

    $('.lightb').on('click', function() { $('#box').remove();
        var img = $(this).prev().find('.img-holder').html()
        var description = $(this).prev().find('.name-wrapper span').html();
        description = '<span style="font-size:12pt; font-weight: bold; width:100%; text-align: left; padding: 5px;">' + description + '</span>';
        var button = $(this).prev().find('.buttons').find('form').html();
        button  = '<div class="buttons"><form class="cart" method="post" enctype="multipart/form-data" style="width: 50%;">'+ button +'</form></div>';
        var price = $(this).prev().find('.amount').html();
        price = '<div class="modal-price">' + price + '</div>';
        var box = $('<div/>', { id: 'box', });
        box.append('<div class="modal-box"><a class="close"><img src="/wp-content/themes/elegant-life/images/close.png"/></a><div class="add_info">'+ description + price + button + '<div class="clear"></div></div>'+img+'</div><div class="over close"></div>');
        //box.children('.modal-box').children('img').attr('src', $(this).siblings('.product-container').children('a').children('.img-holder').children('.img-wrapper').children('img').attr('src'));
        box.children('.modal-box').children('span').html($(this).siblings('.product-container').children('a').children('.name-wrapper').children('.product-title').html());
        $('body').append(box);

        $('#box').find('.cart').on('submit', function(e) {
            e.preventDefault();
            var product = $(this).find('input').val();
            var img = $('#box').find(".img-wrapper");
            var cart = $(".cart-tab a.cart-parent");
            img.clone().appendTo($("body")).css({ display: "block", position: "absolute", left: img.offset().left, top: img.offset().top, width: img.innerWidth(), height: img.innerHeight(), opacity: .5, "z-index": 9999 }).animate({ top: cart.offset().top, left: cart.offset().left }, 1000, 'linear', function() { $(this).remove(); });
            $.ajax({ url: '/', data: { 'add-to-cart': product }, success: function(data) {
                var newcart = $(data).find("li.cart").html();
                $("li.cart").html(newcart).addClass('animated shake');
                $("li.cart").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() { $(this).removeClass('animated shake'); }); } })
            return false;
        });

        $('.modal-box').hide().fadeIn();

            function popup_position() {
            var my_popup = $('.modal-box'),
                my_popup_w = my_popup.width(),
                my_popup_h = my_popup.height(),
                popup_half_w = my_popup_w / 2,
                popup_half_h = my_popup_h / 2,
                win_w = $(window).width(),
                win_h = $(window).height();
            if (win_w > my_popup_w) { my_popup.css({ 'margin-left': -popup_half_w, 'left': '50%' }); }
            if (win_w < my_popup_w) { my_popup.css({ 'margin-left': 5, 'left': '0' }); }
            if (win_h > my_popup_h) { my_popup.css({ 'margin-top': -popup_half_h + $(document).scrollTop(), 'top': '50%' }); }
            if (win_h < my_popup_h) { my_popup.css({ 'margin-top': 5, 'top': '0' }); } };
        popup_position();
        $('.close').on('click', function() { $('#box').fadeOut('fast');
            setTimeout(function() { $('#box').remove(); }, 1000); });
        $(window).resize(function() { popup_position(); }); });

    $('ul.parent > li.cat-item > div > .dropdown').each(function() {
        if (!($(this).parent().siblings().hasClass('children'))) { $(this).css('display', 'none'); }; });
    $('.breadcrumb-trail').children('a:contains("Товары") + span').hide();
    $('.breadcrumb-trail').children('a:contains("Товары")').hide();
    $("img").attr("title", '');
    $("img").attr("alt", '');
    $('.callme-link').on('click', function() { $('.call-overlay').fadeIn();
        return false; });
    $('.call-close').on('click', function() { $('.call-overlay').fadeOut();
        return false; });
    $(".callme_submit").click(function() { show(); });



    $('.images .thumbnails').bxSlider({
        moveSlides: 1,
        pager: 0,
        auto: 1,
        minSlides: 3,
        maxSlides: 3,
        slideWidth: 80,
        slideMargin: 10,
        onSliderLoad: function(){
            $(".images .thumbnails").css("visibility", "visible");
        }
    });

     $('.reviews').appendTo($('.custom-reviews'));

});
