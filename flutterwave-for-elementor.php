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

define( 'FLWELEMENTOR_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'FLWELEMENTOR_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
/**
 * Missing Flutterwave for Business notice
 */
function flutterwave_elementor_admin_notice_missing_main_plugin()	{
	$message = sprintf(
		esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'flutterwave-for-elementor' ),
		'<strong>' . esc_html__( 'Flutterwave for Elementor', 'flutterwave-for-elementor' ) . '</strong>',
		'<strong>' . esc_html__( 'Flutterwave for Business', 'flutterwave-for-elementor' ) . '</strong>'
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

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

	$widgets_manager->register( new \Flutterwave_Button_Widget() );
	$widgets_manager->register( new \Flutterwave_Form_Widget() );

}

function flutterwave_elementor_init()
{
	add_action( 'elementor/widgets/register', 'register_button_widget' );
}

/**
 * Get the list of active plugin...
 * ...then check if Flutterwave for Business is active, and register my widgets
 */
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins'));

if (in_array( 'flutterwave-for-business/flutterwave-for-business.php', $active_plugins ) ) {
	add_action( 'init', 'flutterwave_elementor_init' );
} else {
	add_action( 'admin_notices', 'flutterwave_elementor_admin_notice_missing_main_plugin');								
}