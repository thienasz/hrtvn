<?php

// Creating the widget
class Look_link_block_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
        // Base ID of your widget
            'link_block_widget',

            // Widget name will appear in UI
            __('Look - Footer Menu Block Widget', 'look'),

            // Widget description
            array( 'description' => __( 'Footer Menu Block Widget', 'look' ), )
            );
    }


    public function widget( $args, $instance ) {
        ob_start();
        $file =  THEME_DIR . '/includes/widget/views/link_block.php';;
        require($file);
        echo ob_get_clean();

    }


    public function form( $instance ) {

        $menus = wp_get_nav_menus();
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'look' );
        }
        $menu = 0;
        if ( isset( $instance[ 'menu' ] ) ) {
            $menu = $instance[ 'menu' ];
        }

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:','look' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'menu' )); ?>"><?php _e( 'Menu:','look' ); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name( 'menu' )); ?>">
                <option value="0"><?php echo __( 'Choose menu', 'look' ); ?></option>
                <?php foreach($menus as $m): ?>
                    <option value="<?php echo esc_attr($m->term_id); ?>" <?php if($menu == $m->term_id): ?> selected="selected" <?php endif; ?> ><?php echo sanitize_text_field($m->name); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $updated_instance = $new_instance;
        return $updated_instance;
    }
}

