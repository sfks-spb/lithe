<?php

if ( ! class_exists( 'Lithe_WPCF7' ) ) {

    class Lithe_WPCF7 {

        /**
         * Contains list of captured forms
         *
         * @var array
         */
        protected $forms = array();

        /**
         * Constructs new lithe wpcf7 instance
         *
         * @return void
         */
        public function __construct() {
            add_filter( 'wpcf7_display_message', array( $this, 'add_icons_for_message' ), 10, 2 );
            add_filter( 'wpcf7_form_elements', array( $this, 'do_form_shortcodes' ) );
            add_filter( 'wpcf7_form_action_url', array( $this, 'save_current_form_config' ) );
            add_action( 'wp_print_footer_scripts', array( $this, 'add_config_data_storage' ), 8 );
        }

        /**
         * Stores current form config
         *
         * @param  string $url
         * @return string
         */
        public function save_current_form_config( $url ) {

            $form = WPCF7_ContactForm::get_current();

            $unit_tag = $this->unit_tag_from_url( $url );
            $postID = (int) ltrim( $unit_tag[2], 'p' );

            $is_registration_form = get_post_meta( $postID, 'registration_form', true );

            if ( ! empty( $form ) ) {

                $this->forms[ $form->id() ] = array(
                    'title'              => $form->title(),
                    'isRegistrationForm' => ! empty( $is_registration_form ),
                );

            }

            return $url;
        }

        /**
         * Adds JS object, that contains form data
         *
         * @return void
         */
        public function add_config_data_storage(): void {

            if ( ! empty( $this->forms ) ) {

                wp_register_script( 'lithe-wpcf7', '' );
                wp_enqueue_script( 'lithe-wpcf7' );
                wp_add_inline_script( 'lithe-wpcf7', 'var _lithe_wpcf7 = ' . wp_json_encode( $this->forms ) . ';' );

            }

        }

        /**
         * Handles shortcodes in forms
         *
         * @param  string $form - form body
         *
         * @return string
         */
        public function do_form_shortcodes( string $form ): string {
            return do_shortcode( $form );
        }

        /**
         * Adds FontAwesome icon to form responses
         *
         * @param  string $message - message body
         * @param  string $status - status string
         *
         * @return string
         */
        public function add_icons_for_message( $message, $status ) {

            switch( $status ) {
                case 'mail_sent_ok':
                    $message = '<i class="fas fa-check"></i><span class="message">' . $message . '</span>';
                    break;
                case 'mail_sent_ng':
                case 'validation_error':
                case 'spam':
                    $message = '<i class="fas fa-exclamation-triangle"></i><span class="message">' . $message . '</span>';
                    break;
            }

            return $message;
        }

        /**
         * Constructs unit tag array from action url
         *
         * @param  string $url
         *
         * @return array
         */
        protected function unit_tag_from_url( string $url ): array {
            $url_parts = explode( '#', $url, 2 );
            return explode( '-', $url_parts[1] );
        }

    }

    return new Lithe_WPCF7();
}
