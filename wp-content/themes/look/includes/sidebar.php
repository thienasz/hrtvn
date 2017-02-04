<?php
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => __('Look Home Widget', 'look'),
		'id' => 'feature-home-sidebar',
		'description' =>  __('Home Widget Area','look'),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="text-heading"><h2 class="module-title"><span>',
		'after_title' => '</span></h2></div>',
		));
	register_sidebar(array(
		'name' => __('Home Category Link Widget','look'),
		'id' => 'home-category-link-sidebar',
		'description' => __('Home Category Link Widget','look'),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
		));
	register_sidebar(array(
		'name' => __('Footer Sidebar','look'),
		'id' => 'footer-sidebar',
		'description' => __('Footer sidebar','look'),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
		));
	register_sidebar(array(
		'name' => __('Blog Top Sidebar','look'),
		'id' => 'blog-top-sidebar',
		'description' => __('Blog Top sidebar','look'),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		));
	register_sidebar(array(
		'name' => __('Blog Right Sidebar','look'),
		'id' => 'blog-right-sidebar',
		'description' => __('Blog Right Sidebar','look'),
		'before_widget' => '<div class="side-bar-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
		));
	register_sidebar(array(
		'name' => __('Shop Left Sidebar','look'),
		'id' => 'woocommerce-left-sidebar',
		'description' => __('Shop Left Sidebar','look'),
		'before_widget' => '<div class="side-bar-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
		));
	register_sidebar(array(
		'name' => __('Shop Right Sidebar','look'),
		'id' => 'woocommerce-right-sidebar',
		'description' => __('Shop Right Sidebar','look'),
		'before_widget' => '<div class="side-bar-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
		));
	register_sidebar(array(
		'name' => __('Product Page Left Sidebar','look'),
		'id' => 'woocommerce-product-left-sidebar',
		'description' => __('Product Page Left Sidebar','look'),
		'before_widget' => '<div class="side-bar-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
		));
	register_sidebar(array(
		'name' => __('Product Page Right Sidebar','look'),
		'id' => 'woocommerce-product-right-sidebar',
		'description' => __('Product Page Right Sidebar','look'),
		'before_widget' => '<div class="side-bar-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
		));
}