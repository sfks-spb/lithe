<!DOCTYPE html>
<html class="nojs" <?php language_attributes(); ?>>

    <head>

        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="profile" href="http://gmpg.org/xfn/11" />

        <?php wp_head(); ?>

    </head>

	<body <?php body_class(); ?>>

		<?php
		wp_body_open();
		?>

        <header id="site-header">

            <div class="content-center has-white-background-color">

                <div id="logo-ribbon" role="banner">

                    <?php lithe_site_logo( 'logo-' . get_theme_mod( 'lithe_logo_svg', 'plain' ) ); ?>

                    <div id="site-description">

                        <?php bloginfo( 'description' ); ?>

                    </div>

                    <div class="text-align-right">

                        <?php get_template_part( 'searchform', 'mobile' ); ?>
                        <?php get_template_part('template-parts/theme-switcher'); ?>

                    </div>

                </div>

            </div>

        </header>

        <header id="sticky-nav" class="sticky">

            <div class="content-center">

                <div id="header-menu" class="collapsible has-very-light-gray-border">

                    <?php get_template_part( 'template-parts/header-menu' ); ?>

                </div>

            </div>

        </header>

        <div id="wrapper">

            <div class="content-center">
