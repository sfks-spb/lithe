<?php

if ( ! class_exists('Lithe_Venues_Controller') ) {

    class Lithe_Venues_Controller {

        /**
         *
         */
        public function get_venue( $request ) {

            $venue_ID = $request['id'];

            $data = array();

            if ( ! $venue_ID ) {
                return rest_ensure_response( $data );
            }

            $venue = get_term( $venue_ID, 'venue' );

            if ( empty( $venue ) ) {
                return rest_ensure_response( $data );
            }

            $data = $this->get_venue_data( $venue );

            return rest_ensure_response( $data );

        }

        /**
         *
         */
        public function get_venues( $request ) {

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
         *
         */
        protected function filter_venues( array &$venue, $sport_ID ): bool {
            $trainers = $this->get_trainers_for_venue( $venue, $sport_ID );

            return ( ! empty( $trainers ) ) ? true : false;
        }

        /**
         *
         */
        protected function filter_venues_and_include_trainers( array &$venue, $sport_ID ): bool {

            $trainers = $this->get_trainers_for_venue( $venue, $sport_ID );

            foreach ( $trainers as &$trainer ) {
                $trainer = $this->get_trainer_data( $trainer, $venue['id'], $sport_ID );
            }

            $venue['trainers'] = $trainers;

            return ( ! empty( $trainers ) ) ? true : false;
        }

        /**
         *
         */
        protected function get_trainers_for_venue( $venue, $sport_ID ) {
            return get_posts( array(
                'post_type'  => 'trainer',
                'meta_key'   => 'sports_' . $venue['id'],
                'meta_value' => $sport_ID,
            ) );
        }

        /**
         *
         */
        protected function get_trainer_data( WP_Post $trainer, $venue_ID, $sport_ID ): array {

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
                'timetable'    => get_post_meta( $trainer->ID, 'timetable_' . $venue_ID . '-' . $sport_ID, true ),
            );

            foreach ( $meta_fields as $field ) {
                $data[ $field ] = esc_html( get_post_meta( $trainer->ID, $field, true ) );
            }

            return $data;
        }

        /**
         *
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
