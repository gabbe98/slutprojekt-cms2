<?php
require 'varabutiker-acf.php';
/**
 * Template name: Våra butiker
 */
get_header();
?>


<?php
// Visar custom post type samt lagrar i transient, Putte
if (false === ($loop = get_transient('varabutiker'))) {
  $loop = new WP_Query(array(
    'post_type' => 'butiker',
    'posts_per_page' => 10
  ));

  set_transient('varabutiker', $loop, 12 * HOUR_IN_SECONDS);
}

while ($loop->have_posts()) : $loop->the_post();


?>

  <?php
  while (have_rows('butikposter')) {
    the_row() ?>
  <?php } ?>

  <div class="container">
    <h4> <?php the_title() ?> </h4>
    <p> <?php the_content() ?> </p>
    <small> <?php the_field('plats') ?></small>
    <!-- KArtbild skall in här senare -->
    <hr>
  </div>

<?php endwhile; ?>

<?php wp_reset_postdata(); ?>

<?php get_footer(); ?>