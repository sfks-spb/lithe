<?php

if ( ! class_exists( 'Lithe_Post_Type_Dog' ) ) {

    class Lithe_Post_Type_Dog extends Lithe_Post_Type {

        /**
         * Contains custom post type handle
         *
         * @var string
         */
        protected $handle = 'dog';

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
                    'name'               => __( 'Dogs', 'lithe' ),
                    'singular_name'      => __( 'Dog', 'lithe' ),
                    'add_new'            => _x( 'Add New', 'Add new dog', 'lithe' ),
                    'add_new_item'       => __( 'Add New Dog', 'lithe' ),
                    'edit_item'          => __( 'Edit Dog', 'lithe' ),
                    'new_item'           => __( 'New Dog', 'lithe' ),
                    'add_items'          => __( 'All Dogs', 'lithe' ),
                    'view_items'         => __( 'View Dogs', 'lithe' ),
                    'search_items'       => __( 'Search Dogs', 'lithe' ),
                    'not_found'          => __( 'No dogs found', 'lithe' ),
                    'not_found_in_trash' => __( 'No dogs found in the Trash', 'lithe' ),
                    'menu_name'          => __( 'Dogs', 'lithe' ),
                    'parent_item_colon'  => '',
                ),
                'description'     => __( 'Holds dogs and dog specific data', 'lithe' ),
                'public'          => true,
                'menu_position'   => 6,
                'supports'        => array( 'title' ),
                'has_archive'     => true,
                'menu_icon'       => 'dashicons-buddicons-activity',
                'rewrite'         => array( 'slug' => $this->handle ),
                'map_meta_cap'    => true,
                'capability_type' => $this->handle,
            ) );

        }

    }

}
