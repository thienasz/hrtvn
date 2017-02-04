<?php

// Creating the widget
class Look_home_category_link_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
        // Base ID of your widget
            'home_category_link_widget',

            // Widget name will appear in UI
            __('Look - Category Link Widget - w p l o c k e r . c o m ', 'look'),

            // Widget description
            array( 'description' => __( 'Category link Widget', 'look' ), )
            );
        add_action('admin_enqueue_scripts', array($this, 'upload_scripts'));
    }


    public function widget( $args, $instance ) {
        ob_start();
        $file =  THEME_DIR . '/includes/widget/views/category_link.php';;
        require($file);
        echo ob_get_clean();

    }

    public function upload_scripts()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('look_upload_media_widget',MAIN_ASSETS_URI . '/js/upload-media.js', array('jquery'));
        wp_enqueue_style('thickbox');
    }

    public function form( $instance ) {
        $title = __('Widget Image', 'look');
        if(isset($instance['title']))
        {
            $title = $instance['title'];
        }
        $descriptions = '';
        if(isset($instance['descriptions']))
        {
            $descriptions = $instance['descriptions'];
        }
        $url = '';

        if(isset($instance['url']))
        {
            $url = $instance['url'];
        }

        $image = '';
        if(isset($instance['image']))
        {
            $image = $instance['image'];
        }
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_name( 'title' )); ?>"><?php _e( 'Title:','look' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_name( 'descriptions' )); ?>"><?php _e( 'Short Descriptions:','look' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'descriptions' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'descriptions' )); ?>" type="text" value="<?php echo esc_attr( $descriptions ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_name( 'url' )); ?>"><?php _e( 'Shop URL:','look' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'url' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'url' )); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_name( 'image' )); ?>"><?php _e( 'Image:','look' ); ?></label>
            <input name="<?php echo esc_attr($this->get_field_name( 'image' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'image' )); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $image ); ?>" />
            <input class="look_upload_image_button button button-primary" type="button" value="Upload Image" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;

    }
}
