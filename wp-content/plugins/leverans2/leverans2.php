<?php

/**
 * Plugin Name: Leveransmetod 2
 * Description: Plugin för att kunna hämta i butik.
 * 
 * E-handeln skall ha ett leveransalternativ för att hämta upp leverans i butik. X
 * 
 * I kassan skall man kunna välja i vilken butik man vill hämta ut sin order. X
 * 
 * Butikerna som kan väljas skall vara samma som listas på sidan med företagets butiker.
 * 
 * Detta leveransalternativ skall vara gratis vid order över ett visst belopp. Det skall gå att ställa in vilket belopp som gäller.
 * 
 * Om man inte överstiger beloppet skall leveransalternativet kosta och leveransavgiften skall gå att ställa in i admin.

 */
require 'leverans2-acf.php';
?>


<?php


//Kollar om Woocommerce är installerat
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

  function egen_leverans()
  {
    if (!class_exists('WC_collect_at_store')) {
      class WC_collect_at_store extends WC_Shipping_Method
      {
        /**
         * Constructor 
         */
        public function __construct()
        {

          $this->id                 = 'collect_at_store';
          $this->method_title       = __('Tomat');
          $this->method_description = __('Vår egna funktion!');

          $this->enabled            = "yes";
          $this->title              = "Hämta i butik";


          $this->init();
        }


        function init()
        {
          // Laddar settings API
          $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
          $this->init_settings(); // This is part of the settings API. Loads settings you previously init.


          // Sparar settings i Admin
          add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
        }

        /**
         * Räknar ut fraktkostnad
         */
        public function calculate_shipping($package = array())
        {
          $rate = array(
            'label' => $this->title,
            'cost' => '',
            'calc_tax' => 'per_item'
          );


          // Kollar totala kostnad och lägger till frakt eller inte

          $varor = WC()->cart->subtotal;
          $frifrakt = get_field('belopp_for_fri_frakt', 'options');

          if ($varor >= $frifrakt) {
            $rate['cost'] = 0;
          } else {
            $rate['cost'] = get_field('fraktkostnad', 'options');
          }

          // Lägger till rate
          $this->add_rate($rate);
        }
      }
    }
  }

  add_action('woocommerce_shipping_init', 'egen_leverans');

  function add_collect_at_store($methods)
  {
    $methods['collect_at_store'] = 'WC_collect_at_store';
    return $methods;
  }
  add_filter('woocommerce_shipping_methods', 'add_collect_at_store');
}
//Option page för leveransmetod
function option_page()
{
  acf_add_options_page([
    'page_title' => 'Leveransmetod 2',
    'menu_title' => 'Leveransmetod 2',
    'menu_slug' => 'Leveransmetod 2-settings',
    'capability' => 'edit_posts',
    'redirect' => false
  ]);
}
add_action('acf/init', 'option_page');
