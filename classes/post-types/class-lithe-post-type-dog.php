<?php

if ( ! class_exists( 'Lithe_Post_Type_Dog' ) ) {

    class Lithe_Post_Type_Dog extends Lithe_Post_Type {

        /**
         * Contains custom post type handle.
         *
         * @var string
         */
        protected $handle = 'dog';

        /**
         * Contains custom post type capabilities.
         *
         * @var array
         */
        protected $capabilities = array(
            'administrator' => 'default',
        );

        /**
         * Registers custom post type.
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

        /**
         * Gets custom post type columns.
         *
         * @return array
         */
        public function columns(): array {
            return array(
                'full_name' => __( 'Full Name', 'lithe' ),
                'gender'    => __( 'Gender', 'lithe' ),
                'breed'     => __( 'Breed', 'lithe' ),
                'height'    => __( 'Height', 'lithe' ),
            );
        }

        /**
         * Handles custom columns output.
         *
         * @param  string $column Current column name.
         * @param  int    $post_id Current post id.
         *
         * @return void
         */
        public function custom_columns( string $column, int $post_id ): void {

            switch ( $column ) {

                case 'full_name':
                    echo get_post_meta( $post_id, 'full_name', true );
                    break;

                case 'gender':
                    echo ( 'f' === get_post_meta( $post_id, 'gender', true ) ) ? __( 'Female', 'lithe' ) : __( 'Male', 'lithe' );
                    break;

                case 'breed':
                    echo get_post_meta( $post_id, 'breed', true );
                    break;

                case 'height':
                    echo get_post_meta( $post_id, 'height', true );
                    break;
            }

        }

    }

}
