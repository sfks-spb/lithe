<?php if ( has_nav_menu( 'primary' ) ): ?>

    <nav id="mobile-nav" class="nav-mobile font-family-serif" role="navigation">

    <?php
        wp_nav_menu( array(
            'container'       => '',
            'theme_location'  => 'primary',
            'depth'           => 1,
        ) );
    ?>

    </nav>

<?php endif; ?>