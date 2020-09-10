<div class="related-wrapper">

    <h2 id="related-title" class="related-posts-title"><?php _e( 'You might also find interesting...', 'lithe' ); ?></h2>

    <ul class="related-posts">

        <?php while( $related->have_posts() ): $related->the_post(); ?>

            <li class="related-post">

                <a rel="nofollow" title="<?php echo esc_attr( get_the_title() ); ?>" href="<?php the_permalink(); ?>">

                    <span class="related-post-thumbnail">
                        <?php the_post_thumbnail( array( 250, 200 ) ); ?>
                    </span>

                    <ul class="related-post-meta">

                        <li class="related-post-date"><?php echo get_the_date( 'j F Y' ); ?></li>
                        <li class="related-post-title"><?php the_title(); ?></li>

                    </ul>

                </a>

            </li>

        <?php endwhile; ?>

    </ul>

</div>