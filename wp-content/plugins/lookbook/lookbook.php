<?php
/*
Plugin Name: Lookbook
Plugin URI: http://magicaltheme.com/look
Description: This plugin allow add lookbook page for wordpress and wooecommerce.
Version: 1.0.1
Author: MagicalTheme
Author URI: http://magicaltheme.com/look
License: GPLv2 or later
Text Domain: lookbook
*/

add_action( 'init', 'create_lookbook_posttype' );
function create_lookbook_posttype() {
    register_post_type( 'look_book',
        array(
            'labels' => array(
                'name' => __( 'Look Books','mix' ),
                'singular_name' => __( 'Look Book','mix' )
                ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'look-books'),
            'show_in_nav_menus'   => false,
            'supports'      => array( 'title', 'thumbnail')
            )
        );
}

add_action( 'admin_menu', 'lookbook_meta_box' );
add_action( 'save_post', 'save_lookbook_meta_box', 10, 2 );

function lookbook_meta_box() {
    add_meta_box( 'my-meta-box', 'Lookbook Details', 'lookbook_sort_meta_box', 'look_book', 'normal', 'high' );
}

function getLookbookIcon()
{
    $icon = plugin_dir_url(__FILE__).'assets/hotspot-icon.png';
    return apply_filters( 'lookbook-icon', $icon );
}

function getLookbookText()
{
    $text = __('Get The look');
    return apply_filters( 'lookbook-text', $text );
}

function lookbook_sort_meta_box( $object, $box ) { ?>
<div style="float: left; width: 100%;padding: 5px 0;">
    <div style="float: left; width: 40%;">
        <label style="min-width: 90px;float: left;font-weight: bold;padding-top: 5px;" for="second-excerpt"><?php _e('Image Width', 'mix')?></label>
        <input type="text" name="img_width" id="second-excerpt"  style="width: 20%;" value="<?php echo (int)get_post_meta( $object->ID, 'img_width', true ); ?>"/><?php _e('px','mix'); ?>
    </div>
    <div  style="float: left; width: 40%;">
        <label style="min-width: 90px;float: left;font-weight: bold;padding-top: 5px;" for="second-excerpt"><?php _e('Image Height', 'mix')?></label>
        <input type="text" name="img_height" id="second-excerpt"  style="width: 20%;" value="<?php echo (int)get_post_meta( $object->ID, 'img_height', true ); ?>"/><?php _e('px','mix'); ?>
    </div>
</div>
<p>
    <label style="min-width: 90px;float: left;font-weight: bold;padding-top: 5px;" for="second-excerpt"><?php  _e('Sort Order', 'mix')?></label>
    <input type="text" name="sort_order" id="second-excerpt"  style="width: 20%;" value="<?php echo (int)get_post_meta( $object->ID, 'sort_order', true ); ?>"/>
    <input type="hidden" name="lookbook_meta_box_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
</p>
<?php }

function save_lookbook_meta_box( $post_id, $post ) {

    if (!isset($_POST['lookbook_meta_box_nonce']) ||  !wp_verify_nonce( $_POST['lookbook_meta_box_nonce'], plugin_basename( __FILE__ ) ) )
        return $post_id;

    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;



    $meta_value = get_post_meta( $post_id, 'sort_order', true );
    $new_meta_value = stripslashes( esc_attr($_POST['sort_order']) );

    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, 'sort_order', $new_meta_value, true );

    elseif ( $new_meta_value != $meta_value )
        update_post_meta( $post_id, 'sort_order', $new_meta_value );

    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, 'sort_order', $meta_value );

    //width
    $meta_value = get_post_meta( $post_id, 'img_width', true );
    $new_meta_value = stripslashes( esc_attr($_POST['img_width']) );

    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, 'img_width', $new_meta_value, true );

    elseif ( $new_meta_value != $meta_value )
        update_post_meta( $post_id, 'img_width', $new_meta_value );

    elseif ( '' == $new_meta_value && $meta_value )
        update_post_meta( $post_id, 'img_width', 0 );
    $width = $new_meta_value;
    //height
    $meta_value = get_post_meta( $post_id, 'img_height', true );
    $new_meta_value = stripslashes( esc_attr($_POST['img_height']) );

    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, 'img_height', $new_meta_value, true );

    elseif ( $new_meta_value != $meta_value )
        update_post_meta( $post_id, 'img_height', $new_meta_value );

    elseif ( '' == $new_meta_value && $meta_value )
        update_post_meta( $post_id, 'img_height', 0 );
    $height = $new_meta_value;
    $thumb = get_attached_file( get_post_thumbnail_id($post_id) );
    $size = getimagesize($thumb);

    if(!empty($size))
    {
        $path_parts = pathinfo($thumb);
        $owidth = $size[0];
        $oheight = $size[1];
        if($height)
        {
            $owidth = round(($height/$oheight)*$owidth);
        }
        if($width)
        {
            $oheight = round(($width/$owidth)*$oheight);
        }
        if(!$height)
        {
            $height = $oheight;
        }
        if(!$width)
        {
            $width = $owidth;
        }
        $filname = implode('x',array($width,$height)).'-'.$path_parts['filename'].'.'.$path_parts['extension'];
        $image = wp_get_image_editor( $thumb );
        $image->resize( $width, $height, true );
        $upload_dir = wp_upload_dir();
        $upload_dir_file = $upload_dir['path'].'/'.$filname;
        $meta_upload_file = $upload_dir['subdir'].'/'.$filname;

        $image->save( $upload_dir_file);
        update_post_meta( $post_id, 'img_src', $meta_upload_file );
        update_post_meta( $post_id, 'img_height', $height );
        update_post_meta( $post_id, 'img_width', $width );

    }
}

add_filter('manage_edit-look_book_columns', 'lookbook_columns');
function lookbook_columns($columns) {
    foreach($columns as $key => $c)
    {
        $base = array('cb','title','date');
        if(!in_array($key,$base))
        {
            unset($columns[$key]);
        }
    }
    unset($columns['date']);
    $columns['lookbook_image'] = 'Images';
    $columns['date'] = 'Date';
    $columns['lookbook_shortcode'] = 'ShortCode';
    $columns['lookbook_action'] = 'Action';
    return $columns;
}

add_action('manage_posts_custom_column',  'lookbook_show_columns');
function lookbook_show_columns($name) {
    global $post;
    switch ($name) {
        case 'lookbook_image':
        echo get_the_post_thumbnail( $post->ID,array( 100, 100) );
        break;
        case 'lookbook_shortcode':
        echo '[lookbook id="'.$post->ID.'"]';
        break;
        case 'lookbook_action':
        $url = menu_page_url('add_note_page',false);
        $url = add_query_arg( array('id'=>$post->ID), $url );
        if($post->post_status == 'publish')
        {
            echo '<a href="'.$url.'">'.__('Add Note','mix').'</a>';
        }

    }
}

add_action( 'admin_menu', 'register_newpage' );

function register_newpage(){
    add_menu_page('Add Notes', 'Add Notes', 'administrator','add_note_page', 'add_note');
    remove_menu_page('add_note_page');
}

//add admin js
function lookbook_admin_enqueue($hook) {

    if ( 'toplevel_page_add_note_page' != $hook ) {
        return;
    }
    wp_enqueue_script( 'jquery-ui-autocomplete' );
    wp_enqueue_style('jquery-ui-autocomplete');
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_style('jquery-ui-draggable');
    wp_enqueue_style( 'lookbook',plugin_dir_url(__FILE__).'assets/lookbook.css' );
    wp_enqueue_script( 'lookbook', plugin_dir_url(__FILE__).'assets/lookbook.js' );
}
add_action( 'admin_enqueue_scripts', 'lookbook_admin_enqueue' );


function lookbook_scripts() {

    wp_enqueue_style( 'lookbook_front', plugin_dir_url(__FILE__).'assets/lookbook_front.css' );
    wp_register_script( 'lookbook_grid_lib', plugin_dir_url(__FILE__).'assets/freewall.js',array( 'jquery' ) );
    wp_register_script( 'lookbook_grid', plugin_dir_url(__FILE__).'assets/lookbook_grid.js',array( 'jquery' ) );
    wp_enqueue_script('lookbook_grid_lib');
    wp_enqueue_script('lookbook_grid');
}

add_action( 'wp_enqueue_scripts', 'lookbook_scripts' );



function add_note()
{
    ob_start();
    require(plugin_dir_path( __FILE__ ).'/annotorious.php');
    echo ob_get_clean();
}

add_action('wp_ajax_save_annotorious', 'saveAnnotorious');
function saveAnnotorious()
{
    $id = (int)esc_attr($_REQUEST['id']);
    $data = maybe_serialize($_REQUEST['data']);
    if ( ! add_post_meta( $id, 'notes', $data, true ) ) {
        update_post_meta ( $id, 'notes', $data );
    }
    $result = array(
        'status' => 1,
        'message' => 'Save success'
        );
    echo json_encode($result);
    exit;
}

add_action('wp_ajax_search_products', 'searchProducts');
add_action("wp_ajax_nopriv_search_products", "searchProducts");
function searchProducts()
{
    $result = array();
    $search_query['s'] = urldecode(esc_attr($_REQUEST['q']));
    $search_query['post_type'] = 'product';
    $search = new WP_Query($search_query);
    if ( $search->have_posts() ) {
        while ( $search->have_posts() ) {
            $search->the_post();
            $post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
            $thumb = wp_get_attachment_image_src( $post_thumbnail_id, array(100,100) );
            if(!empty($thumb))
            {
                $thumb = $thumb[0];
            }else{
                $thumb = '';
            }
            $result[] = array(
                'id' => get_the_ID(),
                'label' => get_the_title(),
                'value' => get_the_ID(),
                'thumb' => $thumb
                );
        }
    }
    echo json_encode($result);
    exit;
}


add_action('save_post', 'wpds_check_thumbnail');
add_action('admin_notices', 'wpds_thumbnail_error');

function wpds_check_thumbnail($post_id) {

    // change to any custom post type
    if(get_post_type($post_id) != 'look_book')
        return;

    if(isset($_GET['action']) && $_GET['action'] == 'trash')
    {
        return;
    }

    if ( !has_post_thumbnail( $post_id ) ) {
        // set a transient to show the users an admin message
        set_transient( "has_post_thumbnail", "no" );
        // unhook this function so it doesn't loop infinitely
        remove_action('save_post', 'wpds_check_thumbnail');
        // update the post set it to draft
        wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));

        add_action('save_post', 'wpds_check_thumbnail');
    } else {
        delete_transient( "has_post_thumbnail" );
    }


}

function wpds_thumbnail_error()
{
    // check if the transient is set, and display the error message
    if ( get_transient( "has_post_thumbnail" ) == "no" ) {
        echo "<div id='message' class='error'><p><strong>You must select Featured Image. Your Post is saved but it can not be published.</strong></p></div>";
        delete_transient( "has_post_thumbnail" );
    }
}

//start shortcode
function lookbook_item_func( $atts ) {
    $a = shortcode_atts( array(
        'id' => 0
        ), $atts );
    if((int)$a['id'] > 0)
    {
        ob_start();
        $file =  plugin_dir_path( __FILE__ ). 'views/item.php';;
        require_once ($file);
        return ob_get_clean();
    }else{
        return '';
    }

}
add_shortcode( 'lookbook', 'lookbook_item_func' );

function lookbook_func( ) {
    ob_start();
    $file =  plugin_dir_path( __FILE__ ). 'views/lookbook.php';;
    require_once ($file);
    return ob_get_clean();
}
add_shortcode( 'lookbooks', 'lookbook_func' );
//end shortcode

add_action('wp_ajax_lookbook_more', 'lookbook_more');
add_action("wp_ajax_nopriv_lookbook_more", 'lookbook_more');

if(!function_exists('lookbook_more'))
{
    function lookbook_more()
    {
        $display_count = 12;
        // Next, get the current page
        $page = $_POST['paged'] ? (int)$_POST['paged'] : 1;

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
                            <a href="<?php echo get_permalink($n->product_id); ?>"><?php echo getLookbookText(); ?></a>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        <?php endwhile; ?>
        <?php
        exit;
    }
}