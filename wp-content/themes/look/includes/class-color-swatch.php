<?php
/**
 * Created by PhpStorm.
 * User: Vu Anh
 * Date: 7/1/2015
 * Time: 9:58 PM
 */

class Look_ColorSwatch
{
    public function init()
    {
        add_action('admin_enqueue_scripts', array($this, 'upload_scripts'));
        $attrs = look_get_option('look_attribute_swatch');
        if(is_array($attrs))
        {
            foreach($attrs as $key => $val)
            {
                if($val)
                {
                    add_action( $key.'_add_form_fields', array( $this, 'add_attribute_fields' ) );
                    add_action( $key.'_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10 );
                    add_action( 'created_term', array( $this, 'save_attribute_fields' ), 10, 3 );
                    add_action( 'edit_term', array( $this, 'save_attribute_fields' ), 10, 3 );
                }
            }
        }
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_image_swatch' ), 20, 2 );


        add_action('wp_ajax_look_swatch_images', array($this,'swatch_images'));
        add_action("wp_ajax_nopriv_look_swatch_images", array($this,'swatch_images'));


        add_action( 'woocommerce_before_shop_loop_item_title', array($this,'woocommerce_template_loop_product_thumbnail'), 100 );
        add_action('woocommerce_after_shop_loop_item',array($this,'woocommerce_after_shop_loop_item'));
    }

    public function woocommerce_after_shop_loop_item()
    {
        global $post;
        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($post->ID);
        $attributes = $product->get_attributes();
        $swatch = esc_attr( sanitize_title(look_get_option('look_attribute_image_swatch')));

        $html = '';

        if(isset($attributes[$swatch])) {
            $attribute = $attributes[$swatch];
            $slug = array();
            $ids = array();
            if ( $attribute['is_taxonomy'] ) {

                $values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
                $slug = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'slugs' ));
                $ids = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'ids' ));
            } else {
                $values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
            }


            if (!empty($values)) {
                $html .= '<div class="item-colors"><ul>';
                $slug = array_values($slug);
                $ids = array_values($ids);
                foreach ($values as $key => $value) {
                    if(isset($slug[$key]))
                    {
                        $thumbnail_id = absint( get_woocommerce_term_meta( $ids[$key], 'thumbnail_id', true ) );
                        $style = '';
                        if ( $thumbnail_id ) {
                            $style = "background: url('".wp_get_attachment_thumb_url( $thumbnail_id )."') no-repeat; text-indent: -999em;'";
                        }
                        $html .= '<li class="catalog-swatch-item"><a href="javascript:void(0);" class="catalog-swatch" swatch="'.sanitize_title($slug[$key]).'" style="'.$style.'">'.$value.'</a></li>';
                    }else{

                        $html .= '<li><a href="javascript:void(0);">'.$value.'</a></li>';
                    }

                }
                $html .= '</ul></div>';
            }

        }
        echo balanceTags($html);

    }

    public function woocommerce_template_loop_product_thumbnail()
    {
        global $post;
        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($post->ID);
        $attributes = $product->get_attributes();
        $swatch = esc_attr( sanitize_title(look_get_option('look_attribute_image_swatch')));

        if(isset($attributes[$swatch]))
        {

            $tmp = get_post_meta( $product->id, '_product_image_swatch_gallery', true );

            if(!$tmp && $product->product_type == 'variable')
            {
                $variations = $product->get_available_variations();
                $tmp = array();

                foreach($variations as $variation)
                {
                    $id = $variation['variation_id'];
                    if(isset($variation['attributes']['attribute_'.$swatch]) )
                    {
                        if($variation['image_src'] != '')
                        {
                            $option = $variation['attributes']['attribute_'.$swatch];
                            $vari = new WC_Product_Variation($id);
                            $tmp[$option] = $vari->get_image_id();
                        }
                    }
                }
            }
            if($tmp)
            {
                foreach($tmp as $option => $value)
                {

                    $attachment_ids = array_filter(explode(',',$value));
                    $html = '';



                    if(!empty($attachment_ids))
                    {
                        $attr = array('style'=>"display:none;",'swatch' =>$option);
                        $post_thumbnail_id = (int)$attachment_ids[0];
                        $size = apply_filters( 'post_thumbnail_size', 'shop_catalog' );
                        if ( $post_thumbnail_id ) {

                            do_action( 'begin_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size );
                            if ( in_the_loop() )
                                update_post_thumbnail_cache();
                            $html = wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr );
                            do_action( 'end_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size );
                        }
                        echo apply_filters( 'post_thumbnail_html', $html, $post->ID, $post_thumbnail_id, $size, $attr );
                    }

                }
            }
        }
    }

    public function add_meta_boxes()
    {
        global $post;
        if($post->post_type == 'product')
        {
            $attr = look_get_option('look_attribute_image_swatch');
            $_pf = new WC_Product_Factory();
            $product = $_pf->get_product($post->ID);
            $attributes = $product->get_attributes();

            if($attr && isset($attributes[$attr]) && $attributes[$attr]['is_variation'] == 1 )
            {
                $attribute = $attributes[$attr];
                if ( $attribute['is_taxonomy'] ) {

                    $values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
                } else {
                    $values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
                }

                foreach($values as $val)
                {
                    $key = esc_attr($val);
                    add_meta_box( 'look-product-images-swatch-'.$key, __( 'Product Swatch Gallery', 'look' ).'- '.$val, array($this,'swatchMetaBox'), 'product', 'side', 'low',$key );
                }

            }

        }

    }

    public function swatch_images()
    {
        $productId = esc_attr($_POST['product_id']);
        $option = esc_attr($_POST['option']);
        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($productId);
        $attributes = $product->get_attributes();
        $swatch = esc_attr( sanitize_title(look_get_option('look_attribute_image_swatch')));
        $images = '';
        if(isset($attributes[$swatch]) || $option == 'null')
        {
            $attribute = $attributes[$swatch];

            $tmp = get_post_meta( $productId, '_product_image_swatch_gallery', true );
            if(isset($tmp[$option])  || $option == 'null')
            {
                if($option == 'null')
                {
                    $attachment_ids = $product->get_gallery_attachment_ids();
                }else{
                    $attachment_ids = explode(',',$tmp[$option]);
                }

                $loop 		= 0;
                $columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
                foreach ( $attachment_ids as $attachment_id ) {

                    $classes = array();

                    if ( $loop == 0 || $loop % $columns == 0 )
                        $classes[] = 'first';

                    if ( ( $loop + 1 ) % $columns == 0 )
                        $classes[] = 'last';

                    $image_link = wp_get_attachment_url( $attachment_id );

                    if ( ! $image_link )
                        continue;

                    $image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
                    $image_class = esc_attr( implode( ' ', $classes ) );
                    $image_title = esc_attr( get_the_title( $attachment_id ) );
                    $images .= '<li>';
                    if(look_get_option('product_image_zoom')  == 1)
                    {
                        $base = wp_get_attachment_image_src( $attachment_id,'full');
                        $images .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s img-thumb" title="%s" data-big="%s" >%s</a>', $image_link, $image_class, $image_title,$base[0], $image ), $attachment_id, $productId, $image_class );


                    }else{
                        $classes[] = 'zoom';
                        $images .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" data-rel="prettyPhoto[product-gallery]" class="%s img-thumb" title="%s">%s</a>', $image_link, $image_class, $image_title, $image ), $attachment_id, $productId, $image_class );

                    }
                    $images .= '</li>';
                    $loop++;
                }

            }
        }
        echo balanceTags($images);exit;

    }

    public function swatchMetaBox($post,$box)
    {

        $attr = esc_attr(sanitize_title($box['args']));
        ?>

        <div id="product_images_swatch_container">
            <ul class=" product_swatch_images product_images_<?php echo esc_attr($attr); ?>">
                <?php
                if ( metadata_exists( 'post', $post->ID, '_product_image_swatch_gallery' ) ) {
                    $tmp = get_post_meta( $post->ID, '_product_image_swatch_gallery', true );
                    if(isset($tmp[$attr]))
                    {
                        $product_image_swatch_gallery = $tmp[$attr];
                    }else{
                        $product_image_swatch_gallery = '';
                    }
                } else {
                    $attachment_ids = array();
                    $product_image_swatch_gallery = '';
                }

                $attachments = array_filter( explode( ',', $product_image_swatch_gallery ) );

                if ( ! empty( $attachments ) ) {
                    foreach ( $attachments as $attachment_id ) {
                        echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image', 'look' ) . '">' . __( 'Delete', 'look' ) . '</a></li>
								</ul>
							</li>';
                    }
                }
                ?>
            </ul>

            <input type="hidden" id="product_image_gallery_<?php echo esc_attr($attr); ?>" class="input_product_image_swatch_gallery" name="product_image_swatch_gallery[<?php echo esc_attr($attr); ?>]" value="<?php echo esc_attr( $product_image_swatch_gallery ); ?>" />

        </div>
        <p class="add_product_swatch_images  hide-if-no-js">
            <a href="#" data-choose="<?php esc_attr_e( 'Add Images to Product Gallery', 'look' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'look' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'look' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'look' ); ?>"><?php _e( 'Add product gallery images', 'look' ); ?></a>
        </p>

    <?php
    }

    public function save_image_swatch($post_id,$post)
    {
        $attachment_ids = isset( $_POST['product_image_swatch_gallery'] ) ? $_POST['product_image_swatch_gallery'] : array();
        update_post_meta( $post_id, '_product_image_swatch_gallery', $attachment_ids );
    }

    public function upload_scripts()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
    }

    public function add_attribute_fields() {

        ?>

        <div class="form-field">
            <label><?php _e( 'Thumbnail', 'look' ); ?></label>
            <div id="product_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
            <div style="line-height: 60px;">
                <input type="hidden" name="is_attribute" value="1">
                <input type="hidden" id="product_attribute_thumbnail_id" name="product_attribute_thumbnail_id" />
                <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'look' ); ?></button>
                <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'look' ); ?></button>
            </div>
            <script type="text/javascript">
                ( function( $ ) {
                    "use strict";
                    // Only show the "remove image" button when needed
                    if ( ! $( '#product_attribute_thumbnail_id' ).val() ) {
                        $( '.remove_image_button' ).hide();
                    }

                    // Uploading files
                    var file_frame;

                    $( document ).on( 'click', '.upload_image_button', function( event ) {

                        event.preventDefault();

                        // If the media frame already exists, reopen it.
                        if ( file_frame ) {
                            file_frame.open();
                            return;
                        }

                        // Create the media frame.
                        file_frame = wp.media.frames.downloadable_file = wp.media({
                            title: '<?php _e( "Choose an image", "woocommerce" ); ?>',
                            button: {
                                text: '<?php _e( "Use image", "woocommerce" ); ?>'
                            },
                            multiple: false
                        });

                        // When an image is selected, run a callback.
                        file_frame.on( 'select', function() {
                            var attachment = file_frame.state().get( 'selection' ).first().toJSON();
                            $( '#product_cat_thumbnail_id' ).val( attachment.id );
                            $( '#product_cat_thumbnail img' ).attr( 'src', attachment.sizes.thumbnail.url );
                            $( '.remove_image_button' ).show();
                        });

                        // Finally, open the modal.
                        file_frame.open();
                    });

                    $( document ).on( 'click', '.remove_image_button', function() {
                        $( '#product_cat_thumbnail img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                        $( '#product_attribute_thumbnail_id' ).val( '' );
                        $( '.remove_image_button' ).hide();
                        return false;
                    });
                } )( jQuery );
            </script>
            <div class="clear"></div>
        </div>
    <?php
    }

    public function edit_attribute_fields( $term ) {
        $thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );

        if ( $thumbnail_id ) {
            $image = wp_get_attachment_thumb_url( $thumbnail_id );
        } else {
            $image = wc_placeholder_img_src();
        }
        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'look' ); ?></label></th>
            <td>
                <div id="product_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
                <div style="line-height: 60px;">
                    <input type="hidden" name="is_attribute" value="1">
                    <input type="hidden" id="product_attribute_thumbnail_id" name="product_attribute_thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>" />
                    <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'look' ); ?></button>
                    <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'look' ); ?></button>
                </div>
                <script type="text/javascript">
                    ( function( $ ) {
                        "use strict";
                        // Only show the "remove image" button when needed
                        if ( '0' === $( '#product_attribute_thumbnail_id' ).val() ) {
                            $( '.remove_image_button' ).hide();
                        }

                        // Uploading files
                        var file_frame;

                        $( document ).on( 'click', '.upload_image_button', function( event ) {

                            event.preventDefault();

                            // If the media frame already exists, reopen it.
                            if ( file_frame ) {
                                file_frame.open();
                                return;
                            }

                            // Create the media frame.
                            file_frame = wp.media.frames.downloadable_file = wp.media({
                                title: '<?php _e( "Choose an image", "look" ); ?>',
                                button: {
                                    text: '<?php _e( "Use image", "look" ); ?>'
                                },
                                multiple: false
                            });

                            // When an image is selected, run a callback.
                            file_frame.on( 'select', function() {
                                var attachment = file_frame.state().get( 'selection' ).first().toJSON();

                                $( '#product_attribute_thumbnail_id' ).val( attachment.id );
                                $( '#product_cat_thumbnail img' ).attr( 'src', attachment.url );
                                $( '.remove_image_button' ).show();
                            });

                            // Finally, open the modal.
                            file_frame.open();
                        });

                        $( document ).on( 'click', '.remove_image_button', function() {
                            $( '#product_cat_thumbnail img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                            $( '#product_attribute_thumbnail_id' ).val( '' );
                            $( '.remove_image_button' ).hide();
                            return false;
                        });
                    } )( jQuery );


                </script>
                <div class="clear"></div>
            </td>
        </tr>
    <?php
    }


    public function save_attribute_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
        if ( isset( $_POST['product_attribute_thumbnail_id'] ) && isset($_POST['is_attribute']) && $_POST['is_attribute'] == 1 ) {
            update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_attribute_thumbnail_id'] ) );
        }
    }
}

$ColorSwatch = new Look_ColorSwatch();
$ColorSwatch->init();