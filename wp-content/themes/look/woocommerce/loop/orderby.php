<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
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
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
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
$look_attribute_image_swatch = look_get_option('look_attribute_image_swatch');
global $woocommerce;
$woo_version = $woocommerce->version;
?>
<form class="woocommerce-ordering" action="<?php echo esc_url_raw( str_replace( 999999999, '1', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ); ?>" method="get">
    <?php foreach($attrs as $attr): ?>
    <?php
        $terms = get_terms($attr);
        $tax = get_taxonomy($attr);
        $selected = isset($_REQUEST[$attr])?$_REQUEST[$attr]:'';
    ?>
    <?php if($attr == $look_attribute_image_swatch):?>
            <input type="hidden" name="<?php echo esc_attr($attr); ?>" value="<?php echo $selected; ?>"/>
            <div class="dropdown-select">
                <span><?php echo sanitize_text_field($tax->label);?></span>
                <ul name="<?php echo esc_attr($attr); ?>" class="swatch-filter <?php echo esc_attr($attr); ?>">

                    <?php foreach($terms as $term): ?>
                        <?php
                            $taxonomy = $tax->name;
                            $transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );

                            if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

                                $_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

                                set_transient( $transient_name, $_products_in_term, DAY_IN_SECONDS * 30 );
                            }
                            if($woo_version >= 2.6)
                            {
                                global $wp_the_query;
                                $layered_count = wp_list_pluck( $wp_the_query->posts, 'ID' );
                                $count = sizeof( array_intersect( $_products_in_term, $layered_count ) );
                            }else{
                                $count = sizeof( array_intersect( $_products_in_term, WC()->query->unfiltered_product_ids ) );
                            }

                            $thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
                            $style = '';
                            if ( $thumbnail_id && $count ) {
                                $style = "background: url('".wp_get_attachment_thumb_url( $thumbnail_id )."') no-repeat; text-indent: -999em;'";
                            }
                        ?>
                        <?php if($count): ?>
                        <li style="<?php echo (string)$style; ?>" <?php selected( $selected,  esc_attr($term->slug) ); ?> swatch-value="<?php echo esc_attr($term->slug); ?>"><?php echo sanitize_text_field($term->name); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
    <?php else: ?>

            <div class="dropdown-select">

                <select name="<?php echo esc_attr($attr); ?>" class="swatch-filter <?php echo esc_attr($attr); ?>">
                    <option value=""><?php echo sanitize_text_field($tax->label);?></option>
                    <?php foreach($terms as $term): ?>
                        <?php
                            $taxonomy = $tax->name;
                            $transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );

                            if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

                                $_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

                                set_transient( $transient_name, $_products_in_term, DAY_IN_SECONDS * 30 );
                            }
                            if($woo_version >= 2.6)
                            {
                                global $wp_the_query;
                                $layered_count = wp_list_pluck( $wp_the_query->posts, 'ID' );
                                $count = sizeof( array_intersect( $_products_in_term, $layered_count ) );
                            }else{
                                $count = sizeof( array_intersect( $_products_in_term, WC()->query->unfiltered_product_ids ) );
                            }

                        ?>
                        <?php if($count ): ?>
                            <option <?php selected( $selected,  esc_attr($term->slug) ); ?> value="<?php echo esc_attr($term->slug); ?>"><?php echo sanitize_text_field($term->name); ?></option>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </select>
            </div>
    <?php endif; ?>

    <?php endforeach; ?>

    <?php $ranges = look_price_ranges(); if($ranges && !empty($ranges)):?>
        <div class="dropdown-select">
            <?php
                $selected = (isset($_REQUEST['min_price']) && isset($_REQUEST['max_price']))? implode(',',array($_REQUEST['min_price'],$_REQUEST['max_price'])):'';
            ?>
            <select id="price_range" class="swatch-filter">
                <option value=""><?php _e('Price','look'); ?></option>
                <?php foreach($ranges as $key => $r): ?>
                    <option <?php selected( $selected,  esc_attr($key) ); ?> price-min="<?php echo esc_attr($r['min']); ?>" price-max ="<?php echo esc_attr($r['max']); ?>" value="<?php echo esc_attr($key); ?>"><?php echo (string)$r['label']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

	<div class="dropdown-select">
		<select name="orderby" class="orderby">
			<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
				<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
    <input type="hidden" name="min_price" value="" />
    <input type="hidden" name="max_price" value="" />
	<?php
		// Keep query string vars intact
	foreach ( $_GET as $key => $val ) {
		if ( 'orderby' === $key || 'submit' === $key || in_array($key,$attrs) || 'min_price' === $key || 'max_price' === $key ) {
			continue;
		}
		if ( is_array( $val ) ) {
			foreach( $val as $innerVal ) {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
			}
		} else {
			echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
		}
	}
	?>

</form>
</div></div>

