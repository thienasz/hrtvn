<?php
/**
 * Created by PhpStorm.
 * User: Your Inspiration
 * Date: 12/05/2015
 * Time: 17:07
 */
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 1,
    'orderby'   => 'rand',
    'suppress_filters'   => false
);


switch( $product_from ){
    case 'product':
        if( !empty( $products) ){
            $args['post__in'] = $products;
        }
        break;
    case 'category':
        if( !empty($category) ){
            $args['tax_query']  = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $category,
                    'operator' => 'IN',
                ),
            );
        }
        break;
    case 'onsale':
        $args['post__in'] = wc_get_product_ids_on_sale();
        break;
    case 'featured':
        $args['post__in'] = wc_get_featured_product_ids();
        break;

    default:
}

$products = get_posts( $args );
if( empty( $products) ) return;

$product_id = 0;
foreach( $products as $product ){
    $product_id = $product->ID;
}

$product = wc_get_product( $product_id );


$product_type = $product->product_type;

$yit_addtocart_url = add_query_arg('add-to-cart', $product->id, $redirect_url);
if( $product_type == 'variable' ) {
    $add_to_cart_label = $product->add_to_cart_text();
    $yit_addtocart_url = $product->add_to_cart_url();
}

$image_id = $product->get_image_id();
$image = '';
if( $image_id == 0 ){
    $image = sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) );
}
else{

    $image = sprintf( '<img src="%s" alt="%s" />',wp_get_attachment_url( $image_id ), $product->get_title() );
}

?>

<div class="ypop-product-wrapper woocommerce">
    <?php if( $show_title ): ?>
        <h4><a href="<?php echo get_the_permalink( $product->id );?>"><?php echo  $product->get_title() ?></a></h4>
    <?php  endif  ?>
    <?php if( $show_thumbnail ): ?>
        <div class="ypop-woo-thumb">
            <figure id="yit-popup-image"><a href="<?php echo get_the_permalink( $product->id );?>"><?php echo $image;?></a></figure>
        </div>
    <?php  endif  ?>
    <div class="product-info">
    <?php if( $show_price ): ?>
        <div class="price"><?php echo $product->get_price_html() ?></div>
    <?php  endif  ?>
    <?php if( $show_add_to_cart ): ?>
        <div class="add_to_cart"><a class="btn btn-flat" href="<?php echo esc_url( $yit_addtocart_url ) ?>"><?php echo $add_to_cart_label?></a></div>
    <?php  endif  ?>

    <?php if( $show_summary ): ?>
        <div class="summary"><?php echo $product->post->post_excerpt ?></div>
    <?php  endif  ?>
    </div>
</div>