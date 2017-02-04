<?php

//language

add_action('after_setup_theme', 'look_theme_setup');
function look_theme_setup(){
    load_theme_textdomain('look', THEME_DIR . '/languages');
}


//help

add_action('wp_head','look_addCartJs');
if(!function_exists('look_addCartJs'))
{
    function look_addCartJs()
    {
        wp_enqueue_script( 'wc-add-to-cart-variation' );
    }

}


if ( ! function_exists( 'look_get_template' ) )
{
    function look_get_template($name = null )
    {
        $_template_file = "views/".VIEW."/{$name}.php";
        $located = THEME_DIR . '/' . $_template_file;
        load_template( $located,true );
    }

}


add_action("wp_ajax_load_product", "look_load_product");
add_action("wp_ajax_nopriv_load_product", "look_load_product");
function look_load_product()
{
    $id = (int)$_REQUEST['id'];
    $args = array(
        'p' => $id,
        'post_type' => 'product');
    $my_posts = new WP_Query($args);
    while ( $my_posts->have_posts() )
    {
        $my_posts->the_post();
        wc_get_template_part( 'content', 'single-product-ajax' );
    }

    exit;
}


add_action("wp_ajax_load_mini_cart", "look_load_mini_cart");
add_action("wp_ajax_nopriv_load_mini_cart", "look_load_mini_cart");
function look_load_mini_cart()
{
    $cart_html = '';
    wc_clear_notices();
    if(!function_exists('wc_register_widgets()'))
    {

        ob_start();
        $args['list_class'] = '';
        $file =  THEME_DIR.'/woocommerce/cart/mini-cart.php';
        include_once($file);
        $cart_html = ob_get_clean();
    }else{
        $cart_html = the_widget('WC_Widget_Cart');
    }
    $result = array(
        'cart_total' => WC()->cart->cart_contents_count,
        'cart_html' => $cart_html
        );
    echo json_encode($result);
    exit;
}

if ( is_singular() ) {
    wp_enqueue_script( 'comment-reply' );
}

//wp-title

function look_wp_title( $title, $sep ) {
    global $paged, $page;
    if ( is_feed() )
        return $title;

    // Add the site name.
    $title .= get_bloginfo( 'name', 'display' );

    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
        $title = "$title $sep " . sprintf( __( 'Page %s', 'look' ), max( $paged, $page ) );
    return $title;
}


//pagination
function look_pagination($pages = '', $range = 2)
{
    global $wp_query, $wp_rewrite;

    if ( $wp_query->max_num_pages < 2 ) {
        return;
    }

    $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    $showitems = ($range * 1)+1;


    if(empty($paged)) $paged = 1;

    if($pages == '')
    {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages)
        {
            $pages = 1;
        }
    }

    if(1 != $pages)
    {
        echo "<div class=\"pagination\"><div class='look_pagination'>";
        if($paged > 2 && $paged > $range + 1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&larr; ".__('First','look')."</a>";

        if($paged > 1 && $showitems < $pages)
        {
            echo "<a href='".get_pagenum_link($paged - 1)."' class='inactive'>".__('Prev','look')."</a>";
        }

        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
            }
        }

        if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\" class='inactive'>".__('Next','look')."</a>";
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>".__('Last','look')." &rarr;</a>";
        echo "</div>\n <span class='page-count'>".__('Page','look')." ".$paged." ".__('of','look')."  ".$pages."</span></div>";
    }
}

if(!function_exists('woo_check_breadcrumbs'))
{
    function woo_check_breadcrumbs($crumbs)
    {
        if( !look_get_option('display_breadcrumbs')) {
            return array();
        }
        return $crumbs;
    }
}
add_filter('woocommerce_get_breadcrumb','woo_check_breadcrumbs',5,2);
//look_breadcrumbs
if(!function_exists('look_breadcrumbs'))
{
    function look_breadcrumbs() {
        if( look_get_option('display_breadcrumbs')) {

            // Settings
            $separator = '&nbsp;&nbsp;&rarr;&nbsp;';
            $breadcrums_id = 'breadcrumbs';
            $breadcrums_class = 'breadcrumbs';
            $home_title = __('Home', 'look');
            $class = $breadcrums_class;
            // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
            $custom_taxonomy = 'product_cat';
            $prefix = '';

            // Get the query & post information
            global $post, $wp_query;

            // Do not display on the homepage
            if (!is_front_page()) {

                // Build the breadcrums
                echo '<ul id="' . $breadcrums_id . '" class="' . $class . '">';

                // Home page
                echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
                echo '<li class="separator separator-home"> ' . $separator . ' </li>';

                if (is_post_type_archive() && !is_tax()) {

                    echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';

                } else if (is_post_type_archive() && is_tax()) {

                    // If post is a custom post type
                    $post_type = get_post_type();

                    // If it is a custom post type display name and link
                    if ($post_type != 'post') {

                        $post_type_object = get_post_type_object($post_type);
                        $post_type_archive = get_post_type_archive_link($post_type);

                        echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                        echo '<li class="separator"> ' . $separator . ' </li>';

                    }

                    $custom_tax_name = get_queried_object()->name;
                    echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';

                } else if (is_single()) {

                    // If post is a custom post type
                    $post_type = get_post_type();

                    // If it is a custom post type display name and link
                    if ($post_type != 'post') {

                        $post_type_object = get_post_type_object($post_type);
                        $post_type_archive = get_post_type_archive_link($post_type);

                        echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                        echo '<li class="separator"> ' . $separator . ' </li>';

                    }

                    // Get post category info
                    $category = get_the_category();

                    // Get last category post is in
                    $tmp = array_values($category);
                    $last_category = end($tmp);
                    // Get parent any categories and create array
                    $get_cat_parents = '';
                    if (isset($last_category->term_id)) {
                        $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','), ',');
                    }

                    $cat_parents = explode(',', $get_cat_parents);

                    // Loop through parent categories and store in variable $cat_display
                    $cat_display = '';
                    foreach ($cat_parents as $parents) {
                        $cat_display .= '<li class="item-cat">' . $parents . '</li>';
                        $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                    }

                    // If it's a custom post type within a custom taxonomy
                    if (empty($last_category) && !empty($custom_taxonomy)) {

                        $taxonomy_terms = get_the_terms($post->ID, $custom_taxonomy);
                        if (isset($taxonomy_terms[0]->term_id)) {
                            $cat_id = $taxonomy_terms[0]->term_id;
                            $cat_nicename = $taxonomy_terms[0]->slug;
                            $cat_link = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                            $cat_name = $taxonomy_terms[0]->name;
                        } else {
                            $cat_id = '';
                            $cat_nicename = '';
                            $cat_link = '';
                            $cat_name = '';
                        }


                    }

                    // Check if the post is in a category
                    if (!empty($last_category)) {
                        echo balanceTags($cat_display);
                        echo '<li class="item-current item-' . esc_attr($post->ID) . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

                        // Else if post is in a custom taxonomy
                    } else if (!empty($cat_id)) {

                        echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . esc_attr($cat_nicename) . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                        echo '<li class="separator"> ' . $separator . ' </li>';
                        echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

                    }

                } else if (is_category()) {
                    $category = get_category($GLOBALS['wp_query']->get_queried_object());

                    // Category page
                    echo '<li class="item-current item-cat-' . $category->term_id . ' item-cat-' . $category->category_nicename . '"><strong class="bread-current bread-cat-' . $category->term_id . ' bread-cat-' . $category->category_nicename . '">' . $category->cat_name . '</strong></li>';

                } else if (is_page()) {

                    // Standard page
                    if ($post->post_parent) {

                        // If child page, get parents
                        $anc = get_post_ancestors($post->ID);

                        // Get parents in the right order
                        $anc = array_reverse($anc);
                        $parents = '';
                        // Parent page loop
                        foreach ($anc as $ancestor) {
                            $parents .= '<li class="item-parent item-parent-' . esc_attr($ancestor) . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                            $parents .= '<li class="separator separator-' . esc_attr($ancestor) . '"> ' . $separator . ' </li>';
                        }

                        // Display parent pages
                        echo balanceTags($parents);

                        // Current page
                        echo '<li class="item-current item-' . esc_attr($post->ID) . '"><strong title="' . esc_attr(get_the_title()) . '"> ' . get_the_title() . '</strong></li>';

                    } else {

                        // Just display current page if not parents
                        echo '<li class="item-current item-' . esc_attr($post->ID) . '"><strong class="bread-current bread-' . esc_attr($post->ID) . '"> ' . get_the_title() . '</strong></li>';

                    }

                } else if (is_tag()) {

                    // Tag page

                    // Get tag information
                    $term_id = get_query_var('tag_id');
                    $taxonomy = 'post_tag';
                    $args = 'include=' . $term_id;
                    $terms = get_terms($taxonomy, $args);

                    // Display the tag name
                    echo '<li class="item-current item-tag-' . $terms[0]->term_id . ' item-tag-' . $terms[0]->slug . '"><strong class="bread-current bread-tag-' . $terms[0]->term_id . ' bread-tag-' . $terms[0]->slug . '">' . $terms[0]->name . '</strong></li>';

                } elseif (is_day()) {

                    // Day archive

                    // Year link
                    echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link(get_the_time('Y')) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
                    echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

                    // Month link
                    echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
                    echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';

                    // Day display
                    echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';

                } else if (is_month()) {

                    // Month Archive

                    // Year link
                    echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link(get_the_time('Y')) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
                    echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

                    // Month display
                    echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';

                } else if (is_year()) {

                    // Display year archive
                    echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';

                } else if (is_author()) {

                    // Auhor archive

                    // Get the author information
                    global $author;
                    $userdata = get_userdata($author);

                    // Display author name
                    echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';

                } else if (get_query_var('paged')) {

                    // Paginated archives
                    echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">' . __('Page', 'look') . ' ' . get_query_var('paged') . '</strong></li>';

                } else if (is_search()) {

                    // Search results page
                    echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';

                } elseif (is_404()) {

                    // 404 page
                    echo '<li>' . 'Error 404' . '</li>';
                } else {
                    global $wp_query;
                    if (isset($wp_query->queried_object)) {
                        $obj = $wp_query->queried_object;
                        echo '<li class="item-current item-' . $obj->ID . '"><strong title="' . $obj->post_title . '"> ' . $obj->post_title . '</strong></li>';
                    }
                }

                echo '</ul>';

            }
        }

    }
}


//product ajax search

add_action('wp_ajax_look_search_products', 'look_searchProducts');
add_action("wp_ajax_nopriv_look_search_products", "look_searchProducts");
if(!function_exists('look_searchProducts'))
{
    function look_searchProducts()
    {
        $result = array();
        $search_query['s'] = urldecode($_REQUEST['q']);
        $search_query['post_type'] = 'product';
        $search_query['posts_per_page'] = 10;
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
                    'value' => get_the_title(),
                    'thumb' => $thumb,
                    'url'  => get_the_permalink()
                    );
            }
        }
        echo json_encode($result);
        exit;
    }
}



//metaslider_hoplink
function look_metaslider_hoplink($link) {
    return "https://getdpd.com/cart/hoplink/15318?referrer=ribje3lp1uogw48ks4";
}
add_filter('metaslider_hoplink', 'look_metaslider_hoplink', 10, 1);


if(!function_exists('look_theme_header_script'))
{
    function look_theme_header_script() {
        echo '
        <script type="text/javascript" >
            var look_ajax_url = "'.admin_url('admin-ajax.php').'";
            var look_product_ajax_load  = 0;
            var look_ajax_search = '.(int)look_get_option('ajax_search').';
            var look_ajax_search_thumbnail = '.(int)look_get_option('show_search_thumbnail').';
            var look_swatch_attr = "'.esc_attr( sanitize_title(look_get_option('look_attribute_image_swatch'))).'";
            var look_product_image_zoom = '.(int)look_get_option('product_image_zoom').';
            var look_menu_sticky = '.(int)look_get_option('look_sticky_menu').';
            var look_add_cart_url = "'.get_site_url().'/index.php";
            var look_enable_product_lightbox = '.(int)get_option( 'woocommerce_enable_lightbox' ).';
            var isMobile = {
                Android: function() {
                    return navigator.userAgent.match(/Android/i);
                },
                BlackBerry: function() {
                    return navigator.userAgent.match(/BlackBerry/i);
                },
                iOS: function() {
                    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                },
                Opera: function() {
                    return navigator.userAgent.match(/Opera Mini/i);
                },
                Windows: function() {
                    return navigator.userAgent.match(/IEMobile/i);
                },
                any: function() {
                    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                }
            };
        </script>';
    }
}

add_action( 'wp_head', 'look_theme_header_script' );


// add ga code
if(look_get_option('look_ga_code'))
{
    if(!function_exists('look_theme_footer_script'))
    {
        function look_theme_footer_script() {
            echo '<script type="text/javascript" >'.look_get_option('look_ga_code').'</script>';
        }
    }

    add_action( 'wp_footer', 'look_theme_footer_script' );
}

// Custom CSS
if(look_get_option('look_custom_css'))
{
    if(!function_exists('look_theme_footer_style'))
    {
        function look_theme_footer_style() {
            echo '<style type="text/css">'.look_get_option('look_custom_css').'</style>';
        }
    }
    add_action( 'wp_footer', 'look_theme_footer_style' );
}


if ( ! function_exists( 'look_comment_nav' ) ) {
    function look_comment_nav() {
        // Are there comments to navigate through?
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            ?>
            <nav class="navigation comment-navigation" role="navigation">
                <div class="nav-links">
                    <?php
                    if ( $prev_link = get_previous_comments_link( __( 'Older Comments <span>&rarr;</span>', 'look' ) ) ) :
                        printf( '<div class="nav-previous">%s</div>', $prev_link );
                    endif;

                    if ( $next_link = get_next_comments_link( __( '<span>&larr;</span> Newer Comments', 'look' ) ) ) :
                        printf( '<div class="nav-next">%s</div>', $next_link );
                    endif;
                    ?>
                </div><!-- .nav-links -->
            </nav><!-- .comment-navigation -->
        <?php
        endif;
    }
}
if ( ! isset( $content_width ) ) $content_width = 900;


if(! function_exists('look_price_ranges'))
{
    function look_price_ranges()
    {
        $ranges = look_get_option('look_price_range');
        $result = false;
        if($ranges != '')
        {
            $result = array();
            $lines = explode(PHP_EOL,$ranges);
            foreach($lines as $line)
            {
                $tmp = explode('|',trim($line));
                if(count($tmp) == 2)
                {
                    $key = esc_attr(trim($tmp[0]));
                    $value = trim($tmp[1]);
                    $tmp = explode(',',$key);
                    $min = isset($tmp[0]) ? $tmp[0] : 0;
                    $max = isset($tmp[1]) ? $tmp[1] : 10000000;
                    $key = implode(',',array($min,$max));
                    $value = array(
                        'label' => $value,
                        'min' => $min,
                        'max' => $max
                    );
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }

}

if(!function_exists('look_social_block'))
{
    function look_social_block()
    {

        ?>
        <ul class="socials">
            <?php if(look_get_option('look_fb_url') != ''): ?>
                <li>
                    <a href="<?php echo look_get_option('look_fb_url'); ?>" target="_blank">
                        <?php echo (look_get_option('look_fb_icon') != '') ? '<img src="'.look_get_option('look_fb_icon').'"/>' : '<i class="fa fa-facebook"></i>'  ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php if(look_get_option('look_twtter_username')): ?>
                <li><a href="<?php echo look_get_option('look_twtter_username'); ?>" target="_blank">
                        <?php echo (look_get_option('look_twtter_icon') != '') ? '<img src="'.look_get_option('look_twtter_icon').'"/>' : '<i class="fa fa-twitter"></i>'  ?>
                    </a></li>
            <?php endif; ?>
            <?php if(look_get_option('look_google_plus_url')): ?>
                <li><a href="<?php echo look_get_option('look_google_plus_url'); ?>" target="_blank">
                        <?php echo (look_get_option('look_google_plus_icon') != '') ? '<img src="'.look_get_option('look_google_plus_icon').'"/>' : '<i class="fa fa-google-plus"></i>'  ?>

                    </a></li>
            <?php endif; ?>
            <?php if(look_get_option('look_pinterest_url')): ?>
                <li><a href="<?php echo look_get_option('look_pinterest_url'); ?>" target="_blank">
                        <?php echo (look_get_option('look_pinterest_icon') != '') ? '<img src="'.look_get_option('look_pinterest_icon').'"/>' : '<i class="fa fa-pinterest-p"></i>'  ?>

                    </a></li>
            <?php endif; ?>
            <?php if(look_get_option('look_instagram_url')): ?>
                <li><a href="<?php echo look_get_option('look_instagram_url'); ?>" target="_blank">
                        <?php echo (look_get_option('look_instagram_icon') != '') ? '<img src="'.look_get_option('look_instagram_icon').'"/>' : '<i class="fa fa-instagram"></i>'  ?>
                    </a></li>
            <?php endif; ?>

            <?php if(look_get_option('mg_tumblr_url')): ?>
                <li><a href="<?php echo look_get_option('mg_tumblr_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_tumblr_icon') != '') ? '<img src="'.look_get_option('mg_tumblr_icon').'"/>' : '<i class="fa fa-tumblr"></i>'  ?>
                    </a></li>
            <?php endif; ?>

            <?php if(look_get_option('mg_youtube_url')): ?>
                <li><a href="<?php echo look_get_option('mg_youtube_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_youtube_icon') != '') ? '<img src="'.look_get_option('mg_youtube_icon').'"/>' : '<i class="fa fa-youtube"></i>'  ?>
                    </a></li>
            <?php endif; ?>

            <?php if(look_get_option('mg_vimeo_url')): ?>
                <li><a href="<?php echo look_get_option('mg_vimeo_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_vimeo_icon') != '') ? '<img src="'.look_get_option('mg_vimeo_icon').'"/>' : '<i class="fa fa-vimeo-square"></i>'  ?>
                    </a></li>
            <?php endif; ?>

            <?php if(look_get_option('mg_linkedin_url')): ?>
                <li><a href="<?php echo look_get_option('mg_linkedin_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_linkedin_icon') != '') ? '<img src="'.look_get_option('mg_linkedin_icon').'"/>' : '<i class="fa fa-linkedin"></i>'  ?>
                    </a></li>
            <?php endif; ?>

            <?php if(look_get_option('mg_dribbble_url')): ?>
                <li><a href="<?php echo look_get_option('mg_dribbble_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_dribbble_icon') != '') ? '<img src="'.look_get_option('mg_dribbble_icon').'"/>' : '<i class="fa fa-dribbble"></i>'  ?>
                    </a></li>
            <?php endif; ?>
            <?php if(look_get_option('mg_behance_url')): ?>
                <li><a href="<?php echo look_get_option('mg_behance_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_behance_icon') != '') ? '<img src="'.look_get_option('mg_behance_icon').'"/>' : '<i class="fa fa-behance"></i>'  ?>
                    </a></li>
            <?php endif; ?>
            <?php if(look_get_option('mg_lastfm_url')): ?>
                <li><a href="<?php echo look_get_option('mg_lastfm_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_lastfm_icon') != '') ? '<img src="'.look_get_option('mg_lastfm_icon').'"/>' : '<i class="fa fa-lastfm"></i>'  ?>
                    </a></li>
            <?php endif; ?>
            <?php if(look_get_option('mg_vk_url')): ?>
                <li><a href="<?php echo look_get_option('mg_vk_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_vk_icon') != '') ? '<img src="'.look_get_option('mg_vk_icon').'"/>' : '<i class="fa fa-vk"></i>'  ?>
                    </a></li>
            <?php endif; ?>
            <?php if(look_get_option('mg_stumbleupon_url')): ?>
                <li><a href="<?php echo look_get_option('mg_stumbleupon_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_stumbleupon_icon') != '') ? '<img src="'.look_get_option('mg_stumbleupon_icon').'"/>' : '<i class="fa fa-stumbleupon"></i>'  ?>
                    </a></li>
            <?php endif; ?>
            <?php if(look_get_option('mg_delicious_url')): ?>
                <li><a href="<?php echo look_get_option('mg_delicious_url'); ?>" target="_blank">
                        <?php echo (look_get_option('mg_delicious_icon') != '') ? '<img src="'.look_get_option('mg_delicious_icon').'"/>' : '<i class="fa fa-delicious"></i>'  ?>
                    </a></li>
            <?php endif; ?>
        </ul>
        <?php
    }
}
