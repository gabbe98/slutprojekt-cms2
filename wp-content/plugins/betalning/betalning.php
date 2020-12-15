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
            public function __construct()
            {
                $this->id = 'betalning'; // Sätter id till betalning<
                $this->has_fields = false;
                $this->method_title = __('Faktura', 'betalning');
                $this->method_description = __('Betala med faktura', 'betalning');

                $this->title = $this->get_option( 'title' );
                $this->description = $this->get_option( 'description' );
                $this->instructions = $this->get_option( 'instructions', $this->description );

                $this->init_form_fields();
                $this->init_settings();

                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
                add_action( 'woocommerce_thank_you_' . $this->id, array($this, 'thank_you_page'));
            }

            public function thank_you_page(){
                if ($this->instructions) {
                    echo wpautop($this->instructions);
                }
            }

            public function init_form_fields() {
                $this->form_fields = apply_filters('betalning_fields', array(
                    'enabled' => array(
                        'title' => __('Enable/Disable', 'betalning'),
                        'type' => 'checkbox',
                        'label' => __('Aktivera/Avaktivera', 'betalning'),
                        'default' => 'no'
                    ),'title' => array(
                        'title' => __( 'Titel', 'betalning' ),
                        'type' => 'text',
                        'description' => __( 'Titeln vid betalning', 'betalning' ),
                        'default' => __( 'Faktura', 'betalning' ),
                        'desc_tip'      => true,
                    ),
                    'description' => array(
                        'title' => __( 'Kundmeddelande', 'betalning' ),
                        'type' => 'textarea',
                        'default' => __('Betala via faktura.', 'betalning'),
                        'desc_tip' => true,
                        'description' => __('Meddelande vid betalning', 'betalning')
                    ),
                    'instructions' => array(
                        'title' => __( 'Instruktioner', 'betalning'),
                        'type' => 'textarea',
                        'default' => __( 'Instruktioner', 'betalning'),
                        'desc_tip' => true,
                        'description' => __('Betalningsinstruktioner', 'betalning')
                    )
                ));
            }

            public function luhn_algoritm($personnummer) {
                $personnummer = preg_replace('/[^\d]/', '', $personnummer);
                $sum = '';
            
                for ($i = strlen($personnummer) - 1; $i >= 0; -- $i) {
                    $sum .= $i & 1 ? $personnummer[$i] : $personnummer[$i] * 2;
                }
            
                return array_sum(str_split($sum)) % 10 === 0;
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

                // if ($this->luhn_algoritm($this->personnummer)) {
                
                // } else {

                // }
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