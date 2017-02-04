<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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

global $post, $woocommerce, $product;
$test = new Look_PageTitle();
?>
<?php if($test->getProductVideo($post->ID)): ?>
<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
	jQuery(document).ready(function(){

		var url = $("#cartoonVideo").attr('src');

		$("#videoModal").on('hide.bs.modal', function(){
			$("#cartoonVideo").attr('src', '');
			$('.modal-backdrop').remove();
		});
		$("#videoModal").on('show.bs.modal', function(){
			$("#cartoonVideo").attr('src', url);
		});
	});
</script>
<?php endif; ?>
<div class="images">
	<div class="main-image col-lg-10 col-md-10 col-sm-10 col-xs-12 right">
		<?php
		if ( has_post_thumbnail() ) {

			$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
			$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
			$image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	=> $image_title,
				'alt'	=> $image_title
				) );

			$attachment_count = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			}
            if(look_get_option('product_image_zoom')  == 1)
            {
                $base = wp_get_attachment_image_src( get_post_thumbnail_id(),'full');

                $image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
                    'title'	=> $image_title,
                    'alt'	=> $image_title,
                    'data-large' => $base[0],
                    'class' => ' my-foto'
                ) );
                $gallery = '';
                echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_caption, $image ), $post->ID );
            }else{
                echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_caption, $image ), $post->ID );
            }



		} else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'look' ) ), $post->ID );

		}
		?>
	<?php if($video = $test->getProductVideo($post->ID)): ?>
	<!-- Button HTML (to Trigger Modal) -->
	<a href="#videoModal" class="video-thumb btn" data-toggle="modal"><i class="video-icon">&nbsp;</i></a>

	<!-- Modal HTML -->
	<div id="videoModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<iframe id="cartoonVideo" width="560" height="315" src="<?php echo $video; ?>" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	</div>


	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>
