<?php

if ( ! class_exists('Lithe_Sports_Controller') ) {

    class Lithe_Sports_Controller {

        /**
         * Gets sports
         *
         * @param  WP_Request $request
         *
         * @return WP_REST_Response|WP_Error
         */
        public function get_sports( WP_Request $request ) {

            $sports = get_terms( array(
                'taxonomy'   => 'sport',
                'order'      => 'DESC',
                'hide_empty' => ( $request['hide_empty'] ? false : true ),
            ) );

            foreach ( $sports as &$sport ) {
                $sport = $this->get_sport_data( $sport );
            }

            return rest_ensure_response( $sports );

        }

        /**
         * Gets sport data from term object
         *
         * @param  WP_Term $sport
         *
         * @return array
         */
        protected function get_sport_data( WP_Term $sport ): array {

            $data = array(
                'id'          => $sport->term_id,
                'name'        => esc_html( $sport->name ),
                'description' => esc_html( $sport->description ),
                'trainers'    => $sport->count,
            );

            return $data;
        }

    }

}
