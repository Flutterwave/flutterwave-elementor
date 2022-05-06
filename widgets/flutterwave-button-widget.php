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
	    private $f4b_options;
    	public $plans;
    	public $currencies_array;
        public function __construct($data = array(), $args = null)
        {
            parent::__construct($data, $args);
			$this->f4b_options = get_option( 'f4bflutterwave_options' );
        	$this->plans = [];
        	$this->get_plans();
        	$this->currencies_array = [ 
				'none' => "Select Currency", 
				'NGN' => esc_html__('NGN','flutterwave-for-elementor'),
				'GBP' => esc_html__('GBP','flutterwave-for-elementor'),
				'KES' => esc_html__('KES','flutterwave-for-elementor'),
				'XOR' => esc_html__('XOR','flutterwave-for-elementor'),
				'ZMW' => esc_html__('ZMW','flutterwave-for-elementor'),
				'GHS' => esc_html__('GHS','flutterwave-for-elementor'),
			];

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
            wp_enqueue_script( 'flutterwave-elementor-js', ELEMENTOR_FLUTTERWAVE . 'assets/js/flutterwave-elementor.js' , ['jquery'], true );
            wp_localize_script( 'flutterwave-elementor-js', 'flutterwave_elementor_data', [
                'apiUrl' => home_url( '/wp-json' ),
                'public_key' => $setting['public_key'],
                'success_redirect_url' => $setting['success_redirect_url'],
                'failed_redirect_url' => $setting['failed_redirect_url'],
            ]);
            return [ 'flutterwave-elementor-js' ];
// 			return [];
        }
    
        public function get_style_depends() {
//             wp_enqueue_style( 'flutterwave-elementor-css', ELEMENTOR_FLUTTERWAVE . 'assets/css/flutterwave-elementor.css', [], rand(), 'all' );
//             return [ 'flutterwave-elementor-css' ];
           return [];
        }
        
        protected function register_controls()
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
                    'options' => $this->currencies_array,
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
			
			//One-off or Recurring
        	$this->add_control(
				'payment_frequency',
            	[
                	'label' => esc_html__( 'Payment Frequency', 'flutterwave-for-elementor' ),
                	'type' => \Elementor\Controls_Manager::SELECT,
                	'default' => 'one-off',
                	'options' => [
                    	'one-off'  => esc_html__( 'One-Off', 'flutterwave-for-elementor' ),
                    	'recurring' => esc_html__( 'Recurring', 'flutterwave-for-elementor' ),
                	],
            	]
			);

            $this->add_control(
                'payment_plan',
                [
                    'label' => esc_html__( 'Payment Plan', 'flutterwave-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                	'default' => array_keys($this->plans['control_display'])[0],//first plan id
                	'options' => $this->plans['control_display'],
					'condition' => [
						'payment_frequency' => 'recurring'
					]
				
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
            $tx_ref = "WP_ELEMENTOR_" . uniqid() . "100";
            $currency = $settings['currency'];
            $payment_type = $settings['payment_type'];
            $payment_plan = $settings['payment_plan'];
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
        <?php if($settings['payment_frequency'] == 'recurring' && !empty($settings['payment_plan'])  ){ ?>
        <input type="hidden" name="plan_name"
            value="<?php echo $this->plans['control_display'][$settings['payment_plan']];?>" />
        <input type="hidden" name="payment_plan" value="<?php echo $settings['payment_plan'];?>" />
        <input type="hidden" name="amount"
            value="<?php echo $this->plans['control_display_amount'][$settings['payment_plan']];?>" />
        <input type="hidden" name="currency"
            value="<?php echo $this->plans['control_display_currency'][$settings['payment_plan']];?>" />
        <?php }else { ?>
        <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
        <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
        <?php } ?>
        <input type="hidden" id="flw-elementor-button-redirecturl" name="redirect_url"
            value="https://demoredirect.localhost.me/" />
        <button <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
            <?php echo $button_text; ?>
        </button>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    jQuery(document).ready(function($) {
        //check if the redirect input exists
        if ($("#flw-elementor-button-redirecturl").length) {
            $("#flw-elementor-button-redirecturl").attr(
                "value",
                f4b_data.apiUrl + "/flutterwave-for-business/v1/verifytransaction"
            );
        }
    });
});
</script>
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
	
	    protected function get_plans():void
    	{
        $token = $this->f4b_options['secret_key'];
        $flw_base_url = 
        $response = wp_remote_get("https://api.flutterwave.com/v3/payment-plans", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer " .$token
            ]
        ] );
        $plans = [];
        $plans_amount = [];
        $plans_currency = [];
        $response_code = wp_remote_retrieve_response_code( $response );

        if ( $response_code == 200 ) {
            $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
// 			echo "<pre>";
// 			print_r($response_body['data']);
// 			echo "</pre>";
            $result = $response_body['data'];
			if(isset($result) && $result){
				foreach ( $result as $plan ) {
                	$plans[$plan['id']] = $plan['name'];
                	$plans_amount[$plan['id']] = $plan['amount'];
                	$plans_currency[$plan['id']] = $plan['currency'];
            	}
				$collection = [
            		'control_display' => $plans,
            		'control_display_amount' => $plans_amount,
            		'control_display_currency' => $plans_currency
        		];
			}
            
        }

        $nocollection = [
            'control_display' => [
                'none' => 'No Plans connected to you Flutterwave Account.'
            ],
            'control_display_amount' => [
                'none' => 'No Plans connected to you Flutterwave Account.'
            ],
            'control_display_currency' => [
                'none' => 'No Plans connected to you Flutterwave Account.'
            ]
        ];

        $this->plans = $collection ?? $nocollection;
        return;
    	}
}