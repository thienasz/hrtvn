<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();
$main_image = get_post_thumbnail_id();
if($main_image)
{
    $attachment_ids[] = $main_image;
}
if ( $attachment_ids ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
	?>
	<div id="product-thumb-slide" class="vertical-carousel thumbnails <?php echo 'columns-' . $columns; ?> col-lg-2 col-md-2 col-sm-2 col-xs-12 left">
    <a href="javascript:void(0)" class="scru">Scroll Up</a>
    <div class="vertical-carousel-container">
        <ul class="vertical-carousel-list">
        <?php

		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array();

			if ( $loop == 0 || $loop % $columns == 0 )
				$classes[] = 'first';

			if ( ( $loop + 1 ) % $columns == 0 )
				$classes[] = 'last';

			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link )
				continue;

			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
			$image_class = esc_attr( implode( ' ', $classes ) );
			$image_title = esc_attr( get_the_title( $attachment_id ) );
            echo '<li>';
            if(look_get_option('product_image_zoom')  == 1)
            {
                $base = wp_get_attachment_image_src( $attachment_id,'full');
                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s img-thumb" title="%s" data-big="%s">%s</a>', $image_link, $image_class, $image_title,$base[0], $image ), $attachment_id, $post->ID, $image_class );

            }else{
                $classes[] = 'zoom';
				if($attachment_id == $main_image)
				{
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s img-thumb" title="%s">%s</a>', $image_link, $image_class, $image_title, $image ), $attachment_id, $post->ID, $image_class );
				}else{
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s img-thumb" data-rel="prettyPhoto[product-gallery]" title="%s">%s</a>', $image_link, $image_class, $image_title, $image ), $attachment_id, $post->ID, $image_class );
				}

            }
            echo '</li>';
			$loop++;
		}

		?>
            </ul>
        </div>
        <a href="javascript:void(0)" class="scrd">Scroll Down</a>
	</div>
	<?php
}
