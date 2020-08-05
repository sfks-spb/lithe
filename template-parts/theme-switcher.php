<button title="<?php echo esc_attr__( 'Switch color scheme', 'lithe' ); ?>" class="theme-switcher" role="button">

    <span class="theme-switcher-item light">

        <?php lithe_svg( 'icon-moon', array( 'title' => __( 'Switch to light color scheme', 'lithe' ) ) ); ?>

    </span>

    <span class="theme-switcher-item dark">

        <?php lithe_svg( 'icon-sun', array( 'title' => __( 'Switch to dark color scheme', 'lithe' ) ) ); ?>

    </span>

</button>