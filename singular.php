<?php
get_header();
?>

<main id="site-content" role="main">

    <?php

    if ( have_posts() ) {

        lithe_breadcrumbs();

        while ( have_posts() ) {
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );
        }
    }

    ?>

</main>

<?php
get_sidebar();
get_footer();
