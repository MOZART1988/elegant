/*********************************************************** top menu dropdown **********************************/
$(document).ready(function() {
    $('.header-button').on('click touchstart', function() {

        var subUl = $(this).find('ul');
        var anyAther = $('#header').find('#cart_block');
        var anyAnother1 = $('#menu-wrap.mobile #menu-custom'); // close other menus if opened
        var anyAnotherS = $('#search_block_top'); // close other menus if opened
        if (anyAnotherS.is(':visible')) {
            anyAnotherS.slideUp()
            $('#search_but_id').removeClass('hover-search')
        }
        if (anyAther.is(':visible')) {
            anyAther.slideUp(),
                $('#header_user').removeClass('close-cart')
        }
        if (anyAnother1.is(':visible')) {
            anyAnother1.slideUp(),
                $('.mobile #menu-trigger').find('i').removeClass('icon-minus-sign-alt').addClass('icon-plus-sign-alt');
        } // close ather menus if opened
        if (subUl.is(':hidden')) {
            subUl.slideDown(),
                $(this).find('.icon_wrapp').addClass('active')
        } else {
            subUl.slideUp(),
                $(this).find('.icon_wrapp').removeClass('active')
        }
        $('.header-button').not(this).find('ul').slideUp(),
            $('.header-button').not(this).find('.icon_wrapp').removeClass('active');
        return false
    });


    /*********************************************************** search menu dropdown **********************************/

    $('#search_but_id').on('click touchstart', function() {
        var searchContent = $('#header').find('#search_block_top');
        var anyAnother = $('.header-button').find('ul'); // close other menus if opened
        var anyAnother1 = $('#menu-wrap.mobile #menu-custom'); // close other menus if opened
        var anyAther = $('#header').find('#cart_block');
        if (anyAther.is(':visible')) {
            anyAther.slideUp(),
                $('#header_user').removeClass('close-cart')
        }
        if (anyAnother.is(':visible')) {
            anyAnother.slideUp();
            $('.header-button').find('.icon_wrapp').removeClass('mobile-open')
        }
        if (anyAnother1.is(':visible')) {
            anyAnother1.slideUp(),
                $('.mobile #menu-trigger').removeClass('menu-custom-icon');
        }
        if (searchContent.is(':hidden')) {
            searchContent.slideDown()
            $('#search_but_id').addClass('hover-search')
        } else {
            searchContent.slideUp()
            $('#search_but_id').removeClass('hover-search')
        }
        return false
    });


    /*********************************************************** header-cart menu dropdown **********************************/
    if ((typeof ajaxcart_allowed !== "undefined") && ajaxcart_allowed == 1) {
        $('#header_user').on('click touchstart', function() {
            var cartContent = $('#header').find('#cart_block');
            var anyAnother = $('.header-button').find('ul'); // close other menus if opened
            var anyAnother1 = $('#menu-wrap.mobile #menu-custom'); // close other menus if opened
            var anyAnotherS = $('#search_block_top'); // close other menus if opened
            if (anyAnother.is(':visible')) {
                anyAnother.slideUp();
                $('.header-button').find('.icon_wrapp').removeClass('active')
            }
            if (anyAnother1.is(':visible')) {
                anyAnother1.slideUp(),
                    $('.mobile #menu-trigger').find('i').removeClass('icon-minus-sign-alt').addClass('icon-plus-sign-alt');
            }
            if (anyAnotherS.is(':visible')) {
                anyAnotherS.slideUp()
            }
            if (cartContent.is(':hidden')) {
                cartContent.slideDown(),
                    $(this).addClass('close-cart')
            } else {
                cartContent.slideUp(),
                    $(this).removeClass('close-cart')
            }
            return false
        });
    }
    $('#header #cart_block, .header-button ul, div.alert_cart a, #search_block_top').on('click touchstart', function(e) {
        e.stopPropagation();
    });

});
