<?php

if ( ! class_exists('Lithe_Trainers_Controller') ) {

    class Lithe_Trainers_Controller {

        /**
         * Gets trainers.
         *
         * @param  WP_REST_Request $request WordPress request instance.
         *
         * @return WP_REST_Response|WP_Error
         */
        public function get_trainers( WP_REST_Request $request ) {

            $tax_query = array();

            if ( ! empty( $request['venue_id'] ) ) $tax_query[] = $this->get_tax_query( 'venue', $request['venue_id'] );
            if ( ! empty( $request['sport_id'] ) ) $tax_query[] = $this->get_tax_query( 'sport', $request['sport_id'] );

            $trainers = get_posts( array(
                'post_type' => 'trainer',
                'tax_query' => $tax_query,
                'nopaging'  => true,
                'orderby'   => 'rand',
            ) );

            foreach ( $trainers as &$trainer ) $trainer = $this->get_trainers_data( $trainer, $request['venue_id'], $request['sport_id'] );

            return rest_ensure_response( array(
                'data' => $trainers,
                'meta' => array(
                    'count' => count( $trainers ),
                ),
            ) );

        }

        /**
         * Constructs tax query item.
         *
         */
        protected function get_tax_query( string $taxonomy, int $term_id ): array {

            return array(
                'taxonomy'         => $taxonomy,
                'field'            => 'term_id',
                'terms'            => $term_id,
                'include_children' => false,
            );

        }

        /**
         * Gets trainer data.
         *
         * @param  WP_Post  $trainer Current trainer post instance.
         * @param  int|null $venue_id Current venue id.
         * @param  int|null $sport_id Current sport id.
         *
         * @return array
         */
        protected function get_trainers_data( WP_Post $trainer, ?int $venue_id, ?int $sport_id ): array {

            static $meta_fields = array(
                'first_name',
                'middle_name',
                'last_name',
                'phone',
                'email',
                'social',
                'organization',
            );

            $data = array(
                'id'           => $trainer->ID,
                'display_name' => esc_html( $trainer->post_title ),
            );

            foreach ( $meta_fields as $field ) {
                $data[ $field ] = esc_html( get_post_meta( $trainer->ID, $field, true ) );
            }

            if ( ! empty( $venue_id ) && ! empty( $sport_id ) ) {
                $data['timetable'] = nl2br( get_post_meta( $trainer->ID, 'timetable_' . $venue_id . '-' . $sport_id, true ) );
            }

            $data['sports'] = get_the_terms( $trainer, 'sport' );

            $data['photo'] = $this->get_the_photo( $trainer );

            return $data;

        }

        /**
         * Gets trainer photo data.
         *
         * @param  WP_Post Current trainer post instance.
         *
         * @return array|null
         */
        protected function get_the_photo( WP_Post $trainer ): ?array {

            $photo_id = get_post_meta( $trainer->ID, 'photo_id', true );
            $photo = wp_get_attachment_image_src( $photo_id, array( 192, 192 ) );

            if ( is_array( $photo ) ) {

                return array(
                    'src'    => $photo[0],
                    'width'  => $photo[1],
                    'height' => $photo[2]
                );

            }

            return null;

        }

    }

}
