<?php
 /*
 Plugin Name: Leveransmetod 1
 Version: 1.0
 Description: Leverans med bud
 */

require 'leverans1-plugin-acf.php';

// Kollar om WooCommerce är aktiv
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
    function leverans_ett_shipping_method() {
        if ( ! class_exists( 'leverans_ett_shipping_method' ) ) {
            class leverans_ett_shipping_method extends WC_Shipping_Method {
              
                public function __construct() {
                    $this->id                 = 'leverans_ett'; // ID på vår leverans, required. 
                    $this->method_title       = __( 'Leverans med bud', 'Levereras hem med bud' );   //Namnet på vår leverans som visas i admin
                    $this->method_description = __( 'Paketet levereras hem med bud', 'Levereras hem med bud' ); //Kort beskrivning av leveransen som visas i admin. 
 
                    
                    $this->availability = 'including'; //Om leveransen är tillgänglig eller ej. 
                    $this->countries = array(  //Vilka länder det går att posta till. 
                        'US', // Unites States of America
                        'CA', // Canada
                        'DE', // Germany
                        'GB', // United Kingdom
                        'IT',  // Italy
                        'ES', // Spain
                        'HR',  // Croatia
                        'SE', //Sweden
                        'DK', //Denmark
                        'NO' //Norway
                        );
 
                    $this->init(); 
 
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes'; //Ger infon ifall leveransen aktiverad eller ej.
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'leverans_ett Shipping', 'leverans_ett' ); //Visar leveransnamnet på sidan. 
                }


                function init() { //Skapar formulärfält och inställningar
                    // Laddar inställnings API
                    $this->init_form_fields(); 
                    $this->init_settings(); 

                    //Sparar inställningarna i admin
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
 

                //Lägger till fält på sidan WooCommerce > inställningar > frakt > Leverans med bud
                function init_form_fields() { 
 
                    $this->form_fields = array(
 
                     'enabled' => array( //Gör det möjligt att avaktivera/aktivera denna funktionen
                          'title' => __( 'Enable', 'leverans_ett' ),
                          'type' => 'checkbox',
                          'description' => __( 'Enable this shipping.', 'leverans_ett' ),
                          'default' => 'yes'
                          ),
 
                     'title' => array( //Fält för titeln.
                        'title' => __( 'Title', 'leverans_ett' ),
                          'type' => 'text',
                          'description' => __( 'Title to be display on site', 'leverans_ett' ),
                          'default' => __( 'leverans_ett Shipping', 'leverans_ett' )
                          ),
                     );
                }

 
               //Metod för att beräkna kostanden för leveransen. 
                public function calculate_shipping( $package = array() ) { //Package är en array med alla produkter som ska levereras. 

                    $weight = 0; //Totala vikten av alla produkter i varukorgen. 
                    $cost = 0; //Kostnaden av frakten. 
                    $country = $package["destination"]["country"]; //ISO code för det valda landet. 
 
                    foreach ( $package['contents'] as $item_id => $values ) 
                    { 
                        $_product = $values['data']; 
                        $weight = $weight + $_product->get_weight() * $values['quantity']; 
                    }
 

                    //Hämtar priset för de olika fraktklasserna ifrån ACF-fälten
                      $fraktklassett = get_field('fraktklass_ett', 'options');
                      $fraktklasstva = get_field('fraktklass_tva', 'options');
                      $fraktklasstre = get_field('fraktklass_tre', 'options');

                    $weight = wc_get_weight( $weight, 'kg' ); //Konverterar vikten till kg. 
                    
 
                    //Bestämmer priset beroende på vikten av produkterna i varukorgen. 
                    if( $weight <= 1 ) {
                        $cost = $fraktklassett;
 
                    } elseif( $weight >= 2 && $weight < 3) {
                        $cost = $fraktklasstva;
 
                    } elseif( $weight > 3 ) {
                        $cost = $fraktklasstre;
                    }

 
                    $countryZones = array( //Zoner 0-3. 0 (billigast), 3 (dyrast).
                        'SE' => 0,
                        'DK' => 1,
                        'NO' => 1,
                        'HR' => 1,
                        'US' => 3,
                        'GB' => 2,
                        'CA' => 3,
                        'ES' => 2,
                        'DE' => 1,
                        'IT' => 1,
                        );
 
                         //Hämtar priset för de olika zonerna ifrån ACF-fälten
                          $zonNoll = get_field('zon_0', 'options');
                          $zonEtt = get_field('zon_1', 'options');
                          $zontva = get_field('zon_2', 'options');
                          $zontre = get_field('zon_3', 'options');


                    $zonePrices = array(  //Priserna för varje zon. 
                        0 => $zonNoll, //Zon 0 
                        1 => $zonEtt, //Zon 1
                        2 => $zontva, //Zon 2 
                        3 => $zontre  //Zon 3 
                        );
 
                    $zoneFromCountry = $countryZones[ $country ]; //Iso country koden lägg i arrayen $countryZones för att komma till rätt zon. 
                    $priceFromZone = $zonePrices[ $zoneFromCountry ]; //För att få fram kostnaden.
 
                    $cost += $priceFromZone; //Adderar den retunerade kostnaden till variabeln $cost
 
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title, 
                        'cost' => $cost
                    );
 
                    $this->add_rate( $rate ); //Lägger till en leveranskostnad, $rate
                    
                }
            }
        }
    }
 
    add_action( 'woocommerce_shipping_init', 'leverans_ett_shipping_method' );
 
    function add_leverans_ett_shipping_method( $methods ) {
        $methods[] = 'leverans_ett_shipping_method';
        return $methods;
    }
 
    add_filter( 'woocommerce_shipping_methods', 'add_leverans_ett_shipping_method' );
 
    function leverans_ett_validate_order( $posted )   {
 
        $packages = WC()->shipping->get_packages();
 
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
         
        if( is_array( $chosen_methods ) && in_array( 'leverans_ett', $chosen_methods ) ) {
             
            foreach ( $packages as $i => $package ) {
 
                if ( $chosen_methods[ $i ] != "leverans_ett" ) {
                             
                    continue; 
                }
 
                $leverans_ett_shipping_method = new leverans_ett_shipping_method();
                $weight = 0;
 
                foreach ( $package['contents'] as $item_id => $values ) 
                { 
                    $_product = $values['data']; 
                    $weight = $weight + $_product->get_weight() * $values['quantity']; 
                }
                $weight = wc_get_weight( $weight, 'kg' );
            }       
        } 
    }
    add_action( 'woocommerce_review_order_before_cart_contents', 'leverans_ett_validate_order' , 10 );
    add_action( 'woocommerce_after_checkout_validation', 'leverans_ett_validate_order' , 10 );


    //Skriver ut varukorgsvikten i kassan
    function print_cart_weight() {
        $notice = 'Din varukorgsvikt är: ' . WC()->cart->get_cart_contents_weight() . get_option( 'woocommerce_weight_unit' );
        if ( is_cart() ) {
        wc_print_notice( $notice, 'notice' );
        } else {
        wc_add_notice( $notice, 'notice' );
     }
    }
    add_action( 'woocommerce_after_shipping_calculator', 'print_cart_weight' );
    }


// Option page för leverans metod 1, fraktkostnad
function option_page_one()
{
  acf_add_options_page([
    'page_title' => 'Leveransmetod 1',
    'menu_title' => 'Leveransmetod 1',
    'menu_slug' => 'Leveransmetod 1-settings',
    'capability' => 'edit_posts',
    'redirect' => false
  ]);
}
add_action('acf/init', 'option_page_one');