<article <?php post_class(); ?> id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>">

    <?php get_template_part( 'template-parts/entry-header' ); ?>

    <div class="entry-content">

        <?php the_content(); ?>

    </div>

    <?php if ( ! ( is_single() || is_page() ) ): ?>

        <footer class="entry-footer">

            <?php lithe_comments_link(); ?>
            <?php lithe_the_views(); ?>

        </footer>

    <?php endif; ?>

    <?php if ( is_single() && has_tag() ): ?>

        <div class="post-tags meta-wrapper">

            <?php lithe_the_tags(); ?>

        </div>

    <?php endif; ?>

    <?php if ( is_single() ): ?>

            <?php lithe_related_posts(); ?>

    <?php endif; ?>

    <?php if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ): ?>

        <div class="comments-wrapper">

            <?php comments_template(); ?>

		</div>

    <?php endif; ?>

</article>
