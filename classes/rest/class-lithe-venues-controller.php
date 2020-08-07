<?php

if ( ! class_exists('Lithe_Venues_Controller') ) {

    class Lithe_Venues_Controller {

        /**
         * Gets venue by id.
         *
         * @param  WP_REST_Request $request WordPress request instance.
         *
         * @return WP_REST_Response|WP_Error
         */
        public function get_venue( WP_REST_Request $request ) {

            $venue_id = $request['id'];

            $data = array();

            if ( ! $venue_id ) {
                return rest_ensure_response( $data );
            }

            $venue = get_term( $venue_id, 'venue' );

            if ( empty( $venue ) ) {
                return rest_ensure_response( $data );
            }

            $data = $this->get_venue_data( $venue );

            return rest_ensure_response( $data );

        }

        /**
         * Gets venues by sport id.
         *
         * @param  WP_REST_Request $request WordPress request instance.
         *
         * @return WP_REST_Response|WP_Error
         */
        public function get_venues( WP_REST_Request $request ) {

            $sport_ID = $request['sport_id'];

            $venues = get_terms( array(
                'taxonomy' => 'venue',
                'order'    => 'DESC',
            ) );

            foreach ( $venues as $index => &$venue ) {
                $venue = $this->get_venue_data( $venue );

                if ( ! $sport_ID && 1 != $request['include_trainers'] ) continue;

                $fn = ( 1 == $request['include_trainers'] ) ? 'filter_venues_and_include_trainers' : 'filter_venues';

                if ( ! $this->$fn( $venue, $sport_ID ) ) {
                    unset( $venues[ $index ] );
                }
            }

            return rest_ensure_response( $venues );

        }

        /**
         * Filters venues by sport id.
         *
         * @param  array    &$venue Venue data.
         * @param  int|null $sport_id Current sport id.
         *
         * @return bool
         */
        protected function filter_venues( array &$venue, ?int $sport_id ): bool {
            $trainers = $this->get_trainers_for_venue( $venue, $sport_id );

            return ( ! empty( $trainers ) ) ? true : false;
        }

        /**
         * Filters venues by sport id and includes trainers.
         *
         * @param  array    &$venue Venue data.
         * @param  int|null $sport_id Current sport id.
         *
         * @return bool
         */
        protected function filter_venues_and_include_trainers( array &$venue, ?int $sport_id ): bool {

            $trainers = $this->get_trainers_for_venue( $venue, $sport_id );

            foreach ( $trainers as &$trainer ) {
                $trainer = $this->get_trainer_data( $trainer, $venue['id'], $sport_id );
            }

            $venue['trainers'] = $trainers;

            return ( ! empty( $trainers ) ) ? true : false;
        }

        /**
         * Gets trainer list for current venue.
         *
         * @param  array    $venue Venue data.
         * @param  int|null $sport_id Current sport id.
         *
         * @return array
         */
        protected function get_trainers_for_venue( array $venue, ?int $sport_id ): array {
            return get_posts( array(
                'post_type'  => 'trainer',
                'meta_key'   => 'sports_' . $venue['id'],
                'meta_value' => $sport_id,
            ) );
        }

        /**
         * Gets trainer data from post object.
         *
         * @param  WP_Post  $trainer Current trainer post instance.
         * @param  int      $venue_id Current venue id.
         * @param  int|null $sport_id Current sport id.
         *
         * @return array
         */
        protected function get_trainer_data( WP_Post $trainer, int $venue_id, ?int $sport_id ): array {

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
                'timetable'    => get_post_meta( $trainer->ID, 'timetable_' . $venue_id . '-' . $sport_id, true ),
            );

            foreach ( $meta_fields as $field ) {
                $data[ $field ] = esc_html( get_post_meta( $trainer->ID, $field, true ) );
            }

            return $data;
        }

        /**
         * Gets venue data from term object.
         *
         * @param  WP_Term $venue Venue term instance.
         *
         * @return array
         */
        protected function get_venue_data( WP_Term $venue ): array {

            static $meta_fields = array(
                'address',
                'link',
                'coords',
            );

            $data = array(
                'id'          => $venue->term_id,
                'name'        => esc_html( $venue->name ),
                'description' => esc_html( $venue->description ),
            );

            foreach ( $meta_fields as $field ) {
                $data[ $field ] = esc_html( get_term_meta( $venue->term_id, $field, true ) );
            }

            return $data;

        }

    }

}
