<?php

// Creating the widget
class Look_product_tab_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
        // Base ID of your widget
            'look_product_tab_widget',

            // Widget name will appear in UI
            __('Look - Products Tab Widget', 'look'),

            // Widget description
            array( 'description' => __( 'Look Products Tab Widget', 'look' ), )
            );
    }


    public function widget( $args, $instance ) {
        $types = json_decode($instance['type']);
        $limit = (int)$instance['limit'];
        if(!empty($types)) {
            $tabs = array();
            foreach($types as $type)
            {
                switch($type)
                {
                    case 'feature':
                    $tabs[] = array(
                        'title' => __('Featured','look'),
                        'shortcode' => 'featured_products'
                        );
                    break;
                    case 'bestseller':
                    $tabs[] = array(
                        'title' => __('Best Seller','look'),
                        'shortcode' => 'best_selling_products'
                        );
                    break;
                    case 'newarrived':
                    if(class_exists('Mg_NewArrive'))
                    {
                        $tabs[] = array(
                            'title' => esc_html__('New arrivals','look'),
                            'shortcode' => 'new_arrived_product'
                            );
                    }else{
                        $tabs[] = array(
                            'title' => esc_html__('New arrivals','look'),
                            'shortcode' => 'recent_products'
                            );
                    }
                    break;
                    case 'recent':
                    $tabs[] = array(
                        'title' => esc_html__('Recent Product','look'),
                        'shortcode' => 'recent_products'
                        );
                    break;
                    case 'sale':
                    $tabs[] = array(
                        'title' => __('Sale','look'),
                        'shortcode' => 'sale_products'
                        );
                    break;
                }
            }
            ?>
            <div class="product-tab">
                <!-- Nav tabs -->
                <div class="tablist">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach($tabs as $key => $tab): ?>
                            <li role="<?php echo $tab['shortcode']; ?>" class="<?php if($key == 0): ?>active  <?php endif; ?>">
                                <a href="#<?php echo $tab['shortcode']; ?>" aria-controls="<?php echo $tab['shortcode']; ?>" role="tab" data-toggle="tab"><?php echo $tab['title']; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- Tab panes -->
                <div class="tab-content">
                    <?php foreach($tabs as $key => $tab): ?>
                        <div role="tabpanel" class="tab-pane <?php echo $tab['shortcode']; ?> fade in <?php if($key == 0): ?>active  <?php endif; ?>" id="<?php echo $tab['shortcode']; ?>">
                            <p>
                                <?php
                                $short_code = '['.$tab['shortcode'].' per_page="'.$limit.'" ';
                                if(isset($tab['orderby']))
                                {
                                    $short_code .= ' orderby="'.$tab['orderby'].'" ';
                                }
                                if(isset($tab['order']))
                                {
                                    $short_code .= ' order="'.$tab['order'].'" ';
                                }
                                $short_code .= ']';
                                echo do_shortcode($short_code);
                                ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        }
    }


    public function form( $instance ) {


        if ( isset( $instance[ 'limit' ] ) ) {
            $limit = $instance[ 'limit' ];
        }
        else {
            $limit = 6;
        }
        $type = array();
        if ( isset( $instance[ 'type' ] )) {
            $type = (array)json_decode($instance[ 'type' ]);

        }
        if(!isset($instance['orderby']))
        {
            $instance['orderby'] = 'rand';
        }
        if(!isset($instance['order']))
        {
            $instance['order'] = 'esc';
        }
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Type:','look' ); ?></label>
            <p><input type="checkbox" <?php echo in_array('feature',$type) ? 'checked':''; ?>  name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>[]"  value="feature"/><?php esc_html_e('Feature Products','look')?></p>
            <p><input type="checkbox" <?php echo in_array('bestseller',$type) ? 'checked':''; ?>  name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>[]" value="bestseller"/><?php esc_html_e('Best Seller Products','look')?></p>
            <?php if(class_exists('Mg_NewArrive')): ?>
                <p><input type="checkbox" <?php echo in_array('newarrived',$type) ? 'checked':''; ?>  name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>[]"  value="newarrived"/><?php esc_html_e('New Arrivals Products','look')?></p>
            <?php endif; ?>
            <p><input type="checkbox" <?php echo in_array('sale',$type) ? 'checked':''; ?>  name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>[]"  value="sale"/><?php esc_html_e('On Sales Products','look')?></p>
            <p><input type="checkbox" <?php echo in_array('recent',$type) ? 'checked':''; ?>  name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>[]"  value="recent"/><?php esc_html_e('Recent Products','look')?></p>
        </p>
        <p>
            <label for="widget-woocommerce_products-2-orderby"><?php esc_html_e( 'Order by','look' ); ?></label>
            <select class="widefat" id="widget-woocommerce_products-2-orderby" name="<?php echo esc_attr($this->get_field_name( 'orderby' )); ?>">
                <option <?php echo selected($instance['orderby'],'date'); ?> value="date"><?php esc_html_e( 'Date','look' ); ?></option>
                <option <?php echo selected($instance['orderby'],'price'); ?> value="price"><?php esc_html_e( 'Price','look' ); ?></option>
                <option <?php echo selected($instance['orderby'],'rand'); ?> value="rand"><?php esc_html_e( 'Random','look' ); ?></option>
                <option <?php echo selected($instance['orderby'],'sales'); ?> value="sales"><?php esc_html_e( 'Sales','look' ); ?></option>
            </select>
        </p>
        <p>
            <label for="widget-woocommerce_products-2-order"><?php esc_html_e( 'Order','look' ); ?></label>
            <select class="widefat" id="widget-woocommerce_products-2-order" name="<?php echo esc_attr($this->get_field_name( 'order' )); ?>">
                <option <?php echo selected($instance['order'],'asc'); ?> value="asc">ASC</option>
                <option <?php echo selected($instance['order'],'desc'); ?> value="desc">DESC</option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Limit:','look' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'Limit' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'limit' )); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? (int)$new_instance['limit']  : '0';
        $instance['type'] = ( ! empty( $new_instance['type'] ) ) ?  json_encode($new_instance['type'])  : json_encode(array());
        $instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? esc_attr( $new_instance['orderby'] )  : 'rand';
        $instance['order'] = ( ! empty( $new_instance['order'] ) ) ? esc_attr( $new_instance['order'] )  : 'esc';

        return $instance;
    }
}

