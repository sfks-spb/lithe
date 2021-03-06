<?php

if ( ! class_exists( 'Lithe_Shortcodes' ) ) {

    class Lithe_Shortcodes {

        /**
         * List of shortcodes.
         *
         * @var array
         */
        protected $shortcodes = array(
            'email'                => 'antispam_email_shortcode',
            'phone'                => 'antispam_phone_shortcode',
            'required'             => 'required_shortcode',
            'spinner'              => 'spinner_shortcode',
            'recaptcha-disclaimer' => 'recaptcha_disclaimer_shortcode',
            'available'            => 'available_shortcode',
        );

        /**
         * Constructs new lithe shortcodes instance.
         *
         * @return void
         */
        public function __construct() {
            $this->register();
        }

        /**
         * Registers shortcodes.
         *
         * @return Lithe_Shortcodes
         */
        public function register(): self {

            foreach ( $this->shortcodes as $shortcode => $callback ) {

                if ( is_callable( array( $this, $callback ) ) ) {

                    $callback = array( $this, $callback );

                }

                add_shortcode( "lithe-$shortcode", $callback );

            }

            return $this;
        }

        /**
         * Adds antispam shortcode for email addresses.
         *
         * @param  string|array $atts Shortcode attributes. By default empty string ''.
         * @param  string|null  $content Shortcode content.
         *
         * @return string
         */
        public function antispam_email_shortcode( $atts, ?string $content = null ): string {

            $fields = shortcode_atts( array(
                'text'    => $content,
                'subject' => null,
                'body'    => null,
                'cc'      => null,
            ), $atts );

            $href = antispambot( 'mailto:' . (string) $content ) . '?';

            foreach ( array( 'subject', 'body', 'cc' ) as $param ) {

                if ( ! is_null( $fields[ $param ] ) ) {
                    $href .= $param . '=' . $fields[ $param ] . '&';
                }

            }

            return sprintf( '<a href="%s"><span class="email hidden" style="display: none;">'
                    . __( 'Please upgrade your browser!', 'lithe' ) . '</span>%s</a>',
                    esc_attr( rtrim( $href, '?&' ) ),
                    antispambot( $fields['text'] ) );

        }

        /**
         * Adds antispam shortcode for phone numbers.
         *
         * @param  string|array $atts Shortcode attributes. By default empty string ''.
         * @param  string|null  $content Shortcode content.
         *
         * @return string
         */
        public function antispam_phone_shortcode( $atts, ?string $content = null ): string {

            $fields = shortcode_atts( array(
                'text' => $content,
            ), $atts );

            return sprintf( '<a href="%1$s">%2$s</a>',
                    esc_attr( antispambot( 'tel:' . (string) $content ) ),
                    antispambot( $fields['text'] ) );

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
            return '<span class="required has-red-color">
                <i title="' . __( 'required', 'lithe' ) . '" class="fas fa-asterisk fa-sm" aria-label="' . __( 'required', 'lithe' ) . '"></i></span>';
        }

        /**
         * Adds spinner element shortcode.
         *
         * @param  string|array $atts Shortcode attributes. By default empty string ''.
         * @param  string|null  $content Shortcode content.
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
         * @param  string|array $atts Shortcode attributes. By default empty string ''.
         * @param  string|null  $content Shortcode content.
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
         * @param  string|array $atts Shortcode attributes. By default empty string ''.
         * @param  string|null  $content Shortcode content.
         *
         * @return string|null
         */
        public function available_shortcode( $atts, ?string $content = null ): ?string {

            $fields = shortcode_atts( array(
                'from'        => null,
                'till'        => null,
                'before'      => null,
                'after'       => null,
                'exclude_cap' => 'edit_others_posts',
            ), $atts );

            if ( is_user_logged_in() && current_user_can( trim( $fields['exclude_cap'] ) ) ) {
                return '<span class="hidden-content">' . do_shortcode( $content ) . '</span>';
            }

            $now = lithe_now();

            if ( ! is_null( $fields['from'] ) ) {

                $from_dt = lithe_strtotime( $fields['from'] );

                if ( false !== $from_dt && $now < $from_dt ) $content = $fields['before'];

            }

            if ( ! is_null( $fields['till'] ) ) {

                $till_dt = lithe_strtotime( $fields['till'] );

                if ( false !== $till_dt && $now > $till_dt  ) $content = $fields['after'];

            }

            return do_shortcode( $content );

        }

    }

}
