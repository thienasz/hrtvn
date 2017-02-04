<?php
class Look_Sample{
    private $filename;
    public $tables = array();
    public function __construct()
    {
        $this->filename = 'look.json';
        $this->tables = array('options','postmeta','posts','terms','term_relationships','term_taxonomy','woocommerce_attribute_taxonomies','woocommerce_termmeta');
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action("wp_ajax_look_import_demo", array($this,"import"));
        add_action("wp_ajax_nopriv_look_import_demo", array($this,"import"));
    }

    public function getDemoFilePath()
    {
        return THEME_DIR.'/demo-files/'.$this->filename;
    }

    public function admin_menu()
    {
        add_theme_page( 'Import Look Sample Data','Look Sample Data','manage_options','look_sample', array( $this, 'import_page' ) );
    }

    public function import_page(){
        if(isset($_REQUEST['export']) && $_REQUEST['export'] == 1)
        {
            $this->export();
            echo "<div style='padding: 5px;'>Your export file url: ".THEME_URI.'/cache/'.$this->filename.'</div>';
        }else{
            $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
            ob_start();
            $file =  THEME_DIR . '/modules/import/view/import.php';;
            require($file);
            echo ob_get_clean();

            if( 'demo-data' == $action && check_admin_referer('look-demo-code' , 'demononce')){
                if($_POST && isset($_POST['layout']))
                {
                    echo '<p style="color:green">Start import! Please wait...</p>';
                    $this->import($_POST['layout']);
                    echo '<p style="color:green">Done!</p>';
                }
            }
        }
    }

    public function import()
    {
        global $wpdb,$table_prefix,$wp_filesystem;
        $view = $_REQUEST['layout'];
        if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

        require_once ABSPATH . 'wp-admin/includes/import.php';

        if ( !class_exists( 'WP_Importer' ) ) {

            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

            if ( file_exists( $class_wp_importer ) ){

                require_once($class_wp_importer);

            }

        }

        if ( !class_exists( 'WP_Import' ) ) {

            $class_wp_import = THEME_DIR . '/modules/import/wordpress-importer.php';

            if ( file_exists( $class_wp_import ) )
                require_once($class_wp_import);
            else
                $importer_error = true;

        }

        $content = file_get_contents(THEME_DIR.'/demo-files/'.$view.'/'.$this->filename);
        $data = (array)json_decode($content);

        foreach($data as $key => $value)
        {
            $table = $table_prefix.$key;
            $rows = $value;
            if($key == 'options')
            {
                
                $wpdb->get_results( 'DELETE FROM '.$table.' WHERE option_name NOT IN ("siteurl","fileupload_url","home","'.$table_prefix.'user_roles")');
            }else{
                $wpdb->get_results( 'TRUNCATE TABLE '.$table);
            }
            foreach($rows as $row)
            {

                $row = (array)$row;
                if($key == 'options')
                {
                    if($row['option_name'] != 'wp_user_roles' && $row['option_name'] != 'siteurl' && $row['option_name'] != 'fileupload_url' && $row['option_name'] != 'home')
                    {
                        $wpdb->insert($table,$row);
                    }
                }else{
                    $wpdb->insert($table,$row);
                }
            }
        }

        $args = array(
            'post_type' => 'attachment',
            'order' => 'ASC',
            'posts_per_page' => 1000
        );

        $posts = get_posts( $args );
        $wp_import = new WP_Import();
        foreach ($posts as $post)
        {
            $post = (array)$post;
            $post['upload_date'] = date('Y-m-d');
            $url = THEME_URI.'/demo-files/img.png';
            $upload = $wp_import->fetch_remote_file( $url, (array)$post );

            update_attached_file( $post['ID'], $upload['file'] );
            wp_update_attachment_metadata( $post['ID'], wp_generate_attachment_metadata( $post['ID'], $upload['file'] ) );

        }
        wp_reset_postdata();


        $to_url = get_site_url();
        $from_url = 'http://magicaltheme.com/look/look-full';
        $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='_menu_item_url'", $from_url, $to_url) );


        $from_url = 'http://magicaltheme.com/look/look-left';
        $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='_menu_item_url'", $from_url, $to_url) );


        $from_url = 'http://magicaltheme.com/look/look-fix';
        $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='_menu_item_url'", $from_url, $to_url) );


    }

    public function export()
    {
        global $wpdb,$table_prefix;
        global $wp_filesystem;

        $result = array();
        if(is_writable(THEME_DIR.'/cache'))
        {
            foreach($this->tables  as $table)
            {
                if($table == 'postmeta' )
                {
                    $sql = "SELECT postmeta.* FROM ".$table_prefix.$table." postmeta LEFT JOIN ".$table_prefix."posts post ON post.ID = postmeta.post_id WHERE post.post_status = 'publish' OR post.post_status = 'inherit' ";
                    $result[$table] = $wpdb->get_results( $sql );
                }elseif($table == 'posts' )
                {
                    $result[$table] = $wpdb->get_results( "SELECT * FROM ".$table_prefix.$table." post  WHERE post.post_status = 'publish' OR post.post_status = 'inherit'" );
                }elseif($table == 'options'){
                    $sql = "SELECT * FROM ".$table_prefix.$table;
                    $sql .= " WHERE ".$table_prefix.$table.".option_id NOT IN (SELECT option_id FROM ".$table_prefix.$table." WHERE option_name LIKE '%_transient_%' )";
                    $result[$table] = $wpdb->get_results( $sql );
                }else{
                    $result[$table] = $wpdb->get_results( "SELECT * FROM ".$table_prefix.$table );
                }
            }

            file_put_contents(
                THEME_DIR.'/cache/'.$this->filename,
                json_encode($result)
            );
            return true;
        }
        return false;
    }


}

