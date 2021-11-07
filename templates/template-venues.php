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

        <h2>Найдите ближайшую к вам площадку по интересующему вас виду спорта</h2>

        <div id="sports-selector-top" class="sports-selector">

            <?php lithe_sports( array( 'hide_trainerless' => true ) ); ?>

        </div>

        <div class="description">
            В списке под картой, показаны тренеры по выбранному вами виду спорта.
            Вы также можете нажать на значок площадки на карте, чтобы показывать тренеров только с выбранной площадки.
        </div>

        <div id="venues-map"></div>
        <div id="venues-list"></div>

    </div>

</main>

<?php
get_sidebar( 'mobile' );
get_footer();
