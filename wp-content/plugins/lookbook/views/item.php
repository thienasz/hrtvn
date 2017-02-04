<?php
$id = (int)$a['id'];
$width = get_post_meta($id, 'img_width', true);
$height = get_post_meta( $id, 'img_height', true);
$img = get_post_meta( $id, 'img_src', true);
$upload_dir = wp_upload_dir();
if(file_exists($upload_dir['basedir'].$img))
{
    $url = $upload_dir['baseurl'].$img;
}else{
    $attachment_id = get_post_thumbnail_id($id);
    $image = wp_get_attachment_image_src($attachment_id, array($width,$height));
    if(!empty($image))
    {
        $url = $image[0];
    }

}

$notes = get_post_meta( $id, 'notes', true);
if($notes != '')
{
    $notes = json_decode(get_post_meta( $id, 'notes', true));
}else{
    $notes = array();
}

?>
<?php if($img): ?>
<style>
    .ldraggable{
        background: url('<?php echo getLookbookIcon(); ?>') no-repeat;
        width: 40px;
        height: 40px;

    }
</style>
<div class="item-wrap" style="background: url('<?php echo esc_url($url); ?>'); width: <?php echo (int)$width; ?>px; height: <?php echo (int)$height; ?>px;">
    <?php foreach($notes as $key => $n):?>
        <div id="draggable-<?php echo esc_attr($key); ?>" class="ldraggable" style="position: relative;left:<?php echo esc_attr($n->left);?>px;top:<?php echo esc_attr($n->top);?>px" >
            <div class="look-info" style="position: absolute;">
                <?php
                    $product_id = $n->product_id;
                    $product = new WC_product($product_id);
                    $image       	= get_the_post_thumbnail( $product_id,'thumbnail' );
                ?>
                <div class="look-product-image"><?php echo $image; ?></div>
                <div class="look-product-name">
                    <a href="<?php echo get_permalink($n->product_id); ?>"><?php echo get_the_title($product_id); ?></a>
                </div>

            </div>
        </div>
    <?php endforeach;?>
</div>
<?php endif; ?>