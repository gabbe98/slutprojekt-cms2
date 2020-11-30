<?php
    add_theme_support('post-thumbnails' ); // Lägger till stöd för att visa vald bild vid ett inlägg
    function slutprojekt_enqueue_scripts() {

        wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.css', array(), null, 'all');
        wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . '/style.css', array(), null, 'all');

        wp_enqueue_script( 'jQuery', 'https://code.jquery.com/jquery-3.5.1.min.js', array(), null, true );
        wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), null, true );
    }
    add_action('wp_enqueue_scripts', 'slutprojekt_enqueue_scripts');
?>