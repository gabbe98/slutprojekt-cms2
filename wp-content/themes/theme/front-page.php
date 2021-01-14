<?php 
get_header();
/*
*Template name: Startsida
 */

require 'slider-acf.php'; ?>

<!-- Avsluta container div från header  -->
</div>

<!-- Slider  -->
<br> <br>
<div id="carouselSliders" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselSliders" data-slide-to="0" class="active"></li>
        <li data-target="#carouselSliders" data-slide-to="1"></li>
        <li data-target="#carouselSliders" data-slide-to="2"></li>
    </ol>

    <div class="carousel-inner">
        <?php $i = 0;
     while(have_rows('slider')){ 
            the_row(); ?>

        <?php $image = get_sub_field('sliderbild');?>
        <div class="carousel-item <?php if ($i==0) { ?> active <?php } ?>"
            style="background-image: url(<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>">

            <div class="sliderTextBox">
                <h1 class="sliderTitle"> <?php echo get_sub_field('slider_rubrik')?> </h1>
                <p class="sliderText"> <?php  echo get_sub_field('slider_textfalt')?> </p>
            </div>
        </div>
        <?php $i++; } ?>
    </div>

    <a class="carousel-control-prev" href="#carouselSliders" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselSliders" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<br> <br><br> <br>

<!-- *********************************************************** -->

<!-- Populära produkter -->
<?php
                    $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 8, //Hur många produkter ska visas
                     'meta_key' => 'total_sales', //Kollar hur många produkter som har sålts av en produkt
                     'orderby' => 'meta_value_num', 
                    'tax_query' => array( 
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => array( 'catalogues' ),
                            'operator' => 'NOT IN'
                        )
                    ),
                    );
                    ?>
<div class="productsBestSellers">

    <h1 class="productListTitle"> Bästsäljare </h1>
    <div class="row">
        <?php $loop = new WP_Query( $args );
                    while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>


        <div class="col span_1_of_3">
            <a id="id-
            <?php the_id();?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">

                <?php 
                    if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); ?>
                <h3><?php the_title(); ?></h3>
            </a>
            <?php echo $product->get_price_html(); ?>
            <?php woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>
        </div>

        <?php endwhile; ?>
        <?php wp_reset_query(); ?>
    </div>
</div>
<br> <br><br> <br>
<!-- *********************************************************** -->


<!-- Lista över rea-varor  -->
<?php
        $argsSales = array(
            'post_type' => 'product',
            'posts_per_page' => 8, //Hur många produkter ska visas
             'meta_key' => '_sale_price', 
             'orderby' => 'meta_value_num', 
            'tax_query' => array( 
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => array( 'catalogues' ),
                    'operator' => 'NOT IN'
                )
            ),
            );?>

<div class="productSales">
    <h1 class="productListTitle"> REA </h1>
    <div class="row">

        <?php
        $loopSales = new WP_Query( $argsSales );
                    while ( $loopSales->have_posts() ) : $loopSales->the_post(); 
                    global $woocommerce;
                $products = new WC_Product(get_the_ID());  ?>

        <div class="col span_1_of_3">
            <a id="id-
            <?php the_id();?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">

                <?php 
            if (has_post_thumbnail( $loopSales->post->ID )) 
            echo get_the_post_thumbnail($loopSales->post->ID, 'shop_catalog'); ?>
                <h3><?php the_title(); ?></h3>
            </a>
            <?php echo $products->get_price_html(); ?>
            <?php woocommerce_template_loop_add_to_cart( $loopSales->post, $products ); ?>
        </div>

        <?php endwhile; ?>
        <?php wp_reset_query(); ?>
    </div>
</div>
<br> <br><br> <br>
<!-- *********************************************************** -->


<!-- Featured produkter -->

<?php 
$meta_query  = WC()->query->get_meta_query();
$tax_query   = WC()->query->get_tax_query();
$tax_query[] = array(
    'taxonomy' => 'product_visibility',
    'field'    => 'name',
    'terms'    => 'featured',
    'operator' => 'IN',
);
 
$argsFeatured = array(
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => 10,
    'meta_query'          => $meta_query,
    'tax_query'           => $tax_query,
);
 ?>

<div class="productFeatured">
    <h1 class="productListTitle"> Featured </h1>
    <div class="row">

        <?php
$featured_query = new WP_Query( $argsFeatured );
if ($featured_query->have_posts()) {
 
    while ($featured_query->have_posts()) : ?>
        <div class="col span_1_of_3">

            <?php $featured_query->the_post();
        $productFeatured = wc_get_product( $featured_query->post->ID );
        $price = $productFeatured->get_price_html(); ?>

            <a href="<?php the_permalink(); ?>">
                <?php echo woocommerce_get_product_thumbnail(); ?>
            </a>
            <a href="<?php the_permalink(); ?>">
                <h3><?php the_title(); ?></h3>
            </a>
            <?php echo $price; ?>
        </div>
        <?php
         
    endwhile;
  ?>
    </div>
</div> <?php
}
?>
<br> <br><br> <br>
<!-- *********************************************************** -->


<!-- Puff av senaste blogginlägget -->

<div class="puffBlogPost">
    <?php 
		$query = $wp_query; $wp_query= null;
		$wp_query = new WP_Query(); $wp_query->query('posts_per_page=1' . '&paged='.$paged);
        while ($wp_query->have_posts()) : $wp_query->the_post(); 
    ?>

    <a href="
    <?php the_permalink(); ?>">

        <?php the_post_thumbnail('post-thumbnail', ['class' => 'bloggimage', 'title' => 'Feature image']);?>

        <h2 class="puffTitle">
            <?php the_title(); ?>
        </h2>
    </a>

    <?php  function wp_example_excerpt_length( $length ) {
    return 40;
}
add_filter( 'excerpt_length', 'wp_example_excerpt_length');

    echo '<p class="puffExcerpt">' 
    . get_the_excerpt() . 
    '</p>'?>

    <?php endwhile; ?>
</div>
</div>
<br> <br><br> <br>

<?php
get_footer();
?>