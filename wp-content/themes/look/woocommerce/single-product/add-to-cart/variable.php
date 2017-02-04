<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $post;

if(!$swatch_attrs = look_get_option('look_attribute_swatch'))
{
    $swatch_attrs = array();
}
$look_attribute_image_swatch = look_get_option('look_attribute_image_swatch');
$allow_swatch = false;
foreach($swatch_attrs as $s)
{
    if($s == 1)
    {
        $allow_swatch = true;
    }
}
$unique_id = rand();
$lightbox_en          = 'yes' === get_option( 'woocommerce_enable_lightbox' );
?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<div class="product-addtocart">
	<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo esc_attr($post->ID); ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
		<?php if ( ! empty( $available_variations ) ) : ?>
			<div class="variations" cellspacing="0">
				<?php $loop = 0;$default = array(); foreach ( $attributes as $name => $options ) : $loop++; ?>
				<h4 class="label"><label for="<?php echo sanitize_title( $name ); ?>"><?php echo wc_attribute_label( $name ); ?></label></h4>
				<div class="value" <?php if(isset($swatch_attrs[$name]) && $swatch_attrs[$name] == 1 && taxonomy_exists( $name )): ?>style="display: none;" <?php endif; ?>  >
					<div class="dropdown-select"><select id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>">
						<option value=""><?php echo __( 'Choose an option', 'look' ) ?>&hellip;</option>
						<?php
						if ( is_array( $options ) ) {

							if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
								$selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
							} elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
								$selected_value = $selected_attributes[ sanitize_title( $name ) ];
                                $default[sanitize_title( $name )] = $selected_value;
							} else {
								$selected_value = '';
							}

									// Get terms if this is a taxonomy - ordered
							if ( taxonomy_exists( $name ) ) {

								$terms = wc_get_product_terms( $post->ID, $name, array( 'fields' => 'all' ) );

								foreach ( $terms as $term ) {
									if ( ! in_array( $term->slug, $options ) ) {
										continue;
									}
									echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
								}

							} else {

								foreach ( $options as $option ) {
									echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
								}

							}
						}
						?>
					</select></div>
                </div>
                <?php if(isset($swatch_attrs[$name]) && $swatch_attrs[$name] == 1): ?>
                    <div class="value" >
                        <ul  id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" class="swatch">
                            <?php
                            if ( is_array( $options ) ) {

                                if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
                                    $selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
                                } elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
                                    $selected_value = $selected_attributes[ sanitize_title( $name ) ];
                                } else {
                                    $selected_value = '';
                                }

                            // Get terms if this is a taxonomy - ordered
                                if ( taxonomy_exists( $name ) ) {

                                    $terms = wc_get_product_terms( $post->ID, $name, array( 'fields' => 'all' ) );

                                    foreach ( $terms as $term ) {
                                        if ( ! in_array( $term->slug, $options ) ) {
                                            continue;
                                        }
                                        $class = ( sanitize_title( $selected_value ) == sanitize_title( $term->slug ) ) ? 'selected':'';

                                        $thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );

                                        if ( $thumbnail_id ) {
                                            $style = "background: url('".wp_get_attachment_thumb_url( $thumbnail_id )."') no-repeat; text-indent: -999em;'";
                                        } else {
                                            $style = '';
                                        }
                                        echo '<li option-value="' . esc_attr( $term->slug ) . '" data-toggle="tooltip" title="'.$term->name.'" class="swatch-item swatch-item-'.$unique_id.' ' . $class . '  ' . esc_attr( $term->slug ) . '" ><span style="'.$style.'" >' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</span></li>';
                                    }

                                }
                            }
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>
                    <?php
                    if ( sizeof( $attributes ) === $loop ) {
                        echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'look' ) . '</a>';
                    }
                    ?>
            <?php endforeach;?>
        </div>

        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

        <div class="single_variation_wrap" style="display:none;">
            <?php do_action( 'woocommerce_before_single_variation' ); ?>

            <div class="single_variation"></div>

            <div class="variations_button">

             <?php woocommerce_quantity_input( array(
              'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
              ) ); ?>
              <button type="submit" class="single_add_to_cart_button button alt"><?php echo sanitize_text_field($product->single_add_to_cart_text()); ?></button>
          </div>

          <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->id); ?>" />
          <input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
          <input type="hidden" name="variation_id" class="variation_id" value="" />

          <?php do_action( 'woocommerce_after_single_variation' ); ?>
      </div>

      <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

  <?php else : ?>

    <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'look' ); ?></p>

<?php endif; ?>

        <?php if ( ! empty( $available_variations ) && $allow_swatch ) : ?>
            <script type="text/javascript">
                (function($) {
                    "use strict";

                    function swatchImage(productId,option)
                    {
                        if(look_product_ajax_load && look_product_ajax_load == 1)
                        {
                            productId = $('.variations_form').find('input[name="product_id"]').first().val();
                        }

                        if(!$('.thumbnails').hasClass('processing'))
                        {
                            $.ajax({
                                type: 'post',
                                url: look_ajax_url,
                                data: {action:'look_swatch_images',product_id:productId,option:option},
                                dataType: 'html',
                                beforeSend:function(){
                                    $('.thumbnails').addClass('processing');
                                },
                                success:function(data){
                                    $('.thumbnails').removeClass('processing');
                                    if(data.length > 5)
                                    {
                                        $('.thumbnails ul').html(data);
                                        $('.thumbnails').find('a.img-thumb').first().trigger('click');

                                    }
                                    $('#product-thumb-slide').waitForImages({
                                        finished: function () {
                                            $("#product-thumb-slide").verticalCarousel({nSlots: 4, speed: 400});
                                            $('.vertical-carousel-list').css('top',0);
                                        },
                                        waitForAll: true
                                    });
                                    // Lightbox
                                    <?php if(look_get_option('product_image_zoom')  != 1 && $lightbox_en) : ?>
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
                                    <?php else : ?>
                                    $("a.zoom").click(function(event){
                                        event.preventDefault();
                                    });
                                    $("a[data-rel^='prettyPhoto']").click(function(event){
                                        event.preventDefault();
                                    });
                                    <?php endif; ?>
                                }
                            });
                        }
                    }

                    $(document).ready(function(){

                        <?php if($look_attribute_image_swatch != ''): ?>
                        $(document).on('change','select#<?php echo esc_attr($look_attribute_image_swatch); ?>',function(){
                            var selected = $(this).val();
                            if(selected != '')
                            {
                                swatchImage('<?php echo (int)$product->id; ?>',selected);
                            }
                        });
                        <?php endif; ?>
                        var isMobile = false;

                        $('[data-toggle="tooltip"]').tooltip();
                        var attributes = [<?php foreach ( $attributes as $name => $options ): ?> '<?php echo esc_attr( sanitize_title( $name ));?>', <?php endforeach; ?>];
                        var $variation_form = $('.variations_form');
                        var $product_variations = $variation_form.data( 'product_variations' );
                        //$variation_form.trigger('reload_product_variations');

                        $('body').on('click touchstart','li.swatch-item-<?php echo $unique_id; ?>',function(){
                            var current = $(this);
                            var value = current.attr('option-value');
                            var selector_name = current.closest('ul').attr('id');
                            if(selector_name == attributes[0])
                            {
                                $('ul.swatch li').each(function(){
                                    $(this).removeClass('selected');
                                });
                                $variation_form.find( '.variations select' ).val( '' ).change();
                                $variation_form.trigger('reset_data');

                            }

                            if($("select#"+selector_name).find('option[value="'+value+'"]').length > 0)
                            {
                                $(this).closest('ul').children('li').each(function(){
                                    $(this).removeClass('selected');
                                    $(this).removeClass('disable');
                                });
                                if(!$(this).hasClass('selected'))
                                {
                                    current.addClass('selected');
                                    $("select#"+selector_name).val(value).change();
                                    $("select#"+selector_name).trigger('change');
                                    $variation_form.trigger( 'wc_variation_form' );
                                    $variation_form
                                        .trigger( 'woocommerce_variation_select_change' )
                                        .trigger( 'check_variations', [ '', false ] );
                                }
                            }else{
                                current.addClass('disable');
                            }

                            if(selector_name == attributes[0])
                            {
                                var check = false;
                                $('ul#'+selector_name+' li').each(function(){
                                    if($(this).hasClass('selected'))
                                    {
                                        check = true;
                                    }
                                });
                                if(check)
                                {
                                    for(var i = 1;i<attributes.length;i++)
                                    {
                                        var attribute = attributes[i];
                                        var check = false;
                                        $('ul#'+attribute+' li').each(function(){
                                            if($(this).hasClass('selected'))
                                            {
                                                check = true;
                                            }
                                        });

                                        if(!check)
                                        {
                                            if($('select#'+attribute+' option').length > 1)
                                            {
                                                var value = $('select#'+attribute+' option:last-child').val();
                                                $('ul#'+attribute+' li[option-value="'+value+'"]').trigger('click');
                                                $('select#'+attribute+' option[value="'+value+'"]').prop('selected',true);
                                                $variation_form.trigger( 'wc_variation_form' );
                                                $variation_form
                                                    .trigger( 'woocommerce_variation_select_change' )
                                                    .trigger( 'check_variations', [ '', false ] );
                                            }

                                        }

                                    }
                                }
                            }

                        });

                        var $variation_form = $('.variations_form');
                        $variation_form.on('wc_variation_form', function() {
                            $( this ).on( 'click', '.reset_variations', function( event ) {
                                $('ul.swatch li').each(function(){
                                    $(this).removeClass('selected');
                                });
                                swatchImage('<?php echo (int)$product->id; ?>','null');
                                $('#product-thumb-slide').waitForImages({
                                    finished: function () {
                                        $("#product-thumb-slide").verticalCarousel({nSlots: 4, speed: 400});
                                        $('.vertical-carousel-list').css('top',0);
                                    },
                                    waitForAll: true
                                });
                            });
                        });
                        var $single_variation_wrap = $variation_form.find( '.single_variation_wrap' );
                        $single_variation_wrap.on('show_variation', function(event,variation) {
                            var $product            = $variation_form.closest('.product');

                            if(variation.image_link)
                            {
                                var variation_image = variation.image_link;
                                $('.main-image img').attr('data-large',variation_image);
                            }
                            $('#product-thumb-slide').waitForImages({
                                finished: function () {
                                    $("#product-thumb-slide").verticalCarousel({nSlots: 4, speed: 400});
                                    $('.vertical-carousel-list').css('top',0);
                                },
                                waitForAll: true
                            });
                        });


                    });
                } )( jQuery );
            </script>

        <?php endif; ?>
</form>
</div>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
