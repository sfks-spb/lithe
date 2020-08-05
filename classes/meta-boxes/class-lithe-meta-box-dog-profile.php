<?php

if ( ! class_exists('Lithe_Meta_Box_Dog_Profile') ) {

    class Lithe_Meta_Box_Dog_Profile extends Lithe_Meta_Box {

        /**
         *
         */
        protected $post_type = 'dog';

        /**
         *
         */
        protected $meta_kses = array(
            'full_name'    => 'strip',
            'name'         => 'strip',
            'dob'          => 'strip',
            'gender'       => 'strip',
            'breed'        => 'strip',
            'pedigree_no'  => 'strip',
            'tattoo'       => 'strip',
            'microchip_no' => 'strip',
            'workbook_no'  => 'strip',
            'height'       => 'strip',
        );

        /**
         *
         */
        public static function render( $post ):void {
            wp_nonce_field( 'lithe_save_data', 'lithe_meta_nonce' );

            lithe_render( 'meta-boxes/views/view-dog-profile', array(
                'post' => $post,
            ) );
        }

        /**
         *
         */
        public function save( $post_id, $post ):void {

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

    return new Lithe_Meta_Box_Dog_Profile();

}