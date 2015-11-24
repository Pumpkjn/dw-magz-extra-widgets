<?php
/**
 * @package DW Magz Extra Widgets
 */
/*
Plugin Name: DW Magz Extra Widgets
Plugin URI: 
Description: 
Version: 1.0.0
Author: Design Wall
Author URI: 
License: GPLv2 or later
*/

if ( ! defined( 'DWMZ_DIR' ) ) {
	define( 'DWMZ_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'DWMZ_URI' ) ) {
	define( 'DWMZ_URI', plugin_dir_url( __FILE__ ) );
}

require_once DWMZ_DIR  . 'classes/dw_slider.php';
require_once DWMZ_DIR  . 'classes/dw_feature_content.php';
// require_once DWMZ_DIR  . 'classes/dw_category.php';

function dwmz_init() {
	add_image_size( 'dw-slider-style-1', 810, 400, true );
	add_image_size( 'dw-slider-style-2', 255, 132, true );;
}
add_action( 'init', 'dwmz_init' );

function dwmz_enqueue_scripts() {
	wp_enqueue_style( 'dwmz-slider-css', DWMZ_URI . '/assets/css/dwmz_slider.css' );
	wp_enqueue_style( 'dwmz-feature-content-css', DWMZ_URI . '/assets/css/dwmz_feature-content.css' );

	wp_enqueue_script( 'dwmz-script', DWMZ_URI . '/assets/js/script.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'dwmz_enqueue_scripts' );


function dwmz_admin_enqueue_scripts() {

	wp_enqueue_script( 'dwmz-admin-script', DWMZ_URI . '/assets/js/admin.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'dwmz_admin_enqueue_scripts' );

