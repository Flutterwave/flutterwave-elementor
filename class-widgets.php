<?php
/**
 * Widgets class.
 *
 * @category   Class
 * @package    ElementorFlutterwave
 * @author     Flutterwave Developers <developers@flutterwavego.com>
 * @copyright  2022 Flutterwave Developers
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://developers.flutterwave.com)
 * @since      1.1.0
 * php version 7.4.0
 */

namespace ElementorFlutterwave;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 1.0.0
 */
class Widgets {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once 'widgets/flutterwave-button-widget.php';
        require_once 'widgets/flutterwave-form-widget.php';
	}

    public function flutterwave_add_elementor_category() {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'flutterwave-blocks',
            [
                'title' => esc_html__( 'Flutterwave Blocks', 'flutterwave-for-elementor' ),
            ]
        );
    }

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets() {
		// It's now safe to include Widgets files.
		$this->include_widgets_files();

		// Register the plugin widget classes.
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Flutterwave_Button_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Flutterwave_Form_Widget() );
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
        // Register new Category for the plugin.
        add_action( 'elementor/elements/categories_registered', [ $this, 'flutterwave_add_elementor_category' ] );
		// Register the widgets.
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}
}

// Instantiate the Widgets class.
Widgets::instance();