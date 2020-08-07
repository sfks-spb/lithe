<?php

if ( ! class_exists('Lithe_Meta_Box_Trainer_Timetable') ) {

    class Lithe_Meta_Box_Trainer_Timetable extends Lithe_Meta_Box {

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
            'sports'    => 'strip',
            'timetable' => 'strip',
        );

        /**
         * Outputs metabox html
         *
         * @param  WP_Post $post
         *
         * @return void
         */
        public static function render( WP_Post $post ): void {
            $venues = get_the_terms( $post->ID, 'venue' );
            $sports = get_the_terms( $post->ID, 'sport' );

            if ( empty( $venues ) || empty( $sports ) ) {
                _e( 'Trainer timetable will be available after selecting sports and venues.', 'lithe' );
                return;
            }

            wp_nonce_field( 'lithe_save_data', 'lithe_meta_nonce' );

            lithe_render( 'meta-boxes/views/view-trainer-timetable', array(
                'post'   => $post,
                'venues' => $venues,
                'sports' => $sports,
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

                foreach ( $_POST[ $meta_key ] as $meta_key_postfix => $new_meta_values ) {

                    $new_meta_key = $meta_key . '_' . $meta_key_postfix;

                    delete_post_meta( $post_id, $new_meta_key );

                    foreach ( $new_meta_values as $new_meta_value ) {
                        add_post_meta( $post_id, $new_meta_key, $new_meta_value );
                    }

                }

            }

        }

    }

    return new Lithe_Meta_Box_Trainer_Timetable();

}
