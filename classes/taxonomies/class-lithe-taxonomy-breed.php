<?php

if ( ! class_exists( 'Lithe_Taxonomy_Breed' ) ) {

    class Lithe_Taxonomy_Breed extends Lithe_Taxonomy {

        /**
         * Contains taxonomy handle.
         *
         * @var string
         */
        protected $handle = 'breed';

        /**
         * Contains taxonomy capabilities.
         *
         * @var array
         */
        protected $capabilities = array(
            'administrator' => array(
                'manage_breeds',
            ),
        );

        /**
         * Registers taxonomy.
         *
         * @return void
         */
        public function register(): void {
            register_taxonomy( $this->handle, 'dog', array(
                'labels' => array(
                    'name'          => __( 'Breeds', 'lithe' ),
                    'singular_name' => __( 'Breed', 'lithe' ),
                    'search_items'  => __( 'Search Breeds', 'lithe' ),
                    'all_items'     => __( 'All Breeds', 'lithe' ),
                    'edit_item'     => __( 'Edit Breed', 'lithe' ),
                    'update_item'   => __( 'Update Breed', 'lithe' ),
                    'add_new_item'  => __( 'Add New Breed', 'lithe' ),
                    'new_item_name' => __( 'New Breed', 'lithe' ),
                    'menu_name'     => __( 'Breeds', 'lithe' ),
                ),
                'meta_box_cb' => false,
                'capabilities' => array(
                    'manage_terms' => 'manage_breeds',
                    'edit_terms'   => 'manage_breeds',
                    'delete_terms' => 'manage_breeds',
                    'assign_terms' => 'edit_dogs',
                )
            ) );
        }

        /**
         * Gets list of columns.
         *
         * @return array
         */
        public function columns(): array {
            return array(
                'posts' => __( 'Dogs', 'lithe' ),
            );
        }

        /**
         * Outputs new taxonomy item form HTML.
         *
         * @param  string $taxonomy Current taxonomy slug.
         *
         * @return void
         */
        public function add_form_fields( string $taxonomy ):void {
            //
        }

        /**
         * Outputs edit taxonomy item form HTML.
         *
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        public function edit_form_fields( WP_Term $term ):void {
            //
        }

        /**
         * Handles taxonomy item save.
         *
         * @param  int $term_id Current term id.
         * @param  int $term_taxonomy_id Current term instance.
         *
         * @return void
         */
        public function save( int $term_id, int $term_taxonomy_id ): void {
            //
        }

    }

}
