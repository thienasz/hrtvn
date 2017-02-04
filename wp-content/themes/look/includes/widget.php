<?php
require_once THEME_DIR . '/includes/widget/home_feature_product.php';
require_once THEME_DIR . '/includes/widget/home_category_link.php';
require_once THEME_DIR . '/includes/widget/link_block.php';
require_once THEME_DIR . '/includes/widget/product_tab.php';
// Register and load the widget
function look_load_widget() {
	if ( class_exists( 'WooCommerce' ) ) {
		register_widget( 'Look_home_feature_product_widget' );
	}
	register_widget( 'Look_home_category_link_widget' );
	register_widget( 'Look_link_block_widget' );
    register_widget( 'Look_product_tab_widget' );
}
add_action( 'widgets_init', 'look_load_widget' );