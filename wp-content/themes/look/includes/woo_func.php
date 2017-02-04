<?php

//Declare WooCommerce support
add_action( 'after_setup_theme', 'look_woocommerce_support' );
function look_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}


//breadcrumb
add_filter( 'woocommerce_breadcrumb_defaults', 'look_change_breadcrumb_delimiter' );
function look_change_breadcrumb_delimiter( $defaults ) {
// Change the breadcrumb delimeter from '/' to '->'
	$defaults['delimiter'] = ' &nbsp;&nbsp;&rarr;&nbsp; ';
	return $defaults;
}

// product search
add_filter( 'get_product_search_form' , 'look_wc_product_searchform' );
function look_wc_product_searchform( $form ) {
	$form = '
	<form class="look-product-search mini-search" role="search" method="get" action="' . esc_url( home_url( '/'  ) ) . '">
		<input class="search-field" type="text" value="' . esc_attr( get_search_query() ) . '" name="s" placeholder="' . __( 'Search products...', 'look' ) . '" />
		<input type="submit" value="'.__( 'Search','look' ).'" class="hidden" />
		<input type="hidden" name="post_type" value="product" />
	</form>';
	return $form;
}


//Product detail
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing');

add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 55);


// Remove the product rating display on product loops
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

//NUMBER OF PRODUCTS TO DISPLAY ON SHOP PAGE


if(!function_exists('look_shop_per_page'))
{
    function look_shop_per_page()
    {
       return (int)look_get_option('loop_shop_per_page');
    }
}
add_filter('loop_shop_per_page','look_shop_per_page');


$preview = THEME_DIR . '/woocommerce/emails/woo-preview-emails.php';

if(file_exists($preview)) {
	require $preview;
}

// attribute filter

add_action('woocommerce_product_query','look_woocommerce_product_query',55);

function look_woocommerce_product_query($q)
{

    global $wpdb;
    $attrs_setting = look_get_option('look_attribute_filters');
    $attrs = array();
    if(is_array($attrs_setting))
    {
        foreach($attrs_setting as $k => $a)
        {
            if($a == 1)
            {
                $attrs[] = $k;
            }

        }

    }
    $post_in = array();
    $check = false;

    foreach($_REQUEST as $key => $val)
    {
        if(in_array($key,$attrs) && $val != '')
        {
            $meta_key = esc_attr('attribute_'.$key);
            $meta_value = esc_attr(sanitize_title($val));
            $posts = get_posts(
                array(
                    'post_type' 	=> 'product',
                    'numberposts' 	=> -1,
                    'post_status' 	=> 'publish',
                    'fields' 		=> 'ids',
                    'no_found_rows' => true,
                    'tax_query' => array(
                        array(
                            'taxonomy' 	=> esc_attr($key),
                            'terms' 	=> $meta_value,
                            'field' 	=> 'slug'
                        )
                    )
                )
            );
            if(!empty($post_in))
            {
                $post_in = array_intersect($post_in,$posts);
            }else{
                $post_in = array_merge($post_in,$posts);
            }

            $check = true;
        }

    }

    

    if(!empty($post_in))
    {
        $q->set( 'post__in',$post_in );
    }else{
        if($check)
        {
            $post_in[] = 0;
            $q->set( 'post__in',$post_in );
        }
    }

}

add_action('wp_enqueue_scripts', 'look_woo_assets',100);
function look_woo_assets()
{
    global $post;
    if ( class_exists( 'WooCommerce' ) )
    {
        if(!is_product())
        {
            $suffix               = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            $assets_path          = str_replace( array( 'http:', 'https:' ), '', plugins_url().'/woocommerce' ) . '/assets/';
            wp_register_script( 'prettyPhoto', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.6', true );
            wp_register_script( 'prettyPhoto-init', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery','prettyPhoto' ) );
            wp_enqueue_style( 'woocommerce_prettyPhoto_css', $assets_path . 'css/prettyPhoto.css' );
            wp_enqueue_script('prettyPhoto');
            wp_enqueue_script('prettyPhoto-init');
        }else{
            $lightbox_en          = 'yes' === get_option( 'woocommerce_enable_lightbox' );
            if(!$lightbox_en)
            {
                wp_dequeue_script('prettyPhoto');
                wp_dequeue_script('prettyPhoto-init');
                wp_dequeue_style('woocommerce_prettyPhoto_css');
            }
        }
    }


}


add_filter('wc_get_template_part','look_wc_get_template_part_product_content',10,3);
function look_wc_get_template_part_product_content($template, $slug, $name)
{
    if($slug == 'content' && $name == 'product')
    {
        if(look_get_option('product_listing_style') == 2)
        {
            $name = 'product-2';
            $template =  locate_template( array( "{$slug}-{$name}.php", WC()->template_path() . "{$slug}-{$name}.php" ) );
        }
    }
    return $template;
}


function custom_wc_ajax_variation_threshold( $qty, $product ) {
    return 100;
}

add_filter( 'woocommerce_ajax_variation_threshold', 'custom_wc_ajax_variation_threshold', 100, 2 );



