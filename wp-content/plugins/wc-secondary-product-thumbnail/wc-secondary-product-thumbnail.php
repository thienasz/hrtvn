<?php
/**
 * Plugin Name: WC Secondary Product Thumbnail
 * Plugin URI:  https://wordpress.org/plugins/wc-secondary-product-thumbnail/
 * Description: Adds a hover effect that will reveal a secondary product thumbnail to product images in your WooCommerce store.
 * Version:     1.1.2
 * Author:      Hendy Tarnando
 * Author URI:  https://www.thewebflash.com/
 * Text Domain: wc-secondary-product-thumbnail
 * Domain Path: /languages
 * License:     GPLv3
 */

/**
 * Copyright (C) 2015-2016 Hendy Tarnando (https://www.thewebflash.com/contact/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * Localization (with WPML support)
	 */
	add_action( 'init', 'wcspt_load_textdomain' );
	function wcspt_load_textdomain() {
		load_plugin_textdomain( 'wc-secondary-product-thumbnail', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}


	/**
	 * New Badge class
	 */
	if ( ! class_exists( 'WCSPT' ) ) {

		class WCSPT {

			public function __construct() {
				if ( ! is_admin() && ! wp_is_mobile() ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'wcspt_scripts' ) );										// Enqueue the styles
					add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'woocommerce_template_loop_second_product_thumbnail' ), 11 );
					add_filter( 'post_class', array( $this, 'product_has_gallery' ), 11, 3 );
				}
			}


			/*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/

			// Setup styles
			function wcspt_scripts() {
				$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

				if ( apply_filters( 'wc_secondary_product_thumbnail_styles', true ) ) {
					wp_enqueue_style( 'wcspt-styles', plugins_url( '/assets/css/style' . $suffix . '.css', __FILE__ ) );
				}
				wp_enqueue_script( 'wcspt-script', plugins_url( '/assets/js/script' . $suffix . '.js', __FILE__ ), array( 'jquery' ), false, true );
			}

			// Add wcspt-has-gallery class to products that have a gallery
			function product_has_gallery( $classes, $class = '', $post_id = null ) {
				if ( ! $post_id || get_post_type( $post_id ) !== 'product' ) {
					return $classes;
				}

				global $product;

				if ( is_object( $product ) ) {
					if ( $product->get_gallery_attachment_ids() ) {
						$classes[] = 'wcspt-has-gallery';
					}
				}

				return $classes;
			}


			/*-----------------------------------------------------------------------------------*/
			/* Frontend Functions */
			/*-----------------------------------------------------------------------------------*/

			// Display the second thumbnails
			function woocommerce_template_loop_second_product_thumbnail() {
				global $product;

				$attachment_ids = $product->get_gallery_attachment_ids();

				if ( $attachment_ids ) {
					$secondary_image_id = $attachment_ids['0'];
					echo wp_get_attachment_image( $secondary_image_id, 'shop_catalog', '', $attr = array( 'class' => 'attachment-shop_catalog secondary-thumb wcspt-transition wcspt-ie8-tempfix' ) );
				}
			}

		}


		$WCSPT = new WCSPT();
	}
}