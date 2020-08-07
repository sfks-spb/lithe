<?php

if ( ! class_exists('Lithe_Rest') ) {

    class Lithe_Rest {

        /**
         * Contains rest api namespace
         *
         * @var string
         */
        protected $ns = 'lithe/v1';

        /**
         * Contains list of controller instances
         *
         * @var array
         */
        protected $controllers = array();

        /**
         * Constructs new lithe REST instance
         *
         * @return void
         */
        public function __construct() {
            $this->includes();
        }

        /**
         * Gets REST namespace
         *
         * @return string
         */
        public function get_namespace(): string {
            return $this->ns;
        }

        /**
         * Gets REST root url
         *
         * @return string
         */
        public function get_root(): string {
            return get_rest_url( null, $this->ns );
        }

        /**
         * Gets REST settings
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
         * Registers new get route
         *
         * @param  string $endpoint
         * @param  string $handler
         *
         * @return void
         */
        public function get( string $endpoint, string $handler ): void {
            $this->register_route( 'get', $endpoint, $handler );
        }

        /**
         * Registers new post route
         *
         * @param  string $endpoint
         * @param  string $handler
         *
         * @return void
         */
        public function post( string $endpoint, string $handler ): void {
            $this->register_route( 'post', $endpoint, $handler );
        }

        /**
         * Registers new put route
         *
         * @param  string $endpoint
         * @param  string $handler
         *
         * @return void
         */
        public function put( string $endpoint, string $handler ): void {
            $this->register_route( 'put', $endpoint, $handler );
        }

        /**
         * Registers new delete route
         *
         * @param  string $endpoint
         * @param  string $handler
         *
         * @return void
         */
        public function delete( string $endpoint, string $handler ): void {
            $this->register_route( 'post', $endpoint, $handler );
        }

        /**
         * Registers new REST route
         *
         * @param  string $methods
         * @param  string $endpoint
         * @param  string $handler
         *
         * @return void
         */
        protected function register_route( string $methods, string $endpoint, string $handler ): void {

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
         * Includes dependencies
         *
         * @return void
         */
        protected function includes(): void {
            $template_directory = get_template_directory();

            // controllers
            include_once $template_directory . '/classes/rest/class-lithe-post-views-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-sports-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-trainers-controller.php';
            include_once $template_directory . '/classes/rest/class-lithe-venues-controller.php';

            // routes ($this is renamed to route for clarity)
            $route = $this;
            include_once $template_directory . '/classes/rest/routes.php';
        }

    }

}
