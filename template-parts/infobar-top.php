<?php
    $message = get_theme_mod( 'lithe_infobar_message', '' );

    if ( '' !== $message ):

        $url = get_theme_mod( 'lithe_infobar_link_url', '' );
        $icon = get_theme_mod( 'lithe_infobar_icon_name', '' );
?>

<div class="infobar infobar-top text-align-center<?php echo ( '' === $url ) ? '' : ' has-cta-button'; echo ( '' === $icon ) ? '' : ' has-icon'; ?>"
    role="alert"
    style="background-color: <?php echo esc_attr( get_theme_mod( 'lithe_infobar_bg_color', '#105991' ) ); ?>;">

    <div class="infobar-wrapper">

        <?php if ( '' !== $icon ): ?>

            <span class="infobar-icon">

                <i class="<?php echo esc_attr( get_theme_mod( 'lithe_infobar_icon_collection', 'fas' ) ); ?> fa-<?php echo esc_attr( $icon ); ?>"></i>

            </span>

        <?php endif; ?>

        <span class="infobar-content">

            <?php echo esc_html( $message ); ?>

        </span>

        <?php if ( '' !== $url ): ?>

            <span class="infobar-link awaits-icons zooms-in">

                <a title="<?php echo esc_attr( get_theme_mod( 'lithe_infobar_link_title', '' ) ) ?>" href="<?php echo esc_attr( $url ); ?>">

                    <span class="infobar-link-content"><?php echo get_theme_mod( 'lithe_infobar_link_text', __( 'Learn more', 'lithe' ) ); ?></span><i class="fas fa-external-link-alt"></i>

                </a>

            </span>

        <?php endif; ?>

    </div>

</div>

<?php endif; ?>