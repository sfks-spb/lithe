<?php

if ( ! class_exists( 'Lithe_Post_Type_Trainer' ) ) {

    class Lithe_Post_Type_Trainer extends Lithe_Post_Type {

        /**
         *
         */
        protected $handle = 'trainer';

        /**
         *
         */
        protected $capabilities = array(
            'administrator' => 'trainer',
        );

        /**
         *
         */
        public function register():void {

            register_post_type( $this->handle, array(
                'labels'          => array(
                    'name'               => __( 'Trainers', 'lithe' ),
                    'singular_name'      => __( 'Trainer', 'lithe' ),
                    'add_new'            => _x( 'Add New', 'Add new trainer', 'lithe' ),
                    'add_new_item'       => __( 'Add New Trainer', 'lithe' ),
                    'edit_item'          => __( 'Edit Trainer', 'lithe' ),
                    'new_item'           => __( 'New Trainer', 'lithe' ),
                    'add_items'          => __( 'All Trainers', 'lithe' ),
                    'view_items'         => __( 'View Trainers', 'lithe' ),
                    'search_items'       => __( 'Search Trainers', 'lithe' ),
                    'not_found'          => __( 'No trainers found', 'lithe' ),
                    'not_found_in_trash' => __( 'No trainers found in the Trash', 'lithe' ),
                    'menu_name'          => __( 'Trainers', 'lithe' ),
                    'parent_item_colon'  => '',
                ),
                'description'     => __( 'Holds trainers and trainer specific data', 'lithe' ),
                'public'          => true,
                'menu_position'   => 5,
                'supports'        => array( 'title' ),
                'has_archive'     => false,
                'menu_icon'       => 'dashicons-welcome-learn-more',
                'taxonomies'      => array( 'venue', 'sport' ),
                'rewrite'         => array( 'slug' => $this->handle ),
                'map_meta_cap'    => true,
                'capability_type' => $this->handle,
            ) );

        }

        /**
         *
         */
        public function columns():array {
            return array(
                'phone'  => __( 'Phone', 'lithe' ),
                'sports' => __( 'Sports', 'lithe' ),
                'venues' => __( 'Venues', 'lithe' ),
            );
        }

        /**
         *
         */
        public function custom_columns( $column, $post_id ):void {

            switch ( $column ) {

                case 'phone':
                    echo get_post_meta( $post_id, 'phone', true );
                    break;

                case 'sports':
                    $sports = get_the_term_list( $post_id, 'sport', '', ', ', '' );

                    if ( is_string( $sports ) ) {
                        echo $sports;
                    } else {
                        _e( 'Unable to get list of sports', 'lithe' );
                    }

                    break;

                case 'venues':
                    $venues = get_the_term_list( $post_id, 'venue', '', ', ', '' );

                    if ( is_string( $venues ) ) {
                        echo $venues;
                    } else {
                        _e( 'Unable to get list of venues', 'lithe' );
                    }

            }

        }

    }

}