<?php
if (function_exists('acf_add_local_field_group')) :

  acf_add_local_field_group(array(
    'key' => 'group_5fc63ccfab6a8',
    'title' => 'Leveransmetod2',
    'fields' => array(
      array(
        'key' => 'field_5fc63cdd716a0',
        'label' => 'Belopp för fri frakt',
        'name' => 'belopp_for_fri_frakt',
        'type' => 'number',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => 0,
        'placeholder' => 'Skriv in belopp för fri frakt',
        'prepend' => '',
        'append' => '',
        'min' => '',
        'max' => '',
        'step' => '',
      ),
      array(
        'key' => 'field_5fc63d5010652',
        'label' => 'Fraktkostnad',
        'name' => 'fraktkostnad',
        'type' => 'number',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => 0,
        'placeholder' => 'Fraktkostnad om fri frakt ej uppnås',
        'prepend' => '',
        'append' => '',
        'min' => '',
        'max' => '',
        'step' => '',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'Leveransmetod 2-settings',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
  ));

endif;
