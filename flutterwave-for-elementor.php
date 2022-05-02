<?php
/**
* Plugin Name: Flutterwave for Elementor
* Description: The Official FLutterwave Payment Gateway for Elementor
* Plugin URI: https://flutterwave.com/ng
* Version: 1.0.0
* Author: Flutterwave Developers
* Author URI: https://developers.flutterwave.com
* Text Domain: flutterwave-for-elementor
*
* Elementor tested up to: 3.5.0
* Elementor Pro tested up to: 3.5.0
*/

if( !defined('ABSPATH') ) return exit;

add_action( 'elementor/elements/categories_registered', 'add_flutterwave_elementor_widget_categories' );

/**
 * Register Currency Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_button_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/flutterwave-button-widget.php' );
	require_once( __DIR__ . '/widgets/flutterwave-link-widget.php' );
	require_once( __DIR__ . '/widgets/flutterwave-form-widget.php' );

	$widgets_manager->register( new \Elementor_Currency_Widget() );

}
add_action( 'elementor/widgets/register', 'register_button_widget' );