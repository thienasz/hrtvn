<?php if((int)$instance['menu'] > 0): ?>
	<?php
	$title = apply_filters( 'widget_title', $instance['title'] );
	?>
	<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
		<h3 class="box-title"><?php echo sanitize_text_field($title); ?></h3>
        <?php if(isset($instance['menu']) && is_array(wp_get_nav_menu_items($instance['menu']))): ?>
		<ul>
			<?php foreach(wp_get_nav_menu_items($instance['menu']) as $item): ?>
				<li><a href="<?php echo esc_url($item->url); ?>"><?php echo sanitize_text_field($item->title); ?></a></li>
			<?php endforeach; ?>
		</ul>
        <?php endif; ?>
	</div>
<?php endif; ?>