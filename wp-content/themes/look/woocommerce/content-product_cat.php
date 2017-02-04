<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );
}

// Increase loop count
$woocommerce_loop['loop']++;
?>
<div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12 product-category product<?php
if ( ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] == 0 || $woocommerce_loop['columns'] == 1 )
	echo ' first';
if ( $woocommerce_loop['loop'] % $woocommerce_loop['columns'] == 0 )
	echo ' last';
?>">
<div class="item-wrap">
	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>

	<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
		<div class="item-thumb">
			<?php
			/**
			 * woocommerce_before_subcategory_title hook
			 *
			 * @hooked woocommerce_subcategory_thumbnail - 10
			 */
			do_action( 'woocommerce_before_subcategory_title', $category );
			?>
			<h3 class="category-name">
				<?php
				echo esc_html($category->name);

				if ( $category->count > 0 )
					echo apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">(' . $category->count . ')</span>', $category );
				?>
			</h3>
			<?php
			/**
			 * woocommerce_after_subcategory_title hook
			 */
			do_action( 'woocommerce_after_subcategory_title', $category );
			?>
		</div>	
	</a>
	

	<?php do_action( 'woocommerce_after_subcategory', $category ); ?>
</div>
</div>
