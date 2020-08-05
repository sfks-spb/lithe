<?php
    $message = get_theme_mod( 'lithe_infobar_message', '' );

    if ( '' !== $message ):
?>

<div class="infobar infobar-top text-align-center" role="alert" style="background-color: <?php echo esc_attr( get_theme_mod( 'lithe_infobar_bg_color', '#105991' ) ); ?>;">

    <div class="infobar-wrapper awaits-icons slides-down">

        <?php
            $icon = get_theme_mod( 'lithe_infobar_icon_name', '' );

            if ( '' !== $icon ):
        ?>

            <i class="<?php echo esc_attr( get_theme_mod( 'lithe_infobar_icon_collection', 'fas' ) ); ?> fa-<?php echo esc_attr( $icon ); ?>"></i>

        <?php endif; ?>

        <span class="infobar-content">
            <?php echo esc_html( $message ); ?>
        </span>

        <?php
            $url = get_theme_mod( 'lithe_infobar_link_url', '' );

            if ( '' !== $url ):
        ?>

            <a title="<?php echo esc_attr( get_theme_mod( 'lithe_infobar_link_title', '' ) ) ?>" href="<?php echo esc_attr( $url ); ?>" class="infobar-link">
                <?php echo get_theme_mod( 'lithe_infobar_link_text', __( 'Learn more', 'lithe' ) ); ?>
            </a>

        <?php endif; ?>

    </div>

</div>

<?php endif; ?>