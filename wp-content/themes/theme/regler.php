<?php
/*
*Template name: Regler och villkor
 */
get_header()
?>

<?php
while (have_posts()) {
  the_post();
?>
<?php } ?>

<section>
  <div class="container w-75 mt-3 mb-3">
    <h1 class="mb-3"> <?php the_title() ?> </h1>
    <p> <?php the_content() ?> </p>
  </div>
</section>


<?php get_footer() ?>