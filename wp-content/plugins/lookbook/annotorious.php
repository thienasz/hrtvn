<?php
$id = (int)$_GET['id'];


$width = get_post_meta( $id, 'img_width', true);
$height = get_post_meta( $id, 'img_height', true);
$img = get_post_meta( $id, 'img_src', true);
$upload_dir = wp_upload_dir();
$url = $upload_dir['baseurl'].$img;

$notes = get_post_meta( $id, 'notes', true);
if($notes != '')
{
    $notes = json_decode(get_post_meta( $id, 'notes', true));
}else{
    $notes = array();
}


?>
<script>
    function makeDrag(id)
    {
        var element = jQuery('#draggable-'+id);
        element.draggable({
            cursor: "move",
            create: function( event, ui ) {
            },
            start: function( event, ui ) {
                element.attr('data-left',ui.position.left).attr('data-top',ui.position.top);
            },
            stop: function( event, ui ) {

                element.attr('data-left',ui.position.left).attr('data-top',ui.position.top);
            }
        });
    }

    function SearchProduct(element)
    {
        var input = element.find('input');
        input.autocomplete({
            source: function( request, response ) {
                jQuery.ajax({
                    url: "<?php echo  admin_url('admin-ajax.php'); ?>",
                    dataType: "json",
                    data: {
                        q: request.term,
                        action: 'search_products'
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 3,
            select: function( event, ui ) {

                element.attr('data-product-thumb',ui.item.thumb);
                element.attr('data-product-name',ui.item.label);
                element.attr('data-product-id',ui.item.value);
                jQuery('.note-menu .product-name').text(ui.item.label);
                jQuery('.note-menu .product-image').html('<img src="'+ui.item.thumb+'" width="100px">')

            },
            open: function() {
                jQuery( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                jQuery( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
                jQuery( "input[name='note-product']").val('');
            }
        });
    }

    jQuery(function() {
        <?php foreach($notes as $key => $n):?>
        makeDrag(<?php echo esc_attr($key);?>);
        SearchProduct(jQuery('#draggable-<?php echo esc_attr($key);?>'));
    <?php endforeach; ?>
    jQuery('#add-product').click(function(){
        var total = jQuery('.draggable').length + 1;
        var html = '<div id="draggable-'+total+'" class="ui-widget-content draggable">';
        html += '<img class="spot" src="<?php echo getLookbookIcon(); ?>" width="40px" height="40px">';
        html += '<div class="note-menu" style="display: none;">';
        html += '<div class="product-info" style="text-align: center;">';
        html += '<div class="product-image"></div>';
        html += '<div class="product-name"></div>';
        html += '</div>';
        html += '<div class="find-product" style="margin: 0 auto;">';
        html += '<form>';
        html += '<input type="text" name="note-product" placeholder="input product name">';
        html += '</form>';
        html += '</div>';
        html += '<div class="product-action" style="text-align: center;"><a href="javascript:void(0);" class="submitdelete deletion"><?php _e('Remove','mix')?></a></div>';
        html += '</div>';
        html += '</div>';
        jQuery('.image-note').append(html);
        jQuery('.note-menu').hide();
        jQuery('.note-menu .product-name').text('');
        makeDrag(total);
        SearchProduct(jQuery('#draggable-'+total));
    });

    jQuery('body').on('click','.spot',function(){
        var id = jQuery(this).closest('.draggable').attr('id');
        var name = jQuery(this).closest('.draggable').attr('data-product-name');
        var product_id = jQuery(this).closest('.draggable').attr('data-product-id');
        var thumb = jQuery(this).closest('.draggable').attr('data-product-thumb');
        if(jQuery(this).closest('.draggable').find('.note-menu').hasClass('show'))
        {
            jQuery(this).closest('.draggable').find('.note-menu').hide();
            jQuery('.note-menu').removeClass('show');
        }else{
            jQuery('.note-menu').hide();
            jQuery(this).closest('.draggable').find('.note-menu').show();
            jQuery(this).closest('.draggable').find('.note-menu').addClass('show');
            jQuery(this).closest('.draggable').find('.note-menu .product-name').text(name);
            if(thumb && thumb.length > 0)
            {
                jQuery(this).closest('.draggable').find('.note-menu .product-image').html('<img src="'+thumb+'" width="100px">');
            }
        }

    });
    jQuery('body').on('click','.product-action a',function(){
        var drag = jQuery(this).closest('.draggable');
        drag.draggable( "disable" );
        drag.remove();
    });

    jQuery('#save-product-note').click(function(){
        var data = new Array();
        jQuery('.draggable').each(function(){
            var tmp = new Object();
            tmp['product_id'] = jQuery(this).attr('data-product-id');
            tmp['top']  = jQuery(this).attr('data-top');
            tmp['left'] = jQuery(this).attr('data-left');
            if(parseInt(tmp['product_id']) > 0)
            {
                data.push(tmp);
            }else{
                jQuery('.note-menu').hide();
                jQuery( this ).draggable( "disable" );
                jQuery(this).remove();
            }
        });
        jQuery.ajax({
            url: "<?php echo  admin_url('admin-ajax.php'); ?>",
            dataType: "json",
            type: 'POST',
            data: {
                data: JSON.stringify(data),
                action: 'save_annotorious',
                id: <?php echo esc_attr($id); ?>
            },
            success: function( data ) {
                jQuery('.note-message').text(data.message);

                var tmp = setInterval(function(){
                    jQuery('.note-message').text('');
                    clearInterval(tmp);
                }, 5000);
            }
        });
    });
});
</script>
<style>
    .ui-autocomplete{
        z-index: 9999;
    }
</style>
<div class="wrap lookbook">
    <h2><?php _e('Add New Look','mix'); ?><button id="add-product" class="add-new-h2"><?php _e('Add Note','mix')?></button></h2>
    <div class="note-action">
        <button id="save-product-note" class="button button-primary button-large"><?php _e('Save','mix')?></button>
    </div>
    <p><?php _e('Add a note to your image and drag to product you want to link with this look, you can add more note on this look','mix');?></p>
    <div class="note-message">
    </div>
    
    <div class="note-content">

        <div class="image-note" style="width:<?php echo esc_attr($width);?>px; float:left;border: #ddd solid 1px; height:<?php echo esc_attr($height);?>px; background: url('<?php echo esc_url($url); ?>') no-repeat; background-size: contain;">
            <?php foreach($notes as $key => $n):?>
                <?php
                $post_thumbnail_id = get_post_thumbnail_id( $n->product_id );
                $thumb = wp_get_attachment_image_src( $post_thumbnail_id, array(100,100) );
                if(!empty($thumb))
                {
                    $thumb = $thumb[0];
                }else{
                    $thumb = '';
                }
                ?>
                <div id="draggable-<?php echo esc_attr($key); ?>" class="draggable" data-left="<?php echo esc_attr($n->left);?>" data-top="<?php echo esc_attr($n->top);?>" data-product-thumb="<?php echo esc_url($thumb); ?>" data-product-name="<?php echo get_the_title($n->product_id); ?>" data-product-id="<?php echo esc_attr($n->product_id);?>" style="position: relative;top:<?php echo esc_attr($n->top); ?>px;left:<?php echo esc_attr($n->left); ?>px;">
                    <img class="spot" src="<?php echo getLookbookIcon(); ?>" width="40px" height="40px"/>
                    <div class="note-menu" style="display: none;">
                        <div class="product-info" style="text-align: center;">
                            <div class="product-image"></div>
                            <div class="product-name"></div>
                        </div>
                        <div class="find-product" style="margin: 0 auto;">
                            <form>
                                <input type="text" name="note-product" placeholder="input product name">
                            </form>
                        </div>
                        <div class="product-action" style="text-align: center;"><a href="javascript:void(0);" class="submitdelete deletion"><?php _e('Remove','mix')?></a></div>
                    </div>

                </div>
            <?php endforeach;?>
        </div>

    </div>

</div>