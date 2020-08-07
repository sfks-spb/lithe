<?php

if ( ! class_exists( 'Lithe_Post_Type_Trial' ) ) {

    class Lithe_Post_Type_Trial extends Lithe_Post_Type {

        /**
         * Contains custom post type handle
         *
         * @var string
         */
        protected $handle = 'trial';

        /**
         * Contains custom post type capabilities
         *
         * @var array
         */
        protected $capabilities = array(
            'administrator' => 'default',
        );

        /**
         * Registers custom post type
         *
         * @return void
         */
        public function register(): void {

            register_post_type( $this->handle, array(
                'labels'          => array(
                    'name'               => __( 'Trials', 'lithe' ),
                    'singular_name'      => __( 'Trial', 'lithe' ),
                    'add_new'            => _x( 'Add New', 'Add new trial', 'lithe' ),
                    'add_new_item'       => __( 'Add New Trial', 'lithe' ),
                    'edit_item'          => __( 'Edit Trial', 'lithe' ),
                    'new_item'           => __( 'New Trial', 'lithe' ),
                    'add_items'          => __( 'All Trials', 'lithe' ),
                    'view_items'         => __( 'View Trials', 'lithe' ),
                    'search_items'       => __( 'Search Trials', 'lithe' ),
                    'not_found'          => __( 'No trials found', 'lithe' ),
                    'not_found_in_trash' => __( 'No trials found in the Trash', 'lithe' ),
                    'menu_name'          => __( 'Trials', 'lithe' ),
                    'parent_item_colon'  => '',
                ),
                'description'     => __( 'Holds trials and tracks specific data', 'lithe' ),
                'public'          => true,
                'menu_position'   => 7,
                'supports'        => array( 'title', 'editor' ),
                'has_archive'     => true,
                'menu_icon'       => 'dashicons-awards',
                'rewrite'         => array( 'slug' => $this->handle ),
                'map_meta_cap'    => true,
                'capability_type' => $this->handle,
            ) );

        }

    }

}
