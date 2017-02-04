<?php echo (string)$args['before_widget'];?>
<?php
$title = apply_filters( 'widget_title', $instance['title'] );
?>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
    <div class="shop-link widget-link">
        <img src="<?php echo esc_url($instance['image']); ?>" alt="<?php echo esc_attr($title); ?>" />
        <a href="<?php echo esc_url($instance['url']); ?>">
        <div class="gradient-background">
            <div class="intro-text animated fadeInUp">
                <?php echo (string)$args['before_title'] ; ?><?php echo sanitize_text_field($title); ?><?php echo (string)$args['after_title'] ; ?>
                <p><?php echo balanceTags($instance['descriptions']); ?></p>
            </div>
        </div>
        </a>
    </div>
</div>
<?php echo (string)$args['after_widget'];?>