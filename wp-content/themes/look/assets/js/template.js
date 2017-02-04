var $ = jQuery.noConflict();
( function( $ ) {
    "use strict";
	$( document ).ready(function() {
        if(look_product_image_zoom)
        {
            if( ! isMobile.any() )
            {
                $(".my-foto").imagezoomsl({magnifiersize:[300,550]});
            }


            $('body').on('click',"a.zoom",function(event){
                event.preventDefault();
            });
            $('body').on('click',"a[data-rel^='prettyPhoto']",function(event){
                event.preventDefault();
            });
        }
        $('#product-thumb-slide').waitForImages({
            finished: function () {
                $("#product-thumb-slide").verticalCarousel({nSlots: 4, speed: 400});
            },
            waitForAll: true
        });
        //Color Filter
        $('.sortby .dropdown-select').on('click','li',function(){
            var value = $(this).attr('swatch-value');

            var $form = $(this).closest('form');
            var $ul = $(this).closest('ul');
            var name = $ul.attr('name');
            $('input[name="'+name+'"]').val(value);
            $form.trigger('submit');
        });

        //price filter
        $('.sortby select#price_range').on('change',function(){
            var $form = $(this).closest('form');
            var val = $(this).val();
            if(val != '')
            {
                var tmp = val.split(',');
                if(tmp.length == 2)
                {
                    $('input[name="min_price"]').val(tmp[0]);
                    $('input[name="max_price"]').val(tmp[1]);
                }
            }

            $form.trigger('submit');
        });

        $('.catalog-swatch').on('click',function(){
            var swatch = $(this).attr('swatch');
            if(swatch)
            {
                if($(this).closest('.item-wrap').find('img[swatch="'+swatch+'"]').length > 0)
                {
                    $(this).closest('.item-wrap').find('img').each(function(){
                        if($(this).attr('swatch') == swatch)
                        {
                            $(this).show();
                        }else{
                            $(this).hide();
                        }
                    });
                }

            }
        })
		//Accordion mobile menu
		$('#mobile-menu ul li.has-sub').append('<span class="holder"></span>');
		$('body').on('click','.holder',function(){
			var element = $(this).closest('li');
			if (element.hasClass('open')) {
				element.removeClass('open');
				element.find('li').removeClass('open');
				element.find('ul').slideUp();
			}
			else {
				element.addClass('open');
				element.children('ul').slideDown();
				element.siblings('li').children('ul').slideUp();
				element.siblings('li').removeClass('open');
				element.siblings('li').find('li').removeClass('open');
				element.siblings('li').find('ul').slideUp();
			}
		});

		//Product filter
        $('select.swatch-filter').on('change',function(){
            $(this).closest('form').trigger('submit');
        });

        //Change thumbnail



        $('body').on('click','.thumbnails a.img-thumb',function(event){
            event.preventDefault();
            var src = $(this).attr('href');
            var srcset =  $(this).find('img').first().attr('srcset');
            var img_title =  $(this).find('img').first().attr('title');
            var img_alt = $(this).find('img').first().attr('alt');
            $(this).closest('.images').find('.main-image a').first().attr('href',src);
            $(this).closest('.images').find('.main-image a img').attr('src',src);
            if(srcset)
            {
                $(this).closest('.images').find('.main-image a img').attr('srcset',srcset);
            }else{
                $(this).closest('.images').find('.main-image a img').attr('srcset','');
            }

            $(this).closest('.images').find('.main-image a img').attr('title',img_title);
            $(this).closest('.images').find('.main-image a img').attr('alt',img_alt);
            
            if(look_product_image_zoom)
            {
                var base = $(this).attr('data-big');
                $(this).closest('.images').find('.main-image a img').attr('data-large',base);
                if( !look_product_ajax_load)
                {
                    if( ! isMobile.any() )
                    {
                        $(".my-foto").imagezoomsl({magnifiersize:[300,550]});
                    }
                }
            }
        });

        //Update product quantity
        $('body').on('click','.fa-angle-up',function(){
            var qty_element = $(this).closest('.quantity').find('input.qty').first();
            var qty = $(this).closest( ".quantity").find('input.qty').val();
            $(this).closest( ".quantity").find('input.qty').val(parseInt(qty) + 1);
            qty_element.trigger('change');
        });
        $('body').on('click','.fa-angle-down',function(){
            var qty_element = $(this).closest('.quantity').find('input.qty').first();
            var qty = $(this).closest( ".quantity").find('input.qty').val();
            if(parseInt(qty) > 0)
            {
                $(this).closest( ".quantity").find('input.qty').val(parseInt(qty) - 1);
                qty_element.trigger('change');
                
            }
        });

        //Quick View
        $('body').on('click','.quick-view',function(){
            var url = look_ajax_url;
            var productId = $(this).attr('product-data');
            var html = '<div class="look-page-loading">Loading....</div>';
            $.ajax({
                url: url,
                type: 'POST',
                data: {action : 'load_product',id : productId},
                beforeSend:function(){
                    $('body').append(html);
                },
                success: function(data){
                    $('.look-page-loading').remove();
                    $('.product-quickview-content').html(data);
                    $('#product-thumb-slide').waitForImages({
                        finished: function () {
                            $("#product-thumb-slide").verticalCarousel({nSlots: 4, speed: 400});
                        },
                        waitForAll: true
                    });

                    $("#myModal").modal();
                    if( !look_product_image_zoom && look_enable_product_lightbox)
                    {
                        $("a.zoom").prettyPhoto({
                            hook: 'data-rel',
                            social_tools: false,
                            theme: 'pp_woocommerce',
                            horizontal_padding: 20,
                            opacity: 0.8,
                            deeplinking: false
                        });
                        $("a[data-rel^='prettyPhoto']").prettyPhoto({
                            hook: 'data-rel',
                            social_tools: false,
                            theme: 'pp_woocommerce',
                            horizontal_padding: 20,
                            opacity: 0.8,
                            deeplinking: false
                        });
                    }
                    if (typeof($( '.variations_form' ).wc_variation_form) === "function")
                    {
                        $( '.variations_form' ).wc_variation_form();
                    }
                    $( '.variations_form .variations select' ).change();

                }
            });
        });

        //Comment Validate
        $("#commentform").validate();

        //ajax search
        if(typeof look_ajax_search != undefined && parseInt(look_ajax_search) == 1 )
        {
            $( ".search-field" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: look_ajax_url ,
                        dataType: "json",
                        data: {
                            q: request.term,
                            action: 'look_search_products'
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                minLength: 2,
                select: function( event, ui ) {
                    window.location = ui.item.url;
                },
                open: function() {
                    $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
                },
                close: function() {
                    $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
                }
            }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                if(typeof look_ajax_search_thumbnail != undefined && parseInt(look_ajax_search_thumbnail) == 1)
                {
                    return $( "<li>" )
                    .data( "ui-autocomplete-item", item )
                    .attr( "data-url", item.url )
                    .append( "<img class='ajax-result-item' src='"+item.thumb+"'><a class='ajax-result-item-name' href='"+item.url+"'>" + item.label + "</a>" )
                    .appendTo( ul );
                }else{
                    return $( "<li>" )
                    .data( "ui-autocomplete-item", item )
                    .attr( "data-url", item.url )
                    .append( "<a class='ajax-result-item-name' href='"+item.url+"'>" + item.label + "</a>" )
                    .appendTo( ul );
                }
            };
        }

        $('form.woocommerce-ordering').on('submit',function(){
            if($('input[name="min_price"]').val().length  == 0 && $('input[name="max_price"]').val().length  == 0)
            {
                $('input[name="min_price"]').remove();
                $('input[name="max_price"]').remove();
            }
        });

	});
} )( jQuery );
