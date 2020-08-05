<?php

if ( ! class_exists( 'Lithe_Shortcodes' ) ) {

    class Lithe_Shortcodes {

        /**
         *
         */
        public function __construct() {
            $this->register( array(
                'antispam'             => 'antispam_shortcode',
                'tldr'                 => 'tldr_shortcode',
                'required'             => 'required_shortcode',
                'spinner'              => 'spinner_shortcode',
                'recaptcha-disclaimer' => 'recaptcha_disclaimer_shortcode',
            ) );
        }

        /**
         *
         */
        public function register( array $shortcodes ): void {
            foreach ( $shortcodes as $shortcode => $callback ) {
                if ( is_string( $callback ) && is_callable( array( $this, $callback ) ) ) {
                    $callback = array( $this, $callback );
                }

                add_shortcode( "lithe-$shortcode", $callback );
            }
        }

        /**
         *
         */
        public function antispam_shortcode( $atts, $content = null ): string {
            $fields = shortcode_atts( array(
                'phone' => null,
                'email' => null,
                'text'  => null,
            ), $atts );

            foreach( $fields as $type => $value ) {
                if ( ! is_null( $value ) ) {
                    return lithe_get_antispam( $value, $type );
                }
            }

            return lithe_get_antispam( $content );
        }

        /**
         *
         */
        public function tldr_shortcode( $atts, $content = null ): string {
            return '<span class="tldr">' . $content . '</span>';
        }

        /**
         *
         */
        public function required_shortcode( $atts, $content = null ): string {
            return '<span class="required has-dark-red-color">
                <i title="' . __( 'required', 'lithe' ) . '" class="fas fa-asterisk fa-sm" aria-label="' . __( 'required', 'lithe' ) . '"></i></span>';
        }

        /**
         *
         */
        public function spinner_shortcode( $atts, $content = null ): string {
            $fields = shortcode_atts(  array(
                'prefix' => 'fas',
                'icon'   => 'sync',
                'class'  => 'fa-spin fa-fw',
            ), $atts );

            return '<span class="ajax-loader lithe-ajax-loader">
                <i title="' . __( 'Loading...', 'lithe' ) . '" class="' . $fields['prefix'] . ' fa-' . $fields['icon'] . ' ' . $fields['class'] . '"></i></span>';
        }

        /**
         *
         */
        public function recaptcha_disclaimer_shortcode( $atts, $content = null ): string {

            $links = array(
                __( 'Privacy'         , 'lithe' ) => 'https://policies.google.com/privacy',
                __( 'Terms of Service', 'lithe' ) => 'https://policies.google.com/terms',
            );

            $output  = '<p class="disclaimer recaptha-disclaimer has-dark-gray-color"><i class="fab fa-google"></i> ' . __( 'Form is protected by reCAPTCHA from Google', 'lithe' ) . ' (';
            $output .= '<span class="disclaimer-links">';

            foreach ( $links as $label => $href ) {
                $output .= '<a href=' . $href .'>' . $label . '</a>, ';
            }

            return rtrim($output, ', ') . '</span>)</p>';
        }

    }

}
