<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
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
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

<div class="woocommerce-tabs panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<?php foreach ( $tabs as $key => $tab ) : ?>	
		<div class="panel-default">
			<h4 class="<?php echo esc_attr( $key ); ?>_tab" role="tab">
				<a href="#tab-<?php echo esc_attr( $key ); ?>" role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="true" aria-controls="tab-<?php echo esc_attr($key); ?>" class="toggle"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?> <span class="plus"><i class="fa fa-plus"></i></span><span class="minus"><i class="fa fa-minus"></i></span></a>
			</h4>
			<div class="panel-collapse collapse" role="tabpanel" aria-labelledby="tab-<?php echo esc_attr( $key ); ?>" id="tab-<?php echo esc_attr( $key ); ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ) ?>
			</div>
		</div>
	<?php endforeach; ?>

</div>
<?php endif; ?>
<script type="text/javascript">
    ( function( $ ) {
        "use strict";
        $('h4 a').on('click', function(){
            $(this).toggleClass('current');
        });
    } )( jQuery );
</script>