            </div><!-- .content-center -->

        </div><!-- #wrapper -->

        <footer id="site-footer" role="contentinfo" class="has-gray-color has-very-light-gray-background-color has-light-gray-border-color">

            <div class="content-center">

                <div id="site-copyright">

                    <i class="far fa-copyright fa-lg"></i>

                    <p class="site-copyright-content">

                        <?php lithe_the_copyright( __( 'ROO "SFCS"', 'lithe' ) ); ?><br>
                        <?php _e( "If this website's materials are used, a link to the website must be provided.", 'lithe' ) ?>

                    </p>

                </div>

                <?php if ( has_nav_menu( 'footer' ) ): ?>

                    <nav id="footer-nav">

                        <?php
                            wp_nav_menu( array(
                                'container'       => '',
                                'theme_location'  => 'footer',
                                'depth'           => 1,
                            ) );
                        ?>

                    </nav>

                <?php endif; ?>

            </div><!-- .content-center -->

        </footer><!-- #site-footer -->

        <button title="<?php esc_attr_e( 'Go to Top', 'lithe' ); ?>" id="go-top" class="has-white-border-color has-white-background-color"><i class="fas fa-arrow-up"></i></button>

        <span class="ngg-simplelightbox"></span><!-- dirty fix for ngg lightbox js error -->

        <?php wp_footer(); ?>
    </body>
</html>
