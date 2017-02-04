<?php

$title = apply_filters( 'widget_title', $instance['title'] );

echo (string)$args['before_widget'];
if ( ! empty( $title ) )
{
	echo (string)$args['before_title'] . $title . $args['after_title'];
}
$shortcode = '';
$limit = (int)$instance['limit'];
$orderby = 'date';
$order = 'desc';
if ( isset( $instance[ 'orderby' ] ) ) {
    $orderby = $instance[ 'orderby' ];
}
if ( isset( $instance[ 'order' ] ) ) {
    $order = $instance[ 'order' ];
}
switch($instance['type'])
{
    case 'feature':
        $shortcode = 'featured_products';
        break;
    case 'bestseller':
        $shortcode = 'best_selling_products';
        break;
    case 'newarrived':
        $shortcode = 'new_arrived_product';
        break;
    case 'recent':
        $shortcode = 'recent_products';
        break;
    case 'sale':
        $shortcode = 'sale_products';
        break;
    default:
        $shortcode = 'featured_products';
        break;
}
$short_code_details = '['.$shortcode.' per_page="'.$limit.'" ';
if(isset($orderby))
{
    $short_code_details .= ' orderby="'.$orderby.'" ';
}
if(isset($order))
{
    $short_code_details .= ' order="'.$order.'" ';
}
$short_code_details .= ']';
echo do_shortcode($short_code_details);

?>
<?php echo (string)$args['after_widget']; ?>

