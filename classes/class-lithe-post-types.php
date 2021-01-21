<?php

if ( ! class_exists( 'Lithe_Post_Types' ) ) {

    class Lithe_Post_Types {

        /**
         * Contains list of custom post types.
         *
         * @var array
         */
        protected $post_types = array( 'trainer', 'dog', 'trial' );

        /**
         * Constructs new lithe post types instance.
         *
         * @return void
         */
        public function __construct() {
            $this->includes();

            add_action( 'init', array( $this, 'register_post_types' ) );
        }

        /**
         * Registers custom post types.
         *
         * @return void
         */
        public function register_post_types(): void {

            foreach ( $this->post_types as $post_type ) {

                $classname = 'Lithe_Post_Type_' . ucwords( $post_type );

                if ( class_exists( $classname ) ) {
                    ( new $classname )->register();
                }

            }

        }

        /**
         * Includes dependencies.
         *
         * @return void
         */
        protected function includes(): void {
            $post_types_directory = trailingslashit( lithe_get_classes_directory_path( 'post-types' ) );

            include $post_types_directory . 'class-lithe-post-type.php';

            foreach( $this->post_types as $post_type ) {

                include $post_types_directory . 'class-lithe-post-type-' . strtolower( $post_type ) . '.php';

            }
        }

    }

    return new Lithe_Post_Types();

}
