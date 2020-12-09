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

// Option page för plugin

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





// Funktion för att hämta butiker från CPT samt skapar eget fält i checkout
function hamta_i_butik($customfields)
{

  $loop = new WP_Query(array(
    'post_type' => 'butiker',
    'posts_per_page' => 10
  ));

  $butiksknappar = [];

  while ($loop->have_posts()) {
    $loop->the_post();

    $butiksknappar[get_field('plats')] = get_field('plats');
  }


  echo '<div><h2>' . __('Hämta i butik') . '</h2>';
  woocommerce_form_field('my_field_name', array(
    'type'          => 'radio',
    'options' => $butiksknappar,
    'class'         => array('form-row-wide'),
    'label'         => 'Välj butik:'
  ), $customfields->get_value('my_field_name'));

  echo '</div>';
}
add_action('woocommerce_after_order_notes', 'hamta_i_butik');





// Uppdaterar meta med värdet från fälten
add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');

function my_custom_checkout_field_update_order_meta($order_id)
{
  if (!empty($_POST['my_field_name'])) {
    update_post_meta($order_id, 'My Field', sanitize_text_field($_POST['my_field_name']));
  }
}

// Lägger till vald butik så det visas i ordern på Admin sidan.
add_action('woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1);

function my_custom_checkout_field_display_admin_order_meta($order)
{
  echo '<h2><strong>' . __('Hämtas i butik') . ':</strong> ' . get_post_meta($order->get_id(), 'My Field', true) . '</h2>';
}
