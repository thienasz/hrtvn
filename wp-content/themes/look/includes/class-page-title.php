<?php
class Look_PageTitle{
    function init()
    {

        add_action("add_meta_boxes", array($this,"add_custom_meta_box") );
        add_action("save_post", array($this,"save_custom_meta_box"), 5, 3);
    }

    function LookSizeGuideMetaBox()
    {
        global $post;
        ?>
        <div id="product_size_guide_container">
            <div class="option">
                <div class="controls">
                    <input id="look_retina_logo" class="upload" type="text" name="product_size_guide" value="<?php echo get_post_meta(get_the_ID(),'_product_size_guide',true); ?>" placeholder="No file chosen">
                    <input id="upload-look_retina_logo" class="size-guide-upload-button button" type="button" value="Upload">
                    <div class="screenshot" id="look_retina_logo-image">
                    </div>
                </div><div class="explain"></div>
            </div>
        </div>
    <?php
    }

    function LookProductVideoMetaBox()
    {
        global $post;
        ?>
        <div id="product_size_guide_container">
            <div class="option">
                <div class="controls">
                    <input id="look_product_video" style="width:100%;" class="product_video" type="text" name="look_product_video_url" value="<?php echo get_post_meta(get_the_ID(),'_look_product_video_url',true); ?>">
                </div><div class="explain"><?php _e('Youtube video url','look');?></div>
            </div>
        </div>
        <?php
    }
    function custom_meta_box_markup($object)
    {
        global $post;

        ?>
        <div class="look-page-option">
            <div class="look-page-option-title"><?php _e('Page Option','look');?></div>
            <div class="look-page-option-input-wrapper">
                <div class="look-option-wrapper ">
                    <div class="look-option ">
                        <div class="look-option-title"><?php _e('Page Title Style','look');?></div>
                        <div class="look-option-input">
                            <div class="look-combobox-wrapper">
                                <select name="page_title_style">
                                    <option value="tite-center" <?php echo (get_post_meta(get_the_ID(),'page_title_style',true) != 'tite-center' ) ? 'selected':''; ?>>Center Title</option>
                                    <option value="tite-left" <?php echo (get_post_meta(get_the_ID(),'page_title_style',true) == 'tite-left' ) ? 'selected':''; ?>>Left Title</option>
                                    <option value="tite-right" <?php echo (get_post_meta(get_the_ID(),'page_title_style',true) == 'tite-right' ) ? 'selected':''; ?>>Right Title</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="look-option-wrapper ">
                    <div class="look-option ">
                        <div class="look-option-title"><?php _e('Show Title','look');?></div>
                        <div class="look-option-input">
                            <label for="show-title-id" class="checkbox-wrapper">
                                <div class="checkbox-appearance disable <?php if(get_post_meta(get_the_ID(),'page_title_show_title',true) == 'enable'): ?> enable <?php endif; ?>"></div>
                                <input type="checkbox" name="page_title_show_title" id="show-title-id" <?php if(get_post_meta(get_the_ID(),'page_title_show_title',true) == 'enable'): ?> checked="checked" <?php endif; ?>  value="enable">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="look-option-wrapper ">
                    <div class="look-option ">
                        <div class="look-option-title"><?php _e('Page Caption','look');?></div>
                        <div class="look-option-input ">
                            <textarea name="page_title_caption"><?php echo get_post_meta(get_the_ID(),'page_title_caption',true); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="look-option-wrapper ">
                    <div class="look-option ">
                        <div class="look-option-title"><?php _e('Header Background Image','look');?></div>
                        <div class="look-option-input"><img class="look-upload-img-sample blank">
                            <div class="clear"></div>
                            <input type="text" class="look-upload-box-input" name="page_title_background_image" value="<?php echo get_post_meta(get_the_ID(),'page_title_background_image',true); ?>">
                            <input type="button" class="look_upload_image_button gdl-button" data-title="Header Background Image"  value="<?php _e('Upload','look');?>">
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

    function add_custom_meta_box()
    {
        if(look_get_option('enable_product_size_guide'))
        {
            add_meta_box( 'look-size-guide-block', __( 'Product Size Guide', 'look' ), array($this,'LookSizeGuideMetaBox'), 'product', 'side', 'low' );
        }
        if(look_get_option('look_product_video'))
        {
            add_meta_box( 'look-product-video-block', __( 'Product Youtube Video URL', 'look' ), array($this,'LookProductVideoMetaBox'), 'product', 'side', 'low' );
        }
        add_meta_box("page-title-meta-box", __("Page Tile Options",'look'),array($this,"custom_meta_box_markup") , "page", "side", "low", null);
    }

    function save_custom_meta_box($post_id, $post, $update)
    {
        if(!current_user_can("edit_post", $post_id))
        {
            return $post_id;
        }

        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        {
            return $post_id;
        }

        if($post->post_type == 'product')
        {
            if(isset($_POST["product_size_guide"]))
            {

                $product_size_guide = esc_url($_POST["product_size_guide"]);
                update_post_meta($post_id, "_product_size_guide", $product_size_guide);
            }

            if(isset($_POST["look_product_video_url"]))
            {

                $look_product_video_url = esc_url($_POST["look_product_video_url"]);
                update_post_meta($post_id, "_look_product_video_url", $look_product_video_url);
            }
        }


        $slug = "page";
        if($slug != $post->post_type)
            return $post_id;

        $page_title_background_image = "";
        $page_title_style = "";
        $page_title_show_title = "";
        $page_title_caption = '';

        if(isset($_POST["page_title_background_image"]))
        {
            $page_title_background_image = esc_attr($_POST["page_title_background_image"]);
        }
        update_post_meta($post_id, "page_title_background_image", $page_title_background_image);

        if(isset($_POST["page_title_style"]))
        {
            $page_title_style = esc_attr($_POST["page_title_style"]);
        }
        update_post_meta($post_id, "page_title_style", $page_title_style);

        if(isset($_POST["page_title_show_title"]))
        {
            $page_title_show_title = esc_attr($_POST["page_title_show_title"]);
        }
        update_post_meta($post_id, "page_title_show_title", $page_title_show_title);

        if(isset($_POST["page_title_caption"]))
        {
            $page_title_caption = esc_attr($_POST["page_title_caption"]);
        }
        update_post_meta($post_id, "page_title_caption", $page_title_caption);
    }

    function getProductVideo($product_id)
    {
        if(look_get_option('look_product_video'))
        {
            if($url = get_post_meta($product_id,'_look_product_video_url',true))
            {
                if(strpos($url,'youtube') !== false)
                {
                    $tmp = explode('v=',$url);
                    $vid = end($tmp);
                    $tmp = explode('&',$vid);
                    if(count($tmp) > 0)
                    {
                        $vid = $tmp[0];
                    }
                    $url = '//www.youtube.com/embed/'.$vid;
                }
                return $url;
            }
        }
        return false;
    }

}

$tmp = new Look_PageTitle();
$tmp->init();