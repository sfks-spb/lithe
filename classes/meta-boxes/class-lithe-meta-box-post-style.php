<?php

if ( ! class_exists('Lithe_Meta_Box_Post_Style') ) {

    class Lithe_Meta_Box_Post_Style extends Lithe_Meta_Box {

        /**
         * Contains custom post type handle.
         *
         * @var string
         */
        protected $post_type = 'post';

        /**
         * Contains list of filter mapped by meta box fields.
         *
         * @var array
         */
        protected $meta_kses = array(
            'post_image_float' => 'strip',
        );

        /**
         * Outputs metabox html.
         *
         * @param  WP_Post $post Current post instance.
         *
         * @return void
         */
        public static function render( WP_Post $post ): void {
            wp_nonce_field( 'lithe_save_data', 'lithe_meta_nonce' );

            lithe_render( 'meta-boxes/views/view-post-style', array(
                'post' => $post,
            ) );
        }

        /**
         * Handles meta box save.
         *
         * @param  int     $post_id Current post id.
         * @param  WP_Post $post Current post instance.
         *
         * @return void
         */
        public function save( int $post_id, WP_Post $post ): void {

            foreach ( $this->meta_kses as $meta_key => $allowed_html ) {

                if ( ! array_key_exists( $meta_key, $_POST ) ) {
                    continue;
                }

                if ( '' === $_POST[ $meta_key ] ) {
                    delete_post_meta( $post_id, $meta_key );
                } else {
                    update_post_meta( $post_id, $meta_key, wp_kses( $_POST[ $meta_key ], $allowed_html ) );
                }

            }

        }

    }

    return new Lithe_Meta_Box_Post_Style();

}
