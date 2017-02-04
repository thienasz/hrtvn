(function($) {
    "use strict";
    function refreshCart()
    {
        $.ajax({
            type: 'POST',
            url: look_ajax_url,
            dataType: 'json',
            data: {action:'load_mini_cart'},
            success:function(data){
                if(data.cart_html.length > 0)
                {
                    $('li.cart .widget_shopping_cart .widget_shopping_cart_content').html(data.cart_html);
                }
                $('.mini-cart-total-item').text(data.cart_total);

                window.location = '#';
                $('#myModal').modal('hide');
                $('.top-menu li.cart .widget_shopping_cart').toggleClass("hovered");

            }
        });
    }

    $(document).ready(function(){
        //Add to cart effect
        $(document).mouseup(function (e) {
            var container = $(".hovered");

            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                container.removeClass('hovered');
            }
        });

        $('body').on('click','.single_add_to_cart_button',function(){
            var current = $(this);
            var url = look_add_cart_url;
            if(!current.hasClass('add-cart-loading')) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: 'html',
                    data: $('.product-addtocart form').serialize(),
                    beforeSend: function () {
                        current.addClass('add-cart-loading');
                    },
                    success: function () {
                        current.removeClass('add-cart-loading');
                        refreshCart();
                    }
                });
            }
            return false;
        });

        // Scroll top Top
        var windowHeight = $(window).height();
        $(window).scroll(function() {
            if($(this).scrollTop() > windowHeight ) {
                $('.scrollToTop').fadeIn();
            } else {
                $('.scrollToTop').fadeOut();
            }
        });

        $('.scrollToTop').click(function() {
            $('body,html').animate({scrollTop:0},800);
        });

        //Push menu
        $("#menu-toggle").click(function () {
            $('body').addClass('open');
        });
        $("#close-menu").click(function () {
            $('body').removeClass('open');
        });

        $(document).mouseup(function (e) {
            var container = $("body.open");

            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                container.removeClass('open');
            }
        });
    });

    //Sticky header
    if(look_menu_sticky) {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 1) {
                $('header').addClass("sticky");
            }
            else {
                $('header').removeClass("sticky");
            }
        });
    }
} )( jQuery );