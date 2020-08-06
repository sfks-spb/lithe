<?php

if ( ! class_exists('Lithe_Rest') ) {

    class Lithe_Rest {

        /**
         *
         */
        protected $ns = '/lithe/v1';

        /**
         *
         */
        protected $controllers = array();

        /**
         *
         */
        public function __construct() {
            $this->includes();
        }

        /**
         *
         */
        public function get_namespace(): string {
            return $this->ns;
        }

        /**
         *
         */
        public function get_root(): string {
            return get_rest_url( null, $this->ns );
        }

        /**
         *
         */
        public function get_settings(): array {
            return array(
                'root'      => $this->get_root(),
                'namespace' => $this->get_namespace(),
            );
        }

        /**
         *
         */
        protected function get( $endpoint, $handler ): void {
            $this->register_route( 'get', $endpoint, $handler );
        }

        /**
         *
         */
        protected function post( $endpoint, $handler ): void {
            $this->register_route( 'post', $endpoint, $handler );
        }

        /**
         *
         */
        protected function put( $endpoint, $handler ): void {
            $this->register_route( 'put', $endpoint, $handler );
        }

        /**
         *
         */
        protected function delete( $endpoint, $handler ): void {
            $this->register_route( 'post', $endpoint, $handler );
        }

        /**
         *
         */
        protected function register_route( $methods, $endpoint, $handler ) {

            list( $classname, $fn ) = explode( '@', $handler, 2 );

            if ( ! array_key_exists( $classname, $this->controllers ) ) {
                $this->controllers[ $classname ] = new $classname;
            }

            $callback = array( $this->controllers[ $classname ], $fn );

            if ( is_callable( $callback ) ) {

                    register_rest_route( $this->ns, '/' . $endpoint, array(
                        'methods'  => $methods,
                        'callback' => $callback,
                    ) );

            }

        }

        /**
         *
         */
        protected function includes(): void {
            $template_directory = get_template_directory();

            // controllers
            include_once $template_directory . '/classes/rest/class-lithe-post-views-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-sports-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-trainers-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-venues-controller.php';

            // routes
            // routes ($this is renamed to route for clarity)
            $route = $this;
            include_once $template_directory . '/classes/rest/routes.php';
        }

    }

}
