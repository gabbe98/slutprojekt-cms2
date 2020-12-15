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
            }
        }
    }
}
add_filter('woocommerce_payment_gateways', 'add_to_woo_betalning_gateway' );

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     $fields['billing']['billing_personnummer'] = array(
        'label'     => __('Personnummer', 'woocommerce'),
    'placeholder'   => _x('Personnummer', 'placeholder', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );

     return $fields;
}

/**
 * Display field value on the order edit page
 */
 
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'add_checkout_field_personnummer', 10, 1 );

function add_checkout_field_personnummer($order){
    echo '<p><strong>'.__('Personnummer From Checkout Form').':</strong> ' . get_post_meta( $order->get_id(), '_billing_personnummer', true ) . '</p>';
}

function add_to_woo_betalning_gateway($gateways ) {
    $gateways[] = 'WC_Betalning_Gateway';
    return $gateways;
}

add_action( 'woocommerce_after_checkout_validation', 'complete_purchase', 10, 2);
function complete_purchase($fields, $errors) {

        class WC_Validate_Personnummer {
            public function __construct($fields, $errors)
            {
                if ( !$this->luhn_algoritm($fields['billing_personnummer'] ) ){
                    $errors->add( 'validation', 'Skriv in ett giltigt personnummer.' );
                }
            }
            public function luhn_algoritm($fields) {
                
                if ($fields === '1') {
                    return false;
                } else {
                    return true;
                }
            }
        }
    new WC_Validate_Personnummer($fields, $errors);
}

?>