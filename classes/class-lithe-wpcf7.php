<?php

if ( ! class_exists( 'Lithe_WPCF7' ) ) {

    class Lithe_WPCF7 {

        /**
         * Contains list of captured forms.
         *
         * @var array
         */
        protected $forms = array();

        /**
         * Constructs new lithe wpcf7 instance.
         *
         * @return void
         */
        public function __construct() {
            add_filter( 'wpcf7_form_elements', 'do_shortcode' );
            add_filter( 'wpcf7_display_message', array( $this, 'add_icons_for_message' ), 10, 2 );
            add_filter( 'wpcf7_form_action_url', array( $this, 'save_current_form_config' ) );
            add_filter( 'wpcf7_posted_data', array( $this, 'handle_meta_posted_data' ), 5 );
            add_filter( 'wpcf7_posted_data', array( $this, 'handle_utm_posted_data' ), 6 );
            add_filter( 'wpcf7_posted_data', array( $this, 'add_container_url_with_utm' ), 7 );
            add_action( 'wpcf7_init', array( $this, 'add_custom_form_tags' ) );
            add_action( 'wpcf7_after_save', array( $this, 'save_form_meta_fields' ), 10, 1 );
            add_action( 'wp_print_footer_scripts', array( $this, 'add_config_data_storage' ), 8 );
        }

        /**
         * Adds custom form tags.
         *
         * @return void
         */
        public function add_custom_form_tags(): void {
            wpcf7_add_form_tag(
                    'meta',
                    array( $this, 'meta_form_tag_handler' ),
                    array( 'name-attr' => true ) );

            wpcf7_add_form_tag(
                    'utm',
                    array( $this, 'utm_form_tag_handler' ) );
        }

        /**
         * Handles meta form tag HTML output.
         *
         * @param  WPCF7_FormTag $tag Custom tag instance.
         *
         * @return string
         */
        public function meta_form_tag_handler( WPCF7_FormTag $tag ): string {

            $atts = array(
                'type'  => 'hidden',
                'name'  => 'form_meta[]',
                'value' => $tag->name,
            );

            return sprintf( '<input %s />', wpcf7_format_atts( $atts ) );
        }

        /**
         * Handles utm form tag HTML output.
         *
         * @param  WPCF7_FormTag $tag Custom tag instance.
         *
         * @return string
         */
        public function utm_form_tag_handler( WPCF7_FormTag $tag ): string {

            $allowed_opts = array(
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_content',
                'utm_term',
            );

            $inputs = array();

            foreach ( $tag->options as $option ) {

                $option_parts = explode( ':', $option, 2 );

                $name = 'utm_' . $option_parts[0];

                if ( ! in_array( $name, $allowed_opts ) || ! isset( $option_parts[1] ) ) continue;

                $atts = array(
                    'type'  => 'hidden',
                    'name'  => $name,
                    'value' => $option_parts[1],
                );

                $inputs[] = sprintf( '<input %s />', wpcf7_format_atts( $atts ) );

            }

            return implode( "\n", $inputs );
        }

        /**
         * Injects meta values into the posted data.
         *
         * @param  array $posted_data Data posted via form.
         *
         * @return array
         */
        public function handle_meta_posted_data( array $posted_data ): array {

            if ( ! array_key_exists( 'form_meta', $posted_data ) ) {
                return $posted_data;
            }

            $form = WPCF7_ContactForm::get_current();
            $form_meta = get_post_meta( $form->id(), '_lithe_wpcf7_form_meta', true );

            if ( ! empty ( $form_meta ) ) {

                foreach ( $posted_data['form_meta'] as $name ) {

                    if ( array_key_exists( $name, $form_meta ) ) {
                        $posted_data[ $name ] = $form_meta[ $name ];
                    }

                }

            }

            unset( $posted_data['form_meta'] );

            return $posted_data;

        }

        /**
         * Places utm query string into the posted data.
         *
         * @param  array $posted_data Data posted via form.
         *
         * @return array
         */
        public function handle_utm_posted_data( array $posted_data ): array {

            $allowed_opts = array(
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_content',
                'utm_term',
            );

            $query = array();

            foreach ( $allowed_opts as $opt ) {
                if ( array_key_exists( $opt, $posted_data ) ) {
                    $query[ $opt ] = $posted_data[ $opt ];
                }
            }

            if ( ! empty( $query ) ) {
                $posted_data['utm_query'] = esc_attr( http_build_query( $query ) );
            }

            return $posted_data;

        }

        /**
         * Places container URL with utm query.
         *
         * @param  array $posted_data Data posted via form.
         *
         * @return array
         */
        public function add_container_url_with_utm( array $posted_data ): array {

            if ( array_key_exists( '_wpcf7_container_post', $_POST ) ) {

                $container_url = get_permalink( $_POST[ '_wpcf7_container_post' ] );

                if ( array_key_exists( 'utm_query', $posted_data ) ) {

                    $query_start = '?';

                    if ( false !== strpos( $container_url, '?' ) ) {
                        $query_start = '&';
                    }

                    $container_url .= $query_start . $posted_data['utm_query'];

                }

                $posted_data['container_url'] = $container_url;

            }

            return $posted_data;
        }

        /**
         * Stores current form config.
         *
         * @param  string $url Current WPCF7 form action url.
         * @return string
         */
        public function save_current_form_config( $url ) {

            $form = WPCF7_ContactForm::get_current();

            if ( ! empty( $form ) ) {

                $form_meta = get_post_meta( $form->id(), '_lithe_wpcf7_form_meta', true );

                $this->forms[ $form->id() ] = array(
                    'title'              => $form->title(),
                    'isRegistrationForm' => array_key_exists( 'reg', $form_meta ),
                );

            }

            return $url;
        }

        /**
         * Adds JS object, that contains form data.
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
         * Saves stored meta fields.
         *
         * @param  WPCF7_ContactForm $instance Current contact form instance.
         *
         * @return void
         */
        public function save_form_meta_fields( WPCF7_ContactForm $form ): void {

            $form_meta = array();

            $meta_tags = $form->scan_form_tags( array( 'type' => 'meta' ) );

            if ( ! empty( $meta_tags ) ) {

                foreach ( $meta_tags as $tag ) {

                    $form_meta[ $tag->name ] = isset( $tag->values[0] ) ?
                        $tag->values[0] : '';

                }

            }

            update_post_meta( $form->id(), '_lithe_wpcf7_form_meta', $form_meta );

        }

        /**
         * Adds FontAwesome icon to form responses.
         *
         * @param  string $message Message content.
         * @param  string $status Status string.
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

    }

    return new Lithe_WPCF7();
}
