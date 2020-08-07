<?php

if ( ! class_exists( 'Lithe_Shortcodes' ) ) {

    class Lithe_Shortcodes {

        /**
         * Constructs new lithe shortcodes instance.
         *
         * @return void
         */
        public function __construct() {
            $this->register( array(
                'antispam'             => 'antispam_shortcode',
                'tldr'                 => 'tldr_shortcode',
                'required'             => 'required_shortcode',
                'spinner'              => 'spinner_shortcode',
                'recaptcha-disclaimer' => 'recaptcha_disclaimer_shortcode',
                'available'            => 'available_shortcode',
            ) );
        }

        /**
         * Registers shortcodes.
         *
         * @param  array $shortcodes List of shortcode handlers.
         *
         * @return Lithe_Shortcodes
         */
        public function register( array $shortcodes ): self {
            foreach ( $shortcodes as $shortcode => $callback ) {
                if ( is_string( $callback ) && is_callable( array( $this, $callback ) ) ) {
                    $callback = array( $this, $callback );
                }

                add_shortcode( "lithe-$shortcode", $callback );
            }

            return $this;
        }

        /**
         * Adds antispam shortcode.
         *
         * @param  array       $atts Shortcode attributes. By default empty string ''.
         * @param  string|null $content Shortcode content.
         *
         * @return string
         */
        public function antispam_shortcode( array $atts, ?string $content = null ): string {
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
         * Adds tl;dr shortcode.
         *
         * @param  array       $atts Shortcode attributes. By default empty string ''.
         * @param  string|null $content Shortcode content.
         *
         * @return string
         */
        public function tldr_shortcode( $atts, ?string $content = null ): string {
            return '<span class="tldr">' . $content . '</span>';
        }

        /**
         * Adds required field shortcode.
         *
         * @param  array       $atts Shortcode attributes. By default empty string ''.
         * @param  string|null $content Shortcode content.
         *
         * @return string
         */
        public function required_shortcode( $atts, ?string $content = null ): string {
            return '<span class="required has-dark-red-color">
                <i title="' . __( 'required', 'lithe' ) . '" class="fas fa-asterisk fa-sm" aria-label="' . __( 'required', 'lithe' ) . '"></i></span>';
        }

        /**
         * Adds spinner element shortcode.
         *
         * @param  array       $atts Shortcode attributes. By default empty string ''.
         * @param  string|null $content Shortcode content.
         *
         * @return string
         */
        public function spinner_shortcode( $atts, ?string $content = null ): string {
            $fields = shortcode_atts(  array(
                'prefix' => 'fas',
                'icon'   => 'sync',
                'class'  => 'fa-spin fa-fw',
            ), $atts );

            return '<span class="ajax-loader lithe-ajax-loader">
                <i title="' . __( 'Loading...', 'lithe' ) . '" class="' . $fields['prefix'] . ' fa-' . $fields['icon'] . ' ' . $fields['class'] . '"></i></span>';
        }

        /**
         * Adds recaptcha disclaimer shortcode.
         *
         * @param  array       $atts Shortcode attributes. By default empty string ''.
         * @param  string|null $content Shortcode content.
         *
         * @return string
         */
        public function recaptcha_disclaimer_shortcode( $atts, ?string $content = null ): string {

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

        /**
         * Adds shortcode for time-based availability of content.
         *
         * @param  array       $atts Shortcode attributes. By default empty string ''.
         * @param  string|null $content Shortcode content.
         *
         * @return string|null
         */
        public function available_shortcode( $atts, ?string $content = null ): ?string {

            $fields = shortcode_atts( array(
                'from'        => null,
                'till'        => null,
                'exclude_cap' => 'edit_others_posts',
            ), $atts );

            if ( is_user_logged_in() && current_user_can( trim( $fields['exclude_cap'] ) ) ) {
                return '<span class="hidden-content">' . $content . '</span>';
            }

            $now = lithe_now();

            if ( ! is_null( $fields['from'] ) ) {

                $from_dt = lithe_strtotime( $fields['from'] );

                if ( false !== $from_dt && $now < $from_dt ) $content = null;

            }

            if ( ! is_null( $fields['till'] ) ) {

                $till_dt = lithe_strtotime( $fields['till'] );

                if ( false !== $till_dt && $now > $till_dt  ) $content = null;

            }

            return $content;

        }

    }

}
