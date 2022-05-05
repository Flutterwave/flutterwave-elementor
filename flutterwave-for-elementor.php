<?php
/**
* Plugin Name: Flutterwave for Elementor
* Description: The Official FLutterwave Payment Gateway for Elementor
* Plugin URI: https://flutterwave.com/ng
* Version: 1.1.0
* Author: Flutterwave Developers
* Author URI: https://developers.flutterwave.com
* Text Domain: flutterwave-for-elementor
*
* Elementor tested up to: 3.5.0
* Elementor Pro tested up to: 3.5.0
*/

define( 'ELEMENTOR_FLUTTERWAVE', __FILE__ );

/**
 * Include the Elementor_Flutterwave class.
 */
require plugin_dir_path( ELEMENTOR_FLUTTERWAVE ) . 'class-elementor-flutterwave.php';