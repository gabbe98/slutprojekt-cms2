<?php
get_header();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post(); 
        
        echo '<div class="container">';
            echo '<div class="row">';
                echo '<div class="col-12">';
                if (get_the_post_thumbnail_url() != null) {
                    echo '<img src="';
                    the_post_thumbnail_url();
                    echo '" class="img-thumbnail blogg-img-single float-right">';
                }
                echo '<a href="';
                the_permalink();
                echo '">';
                echo '<h1 class="display-4 blog-title">';
                    the_title();
                echo '</h1>';
                echo '</a>';
                echo '<p class="lead">';
                    the_content();
                echo '</p>';
                
                echo '</div>';
                
            echo '</div>';
            echo '<hr>';
        echo '</div>';
    }
}

get_footer();
?>