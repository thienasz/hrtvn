<?php
/*
Plugin Name: Magic New Arrival Product
Plugin URI: http://magetop.com
Description: This plugin allow add product as new arrived for woocommere.
Version: 1.0.1
Author: Anhvnit
Author URI: http://magetop.com
License: GPLv2 or later
Text Domain: mg_new_product
*/
define('MG_NEW_PRODUCT_PATH',plugin_dir_path( __FILE__ ));
define('MG_NEW_PRODUCT_URI',plugins_url().'/mg_new_product');

class Mg_NewArrive{
    public static $_instance;
    public static function getInstance() {
        if ( !(self::$_instance instanceof self) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function init()
    {
        add_action('init',array($this,'shortcode_init'));
        add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'create_admin_tab' ) );
        add_action( 'woocommerce_product_data_panels', array( $this, 'create_admin_tab_content' ) );
        add_action( 'woocommerce_process_product_meta', array($this,'save_product_new_data'),1 );
        add_filter( 'post_class', array($this,'mg_new_product_post_class'), 20, 3 );
    }

    public function shortcode_init()
    {
        add_shortcode('new_arrived_product', array('Mg_NewArrive','new_products') );

    }

    public function create_admin_tab()
    {
        ?>
        <li class="mg_new_product_tab"><a href="#mg_new_product"><?php _e('New Arrival', 'woocommerce'); ?></a></li>
        <?php
    }
    public function create_admin_tab_content()
    {
        global $post, $thepostid;
        $_new_date_from = get_post_meta( $post->ID, '_new_date_from', true );
        $_new_date_to = get_post_meta( $post->ID, '_new_date_to', true );

        ?>
        <div id="mg_new_product" class="panel woocommerce_options_panel" style="display: block;">
            <div class="options_group pricing new_dates_fields" style="display: block;">
                <p class="form-field new_dates_fields" style="display: block;">
                    <label for="_new_date_from"><?php _e('New From Date','space'); ?></label>
                    <input type="text" class="new_dates_from " name="_new_date_from" id="_new_date_from" value="<?php echo ! empty( $_new_date_from ) ? date_i18n( 'Y-m-d', $_new_date_from ) : ''; ?>" placeholder="<?php _e('From… YYYY-MM-DD','space'); ?>" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])">

                </p>
                <p class="form-field new_dates_fields" style="display: block;">
                    <label for="_new_dates_to"><?php _e('New To Date','space'); ?></label>
                    <input type="text" class="new_dates_to sale_price_dates_to" name="_new_date_to" id="_new_date_to" value="<?php echo ! empty( $_new_date_to ) ? date_i18n( 'Y-m-d', $_new_date_to ) : ''; ?>" placeholder="<?php _e('To…  YYYY-MM-DD','space'); ?>" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])">
                </p>
            </div>
        </div>
        <script>
            ( function( $ ) {
                "use strict";
                $(document).ready(function () {
                    $( '.new_dates_fields' ).each( function() {
                        var dates = $( this ).find( 'input' ).datepicker({
                            defaultDate: '',
                            dateFormat: 'yy-mm-dd',
                            numberOfMonths: 1,
                            showButtonPanel: true,
                            onSelect: function( selectedDate ) {
                                var option   = $( this ).is( '.new_dates_from' ) ? 'minDate' : 'maxDate';
                                var instance = $( this ).data( 'datepicker' );
                                var date     = $.datepicker.parseDate( instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings );
                                dates.not( this ).datepicker( 'option', option, date );
                            }
                        });
                    });
                });
            } )( jQuery );
        </script>
        <?php
    }

    public function save_product_new_data($post_id)
    {

        $_new_date_from = $_POST['_new_date_from'];
        if(!empty($_new_date_from))
        {
            $_new_date_from = strtotime($_new_date_from);
            update_post_meta( $post_id, '_new_date_from', esc_attr($_new_date_from) );
        }else{
            delete_post_meta( $post_id,'_new_date_from' );

        }
        $_new_date_to = $_POST['_new_date_to'];
        if(!empty($_new_date_to))
        {
            $_new_date_to = strtotime($_new_date_to);
            update_post_meta( $post_id, '_new_date_to', esc_attr($_new_date_to) );
        }else{
            delete_post_meta( $post_id,'_new_date_to' );
        }

    }
    public function mg_new_product_post_class( $classes, $class = '', $post_id = '')
    {
        if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
            return $classes;
        }
        if($this->is_newarrived($post_id))
        {
            $classes[] = 'new-arrived';
        }
        return $classes;
    }

    public function is_newarrived($post_id)
    {
        $_new_date_from = get_post_meta( $post_id, '_new_date_from', true );
        $_new_date_to = get_post_meta( $post_id, '_new_date_to', true );
        $result = false;
        if($_new_date_to > time() || ($_new_date_to == '' && $_new_date_from !='' ) )
        {
            $result = true;
        }
        if($_new_date_from > time())
        {
            $result = false;
        }
        return $result;
    }

    public function wc_get_product_ids_on_new() {
        global $wpdb;

        // Load from cache
        $product_ids_on_new = get_transient( 'wc_products_newarrived' );

        // Valid cache found
        if ( false !== $product_ids_on_new ) {
            //return $product_ids_on_new;
        }

        $on_sale_posts = $wpdb->get_results( "
            SELECT post.ID, post.post_parent FROM `$wpdb->posts` AS post
            WHERE post.post_type IN ( 'product', 'product_variation' )
            AND post.post_status = 'publish'
            GROUP BY post.ID;
            " );
        $tmp_new_posts = array();
        foreach($on_sale_posts as $post)
        {
            $id = $post->ID;
            if($this->is_newarrived($id))
            {
                $tmp_new_posts[] = $post;
            }
        }
        $product_ids_on_new = array_unique( array_map( 'absint', array_merge( wp_list_pluck( $tmp_new_posts, 'ID' ), array_diff( wp_list_pluck( $tmp_new_posts, 'post_parent' ), array( 0 ) ) ) ) );
        set_transient( 'wc_products_newarrived', $product_ids_on_new, DAY_IN_SECONDS * 30 );
        return $product_ids_on_new;
    }

    private static function product_loop( $query_args, $atts, $loop_name ) {
        global $woocommerce_loop;

        $products                    = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $query_args, $atts ) );
        $columns                     = absint( $atts['columns'] );
        $woocommerce_loop['columns'] = $columns;

        ob_start();

        if ( $products->have_posts() ) : ?>

        <?php do_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

        <?php woocommerce_product_loop_start(); ?>

        <?php while ( $products->have_posts() ) : $products->the_post(); ?>

            <?php wc_get_template_part( 'content', 'product' ); ?>

        <?php endwhile; // end of the loop. ?>

        <?php woocommerce_product_loop_end(); ?>

        <?php do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

    <?php endif;

    woocommerce_reset_loop();
    wp_reset_postdata();

    return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
}

public static function new_products( $atts = array() ) {
    $atts = shortcode_atts( array(
        'per_page' => '12',
        'columns'  => '4',
        'orderby'  => 'title',
        'order'    => 'asc'
        ), $atts );
    $NewArriveObj = Mg_NewArrive::getInstance();
    $query_args = array(
        'posts_per_page'	=> $atts['per_page'],
        'orderby' 			=> $atts['orderby'],
        'order' 			=> $atts['order'],
        'no_found_rows' 	=> 1,
        'post_status' 		=> 'publish',
        'post_type' 		=> 'product',
        'meta_query' 		=> WC()->query->get_meta_query(),
        'post__in'			=> array_merge( array( 0 ), $NewArriveObj->wc_get_product_ids_on_new() )
        );

    return self::product_loop( $query_args, $atts, 'new_products' );
}

}

$tmp = new Mg_NewArrive();
$tmp->init();