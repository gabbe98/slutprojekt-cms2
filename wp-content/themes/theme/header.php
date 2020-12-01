<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(''); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class();?>>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
<?php 
    wp_nav_menu( array( 
        'theme_location' => 'slutprojekt_nav_bar',
        'container' => false,
        'menu_class' => 'navbar-nav'
        ) 
    ); 
?>
</nav>
