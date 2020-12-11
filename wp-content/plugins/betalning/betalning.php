<?php
/**
 * Plugin Name: Betalning
 * Description: Plugin för att kunna få alternativ för att betala via faktura efter inskrivet personnummer.
 * Author: Oscar & Gabriel
 */
if ( ! in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return ;

add_action( 'plugins_loaded', 'betalning_init');

function betalning_init() {
    if( class_exists('WC_Payment_Gateway')) {
        class WC_Betalning_Gateway extends WC_Payment_Gateway {
            private $personnummer;
            public function __construct($personnummer)
            {
                $this->id = 'betalning'; // Sätter id till betalning
                $this->icon = apply_filters('woocommerce_betalning_icon', plugins_url() . '/assets/icon.png');
                $this->has_fields = false;
                $this->method_title = __('Faktura', 'betalning');
                $this->method_description = __('Betala med faktura', 'betalning');

                $this->init_form_fields();
                $this->init_settings();

                $this->personnummer = $personnummer;
            }

            public function init_form_fields() {
                $this->form_fields = apply_filters('betalning_fields', array(
                    'enabled' => array(
                        'title' => __('Enable/Disable', 'betalning'),
                        'type' => 'checkbox',
                        'label' => __('Aktivera/Avaktivera', 'betalning'),
                        'default' => 'no'
                    ),'title' => array(
                        'title' => __( 'Title', 'woocommerce' ),
                        'type' => 'text',
                        'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                        'default' => __( 'Cheque Payment', 'woocommerce' ),
                        'desc_tip'      => true,
                    ),
                    'description' => array(
                        'title' => __( 'Customer Message', 'woocommerce' ),
                        'type' => 'textarea',
                        'default' => ''
                    )
                ));
            }
            public function luhn_algoritm($personnummer) {

            }
            public function process_payment($order_id) {
                global $woocommerce;
                $order = new WC_Order ($order_id);
                
                $order->update_status('on-hold', __(''));
                
                $woocommerce->cart->empty_cart();
                
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order)
                );
                $order->payment_complete();

                if ($this->luhn_algoritm($this->personnummer)) {
                
                } else {

                }
            }
        }
    }
}
add_filter('woocommerce_payment_gateways', 'add_to_woo_betalning_gateway' );


function add_to_woo_betalning_gateway($gateways ) {
    $gateways[] = 'WC_Betalning_Gateway';
    return $gateways;
}
?>