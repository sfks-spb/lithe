<?php

if ( ! class_exists( 'Lithe_Breadcrumbs' ) ) {

    class Lithe_Breadcrumbs {

        /**
         * Constructs new lithe breadcrumbs instance
         *
         * @return void
         */
        public function __construct() {
            add_filter('wpseo_breadcrumb_separator', array( $this, 'wpseo_breadcrumb_separator' ) );
            add_filter('wpseo_breadcrumb_single_link', array( $this, 'wpseo_breadcrumb_single_link' ), 10, 2 );
        }

        /**
         * Adds custom breadcrumb separator
         *
         * @param  string $this_options_breadcrumbs_sep
         *
         * @return string
         */
        public function wpseo_breadcrumb_separator( string $this_options_breadcrumbs_sep ): string {
            return '<span class="breadcrumbs-separator"><i class="fas fa-angle-right"></i></span>';
        }

        /**
         * Replaces home breadcrumb link with an icon
         *
         * @param  string $link_output
         * @param  array  $link
         *
         * @return string
         */
        public function wpseo_breadcrumb_single_link( string $link_output, array $link ) {
            if ( $link[ 'url' ] === trailingslashit( get_home_url() ) ) {
                $link_output = '<a href="' .  esc_attr( get_home_url() ) . '" rel="home"><i title="' .
                    esc_attr__( 'Home', 'lithe' ) . '" class="fas fa-home"></i></a>';
            }

            return $link_output;
        }

    }

    return new Lithe_Breadcrumbs();

}
