<?php
/**
 * Flutterwave Form class.
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

class Flutterwave_Form_Widget extends Widget_Base
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
			"none" => "Select Currency", 
			"NGN" => "NGN",
			"USD" => "USD",
			"GBP" => "GBP", 
			"EUR" => "EUR", 
			"AUD" => "AUD", 
			"CAD" => "CAD",
			"CNY" => "CNY", 
			"JPY" => "JPY", 
			"KRW" => "KRW", 
			"MXN" => "MXN", 
			"NZD" => "NZD", 
			"RUB" => "RUB", 
			"SGD" => "SGD", 
			"THB" => "THB", 
			"ZAR" => "ZAR",
		];
		wp_register_style( 'flw-elementor-form', plugins_url( '/assets/css/flutterwave-elementor.css', ELEMENTOR_FLUTTERWAVE ), array(), '1.0.0' );
    }

    public function get_name()
    {
        return 'flutterwave-form-widget';
    }
    
    public function get_title()
    {
        return __( 'Flutterwave Form', 'flutterwave-for-elementor' );
    }
    
    public function get_icon()
    {
        return 'eicon-form-horizontal';
    }
	
	public function get_custom_help_url() {
		return 'https://developers.flutterwave.com';
	}
    
    public function get_categories()
    {
        return [ 'flutterwave-blocks' ];
    }
	
	public function get_keywords() {
		return [ 'fintech', 'flutterwave', 'Payment', 'International', 'Funds', 'Subscriptions', 'Payout' ];
	}

//     public function get_script_depends() {
//         //get the settings of flutterwave for Business Plugin
//         $setting = get_option( 'f4bflutterwave_options', ['public_key' => 'FLWSECK_TEST-SANDBOXDEMOKEY-X', 'success_redirect_url' => '', 'failed_redirect_url' => ''] );
//         wp_enqueue_script( 'flutterwave-elementor-js', FLWELEMENTOR_PLUGIN_URL . 'assets/js/flutterwave-elementor.js' , ['jquery'], true );
//         wp_localize_script( 'flutterwave-elementor-js', 'flutterwave_elementor_data', [
//             'apiUrl' => home_url( '/wp-json' ),
//             'public_key' => $setting['public_key'],
//             'success_redirect_url' => $setting['success_redirect_url'],
//             'failed_redirect_url' => $setting['failed_redirect_url'],
//         ]);
//         return [ 'flutterwave-elementor-js' ];
//     }
    
    protected function register_controls()
    {
        $this->start_controls_section(
            'form_section',
            [
                'label' => esc_html__( 'Form', 'flutterwave-for-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => __( 'Text', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Ticket Payment Form', 'flutterwave-for-elementor' ),
                'placeholder' => __( 'Pay Now', 'flutterwave-for-elementor' ),
            ]
        );

        $this->add_control(
			'form_description',
			[
				'label' => esc_html__( 'Description', 'flutterwave-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => esc_html__( 'This form is use to sell tickets online', 'flutterwave-for-elementor' ),
				'placeholder' => esc_html__( 'Type your description here', 'flutterwave-for-elementor' ),
			]
		);

        $this->add_control(
            'amount',
            [
                'label' => esc_html__( 'Amount', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 1000000,
                'step' => 5,
                'default' => 100,
				'condition' => [
					'payment_frequency' => 'one-off'
				]
				
            ]
        );
		
		$this->add_control(
			'currency_picker_enabled',
			[
				'label' => esc_html__( 'Show Amount field', 'flutterwave-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'flutterwave-for-elementor' ),
				'label_off' => esc_html__( 'No', 'flutterwave-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
				
		//currenct list for one-off
		$this->add_control(
            'currency',
            [
                'label' => esc_html__( 'Currency', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'NGN',
                'options' => $this->currencies_array,
				'condition' => [
					'payment_frequency' => 'one-off',
					'currency_picker_enabled!' => 'yes'
				]
            ]
        );

		//currenct list for one-off
		$this->add_control(
			'currency_list',
			[
				'label' => esc_html__( 'Currency List', 'flutterwave-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => $this->currencies_array,
			]
		);
			
		$this->add_control(
			'plans_list',
			[
				'label' => esc_html__( 'Plan List', 'flutterwave-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => $this->plans,
			]
		);
			
			
		//methods to be displayed on the modal
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
		
		//Select a payment plan
        $this->add_control(
            'payment_plan',
            [
                'label' => esc_html__( 'Payment Plan', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
					'default' => esc_html__( 'None', 'flutterwave-for-elementor' ),
                    'monthly'  => esc_html__( 'Monthly', 'flutterwave-for-elementor' ),
                    'quarterly' => esc_html__( 'Quarterly', 'flutterwave-for-elementor' ),
                    'yearly' => esc_html__( 'Yearly', 'flutterwave-for-elementor' ),
                    'saved' => esc_html__( 'Saved Payment Plan ', 'flutterwave-for-elementor' ),
                ],
                'condition' => [
                    'payment_frequency' => 'recurring',
                ],
            ]
        );
		
		$this->add_control(
			'plan_picker_enabled',
			[
				'label' => esc_html__( 'Show Payment Plans', 'flutterwave-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'flutterwave-for-elementor' ),
				'label_off' => esc_html__( 'No', 'flutterwave-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
                    'payment_frequency' => 'recurring',
                ],
			]
		);

        //show plans in the select box
        $this->add_control(
            'saved_plan',
            [
                'label' => esc_html__( 'Saved Plan', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => array_keys($this->plans['control_display'])[0],//first plan id
                'options' => $this->plans['control_display'],
                'condition' => [
					'payment_plan' => 'saved',
                    'payment_frequency' => 'recurring',
                ],
            ]
        );
		
        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_form_style',
            [
                'label' => esc_html__( 'Form Settings', 'flutterwave-for-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_color',
            [
                'label' => esc_html__( 'Form Color', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f2f2f2',
                'selectors' => [
                    '{{WRAPPER}} #flutterwave-elementor-form-form' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
			'form_padding',
            [
                'label' => __( 'Padding', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #flutterwave-elementor-form-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}					{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);
		
		$this->add_control(
			'form_margin',
            [
                'label' => __( 'Margin', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #flutterwave-elementor-form-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 									{{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

        $this->add_control(
            'form_radius',
            [
                'label' => esc_html__( 'Form Radius', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #flutterwave-elementor-form-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 						{{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'form_border_style',
            [
                'label' => esc_html__( 'Border Style', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'solid'  => esc_html__( 'Solid', 'flutterwave-for-elementor' ),
                    'dashed' => esc_html__( 'Dashed', 'flutterwave-for-elementor' ),
                    'dotted' => esc_html__( 'Dotted', 'flutterwave-for-elementor' ),
                    'double' => esc_html__( 'Double', 'flutterwave-for-elementor' ),
                    'none' => esc_html__( 'None', 'flutterwave-for-elementor' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} #flutterwave-elementor-form-form' => 'border-style: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
			'input_padding',
			[
				'label' => esc_html__( 'Input Margin', 'flutterwave-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .flw-elementor-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 						{{LEFT}}{{UNIT}};',
				],
				'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 27,
						'unit' => 'px',
						'isLinked' => false,
				]
			]
		);

        $this->add_control(
            'input_border',
            [
                'label' => esc_html__( 'Input Border', 'flutterwave-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'solid'  => esc_html__( 'Solid', 'flutterwave-for-elementor' ),
                    'dashed' => esc_html__( 'Dashed', 'flutterwave-for-elementor' ),
                    'dotted' => esc_html__( 'Dotted', 'flutterwave-for-elementor' ),
                    'double' => esc_html__( 'Double', 'flutterwave-for-elementor' ),
                    'none' => esc_html__( 'None', 'flutterwave-for-elementor' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .flw-elementor-input' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'Button', 'flutterwave-for-elementor' ),
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

        $this->end_controls_section();
		

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button Style', 'flutterwave-for-elementor' ),
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
		$form_title = $settings['title_text'];
        $amount = $settings['amount'];
        $tx_ref = "WP_ELEMENTOR_".uniqid()."100";
        $currency = $settings['currency'];
        $payment_type = $settings['payment_type'];
        $payment_plan = $settings['payment_plan'];
		//create a new plan and return the id
// 		$payment_plan_amount = $this->plans['control_display_amount'][$settings['pa']];
		if($payment_plan == 'saved' && isset($settings['saved_plan']) && empty($settings['saved_plan'])){
			$payment_plan_amount = $this->plans['control_display_amount'][$settings['saved_plan']];
			$currency = $this->plans['control_display_currency'][$settings['saved_plan']];
		}
        
        $button_text = $settings['button_text'];
        $button_size = $settings['button_size'];
        $button_color = $settings['button_color'];
        $title_color = $settings['title_color'];
        $currency_picker_enabled = $settings['currency_picker_enabled'];
		$plan_picker_enabled = $settings['plan_picker_enabled'];
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
		
		$this->add_render_attribute(
			'form_center',
			[
				'style' => '
					color: black;
					padding: 30px;
					padding-left: auto;
					padding-right: auto;
					filter: drop-shadow(0px 1.44402px 10.8301px rgba(0, 0, 0, 0.1));
					font-family: Inter;
					position: relative;
					justify-content: center;
					width: 50%;
					margin: 5px auto;
					display: flex;
					flex-direction:column;
					font-weight: 300;
					background-color: '. $settings['form_color'].'
				'
			]
		);
		
		$this->add_render_attribute(
			'input_field',
			[
				'style' => '
					margin-bottom: 1em;
					width: 100%;
					height: 55px;
				'
			]
        );
		
        ?>
<?php $current_user = wp_get_current_user(); ?>
<div class="flutterwave-elementor-paynow-form-container">
    <form id="flutterwave-elementor-form-form" method="POST" action="https://checkout.flutterwave.com/v3/hosted/pay"
        <?php echo $this->get_render_attribute_string( 'form_center' ); ?>>
        <h3 style="text-align:center">
            <?php echo $form_title; ?>
        </h3>
        <input type="hidden" name="public_key" value="FLWPUBK_TEST-SANDBOXDEMOKEY-X" />
        <input id="flw-elementor-cust-email" class="" name="customer[email]"
            value="<?php echo $current_user->user_email; ?>"
            <?php echo $this->get_render_attribute_string( 'input_field' ); ?> placeholder="Email" />
        <input id="flw-elementor-cust-name" name="customer[name]"
            <?php echo $this->get_render_attribute_string( 'input_field' ); ?> placeholder="Full Name" />
        <input type="hidden" name="tx_ref" value="<?php echo $tx_ref; ?>" />
        <!-- 	currency and amount Options	 -->
        <?php if($currency_picker_enabled === 'yes'){ ?>
        <select name="currency" <?php echo $this->get_render_attribute_string( 'input_field' ); ?>>
            <?php foreach ($this->currencies_array as $key => $value) { ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php }?>
        </select>
        <input name="amount" value="<?php echo $amount; ?>"
            <?php echo $this->get_render_attribute_string( 'input_field' ); ?> placeholder="Amount" />
        <?php }else{?>
        <?php if($settings['payment_plan'] == 'saved' && !empty($settings['saved_plan']) && $plan_picker_enabled != 'yes'){ ?>
        <input type="hidden" name="currency"
            value="<?php echo $this->plans['control_display_currency'][$settings['saved_plan']]; ?>" />
        <input type="hidden" name="amount"
            value="<?php echo $this->plans['control_display_amount'][$settings['saved_plan']]; ?>" />
        <input type="hidden" name="payment_plan" value="<?php echo $settings['saved_plan'];?>" />
        <?php }elseif ($settings['payment_plan'] == 'saved' && !empty($settings['saved_plan']) || empty($settings['saved_plan'])  && $plan_picker_enabled == 'yes') {?>
        <select id="flutterwave-payment-plan-elementor" name="payment_plan"
            <?php echo $this->get_render_attribute_string( 'input_field' ); ?> placeholder="Select Plan">
            <?php foreach ($this->plans['control_display'] as $key => $value) { ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php }?>
        </select>

        <?php }else{?>
        <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
        <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
        <?php }?>
        <?php }?>
        <input type="hidden" id="flw-elementor-form-redirecturl" name="redirect_url"
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
        if ($("#flw-elementor-form-redirecturl").length) {
            $("#flw-elementor-form-redirecturl").attr(
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
<# view.addRenderAttribute( 'input_field' , { 'style' : 'margin-bottom: 1em;width: 100%;height: 55px;' } ); #>
    <div class="flutterwave-elementor-paynow-form-container">
        <div id="flutterwave-elementor-form-form" style="
	color: black;
	padding: 30px;
	padding-left: auto;
	padding-right: auto;
	filter: drop-shadow(0px 1.44402px 10.8301px rgba(0, 0, 0, 0.1));
	font-family: Inter;
	position: relative;
	justify-content: center;
	width: 50%;
	margin: 5px auto;
	display: flex;
	flex-direction:column;
	font-weight: 300;
	background-color: {{ settings.form_color }}">
            <h3 style="text-align:center">
                {{{ settings.title_text }}}
            </h3>
            <div style="text-align:center">
                {{{ settings.form_description }}}
            </div>
            <input id="flw-elementor-cust-email" {{{ view.getRenderAttributeString( 'input_field' ) }}}
                class="flw-elementor-input" name="customer[email]" placeholder="Email" />
            <input id="flw-elementor-cust-name" {{{ view.getRenderAttributeString( 'input_field' ) }}}
                class="flw-elementor-input" name="customer[name]" placeholder="Name" />
            <# if ( 'yes'===settings.currency_picker_enabled ) { #>
                <select name="currency" class="flw-elementor-input"
                    {{{ view.getRenderAttributeString( 'input_field' ) }}} value="{{{ settings.currency }}}">
                    <option value="None">Select Currency</option>
                    <# _.each( settings.currency_list, function( value, key ) { #>
                        <option value="{{ value }}">{{{ key }}}</option>
                        <# } ); #>
                </select>
                <input class="flw-elementor-input" name="amount" {{{ view.getRenderAttributeString( 'input_field' ) }}}
                    value="{{{ settings.amount }}}" />
                <# }else{ #>
                    <input type="hidden" name="currency" {{{ view.getRenderAttributeString( 'input_field' ) }}}
                        value="{{{ settings.currency }}}" />
                    <input type="hidden" name="amount" {{{ view.getRenderAttributeString( 'input_field' ) }}}
                        value="{{{ settings.amount }}}" />
                    <# } #>
                        <# if ( settings.payment_plan==='saved' && settings.saved_plan){ #>
                            <input type="hidden" name="payment_plan" value="{{{ settings.saved_plan }}}" />
                            <# } #>
                                <button id="f4b-elementor-paynow-button" style="font-size:{{{ settings.button_size }}};
                line-height: 1.25;
                padding: 1.1em 1.44em;
                text-transform:uppercase;
                border:none;
                borderRadius:0.3em;">
                                    {{{ settings.button_text }}}
                                </button>
                                </form>
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
            foreach ( $result as $plan ) {
                $plans[$plan['id']] = $plan['name'];
                $plans_amount[$plan['id']] = $plan['amount'];
                $plans_currency[$plan['id']] = $plan['currency'];
            }
            
        }

        $collection = [
            'control_display' => $plans,
            'control_display_amount' => $plans_amount,
            'control_display_currency' => $plans_currency
        ];

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