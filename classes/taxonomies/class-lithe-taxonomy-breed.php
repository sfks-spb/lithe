<?php

if ( ! class_exists( 'Lithe_Taxonomy_Breed' ) ) {

    class Lithe_Taxonomy_Breed extends Lithe_Taxonomy {

        /**
         *
         */
        protected $handle = 'breed';

        /**
         *
         */
        protected $capabilities = array(
            'administrator' => array(
                'manage_breeds',
            ),
        );

        /**
         *
         */
        public function register():void {
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
         *
         */
        public function columns(): array {
            return array(
                'posts'     => __( 'Dogs', 'lithe' ),
            );
        }

        /**
         *
         */
        public function add_form_fields( $term ):void {
            //
        }

        /**
         *
         */
        public function edit_form_fields( $term ):void {
            //
        }

        /**
         *
         */
        public function save( $term_id, $term ):void {
            //
        }

    }

}