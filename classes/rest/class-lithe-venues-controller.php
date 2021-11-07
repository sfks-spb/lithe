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

            $venue = get_term( $request['id'], 'venue' );

            if ( empty( $venue ) ) {
                return rest_ensure_response( array() );
            }

            return rest_ensure_response( array(
                'data' => $this->get_venue_data( $venue ),
                'meta' => array(),
            ) );

        }

        /**
         * Gets venues.
         *
         * @param  WP_REST_Request $request WordPress request instance.
         *
         * @return WP_REST_Response|WP_Error
         */
        public function get_venues( WP_REST_Request $request ) {

            $venues = get_terms( array(
                'taxonomy' => 'venue',
                'order'    => 'DESC',
            ) );

            $filtered = array();

            foreach ( $venues as $index => $venue ) {

                if ( $request['sport_id'] && ! $this->venue_has_trainers( $venue, $request['sport_id'] ) ) {
                    continue;
                }

                $filtered[] = $this->get_venue_data( $venue );

            }

            return rest_ensure_response( array(
                'data' => $filtered,
                'meta' => array(
                    'placemarks' => $this->get_placemarks( $filtered ),
                    'count'      => count( $filtered ),
                ),
            ) );

        }

        /**
         * Checks if venue has trainers for specific sport.
         *
         * @param  WP_Term  $venue Venue term.
         * @param  int|null $sport_id Current sport id.
         *
         * @return bool
         */
        protected function venue_has_trainers( WP_Term $venue, ?int $sport_id ): bool {

            $wp_query = new WP_Query( array(
                'post_type'  => 'trainer',
                'meta_key'   => 'sports_' . $venue->term_id,
                'meta_value' => $sport_id,
            ) );

            return $wp_query->have_posts();

        }

        /**
         * Gets placemarks for venues.
         *
         * @param  array $data Venues data.
         *
         * @return array
         */
        protected function get_placemarks( array $data ): array {
            $features = array();

            foreach ( $data as $venue_data ) {
                $features[] = $this->get_feature_for_venue( $venue_data );
            }

            return array(
                'type'     => 'FeatureCollection',
                'features' => $features,
            );
        }

        /**
         * Gets placemark features for venue data.
         *
         * @param  array $venue_data Venue data.
         *
         * @return array
         */
        protected function get_feature_for_venue( array $venue_data ): array {

            return array(
                'type'       => 'Feature',
                'id'         => $venue_data['id'],
                'geometry'   => $this->get_placemark_geometry_for_venue( $venue_data ),
                'properties' => $this->get_placemark_properties_for_venue( $venue_data ),
            );

        }

        /**
         * Gets placemark geometry for venue data.
         *
         * @param  array $venue_data
         *
         * @return array
         */
        protected function get_placemark_geometry_for_venue( array $venue_data ): array {
            list( $lat, $lon ) = explode( ', ', $venue_data['coords'] );

            return array(
                'type'        => 'Point',
                'coordinates' => array( (double) $lat, (double) $lon ),
            );
        }

        /**
         * Gets placemark properties for venue data.
         *
         * @param  array $venue_data
         *
         * @return array
         */
        protected function get_placemark_properties_for_venue( array $venue_data ): array {

            $ballon_header = '<b>' . $venue_data['name'] . '</b><br /><span class="venue-type">' .
                __( 'Dog Training Ground', 'lithe' ) . '</span>';

            $ballon_body = '<b>' . __( 'Address', 'lithe' ) . '</b>: ' . $venue_data['address'] . '<br>' .
                '<b>' . __( 'Trainers on the ground', 'lithe' ) . '</b>: ' . $venue_data['trainers'] . '<br>' .
                '<ul class="sport-tags"><li>' . implode( '</li><li>', $venue_data['sports'] ) . '</li></ul>';

            return array(
                'balloonContentHeader' => $ballon_header,
                'balloonContentBody'   => $ballon_body,
                'balloonContentFooter' => __( 'Updated at', 'lithe' ) . ':<br>' . $venue_data['updated_at'],
                'hintContent'          => __( 'Dog Training Ground', 'lithe' ) . ' "' . $venue_data['name'] . '"',
            );

        }

        /**
         * Gets venue sports.
         *
         * @param  array $venue_data Venue data.
         *
         * @global wpdb $wpdb WordPress database instance.
         *
         * @return array
         */
        protected function get_sports_for_venue( array $venue_data ): array {

            global $wpdb;

            $sport_ids = $wpdb->get_col( $wpdb->prepare( "
                SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
                WHERE pm.meta_key = %s
                ORDER BY pm.meta_value DESC",
                'sports_' . $venue_data['id'] ) );

            if ( empty( $sport_ids ) ) return array();

            return get_terms( array(
                'taxonomy' => 'sport',
                'include'  => $sport_ids,
                'fields'   => 'id=>name',
                'orderby'  => 'id',
            ) );

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
                'trainers'    => $venue->count,
            );

            foreach ( $meta_fields as $field ) {
                $data[ $field ] = esc_html( get_term_meta( $venue->term_id, $field, true ) );
            }

            $updated_at = get_term_meta( $venue->term_id, 'updated_at', true );
            $data['updated_at'] = wp_date( lithe_date_format(), (int) $updated_at );
            $data['sports'] = $this->get_sports_for_venue( $data );

            return $data;

        }

    }

}
