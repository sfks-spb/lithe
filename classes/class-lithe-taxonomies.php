<?php

if ( ! class_exists( 'Lithe_Taxonomies' ) ) {

    class Lithe_Taxonomies {

        /**
         * Contains list of custom taxonomies.
         *
         * @var array
         */
        protected $taxonomies = array( 'venue', 'sport', 'breed' );

        /**
         * Constructs new lithe taxonomies instance.
         *
         * @return void
         */
        public function __construct() {
            $this->includes();

            add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
        }

        /**
         * Registers custom taxonomies.
         *
         * @return void
         */
        public function register_taxonomies(): void {

            // add categories support for pages
            register_taxonomy_for_object_type( 'category', 'page' );

            foreach( $this->taxonomies as $taxonomy ) {

                $classname = 'Lithe_Taxonomy_' . ucwords( $taxonomy );

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
            $taxonomies_directory = trailingslashit( lithe_get_classes_directory_path( 'taxonomies' ) );

            include $taxonomies_directory . 'class-lithe-taxonomy.php';

            foreach( $this->taxonomies as $taxonomy ) {

                include $taxonomies_directory . 'class-lithe-taxonomy-' . strtolower( $taxonomy ) . '.php';

            }
        }

    }

    return new Lithe_Taxonomies();
}
