<?php

if ( ! class_exists('Lithe_Meta_Box_Trainer_Profile') ) {

    class Lithe_Meta_Box_Trainer_Profile extends Lithe_Meta_Box {

        /**
         * Contains custom post type handle
         *
         * @var string
         */
        protected $post_type = 'trainer';

        /**
         * Contains list of filter mapped by meta box fields
         *
         * @var array
         */
        protected $meta_kses = array(
            'first_name'   => 'strip',
            'middle_name'  => 'strip',
            'last_name'    => 'strip',
            'organization' => 'strip',
            'phone'        => 'strip',
            'email'        => 'strip',
            'social'       => 'strip',
        );

        /**
         * Outputs metabox html
         *
         * @param  WP_Post $post
         *
         * @return void
         */
        public static function render( WP_Post $post ): void {
            wp_nonce_field( 'lithe_save_data', 'lithe_meta_nonce' );

            lithe_render( 'meta-boxes/views/view-trainer-profile', array(
                'post' => $post,
            ) );
        }

        /**
         * Handles meta box save
         *
         * @param  int     $post_id
         * @param  WP_Post $post
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

    return new Lithe_Meta_Box_Trainer_Profile();

}
