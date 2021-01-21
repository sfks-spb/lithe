<?php
$archive_title = false;
$archive_subtitle = false;

get_header();
?>

<main id="site-content" role="main">

    <?php if ( is_search() ):
        global $wp_query;

        if ( $wp_query->found_posts ) {

        }
    ?>

    <?php elseif ( ! is_home() ):
		$archive_title    = get_the_archive_title();
		$archive_subtitle = get_the_archive_description();
    ?>

    <?php elseif ( is_home() && ! is_paged() ): ?>

        <?php get_template_part('template-parts/infobar', 'top'); ?>

        <?php if ( lithe_has_carousel('home') ): ?>

            <div id="home-slides"><?php lithe_carousel( 'home', 'shuffled' ); ?></div>

        <?php endif; ?>

        <?php if ( lithe_has_carousel('sponsors') ): ?>

            <div id="home-sponsors" class="has-very-light-gray-background-color">

                <div class="sponsors-container">

                    <span class="sponsors-title"><?php _e( 'General sponsors', 'lithe' ); ?></span>

                    <?php lithe_carousel( 'sponsors', 'shuffled' ); ?>

                </div>

                <div class="sponsor-us has-dark-gray-color has-gray-border-color">
                    <?php printf( __( 'If you\'re interested in featuring your brand here, please don\'t hesitate to reach us via the %1$s or by email %2$s.', 'lithe' ),
                        '<a href=' . esc_attr( get_home_url( null, '/about/contact/' ) ) . '>' . __( 'contact form', 'lithe' ) . '</a>',
                        do_shortcode( '[lithe-email subject="' . __( 'Sponsorship Offer', 'lithe' ) . '"]info@sfks.ru[/lithe-email]' ) ); ?>
                </div>

            </div>
        <?php endif; ?>

    <?php endif; ?>

	<?php if ( $archive_title || $archive_subtitle ): ?>

		<header class="archive-header">

            <?php if ( $archive_title ): ?>

                <h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>

            <?php endif; ?>

            <?php if ( $archive_subtitle ): ?>

                <div class="archive-subtitle"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></div>

            <?php endif; ?>

		</header>

    <?php endif; ?>

    <?php
        if ( have_posts() ) {

            while ( have_posts() ) {
                the_post();

                get_template_part( 'template-parts/content', get_post_type() );
            }

            the_posts_pagination();

        }
    ?>

</main>

<?php
get_sidebar();
get_footer();
