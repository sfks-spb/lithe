<?php

if ( ! class_exists( 'Lithe_Taxonomy_Sport' ) ) {

    class Lithe_Taxonomy_Sport extends Lithe_Taxonomy {

        /**
         * Contains taxonomy handle.
         *
         * @var string
         */
        protected $handle = 'sport';

        /**
         * Registers taxonomy.
         *
         * @return void
         */
        public function register(): void {
            register_taxonomy( $this->handle, 'trainer', array(
                'labels' => array(
                    'name'          => __( 'Sports', 'lithe' ),
                    'singular_name' => __( 'Sport', 'lithe' ),
                    'search_items'  => __( 'Search Sports', 'lithe' ),
                    'all_items'     => __( 'All Sports', 'lithe' ),
                    'edit_item'     => __( 'Edit Sport', 'lithe' ),
                    'update_item'   => __( 'Update Sport', 'lithe' ),
                    'add_new_item'  => __( 'Add New Sport', 'lithe' ),
                    'new_item_name' => __( 'New Sport', 'lithe' ),
                    'menu_name'     => __( 'Sports', 'lithe' ),
                ),
            ) );
        }

        /**
         * Gets list of columns.
         *
         * @return array
         */
        public function columns(): array {
            return array(
                'posts'     => __( 'Trainers', 'lithe' ),
            );
        }

        /**
         * Outputs new taxonomy item form HTML.
         *
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        public function add_form_fields( WP_Term $term ):void {
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
         * @param  int     $term_id Current term id.
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        public function save( $term_id, $term ):void {
            //
        }

    }

}
