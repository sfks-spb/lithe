<header class="entry-header">

    <?php
        if ( is_singular() ) {
            if ( ! ( is_page() || has_category( 1 ) ) ) the_category();
            the_title( '<h1 class="entry-title">', '</h1>' );
        } else {
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
        }
    ?>

    <?php if ( ! is_page() ): ?>

        <div class="entry-meta">

            <?php lithe_the_post_meta(); ?>

            <?php edit_post_link( __( 'Edit', 'lithe' ), '<span class="edit-link">', '</span>' ); ?>

        </div>

    <?php endif; ?>

</header>