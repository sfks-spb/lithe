<?php
/**
 * Template Name: Full Width Template
 * Template Post Type: page
 */

get_header();
?>

<main id="site-content" role="main" class="full-width">

    <?php

    if ( have_posts() ) {

        while ( have_posts() ) {
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );
        }
    }

    ?>

</main>

<?php
get_sidebar( 'mobile' );
get_footer();
