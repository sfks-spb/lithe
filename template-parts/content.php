<?php $post_image_class = ( is_singular() ) ? '' : get_post_meta( get_the_ID(), 'post_image_float', true ); ?>

<article <?php post_class( ( empty( $post_image_class ) ) ? '' : 'post-image-' . $post_image_class ); ?> id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>">

    <?php if ( is_sticky() && ! is_singular() ): ?>

        <span class="post-sticky-icon awaits-icons">

            <span class="stars">

                <i class="far fa-star fa-fw"></i><i class="far fa-star fa-fw fa-lg"></i><i class="far fa-star fa-fw"></i>

            </span>

        </span>

    <?php endif; ?>

    <?php get_template_part( 'template-parts/entry-header' ); ?>

    <div class="entry-content">

        <?php
            $excerpt = ( has_excerpt() ) ? apply_filters( 'the_excerpt', get_the_excerpt() ) : '';

            if ( is_singular() ) {
                echo $excerpt;
                lithe_post_thumbnail( '<div class="post-image">', '</div>' );
            } else {
                lithe_post_thumbnail( '<a class="post-image" href="' . esc_attr( get_permalink() ) . '">', '</a>' );
            }
        ?>

        <div class="post-content">

            <?php
                if ( ! is_singular() ) echo $excerpt;
                the_content();
            ?>

        </div>

    </div>

    <?php if ( is_singular() ): ?>

        <?php if ( has_tag() ): ?>

            <div class="post-tags meta-wrapper">

                <?php lithe_the_tags(); ?>

            </div>

        <?php endif; ?>

        <?php if ( is_single() ) lithe_related_posts(); ?>

        <?php if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ): ?>

            <div class="comments-wrapper">

                <?php comments_template(); ?>

            </div>

        <?php endif; ?>

    <?php else: ?>

        <footer class="entry-footer">

            <?php
                lithe_comments_link();
                lithe_the_views();
            ?>

        </footer>

    <?php endif; ?>

</article>
