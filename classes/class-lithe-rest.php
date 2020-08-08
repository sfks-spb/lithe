<?php

if ( ! class_exists('Lithe_Rest') ) {

    class Lithe_Rest {

        /**
         * Contains rest api namespace.
         *
         * @var string
         */
        protected $ns = 'lithe/v1';

        /**
         * Contains list of controller instances.
         *
         * @var array
         */
        protected $controllers = array();

        /**
         * Constructs new lithe REST instance.
         *
         * @return void
         */
        public function __construct() {
            $this->includes();
            add_action( 'rest_api_init', array( $this, 'register_routes' ) );
        }

        /**
         * Registers routes.
         *
         * @return void
         */
        public function register_routes(): void {
            $route = $this;

            include_once get_template_directory() . '/classes/rest/routes.php';
        }

        /**
         * Gets REST namespace.
         *
         * @return string
         */
        public function get_namespace(): string {
            return $this->ns;
        }

        /**
         * Gets REST root url.
         *
         * @return string
         */
        public function get_root(): string {
            return get_rest_url( null, $this->ns );
        }

        /**
         * Gets REST settings.
         *
         * @return array
         */
        public function get_settings(): array {
            return array(
                'root'      => $this->get_root(),
                'namespace' => $this->get_namespace(),
            );
        }

        /**
         * Registers new get route.
         *
         * @param  string          $endpoint REST endpoint path.
         * @param  string|callable $callback Endpoint callback.
         *
         * @return void
         */
        public function get( string $endpoint, $callback ): void {
            $this->register_route( 'get', $endpoint, $callback );
        }

        /**
         * Registers new post route.
         *
         * @param  string          $endpoint REST endpoint path.
         * @param  string|callable $callback Endpoint callback.
         *
         * @return void
         */
        public function post( string $endpoint, $callback ): void {
            $this->register_route( 'post', $endpoint, $callback );
        }

        /**
         * Registers new put route.
         *
         * @param  string          $endpoint REST endpoint path.
         * @param  string|callable $callback Endpoint callback.
         *
         * @return void
         */
        public function put( string $endpoint, $callback ): void {
            $this->register_route( 'put', $endpoint, $callback );
        }

        /**
         * Registers new delete route.
         *
         * @param  string          $endpoint REST endpoint path.
         * @param  string|callable $callback Endpoint callback.
         *
         * @return void
         */
        public function delete( string $endpoint, $callback ): void {
            $this->register_route( 'post', $endpoint, $callback );
        }

        /**
         * Registers new REST route.
         *
         * @param  string          $methods REST request methods.
         * @param  string          $endpoint REST endpoint path.
         * @param  string|callable $callback Endpoint callback.
         *
         * @return void
         */
        protected function register_route( string $methods, string $endpoint, $callback ): void {

            if ( is_string( $callback ) ) {

                list( $classname, $fn ) = explode( '@', $callback, 2 );

                if ( ! array_key_exists( $classname, $this->controllers ) ) {
                    $this->controllers[ $classname ] = new $classname;
                } 

                $callback = array( $this->controllers[ $classname ], $fn );

            }

            if ( is_callable( $callback ) ) {

                    register_rest_route( $this->ns, '/' . $endpoint, array(
                        'methods'  => $methods,
                        'callback' => $callback,
                    ) );

            }

        }

        /**
         * Includes dependencies.
         *
         * @return void
         */
        protected function includes(): void {
            $template_directory = get_template_directory();

            include_once $template_directory . '/classes/rest/class-lithe-post-views-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-sports-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-trainers-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-venues-controller.php';
        }

    }

}
