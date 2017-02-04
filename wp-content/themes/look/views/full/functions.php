<?php

function  look_fix_scripts() {
	wp_enqueue_style( 'look_look-style', ASSETS_URI . '/css/custom.css' );
	wp_enqueue_style( 'look_special-layout', ASSETS_URI . '/css/layout.css' );
	wp_enqueue_style( 'look_responsive', ASSETS_URI . '/css/responsive.css' );
	wp_enqueue_script( 'look_custom-fix', ASSETS_URI . '/js/custom.js', array(),VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'look_fix_scripts' );