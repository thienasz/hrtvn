<?php

if ( ! function_exists( 'look_setup' ) )
{
    function look_setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-background' );
        add_theme_support( 'custom-header') ;
        set_post_thumbnail_size( 825, 510, true );
        add_theme_support( 'html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
            ) );
    }
}



// Date format
add_action( 'after_setup_theme', 'look_setup' );

if ( ! function_exists( 'look_get_posted_on' ) )
{
    function look_get_posted_on() {

        $time_string = sprintf( '<time class="published updated" datetime="%1$s"><span>%2$s</span><span>%3$s</span><span>%4$s</span></time>',
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_time( 'M' ) ),
            esc_html( get_the_time( 'j' ) ),
            esc_html( get_the_time( 'Y' ) )
            );

        $posted_on = sprintf( esc_html_x( '%s', 'post date', 'look' ), '' . $time_string );

        echo '<span class="posted-on">' . $posted_on . '</span>';
    }
}


if ( ! function_exists( 'look_get_posted' ) )
{
    function look_get_posted() {

        $time_string = sprintf( '<time class="published updated" datetime="%1$s">%2$s %3$s, %4$s</time>',
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_time( 'F' ) ),
            esc_html( get_the_time( 'jS' ) ),
            esc_html( get_the_time( 'Y' ) ),
            esc_html( get_the_time( 'l' ) )
            );

        $posted_on = sprintf( esc_html_x( '%s', 'post date', 'look' ), '' . $time_string );

        echo '<span class="posted-on">' . $posted_on . '</span>';
    }
}



//Social share
if ( ! function_exists( 'look_social_share' ) ) :
    function look_social_share() {
        global $post;

// Get post thumbnail
        $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), false, '' );
        ?>
        <div class="social-share">
          <ul>
             <li><a title="<?php echo esc_html__( 'Share this post on Facebook', 'look' ); ?>" class="facebook" href="http://www.facebook.com/sharer.php?u=<?php esc_url( the_permalink() ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=380,width=660');return false;">
                <i class="fa fa-facebook"></i>
            </a></li>
            <li><a title="<?php echo esc_html__( 'Share this post on Twitter', 'look' ); ?>" class="twitter" href="https://twitter.com/share?url=<?php esc_url( the_permalink() ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=380,width=660');return false;">
                <i class="fa fa-twitter"></i>
            </a></li>
            <li><a title="<?php echo esc_html__( 'Share this post on Google Plus', 'look' ); ?>" class="google-plus" href="https://plus.google.com/share?url=<?php esc_url( the_permalink() ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=380,width=660');return false;">
                <i class="fa fa-google-plus"></i>
            </a></li>
            <li><a title="<?php echo esc_html__( 'Share this post on Pinterest', 'look' ); ?>" class="pinterest" href="//pinterest.com/pin/create/button/?url=<?php esc_url( the_permalink() ); ?>&media=<?php echo esc_url( $src[0] ); ?>&description=<?php the_title(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                <i class="fa fa-pinterest-p"></i>
            </a></li>
            <li><a title="<?php echo esc_html__( 'Share this post on Tumbr', 'look' ); ?>" class="tumblr" data-content="<?php echo esc_url( $src[0] ); ?>" href="//tumblr.com/widgets/share/tool?canonicalUrl=<?php esc_url( the_permalink() ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=540');return false;">
                <i class="fa fa-tumblr"></i>
            </a></li>

        </ul>
    </div><!-- .social -->
    <?php }
    endif;

    function look_social_networks_meta() {
        $image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );
        $output  = '<meta property="og:site_name" content="'.get_bloginfo('name').'"/>'. "\n";
        $output .= '<meta property="og:image" content="'.$image_src_array[ 0 ].'"/>'. "\n";
        $output .= '<meta property="og:image:url" content="'.$image_src_array[ 0 ].'"/>'. "\n";
        $output .= '<meta property="og:url" content="'.esc_url(get_permalink()).'"/>'. "\n";
        $output .= '<meta property="og:title" content="'.esc_attr(strip_tags(get_the_title())).'"/>'. "\n";
        $output .= '<meta property="og:description" content="'.esc_attr(strip_tags(get_the_excerpt())).'"/>'. "\n";
        if(class_exists( 'WooCommerce' ) && is_product() )
        {
            $output .= '<meta property="og:type" content="product"/>'. "\n";
        }else{
            $output .= '<meta property="og:type" content="article"/>'. "\n";
        }
        echo balanceTags($output);
    }
    add_action('wp_head', 'look_social_networks_meta');

//Blog Comment
    add_filter( 'look_title_comments', 'look_title_comments');
    function look_title_comments() {
        return comments_number( __(' No Comment ','look'), __('1 Comment','look'), '% Comments ' );
    }

    function look_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo esc_attr($tag); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
        <?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php endif; ?>
            <div class="comment-author vcard">
                <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                <?php printf( __( '<div class="comment-meta"><div class="comment-author"><span class="author">%1s</span> <span class="says">says:</span></div><div class="comment-time">%2$s at %3$s
            </div></div>','look' ), get_comment_author_link(), get_comment_date(),  get_comment_time(), get_comment_link( $comment->comment_ID ) ); ?>
            <div class="comment-text"><?php comment_text(); ?></div>
        </div>
        <?php if ( $comment->comment_approved == '0' ) : ?>
            <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.','look' ); ?></em>
            <br />
        <?php endif; ?>
        <div class="reply">
            <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div>

        <?php if ( 'div' != $args['style'] ) : ?>
            <?php edit_comment_link( __( 'Edit','look' ), '  ', '' );?>
        </div>
    <?php endif; ?>
    <?php
}

/**
 * Load the TGM Plugin Activator class to notify the user
 * to install the Envato WordPress Toolkit Plugin
 */

add_action( 'tgmpa_register', 'look_register_required_plugins' );
function look_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'         => 'Envato WordPress Toolkit',
            'slug'         => 'envato-wordpress-toolkit-master',
            'source'       => THEME_URI.'/demo-files/plugins/envato-wordpress-toolkit-master.zip',
            'external_url' => THEME_URI.'/demo-files/plugins/envato-wordpress-toolkit-master.zip',
            'required'     => false,
            ),

        array(
            'name'        => 'Woocommerce',
            'slug'        => 'woocommerce',
            'required'    => true,
            ),
        array(
            'name'         => 'Look Book',
            'slug'         => 'lookbook',
            'source'       => THEME_URI.'/demo-files/plugins/lookbook.zip',
            'external_url' => THEME_URI.'/demo-files/plugins/lookbook.zip',
            'required'     => true,
            'version'            => '1.0.1',
            ),
        array(
            'name'         => 'MG New Arrival Product',
            'slug'         => 'mg_new_product',
            'source'       => THEME_URI.'/demo-files/plugins/mg_new_product.zip',
            'external_url' => THEME_URI.'/demo-files/plugins/mg_new_product.zip',
            'required'     => true,
            'version'            => '1.0.1',
            ),
        array(
            'name'      => 'Regenerate Thumbnails',
            'slug'      => 'regenerate-thumbnails',
            'required'  => false,
            ),
        array(
            'name'      => 'Instagram Feed',
            'slug'      => 'instagram-feed',
            'required'  => true,
            ),
        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => true,
            ),
        array(
            'name'      => 'MailChimp',
            'slug'      => 'mailchimp-for-wp',
            'required'  => true,
            ),

        array(
            'name'      => 'WP First Letter Avatar',
            'slug'      => 'wp-first-letter-avatar',
            'required'  => false,
            ),
        array(
            'name'      => 'YITH WooCommerce Wishlist',
            'slug'      => 'yith-woocommerce-wishlist',
            'required'  => true,
            ),
        array(
            'name'      => 'YITH WooCommerce Newsletter Popup',
            'slug'      => 'yith-woocommerce-popup',
            'required'  => true,
            ),
        array(
            'name'      => 'Page Builder by SiteOrigin',
            'slug'      => 'siteorigin-panels',
            'required'  => true,
            ),
        array(
            'name'      => 'Black Studio TinyMCE Widget',
            'slug'      => 'black-studio-tinymce-widget',
            'required'  => true,
            ),
        array(
            'name'      => 'SiteOrigin Widgets Bundle',
            'slug'      => 'so-widgets-bundle',
            'required'  => true,
            ),
        array(
            'name'      => 'Meta Slider',
            'slug'      => 'ml-slider',
            'required'  => true,
            ),
        array(
            'name'      => 'Product Thumbnail Flipper',
            'slug'      => 'wc-secondary-product-thumbnail',
            'required'  => true,
            )
        );

$config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        );
tgmpa( $plugins, $config );
}

// admin notices
add_action( 'admin_notices', 'look_admin_notice' );
function look_admin_notice(){

    if(get_option('dismiss_same_notice') != 1)
    {
        ?>
        <div class="update-nag settings-error notice is-dismissible sample-error" id="setting-error-tgmpa">
            <p>If you want to install sample data from live demo you need go to <a href="<?php echo admin_url('themes.php?page=look_sample'); ?>">Import Sample Page</a> and follow the tips.</p>

        </div>
        <?php
    }

}

function look_dismiss_same_notice() {
    if(!get_option('dismiss_same_notice'))
    {
        add_option('dismiss_same_notice',1);
    }else{
        update_option('dismiss_same_notice',1);
    }
}

add_action( 'wp_ajax_dismiss_same_notice', 'look_dismiss_same_notice',5,1 );

if(!function_exists('look_lookbook_icon'))
{
    function look_lookbook_icon($icon)
    {
        if(look_get_option('look_lookbook_icon') != '')
        {
            return look_get_option('look_lookbook_icon');
        }
        return $icon;

    }
}
add_filter('lookbook-icon','look_lookbook_icon');


