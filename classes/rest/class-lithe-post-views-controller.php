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

            $views = get_post_meta( $post_ID, 'post_view_count', true );

            $data['post_id'] = (int) $post_ID;
            $data['views'] = $this->views_to_integer( $views );
            $data['views_string'] = $this->views_to_human_readable( $data['views'] );

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

            $views = get_post_meta( $post_ID, 'post_view_count', true );

            $data['post_id'] = (int) $post_ID;
            $data['views'] = $this->views_to_integer( $views ) + 1;
            $data['views_string'] = $this->views_to_human_readable( $data['views'] );

            update_post_meta( $post_ID, 'post_view_count', $data['views'] );

            return rest_ensure_response( $data );
        }

        /**
         * Formats views as integer.
         *
         * @param  array|string $views Views count.
         *
         * @return int
         */
        protected function views_to_integer( $views ): int {
            if ( empty( $views ) ) {
                return 0;
            }

            return (int) $views;
        }

        /**
         * Formats views as human readable string.
         *
         * @param  int $views Views count.
         *
         * @return string
         */
        protected function views_to_human_readable( int $views ): string {
            if ( $views >= 1000 ) {
                return round( $views / 1000, 1 ) . 'K';
            }

            return (string) $views;
        }

    }

}
