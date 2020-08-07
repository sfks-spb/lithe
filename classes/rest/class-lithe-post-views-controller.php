<?php

if ( ! class_exists('Lithe_Post_Views_Controller') ) {

    class Lithe_Post_Views_Controller {

        /**
         * Gets post views.
         *
         * @param  WP_REST_Request $request WordPress request instance.
         *
         * @return WP_REST_Response|WP_Error
         */
        public function get_views( WP_REST_Request $request ) {
            $post_ID = $request['id'];

            $data = array();

            if ( false === get_post_status( $post_ID ) ) {
                return rest_ensure_response( $data );
            }

            $data['post_id'] = (int) $post_ID;

            $views = get_post_meta( $post_ID, 'post_view_count', true );
            list( $data['views'], $data['views_human'] ) = $this->format_views( $views );


            return rest_ensure_response( $data );
        }

        /**
         * Sets post views.
         *
         * @param  WP_REST_Request $request WordPress request instance.
         *
         * @return WP_REST_Response|WP_Error
         */
        public function set_views( WP_REST_Request $request ) {
            $post_ID = $request['id'];

            $data = array();

            if ( false === get_post_status( $post_ID ) ) {
                return rest_ensure_response( $data );
            }

            $data['post_id'] = (int) $post_ID;

            $views = get_post_meta( $post_ID, 'post_view_count', true );
            list( $data['views'], $data['views_human'] ) = $this->format_views( $views );

            update_post_meta( $post_ID, 'post_view_count', ++$data['views'] );

            return rest_ensure_response( $data );
        }

        /**
         * Formats views as int and human readable string.
         *
         * @param  array|int $views Views counter.
         *
         * @return array
         */
        protected function format_views( $views ): array {
            $views = ( empty( $views ) ) ? 0 : $views;
            $views_human = ( $views >= 1000 ) ? round( $views / 1000, 1 ) . 'K' : $views;

            return array( $views, $views_human );
        }

    }

}
