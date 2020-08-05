<?php

if ( ! class_exists( 'Lithe_Post_Type' ) ) {

    abstract class Lithe_Post_Type {

        /**
         *
         */
        protected $handle;

        /**
         *
         */
        protected $capabilities = array();

        /**
         *
         */
        public function __construct() {
            $this->add_capabilities();

            if ( ! empty( $this->columns() ) ) {
                add_action( 'manage_' . $this->handle . '_posts_columns', array( $this, 'add_custom_columns' ) );
                add_action( 'manage_' . $this->handle . '_posts_custom_column', array( $this, 'custom_columns' ) , 10, 2 );
            }
        }

        /**
         *
         */
        protected function add_capabilities() {

            foreach ( $this->capabilities as $role => $capabilities ) {

                $role = get_role( $role );

                if ( $role ) {
                    $capabilities = ( is_string( $capabilities ) ) ? $this->get_default_capabilities( $capabilities ) : $capabilities;

                    foreach ( $capabilities as $capability ) $role->add_cap( $capability );
                }

            }

        }

        /**
         *
         */
        protected function get_default_capabilities( string $capability_type ): array {
            return array(
                'edit_' . $this->handle,
                'edit_' . $this->handle . 's',
                'edit_others_' . $this->handle . 's',
                'publish_' . $this->handle . 's',
                'read_' . $this->handle,
                'read_private_' . $this->handle . 's',
                'delete_' . $this->handle,
                'edit_published_' . $this->handle . 's',
                'delete_published_' . $this->handle . 's',
            );
        }

        /**
         *
         */
        public function add_custom_columns( $columns ) {
            foreach ( $this->columns() as $column => $label ) {
                $columns[ $column ] = $label;
            }

            return $columns;
        }

        /**
         *
         */
        public function columns():array {
            return array();
        }

        /**
         *
         */
        public function custom_columns( $column, $post_id ) {
            //
        }

        /**
         *
         */
        abstract public function register():void;

    }

}