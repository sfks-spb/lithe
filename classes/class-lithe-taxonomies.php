<?php

if ( ! class_exists( 'Lithe_Taxonomies' ) ) {

    class Lithe_Taxonomies {

        /**
         * Contains list of custom taxonomy classnames.
         *
         * @var array
         */
        protected $taxonomies = array(
            Lithe_Taxonomy_Venue::class,
            Lithe_Taxonomy_Sport::class,
            Lithe_Taxonomy_Breed::class,
        );

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
                ( new $taxonomy )->register();
            }

        }

        /**
         * Includes dependencies.
         *
         * @return void
         */
        protected function includes(): void {
            $post_types_directory = get_template_directory() . '/classes/taxonomies/';

            include_once $post_types_directory . 'class-lithe-taxonomy.php';
            include_once $post_types_directory . 'class-lithe-taxonomy-sport.php';
            include_once $post_types_directory . 'class-lithe-taxonomy-venue.php';
            include_once $post_types_directory . 'class-lithe-taxonomy-breed.php';
        }

    }

    return new Lithe_Taxonomies();
}
