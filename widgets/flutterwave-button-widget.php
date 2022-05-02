<?php

if( !defined('ABSPATH') ) return exit;

use Elementor\Widget_Base;

class Flutterwave_Button_Widget extends Widget_Base
{
        
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
            return [ 'general' ];
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
                    'default' => 'md',
                    'options' => [
                        'xs' => __( 'Extra Small', 'flutterwave-for-elementor' ),
                        'sm' => __( 'Small', 'flutterwave-for-elementor' ),
                        'md' => __( 'Medium', 'flutterwave-for-elementor' ),
                        'lg' => __( 'Large', 'flutterwave-for-elementor' ),
                        'xl' => __( 'Extra Large', 'flutterwave-for-elementor' ),
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
                    'label' => esc_html__( 'Currency', 'flutterwave-for-business' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'NGN',
                    'options' => [
                        'GHS'  => esc_html__( 'GHS', 'flutterwave-for-business' ),
                        'KES' => esc_html__( 'KES', 'flutterwave-for-business' ),
                        'USD' => esc_html__( 'USD', 'flutterwave-for-business' ),
                        'NGN' => esc_html__( 'NGN', 'flutterwave-for-business' ),
                        'NGN' => esc_html__( 'Default', 'flutterwave-for-business' ),
                    ],
                ]
            );

            $this->add_control(
                'payment_type',
                [
                    'label' => esc_html__( 'Payment Type', 'flutterwave-for-business' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'card'  => esc_html__( 'Card', 'flutterwave-for-business' ),
                        'momo'  => esc_html__( 'Mobile Money', 'flutterwave-for-business' ),
                        'ussd'  => esc_html__( 'USSD', 'flutterwave-for-business' ),
                        'account' => esc_html__( 'Account', 'flutterwave-for-business' ),
                        'default' => esc_html__( 'All', 'flutterwave-for-business' ),
                    ],
                ]
            );

            $this->add_control(
                'payment_plan',
                [
                    'label' => esc_html__( 'Payment Plan', 'flutterwave-for-business' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'monthly'  => esc_html__( 'Monthly', 'flutterwave-for-business' ),
                        'quarterly' => esc_html__( 'Quarterly', 'flutterwave-for-business' ),
                        'yearly' => esc_html__( 'Yearly', 'flutterwave-for-business' ),
                        'default' => esc_html__( 'None', 'flutterwave-for-business' ),
                    ],
                ]
            );
    
            $this->add_control(
                'payment_plan_amount',
                [
                    'label' => esc_html__( 'Payment Plan Amount', 'flutterwave-for-business' ),
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
                    'label' => esc_html__( 'Button Color', 'flutterwave-for-business' ),
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
                    'label' => esc_html__( 'Text Color', 'flutterwave-for-business' ),
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
            $currency = $settings['currency'];
            $payment_type = $settings['payment_type'];
            $payment_plan = $settings['payment_plan'];
            $payment_plan_amount = $settings['payment_plan_amount'];
            $button_text = $settings['button_text'];
            $button_size = $settings['button_size'];
            $button_color = $settings['button_color'];
            $title_color = $settings['title_color'];

            ?>

<div class="f4b-elementor-paynow-button-container">
    <button id="f4b-elementor-paynow-button" class="f4b-elementor-paynow-button"
        style="background-color: <?php echo $button_color; ?>; color: <?php echo $title_color; ?>;">
        <?php echo $button_text; ?>
    </button>
    <?php
        }
}