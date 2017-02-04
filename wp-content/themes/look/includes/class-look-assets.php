<?php
if(!class_exists('Minifier'))
{
    require('class.magic-min.php');
}
if(!class_exists('Minify_HTML'))
{
    require('HTML.php');
}

class Look_Assets
{
    public $ignore = array();
    public $cache_dir = '';
    public $localized = array();

    public function __construct()
    {

        $this->public = array();
        $this->ignore = array();
        $this->cache_dir = THEME_DIR . '/cache';
    }

    public function clean_cache()
    {

        $cdir = scandir(THEME_DIR . '/cache/');
        foreach ($cdir as $key => $value)
        {
            if (!in_array($value,array(".","..",'index.html','index.php')))
            {
                $file = THEME_DIR . '/cache/'.$value;
                unlink($file);
            }
        }


    }

    public function init()
    {
        add_action('wp_enqueue_scripts', array($this, 'look_assets'));
        add_action('admin_enqueue_scripts', array($this, 'look_admin_assets'));
        if(look_get_option('asset_minify') > 0)
        {
            if (!is_admin() ) {
                if (is_writable($this->cache_dir)) {
                    $this->mini_assets();
                }else{
                    add_action( 'admin_notices', array($this,'cache_admin_notice') );
                }
            }
        }
        if(look_get_option('html_minify') > 0)
        {
            add_filter('template_include', array($this,'template_include'),10,3);
        }

        add_action('optionsframework_after_validate',array($this,'clean_cache'));
    }

    public function cache_admin_notice()
    {
        ?>
        <div class="update-nag settings-error notice is-dismissible cache-error" id="setting-error-tgmpa">
            <p>You can't use assets minify action because your cache folder is not writable , Please set the folder /themes/look/cache to writable to do this action. </p>
        </div>
        <?php
    }

    public function template_include($template)
    {
        ob_start();
        include($template);
        $buffer = ob_get_clean();
        echo balanceTags($this->minify_output($buffer));
        return;
    }

    function minify_output($buffer)
    {
        $buffer = Minify_HTML::minify($buffer);
        return $buffer;
    }
    //script
    public function  look_assets()
    {
        wp_enqueue_style('look_bootstrap', MAIN_ASSETS_URI . '/css/bootstrap.min.css');
        wp_enqueue_style('look_font-awesome', MAIN_ASSETS_URI . '/css/font-awesome.min.css');
        wp_enqueue_style('look_style', get_stylesheet_uri());
        wp_enqueue_style('look_animate', MAIN_ASSETS_URI . '/css/animate.css');
        wp_enqueue_style('look_retina', MAIN_ASSETS_URI . '/css/retina.css');
        wp_enqueue_style( 'look_google-font', $this->look_fonts_url(), array(), '1.0.0' );

        if ( is_rtl() ) {
            wp_enqueue_style('look-rtl', MAIN_ASSETS_URI . '/css/style-rtl.css');
        }
        if (  defined( 'ECWID_PLUGIN_DIR' ) ) {

            wp_enqueue_style('look_ecwid', MAIN_ASSETS_URI . '/css/style-ecwid.css');
        }

        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('look_bootstrap', MAIN_ASSETS_URI . '/js/bootstrap.min.js',array('jquery'), VERSION, true);
        wp_enqueue_script('look_jquery.validate', MAIN_ASSETS_URI . '/js/jquery.validate.min.js', array('jquery'), VERSION, true);
        wp_enqueue_script('look_verticalCarousel', MAIN_ASSETS_URI . '/js/jquery.verticalCarousel.min.js', array('jquery'), VERSION, true);
        wp_enqueue_script('look_waitforimages', MAIN_ASSETS_URI . '/js/jquery.waitforimages.js', array('jquery'), VERSION, true);
        wp_enqueue_script('look_template', MAIN_ASSETS_URI . '/js/template.js', array('jquery'), VERSION, true);
        wp_enqueue_script('comment-reply');

        if(look_get_option('product_image_zoom')  == 1 )
        {

            wp_enqueue_script('look_zoomsl', MAIN_ASSETS_URI . '/js/zoomsl-3.0.min.js', array('jquery'), VERSION, true);
        }
    }

    function look_fonts_url() {
        $font_url = add_query_arg( 'family', urlencode( 'Lato:300,400,700|Montserrat:400,700&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
        return $font_url;
    }


    public function look_admin_assets()
    {
            //assets
        wp_enqueue_style('look_admin-pagetitle-options', THEME_URI . '/assets/admin/css/page-title-options.css', array(), VERSION);
        wp_enqueue_script('look_admin-pagetitle-options', THEME_URI . '/assets/admin/js/page-title-options.js', array('jquery'), VERSION);
            //end assets
    }

    public function mini_assets()
    {
        if(look_get_option('asset_minify')  == 1 || look_get_option('asset_minify')  == 3)
        {
            add_action('wp_print_styles', array($this, 'dequeue_css'));
        }

        if(look_get_option('asset_minify')  == 2 || look_get_option('asset_minify')  == 3)
        {
            add_action('wp_print_scripts', array($this, 'dequeue_js'));

        }
    }


    protected function getUrlRegex($url)
    {
        $regex = '@^' . str_replace('http\://', '(https?\:)?\/\/', preg_quote($url)) . '@';
        return $regex;
    }


    public function guessPath($file_url)
    {
        $components = parse_url($file_url);
    // Check we have at least a path
        if (!isset($components['path']))
            return false;
        $file_path = false;
        $wp_plugin_url = plugins_url();
        $wp_content_url = content_url();
    // Script is enqueued from a plugin
        $url_regex = $this->getUrlRegex($wp_plugin_url);
        if (preg_match($url_regex, $file_url) > 0)
            $file_path = WP_PLUGIN_DIR . preg_replace($url_regex, '', $file_url);
    // Script is enqueued from a theme
        $url_regex = $this->getUrlRegex($wp_content_url);
        if (preg_match($url_regex, $file_url) > 0)
            $file_path = WP_CONTENT_DIR . preg_replace($url_regex, '', $file_url);
    // Script is enqueued from wordpress
        if (strpos($file_url, WPINC) !== false)
            $file_path = untrailingslashit(ABSPATH) . $file_url;
        return $file_path;
    }

    public function isFileExcluded($file)
    {
        if (in_array($file, $this->ignore)) {
            return true;
        } else {
            return false;
        }

    }

    function extract_css_url_custom($text)
    {
        preg_match_all('/url\((.*)\)/isU',$text,$rs);
        $result = array();
        if(isset($rs[1]))
        {
            foreach($rs[1] as $r) {
                $r = trim($r, '"');
                $r = trim($r, "'");
                $r = trim($r, '?');
                $s = trim($r, "#");
                $result[] = $s;
            }
        }
        return $result;
    }

    function extract_css_urls($text)
    {
        $urls = array();

        $url_pattern = '(([^\\\\\'", \(\)]*(\\\\.)?)+)';
        $urlfunc_pattern = 'url\(\s*[\'"]?' . $url_pattern . '[\'"]?\s*\)';
        $pattern = '/(' .
            '(@import\s*[\'"]' . $url_pattern . '[\'"])' .
            '|(@import\s*' . $urlfunc_pattern . ')' .
            '|(' . $urlfunc_pattern . ')' . ')/iu';
if (!preg_match_all($pattern, $text, $matches))
    return $urls;

        // @import '...'
        // @import "..."
foreach ($matches[3] as $match)
    if (!empty($match))
        $urls['import'][] =
    preg_replace('/\\\\(.)/u', '\\1', $match);

        // @import url(...)
        // @import url('...')
        // @import url("...")
    foreach ($matches[7] as $match)
        if (!empty($match))
            $urls['import'][] =
        preg_replace('/\\\\(.)/u', '\\1', $match);

        // url(...)
        // url('...')
        // url("...")
        foreach ($matches[11] as $match)
            if (!empty($match))
                $urls['property'][] =
            preg_replace('/\\\\(.)/u', '\\1', $match);

            return $urls;
        }

        public function dequeue_css()
        {
            global $wp_styles;
            global $wp_filesystem;
            $assets = array();
            if (empty($wp_styles->queue))
                return;

            $wp_styles->all_deps($wp_styles->queue);

            $min = new Minifier(array('echo'=>false));

            foreach ($wp_styles->to_do as $key => $handle) {

                if ($this->isFileExcluded($wp_styles->registered[$handle]->src))
                    continue;

            //Removes absolute part of the path if it's specified in the src
                $style_path = $this->guessPath($wp_styles->registered[$handle]->src);
            // Script didn't match any case (plugin, theme or wordpress locations)
                if ($style_path == false)
                    continue;

                if (!file_exists($style_path))
                    continue;
                if(strpos('.min.',$wp_styles->registered[$handle]->src) === false)
                {
                    $css_cache = md5($style_path).'.css';
                    if(!file_exists($this->cache_dir.'/'.$css_cache))
                    {
                        $css_content = '';
                        $import = array();
                        $src = $wp_styles->registered[$handle]->src;
                        $content = $min->minify_contents($style_path);
                        $tmp = $this->extract_css_urls($content);
                        $rs = $this->extract_css_url_custom($content);
                        if(isset($tmp['property']))
                        {
                            $tmp['property'] = array_merge($rs,$tmp['property']);
                        }else{
                            $tmp['property'] = $rs;
                        }
                        if(!empty($tmp))
                        {
                            $cssdir = explode('/',$src);

                            if(isset($tmp['property']) && !empty($tmp['property']))
                            {
                                $properties = array_unique($tmp['property']);
                                foreach($properties as $p)
                                {
                                    $s = $p;
                                    if(strpos($p,'//') === false)
                                    {
                                        $count = substr_count($p, '../');
                                        if($count > 0)
                                        {
                                            $tmp = '';
                                            for($i=0;$i<count($cssdir) -1 - $count;$i++)
                                            {
                                                $tmp .= $cssdir[$i].'/';
                                            }

                                            $p = str_replace('../','',$p);
                                            $p = $tmp.$p;


                                        }else{
                                            $p = str_replace(end($cssdir),$s,$src);

                                        }
                                    }

                                    $content = str_replace($s,$p,$content);
                                }
                            }

                            if(isset($tmp['import']) && !empty($tmp['import']))
                            {
                                $properties = $tmp['import'];
                                foreach($properties as $p)
                                {
                                    $s = $p;

                                    if(strpos($p,'//') === false)
                                    {
                                        $count = substr_count($p, '../');
                                        if($count > 0)
                                        {
                                            $tmp = '';
                                            for($i=0;$i<count($cssdir) -1 - $count;$i++)
                                            {
                                                $tmp .= $cssdir[$i].'/';
                                            }
                                            $p = str_replace('../','',$p);
                                            $p = $tmp.$p;
                                        }


                                    }
                                    $import[] = $p;
                                }
                            }
                        }

                        $css_content .= $content.PHP_EOL;
                        $tmp_import = '';
                        foreach($import as $i)
                        {
                            $tmp_import .= '@import url("'.$i.'");'.PHP_EOL;
                        }
                        $css_content = $tmp_import.$css_content;
                        file_put_contents($this->cache_dir.'/'.$css_cache,$css_content);

                    }

                    $wp_styles->registered[$handle]->src = THEME_URI.'/cache/'.$css_cache;
                }

            }

        }


        public function dequeue_js()
        {
            global $wp_scripts;

            if (empty($wp_scripts->queue))
                return;
            $wp_scripts->all_deps($wp_scripts->queue);
            $min = new Minifier(array('echo'=>false));
            foreach ($wp_scripts->to_do as $key => $handle) {
                if($key == 'admin-bar')
                    continue;
                if ($this->isFileExcluded($wp_scripts->registered[$handle]->src))
                    continue;
                $script_path = $this->guessPath($wp_scripts->registered[$handle]->src);
            // Script didn't match any case (plugin, theme or wordpress locations)
                if ($script_path === false)
                    continue;
                $where = 'footer';
                if (empty($wp_scripts->registered[$handle]->extra) && empty($wp_scripts->registered[$handle]->args))
                    $where = 'header';
                if (empty($script_path) || !is_file($script_path))
                    continue;

                if(strpos('.min.',$wp_scripts->registered[$handle]->src) === false)
                {
                    $js_cache = md5($script_path).'.js';
                    if(!file_exists($this->cache_dir.'/'.$js_cache))
                    {
                        $min->minify($script_path,$this->cache_dir.'/'.$js_cache);
                    }
                    $wp_scripts->registered[$handle]->src = THEME_URI.'/cache/'.$js_cache;
                }

            }

        }



    }

    $assets = New Look_Assets();
    $assets->init();