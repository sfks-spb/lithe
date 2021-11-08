<?php
/**
 * Template Name: Venues Template
 * Template Post Type: page
 */

get_header();
?>

<main id="site-content" role="main" class="full-width">

    <?php

    if ( have_posts() ) {

        lithe_breadcrumbs();

        while ( have_posts() ) {
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );
        }
    }

    ?>

    <div class="venue-selector">

        <h2>

            <?php esc_html_e( 'Find closest training ground', 'lithe' ); ?>

        </h2>

        <div id="sports-selector-top" class="sports-selector">

            <?php lithe_sports( array( 'hide_trainerless' => true ) ); ?>

        </div>

        <div class="description">

            <?php

                esc_html_e(
                    'Use selector above to create list of trainers for specific sport. You can also click on placemark to filter trainer by the venue.' ,
                    'lithe'
                );

            ?>

        </div>

        <div id="venues-map"></div>
        <div id="venues-list"></div>

    </div>

</main>

<?php
get_sidebar( 'mobile' );
get_footer();
