<?php
/**
 * Flutterwave Button class.
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

namespace ElementorFlutterwave\Widgets;
use Elementor\Widget_Base;

if( !defined('ABSPATH') ) return exit;

class Flutterwave_Button_Widget extends Widget_Base
{       
        public function __construct($data = array(), $args = null)
        {
            parent::__construct($data, $args);

            // add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);
        }
        public function get_name()
        {
            return 'flutterwave-button-widget';
        }
        
        public function get_title()
        {
            return __( 'Flutterwave Button', 'flutterwave-for-elementor' );
        }
        
        public function get_icon()
        {
            return 'eicon-button';
        }
        
        public function get_categories()
        {
            return [ 'flutterwave-blocks' ];
        }

        public function get_script_depends() {
            //get the settings of flutterwave for Business Plugin
            $setting = get_option( 'f4bflutterwave_options', ['public_key' => 'FLWSECK_TEST-SANDBOXDEMOKEY-X', 'success_redirect_url' => '', 'failed_redirect_url' => ''] );
            wp_enqueue_script( 'flutterwave-elementor-js', FLWELEMENTOR_PLUGIN_URL . 'assets/js/flutterwave-elementor.js' , ['jquery'], true );
            wp_localize_script( 'flutterwave-elementor-js', 'flutterwave_elementor_data', [
                'apiUrl' => home_url( '/wp-json' ),
                'public_key' => $setting['public_key'],
                'success_redirect_url' => $setting['success_redirect_url'],
                'failed_redirect_url' => $setting['failed_redirect_url'],
            ]);
            return [ 'flutterwave-elementor-js' ];
        }
    
        public function get_style_depends() {
            wp_enqueue_style( 'flutterwave-elementor-css', FLWELEMENTOR_PLUGIN_URL . 'assets/css/flutterwave-elementor.css', [], rand(), 'all' );
            return [ 'flutterwave-elementor-css' ];
        }
        
        protected function _register_controls()
        {
            $this->start_controls_section(
                'content_section',
                [
                    'label' => esc_html__( 'Content', 'flutterwave-for-elementor' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'button_text',
                [
                    'label' => __( 'Text', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( 'Make Payment', 'flutterwave-for-elementor' ),
                    'placeholder' => __( 'Pay Now', 'flutterwave-for-elementor' ),
                ]
            );

            $this->add_control(
                'button_size',
                [
                    'label' => __( 'Size', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '16px',
                    'options' => [
                        '10px' => __( 'Extra Small', 'flutterwave-for-elementor' ),
                        '12px' => __( 'Small', 'flutterwave-for-elementor' ),
                        '16px' => __( 'Medium', 'flutterwave-for-elementor' ),
                        '20px' => __( 'Large', 'flutterwave-for-elementor' ),
                        '24px' => __( 'Extra Large', 'flutterwave-for-elementor' ),
                    ],
                ]
            );

            $this->add_control(
                'amount',
                [
                    'label' => esc_html__( 'Amount', 'plugin-name' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 5,
                    'max' => 1000000,
                    'step' => 5,
                    'default' => 100,
                ]
            );

            $this->add_control(
                'currency',
                [
                    'label' => esc_html__( 'Currency', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'NGN',
                    'options' => [
                        'GHS'  => esc_html__( 'GHS', 'flutterwave-for-elementor' ),
                        'KES' => esc_html__( 'KES', 'flutterwave-for-elementor' ),
                        'USD' => esc_html__( 'USD', 'flutterwave-for-elementor' ),
                        'NGN' => esc_html__( 'NGN', 'flutterwave-for-elementor' ),
                        'NGN' => esc_html__( 'Default', 'flutterwave-for-elementor' ),
                    ],
                ]
            );

            $this->add_control(
                'payment_type',
                [
                    'label' => esc_html__( 'Payment Type', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'card'  => esc_html__( 'Card', 'flutterwave-for-elementor' ),
                        'momo'  => esc_html__( 'Mobile Money', 'flutterwave-for-elementor' ),
                        'ussd'  => esc_html__( 'USSD', 'flutterwave-for-elementor' ),
                        'account' => esc_html__( 'Account', 'flutterwave-for-elementor' ),
                        'default' => esc_html__( 'All', 'flutterwave-for-elementor' ),
                    ],
                ]
            );

            $this->add_control(
                'payment_plan',
                [
                    'label' => esc_html__( 'Payment Plan', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'monthly'  => esc_html__( 'Monthly', 'flutterwave-for-elementor' ),
                        'quarterly' => esc_html__( 'Quarterly', 'flutterwave-for-elementor' ),
                        'yearly' => esc_html__( 'Yearly', 'flutterwave-for-elementor' ),
                        'default' => esc_html__( 'None', 'flutterwave-for-elementor' ),
                    ],
                ]
            );
    
            $this->add_control(
                'payment_plan_amount',
                [
                    'label' => esc_html__( 'Payment Plan Amount', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 5,
                    'max' => 100000,
                    'step' => 5,
                    'default' => 0,
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'section_title',
                [
                    'label' => esc_html__( 'Title', 'elementor' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'button_color',
                [
                    'label' => esc_html__( 'Button Color', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} #f4b-elementor-paynow-button' => 'background-color: {{VALUE}}',
                    ],
                    'default' => '#F5A623'
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => esc_html__( 'Text Color', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} #f4b-elementor-paynow-button' => 'color: {{VALUE}};',
                    ],
                    'default' => '#FFFFFF'
                ]
            );
    
            $this->end_controls_section();
        }

        /**
	     * Render Flutterwave widget output on the frontend.
	     *
	     * Written in PHP and used to generate the final HTML.
	     *
	     * @since 1.0.0
	     * @access protected
	     */
	    protected function render() {
            $settings = $this->get_settings_for_display();
            $amount = $settings['amount'];
            $tx_ref = "WP_ELEMENTOR_" + uniqid() + "100";
            $currency = $settings['currency'];
            $payment_type = $settings['payment_type'];
            $payment_plan = $settings['payment_plan'];
            $payment_plan_amount = $settings['payment_plan_amount'];
            $button_text = $settings['button_text'];
            $button_size = $settings['button_size'];
            $button_color = $settings['button_color'];
            $title_color = $settings['title_color'];

            $this->add_render_attribute(
                'button_text',
                [
                    'id' => 'f4b-elementor-paynow-button',
                    'class' => ['flutterwave-elemetor-button', 'elementor-f4b-title'],
                    'style' => '
                    font-size: ' . $button_size . ';
                    line-height: 1.25;
                    padding: 1.1em 1.44em;
                    text-transform:uppercase;
                    border:none;
                    borderRadius:0.3em;'
                ]
            );

            ?>
<?php $current_user = wp_get_current_user(); ?>
<div class="flutterwave-elementor-paynow-button-container">
    <form id="flutterwave-elementor-button-form" method="POST" action="https://checkout.flutterwave.com/v3/hosted/pay">
        <input type="hidden" name="public_key" value="FLWPUBK_TEST-SANDBOXDEMOKEY-X" />
        <input type="hidden" id="flw-elementor-cust-email" name="customer[email]"
            value="<?php echo $current_user->user_email; ?>" />
        <input type="hidden" id="flw-elementor-cust-name" name="customer[name]"
            value="<?php echo $current_user->user_firstname. ' '.$current_user->user_lastname; ?>" />
        <input type="hidden" name="tx_ref" value="<?php echo $tx_ref; ?>" />
        <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
        <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
        <input type="hidden" id="flw-elementor-redirecturl" name="redirect_url"
            value="https://demoredirect.localhost.me/" />
        <button <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
            <?php echo $button_text; ?>
        </button>
    </form>
</div>
<?php
        }
	
		protected function content_template()
		{
		?>
<div class="flutterwave-elementor-paynow-button-container">
    <button id="f4b-elementor-paynow-button">
        {{{ settings.button_text }}}
    </button>
</div>
<?php
		}
}