<aside id="site-sidebar" class="primary-sidebar widget-area" role="complementary">

    <?php get_template_part('template-parts/mobile-menu'); ?>

    <?php if ( has_nav_menu( 'aside' ) ): ?>

        <nav id="aside-nav" class="nav-aside has-light-gray-border-color font-family-serif" role="navigation">

                <?php
                    wp_nav_menu( array(
                        'container'       => '',
                        'theme_location'  => 'aside',
                        'depth'           => 1,
                    ) );
                ?>

                <?php if ( has_nav_menu( 'aside-extra' ) ): ?>

                        <div class="aside-nav-bottom">

                            <?php
                                wp_nav_menu( array(
                                    'container'       => '',
                                    'theme_location'  => 'aside-extra',
                                    'depth'           => 1,
                                ) );
                            ?>

                        </div>

                <?php endif; ?>

        </nav>

    <?php endif; ?>

    <?php dynamic_sidebar( 'sidebar-primary' ) ?>

</aside>
