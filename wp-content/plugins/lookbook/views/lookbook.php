<?php
$display_count = 12;
// Next, get the current page
$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

// After that, calculate the offset
$offset = ( $page - 1 ) * $display_count;
$argsLookbook = array(
    'post_type' => 'look_book',
    'meta_key' => 'sort_order',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'page'       =>  $page,
    'offset'     =>  $offset,
    'post_status' => 'publish',
    'posts_per_page' => $display_count
    );

$lookbooks_query = new WP_Query( $argsLookbook );
$total = $lookbooks_query->found_posts;
$max_num_pages = $lookbooks_query->max_num_pages;
?>
<style>
    .ldraggable{
        background: url('<?php echo getLookbookIcon(); ?>') no-repeat;
        width: 40px;
        height: 40px;
    }
</style>
<div class="lookbooks">
    <div id="container">

        <?php if ($lookbooks_query->have_posts()) : ?>
            <div id="freewall" class="free-wall">
                <?php while ($lookbooks_query->have_posts()) :?>
                    <?php
                    $lookbooks_query->the_post();
                    $width = get_post_meta( get_the_ID(), 'img_width', true);
                    $height = get_post_meta( get_the_ID(), 'img_height', true);
                    $img = get_post_meta( get_the_ID(), 'img_src', true);
                    $upload_dir = wp_upload_dir();

                    if(file_exists($upload_dir['basedir'].$img))
                    {
                        $url = $upload_dir['baseurl'].$img;
                    }else{
                        $attachment_id = get_post_thumbnail_id();
                        $image = wp_get_attachment_image_src($attachment_id, array($width,$height));
                        if(!empty($image))
                        {
                            $url = $image[0];
                        }

                    }

                    $notes = get_post_meta( get_the_ID(), 'notes', true);
                    if($notes != '')
                    {
                        $notes = json_decode(get_post_meta( get_the_ID(), 'notes', true));
                    }else{
                        $notes = array();
                    }

                    ?>

                    <div class="cell" data-cellH="<?php echo esc_attr($height); ?>" data-cellW="<?php echo esc_attr($width); ?>" style="background-image: url(<?php echo esc_url($url); ?>); width: <?php echo esc_attr($width); ?>px; height: <?php echo esc_attr($height); ?>px;">
                        <?php foreach($notes as $key => $n):?>
                            <div id="draggable-<?php echo esc_attr($key); ?>" class="ldraggable" data-left="<?php echo esc_attr($n->left);?>" data-top="<?php echo esc_attr($n->top);?>" style="position: relative;left:<?php echo esc_attr($n->left);?>px;top:<?php echo esc_attr($n->top);?>px" >

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
                <?php endwhile; ?>
            </div>
            <?php if($max_num_pages > 1): ?>
                <div id="load-more"><a href="javascript:void(0);" paged="1"><?php echo __('Load More', 'lookbook'); ?></a></div>
            <?php endif; ?>
        <?php else: ?>
            <div class="row"><?php echo __('No post found', 'lookbook'); ?></div>
        <?php endif; wp_reset_postdata(); ?>
    </div>
</div>