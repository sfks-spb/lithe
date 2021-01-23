<?php

if ( ! class_exists('Lithe_Meta_Box_Dog_Profile') ) {

    class Lithe_Meta_Box_Dog_Profile extends Lithe_Meta_Box {

        /**
         * Contains custom post type handle.
         *
         * @var string
         */
        protected $post_type = 'dog';

        /**
         * Contains list of filter mapped by meta box fields.
         *
         * @var array
         */
        protected $meta_kses = array(
            'full_name'    => 'strip',
            'dob'          => 'strip',
            'gender'       => 'strip',
            'pedigree_no'  => 'strip',
            'tattoo'       => 'strip',
            'microchip_no' => 'strip',
            'workbook_no'  => 'strip',
            'height'       => 'strip',
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

            $breed = get_the_terms( $post, 'breed' );
            $breed_id = ( empty( $breed ) ) ? null : $breed[0]->term_id;

            lithe_render( 'meta-boxes/views/view-dog-profile', array(
                'post'     => $post,
                'gender'   => get_post_meta($post->ID, 'gender', true),
                'breed_id' => $breed_id,
                'breeds'   => get_terms( array( 'taxonomy' => 'breed', 'hide_empty' => false ) ),
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

            // handle breed as post term
            if ( array_key_exists( 'breed', $_POST ) && $_POST['breed'] !== '' ) {

                if ( is_numeric( $_POST['breed'] ) ) {
                    // convert to array of IDs
                    $_POST['breed'] = array( (int) $_POST['breed'] );
                }

                wp_set_post_terms( $post_id, $_POST['breed'], 'breed', false );

            }

        }

    }

    return new Lithe_Meta_Box_Dog_Profile();

}
