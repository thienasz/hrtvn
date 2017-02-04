<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( class_exists( 'YITH_WCWL_Init' ) ) {
    add_action('woocommerce_before_single_product', array(YITH_WCWL_Init::get_instance(), 'add_button'));
}
?>
<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
	?>
<script type="text/javascript" >
    var look_product_ajax_load = 1;

</script>
	<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class('product-page'); ?>>
		<div class="content-item row">
			<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
				<?php
				/**
				 * woocommerce_before_single_product_summary hook
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>
			<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 row product-info">
				<div class="summary entry-summary">
					<?php
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 6
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */

			do_action( 'woocommerce_single_product_summary' );
			?>

			<?php look_social_share(); ?>



		</div><!-- .summary -->

	</div>
</div>





