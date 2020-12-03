<?php
add_theme_support('post-thumbnails'); // Lägger till stöd för att visa vald bild vid ett inlägg
function slutprojekt_enqueue_scripts()
{
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.css', array(), null, 'all');
    wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/style.css', array(), null, 'all');

    wp_enqueue_script( 'jQuery', 'https://code.jquery.com/jquery-3.5.1.min.js', array(), null, true );
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), null, true );
}
add_action('wp_enqueue_scripts', 'slutprojekt_enqueue_scripts');

// Funktion för att ändra "läs mer" på excerpt till länk för inlägget
function wpdocs_excerpt_more( $more ) {
    return '<a href="' . get_the_permalink() . '">...</a>';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );


//Funktion för custom-post butiker, Putte
function butiker()
{
    $labels = array(
        'name' => 'Butiker',
        'singular_name' => 'Butik',
        'menu_name' => 'Butiker',
        'add_new_item' => 'Lägg till butik',
    );
    $args = array(
        'label' => 'butiker',
        'labels' => $labels,
        'supports' => array(
            'title', 'custom-fields',
        ),
        'public' => true,
        'has_archive' => true,
    );

    register_post_type('butiker', $args);
}
add_action('init', 'butiker');

// Woocommerce stöd
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}


// ==================================
//               Nav bar
// ==================================

// Registrera ett eget meny alternativ
function register_nav_bar() {
register_nav_menu('slutprojekt_nav_bar', __( 'Nav Bar'));
}
add_action( 'init', 'register_nav_bar' );

// Lägg
function add_nav_item_class( $classes, $args ) {
    if ( 'slutprojekt_nav_bar' === $args->theme_location ) {
        $classes[] = 'nav-item';
    }
    
    return $classes;
}
add_filter( 'nav_menu_css_class', 'add_nav_item_class', 10, 3 );

function add_link_attributes($attributes) {
    $attributes['class'] = "nav-link";
    return $attributes;
}
add_filter( 'nav_menu_link_attributes', 'add_link_attributes');

//Stöd för google maps i ACF

function my_acf_google_map_api( $api ){
    $api['key'] = 'AIzaSyDXYJDOsJU6QmvuFi-D4r3w1yaWRPVahmE';
    return $api;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');