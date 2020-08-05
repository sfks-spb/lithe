<?php

if ( ! class_exists( 'Lithe_Breadcrumbs' ) ) {

    class Lithe_Breadcrumbs {

        /**
         *
         */
        public function __construct() {
            add_filter('wpseo_breadcrumb_separator', array( $this, 'wpseo_breadcrumb_separator' ) );
            add_filter('wpseo_breadcrumb_single_link', array( $this, 'wpseo_breadcrumb_single_link' ), 10, 2 );
        }

        /**
         *
         */
        public function wpseo_breadcrumb_separator( $this_options_breadcrumbs_sep ): string {
            return '<span class="breadcrumbs-separator"><i class="fas fa-angle-right"></i></span>';
        }

        /**
         *
         */
        public function wpseo_breadcrumb_single_link( $link_output, $link ) {
            if ( $link[ 'url' ] === trailingslashit( get_home_url() ) ) {
                $link_output = '<a href="' .  esc_attr( get_home_url() ) . '" rel="home"><i title="' .
                    esc_attr__( 'Home', 'lithe' ) . '" class="fas fa-home"></i></a>';
            }

            return $link_output;
        }

    }

    return new Lithe_Breadcrumbs();

}
