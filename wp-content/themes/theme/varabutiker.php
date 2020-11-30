<?php

/**
 * Template name: Våra butiker
 */
get_header();
require 'varabutiker-acf.php';
?>

<?php
while (have_rows('butiker')) {
  the_row();
?>
<?php } ?>


<div class="container w-75 mt-3">
  <h1 class="mt-3"> <?php the_title(); ?> </h1>
  <h3><?php the_field('butiksnamn1'); ?></h3>
  <h5><?php the_field('oppetider1'); ?></h5>
  <img src=" <?php echo get_field('karta1')['url']; ?>" alt="" width="250" height="250">
  <h3><?php the_field('butiksnamn2'); ?></h3>
  <h5><?php the_field('oppetider2'); ?></h5>
  <img src=" <?php echo get_field('karta1')['url']; ?>" alt="" width="250" height="250">

</div>

<?php get_footer(); ?>