<?php
$image = false;
$title = '';
$caption = false;
$type = '';
if ( class_exists( 'WooCommerce' ) && !is_tag() && !is_product_tag() )
{
    if ( is_product_category() ){
        global $wp_query;
        $cat = $wp_query->get_queried_object();
        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
        $tmp = wp_get_attachment_image_src( $thumbnail_id,'full' );
        if(!empty($tmp))
        {
            $image = $tmp[0];
            $title = woocommerce_page_title(false);
        }
    }elseif(is_product() || is_category()){

    }elseif(is_page() ){
        global $wp_query;
        $obj = $wp_query->get_queried_object();
        $id = $obj->ID;
        if(get_post_meta($id,'page_title_show_title',true) == 'enable')
        {
            $image = get_post_meta($id,'page_title_background_image',true);
            $title = get_the_title($id);
            $caption = get_post_meta($id,'page_title_caption',true);
            $type = get_post_meta($id,'page_title_style',true);
        }
    }elseif(is_shop() || is_cart() || is_account_page() || is_checkout()){
        $id = wc_get_page_id( 'shop' );
        if(is_cart())
        {
            $id = wc_get_page_id( 'cart' );
        }
        if(is_account_page())
        {
            $id = wc_get_page_id( 'myaccount' );
        }
        if(is_checkout())
        {
            $id = wc_get_page_id( 'checkout' );
        }
        if(get_post_meta($id,'page_title_show_title',true) == 'enable')
        {
            $image = get_post_meta($id,'page_title_background_image',true);
            $title = get_the_title($id);
            $caption = get_post_meta($id,'page_title_caption',true);
            $type = get_post_meta($id,'page_title_style',true);
        }
    }else{
        global $wp_query;
        $obj = $wp_query->get_queried_object();
        $id = get_the_ID();

        if($obj && isset($obj->post_type)  && $obj->post_type == 'page')
        {
            $id = $obj->ID;

        }
        $tmp = wp_get_attachment_image_src( get_post_thumbnail_id($id),'full' );
        if(!empty($tmp))
        {
            $image = $tmp[0];
        }
        $title = get_the_title($id);
        if(is_archive())
        {
            $image = false;
        }
    }
}
?>
<?php if($image): ?>
<div class="page-heading section-background <?php echo esc_attr($type); ?> " style="background-image: url(<?php echo esc_url($image); ?>)">
	<div class="gradient-background">
		<div class="intro-text animated fadeInUp">
			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
				<h1 class="page-title" itempro="category-name"><?php echo sanitize_text_field($title); ?></h1>
			<?php endif; ?>
            <?php if($caption): ?>
                <div id="page-caption"><?php echo balanceTags($caption); ?></div>
            <?php else: ?>
			<?php do_action( 'woocommerce_archive_description' ); ?>
            <?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>