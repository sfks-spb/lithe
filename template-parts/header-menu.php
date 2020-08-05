<input type="checkbox" id="header-nav-toggle" class="collapsible-toggle" autocomplete="off" aria-hidden="true" tabindex="-1">

<label class="animated-menu-icon" for="header-nav-toggle">

    <?php lithe_svg( 'icon-menu', array( 'title' => __( 'Show or hide menu', 'lithe' ) ) ); ?>

</label>

<a title="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>" href="<?php echo home_url( '/' ); ?>" class="compact-logo" rel="home">

    <?php lithe_site_logo_compact( 'logo-' . get_theme_mod( 'lithe_logo_svg', 'plain' ) ); ?>

</a>

<?php if ( has_nav_menu( 'primary' ) ): ?>

    <nav id="main-nav" class="nav-main font-family-serif" role="navigation">

    <?php
        wp_nav_menu( array(
            'container'       => '',
            'theme_location'  => 'primary',
            'depth'           => 1,
        ) );
    ?>

    </nav>

<?php endif; ?>

<?php if ( has_nav_menu( 'social' ) ): ?>

    <nav id="social-nav" class="nav-social awaits-icons fades-in" role="navigation">

    <?php
        wp_nav_menu( array(
            'container'       => '',
            'theme_location'  => 'social',
            'depth'           => 1,
        ) );
    ?>

    </nav>

<?php endif; ?>
