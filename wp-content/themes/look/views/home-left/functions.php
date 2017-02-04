<?php

function  look_left_scripts() {
	wp_enqueue_style( 'look_style', ASSETS_URI . '/css/custom.css' );
	wp_enqueue_style( 'look_special-layout', ASSETS_URI . '/css/layout.css' );
	wp_enqueue_style( 'look_responsive', ASSETS_URI . '/css/responsive.css' );
	wp_enqueue_script( 'look_custom-left', ASSETS_URI . '/js/custom.js', array(),VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'look_left_scripts' );