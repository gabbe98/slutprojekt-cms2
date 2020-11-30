<?php
get_header();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post(); 
        echo '<div class="container">';
            echo '<div class="row">';
                echo '<class="col-8">';
                    echo '<div class="row">';
                            echo '<h1 class="display-1">';
                                the_title();
                            echo '</h1>';
                            echo '<img src="';
                                the_post_thumbnail_url();
                            echo '" class="img-thumbnail">';
                            echo '<p class="lead">';
                                the_content();
                            echo '</p>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
}

get_footer();
?>