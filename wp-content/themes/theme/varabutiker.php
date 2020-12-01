<?php
require 'varabutiker-acf.php';
/**
 * Template name: Våra butiker
 */
get_header();
?>


<?php
// Visar custom post type, Putte
$loop = new WP_Query(array('post_type' => 'butiker', 'posts_per_page' => 10));

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
<?php get_footer(); ?>