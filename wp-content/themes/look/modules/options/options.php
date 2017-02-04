<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */

if(!function_exists('look_optionsframework_option_name'))
{
    function look_optionsframework_option_name() {

        // This gets the theme name from the stylesheet (lowercase and without spaces)
        $themename = get_option( 'stylesheet' );
        $themename = preg_replace("/\W/", "_", strtolower($themename) );

        $optionsframework_settings = get_option('look_optionsframework');
        $optionsframework_settings['id'] = $themename;
        update_option('look_optionsframework', $optionsframework_settings);

    }
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */
if(!function_exists('look_optionsframework_options'))
{
    function look_optionsframework_options() {
        $attributes = array();
        if ( class_exists( 'WooCommerce' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            $attributes = array();
            foreach($attribute_taxonomies as $attr)
            {
                if($attr->attribute_type == 'select')
                {
                    $key = 'pa_'.$attr->attribute_name;
                    $attributes[$key] = $attr->attribute_label;
                }
            }
        }


        // Background Defaults
        $background_defaults = array(
            'color' => '',
            'image' => '',
            'repeat' => 'repeat',
            'position' => 'top center',
            'attachment'=>'scroll' );

        // Typography Defaults
        $typography_defaults = array(
            'size' => '15px',
            'face' => 'georgia',
            'style' => 'bold',
            'color' => '#bada55' );

        // Typography Options
        $typography_options = array(
            'sizes' => array( '6','12','14','16','20' ),
            'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
            'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
            'color' => false
            );

        // Pull all the categories into an array
        $options_categories = array();
        $options_categories_obj = get_categories();
        foreach ($options_categories_obj as $category) {
            $options_categories[$category->cat_ID] = $category->cat_name;
        }

        // Pull all tags into an array
        $options_tags = array();
        $options_tags_obj = get_tags();
        foreach ( $options_tags_obj as $tag ) {
            $options_tags[$tag->term_id] = $tag->name;
        }

        // Pull all the pages into an array
        $options_pages = array();
        $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
        $options_pages[''] = 'Select a page:';
        foreach ($options_pages_obj as $page) {
            $options_pages[$page->ID] = $page->post_title;
        }

        // If using image radio buttons, define a directory path
        $imagepath =  get_template_directory_uri() . '/modules/options/images/';

        $options = array();

        //General setting

        $options[] = array(
            'name' => __('General Settings', 'look'),
            'type' => 'heading' );

        $options[] = array(
            'name' => __('Layout', 'look'),
            'desc' => "",
            'id' => "look_layout",
            'std' => "fix",
            'type' => "images",
            'options' => array(
                'full' => $imagepath . '1col.png',
                'home-left' => $imagepath . '2cl.png',
                'fix' => $imagepath . 'fix.png',
                )
            );


        $options[] = array(
            'name' => __('Sticky Menu', 'look'),
            'desc' =>  __("use for full and fix layout only",'look'),
            'id' => "look_sticky_menu",
            'std' => "1",
            'type' => "select",
            'class' => 'mini',
            'options' => array(
                '0' => __('No', 'look'),
                '1' => __('Yes', 'look')
                )
            );

        $header_layout_array = array(
            '0'	   => __('Header Layout 1','look'),
            '1'	   => __('Header Layout 2','look'),
            '2'    => __('Header Layout 3','look')
            );
        $footer_layout_array = array(
            '0' => __('No', 'look'),
            '1' => __('Yes', 'look')
            );
        $options[] = array(
            'name' => __('Header Layout', 'look'),
            'desc' => __('Run only on full layout or fix layout', 'look'),
            'id'   => 'header',
            'std'  => '0',
            'type' => 'select',
            'class'  =>'header',
            'options' => $header_layout_array);
        $options[] = array(
            'name' => __('Regular Logo', 'look'),
            'id'   => 'look_logo',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Retina Logo', 'look'),
            'id'   => 'look_retina_logo',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Copyright Text', 'look'),
            'id'   => 'look_footer_text',
            'std'  => __('The Look &copy;2015 - All Rights Reserved', 'look'),
            'type' => 'text');
        $options[] = array(
            'name' => __('Footer Layout', 'look'),
            'id'   => 'footer_layout',
            'desc' => __('Display footer on left layout', 'look'),
            'std'  => '0',
            'type' => 'select',
            'class'=> 'footer',
            'options' => $footer_layout_array);
        $options[] = array(
            'name' => __('Display Breadcrumbs ', 'look'),
            'id'   => 'display_breadcrumbs',
            'desc' => __('', 'look'),
            'std'  => '1',
            'type' => 'select',
            'options' => $footer_layout_array);
        $options[] = array(
            'name' => __('Header promo text', 'look'),
            'id'   => 'look_top_notice',
            'std'  => __('Free shipping for standard order over $75', 'look'),
            'type' => 'text');
        $options[] = array(
            'name' => __('Favicon Image', 'look'),
            'id'   => 'look_favicon',
            'type' => 'upload');


        $options[] = array(
            'name' => __('Lookbook Icon', 'look'),
            'id'   => 'look_lookbook_icon',
            'type' => 'upload');


        $options[] = array(
            'name' => __('Custom Css', 'look'),
            'desc' => '',
            'id'   => 'look_custom_css',
            'type' => 'textarea');

        $assets_compress_array = array(
            '0' => __('None', 'look'),
            '1' => __('Css only', 'look'),
            '2' => __('Js only', 'look'),
            '3' => __('Css and Js', 'look')
            );
        $options[] = array(
            'name' => __('Assets Minify', 'look'),
            'desc' => __('Minify javascript and style sheet file', 'look'),
            'id' => 'asset_minify',
            'std' => '0',
            'type' => 'select',
            'class' => 'mini', //mini, tiny, small
            'options' => $assets_compress_array);

        $html_compress_array = array(
            '0' => __('No', 'look'),
            '1' => __('Yes', 'look')
            );
        $options[] = array(
            'name' => __('Html Minify', 'look'),
            'desc' => __('Minify html output on front end', 'look'),
            'id' => 'html_minify',
            'std' => '0',
            'type' => 'select',
            'class' => 'mini', //mini, tiny, small
            'options' => $html_compress_array);
        // shopping setting

        if ( class_exists( 'WooCommerce' ) ) {
            $options[] = array(
                'name' => __('Woocommerce Settings', 'look'),
                'type' => 'heading' );
        }
        $product_layout_array = array(
            '2'	   => __('2 columns','look'),
            '3'	   => __('3 columns','look'),
            '4'	   => __('4 columns','look'),
            );
        $options[] = array(
            'name' => __('Product Listing Style', 'look'),
            'desc' => __('Display style for product list', 'look'),
            'id'   => 'product_listing_style',
            'std'  => '0',
            'type' => 'select',
            'class'  =>'',
            'options' => array(
                0 =>  __('Products List Style 1','look'),
                2 =>  __('Products List Style 2','look')
                )
            );
        $options[] = array(
            'name' => __('Products per page', 'look'),
            'desc' => __('Number of products displayed per page', 'look'),
            'id'   => 'loop_shop_per_page',
            'std'  => '9',
            'type' => 'text',
            'class'  =>'');

        $options[] = array(
            'name' => __('Product Listing Layout', 'look'),
            'desc' => __('Display number of product on a row', 'look'),
            'id'   => 'product_listing',
            'std'  => '3',
            'type' => 'select',
            'class'  =>'',
            'options' => $product_layout_array);

        if(!empty($attributes))
        {
            $tmp = array_keys($attributes);

            $options[] = array(
                'name' => __('Swatch style Attributes', 'look'),
                'desc' => "",
                'id' => "look_attribute_swatch",
                'std' => $tmp[0],
                'type' => "multicheck",
                'options' => $attributes
                );

            $options[] = array(
                'name' => __('Swatch Images Attributes', 'look'),
                'desc' => "",
                'id' => "look_attribute_image_swatch",
                'std' => $tmp[0],
                'type' => "radio",
                'options' => $attributes
                );

            $options[] = array(
                'name' => __('Attributes Filters', 'look'),
                'desc' => "",
                'id' => "look_attribute_filters",
                'std' => $tmp[0],
                'type' => "multicheck",
                'options' => $attributes
                );
        }

        $options[] = array(
            'name' => __('Price Filter', 'look'),
            'desc' => 'Sample: 100,200|$100-$200 (1 range per line).',
            'id'   => 'look_price_range',
            'class' => 'small',
            'type' => 'textarea');

        $options[] = array(
            'name' => __('Ajax Search', 'look'),
            'desc' => __('Ajax search on front end', 'look'),
            'id' => 'ajax_search',
            'std' => '0',
            'type' => 'select',
            'class' => 'mini', //mini, tiny, small
            'options' => $html_compress_array);

        $options[] = array(
            'name' => __('Product Video', 'look'),
            'desc' => __('allow product video', 'look'),
            'id' => 'look_product_video',
            'std' => '0',
            'type' => 'select',
            'class' => 'mini', //mini, tiny, small
            'options' => $html_compress_array);

        $options[] = array(
            'name' => __('Show thumbnail', 'look'),
            'desc' => __('Show product thumbnail on search result.', 'look'),
            'id' => 'show_search_thumbnail',
            'std' => '0',
            'type' => 'checkbox');
        $options[] = array(
            'name' => __('Product Image Zoom', 'look'),
            'desc' => __('Zoom function for product image', 'look'),
            'id' => 'product_image_zoom',
            'std' => '0',
            'type' => 'select',
            'class' => 'mini', //mini, tiny, small
            'options' => $html_compress_array);
        $options[] = array(
            'name' => __('Enable Size Guide', 'look'),
            'desc' => __('Enable product size guide', 'look'),
            'id' => 'enable_product_size_guide',
            'std' => '1',
            'type' => 'select',
            'class' => 'mini', //mini, tiny, small
            'options' => $html_compress_array);
        $options[] = array(
            'name' => __('Default Size Guide', 'look'),
            'desc' => __('Default product size guide', 'look'),
            'id' => 'default_product_size_guide',
            'std' => MAIN_ASSETS_URI.'/images/sizecharts.png',
            'type' => 'upload');
        //social setting

        $options[] = array(
            'name' => __('Social Settings', 'look'),
            'type' => 'heading' );
        $options[] = array(
            'name' => __('Facebook Page URL', 'look'),
            'id' => 'look_fb_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Facebook Icon', 'look'),
            'id' => 'look_fb_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Twitter Page URL', 'look'),
            'id' => 'look_twtter_username',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Twitter Icon', 'look'),
            'id' => 'look_twtter_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Google Plus URL', 'look'),
            'id' => 'look_google_plus_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Google Plus Icon', 'look'),
            'id' => 'look_google_plus_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Instagram URL', 'look'),
            'id' => 'look_instagram_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Instagram Icon', 'look'),
            'id' => 'look_instagram_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Pinterest URL', 'look'),
            'id' => 'look_pinterest_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Pinterest Icon', 'look'),
            'id' => 'look_pinterest_icon',
            'std' => '',
            'type' => 'upload');

        $options[] = array(
            'name' => __('Tumblr URL', 'look'),
            'id' => 'mg_tumblr_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Tumblr Icon', 'look'),
            'id' => 'mg_tumblr_icon',
            'std' => '',
            'type' => 'upload');

        $options[] = array(
            'name' => __('Youtube URL', 'look'),
            'id' => 'mg_youtube_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Youtube Icon', 'look'),
            'id' => 'mg_youtube_icon',
            'std' => '',
            'type' => 'upload');

        $options[] = array(
            'name' => __('Vimeo URL', 'look'),
            'id' => 'mg_vimeo_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Vimeo Icon', 'look'),
            'id' => 'mg_vimeo_icon',
            'std' => '',
            'type' => 'upload');

        $options[] = array(
            'name' => __('Linkedin URL', 'look'),
            'id' => 'mg_linkedin_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Linkedin Icon', 'look'),
            'id' => 'mg_linkedin_icon',
            'std' => '',
            'type' => 'upload');

        $options[] = array(
            'name' => __('Dribbble URL', 'look'),
            'id' => 'mg_dribbble_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Dribbble Icon', 'look'),
            'id' => 'mg_dribbble_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Behance URL', 'look'),
            'id' => 'mg_behance_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Behance Icon', 'look'),
            'id' => 'mg_behance_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('LastFM URL', 'look'),
            'id' => 'mg_lastfm_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('LastFM Icon', 'look'),
            'id' => 'mg_lastfm_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('VK URL', 'look'),
            'id' => 'mg_vk_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('VK Icon', 'look'),
            'id' => 'mg_vk_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Stumbleupon URL', 'look'),
            'id' => 'mg_stumbleupon_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Stumbleupon Icon', 'look'),
            'id' => 'mg_stumbleupon_icon',
            'std' => '',
            'type' => 'upload');
        $options[] = array(
            'name' => __('Delicious URL', 'look'),
            'id' => 'mg_delicious_url',
            'std' => '',
            'type' => 'text');
        $options[] = array(
            'name' => __('Delicious Icon', 'look'),
            'id' => 'mg_delicious_icon',
            'std' => '',
            'type' => 'upload');

        $options[] = array(
            'name' => __('Google Analytics code', 'look'),
            'desc' => '',
            'id' => 'look_ga_code',
            'type' => 'textarea');

        return $options;
    }
}
