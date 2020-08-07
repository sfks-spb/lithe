<?php

if ( ! class_exists( 'Lithe_Post_Type' ) ) {

    abstract class Lithe_Post_Type {

        /**
         * Contains custom post type handle
         *
         * @var string
         */
        protected $handle;

        /**
         * Contains custom post type capabilities
         *
         * @var array
         */
        protected $capabilities = array();

        /**
         * Constructs new lithe custom post type
         *
         * @return void
         */
        public function __construct() {
            $this->add_capabilities();

            if ( ! empty( $this->columns() ) ) {
                add_action( 'manage_' . $this->handle . '_posts_columns', array( $this, 'add_custom_columns' ) );
                add_action( 'manage_' . $this->handle . '_posts_custom_column', array( $this, 'custom_columns' ) , 10, 2 );
            }
        }

        /**
         * Adds custom post type capabilities
         *
         * @return void
         */
        protected function add_capabilities(): void {

            foreach ( $this->capabilities as $role => $capabilities ) {

                $role = get_role( $role );

                if ( $role ) {
                    $capabilities = ( is_string( $capabilities ) ) ? $this->get_default_capabilities( $capabilities ) : $capabilities;

                    foreach ( $capabilities as $capability ) $role->add_cap( $capability );
                }

            }

        }

        /**
         * Gets default capabilities for custom post type
         *
         * @return array
         */
        protected function get_default_capabilities(): array {
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
         * Adds custom columns
         *
         * @param  array $columns
         *
         * @return array
         */
        public function add_custom_columns( array $columns ): array {
            foreach ( $this->columns() as $column => $label ) {
                $columns[ $column ] = $label;
            }

            return $columns;
        }

        /**
         * Gets custom post type columns
         *
         * @return array
         */
        public function columns(): array {
            return array();
        }

        /**
         * Handles custom columns output
         *
         * @param  string $column
         * @param  int    $post_id
         *
         * @return void
         */
        public function custom_columns( string $column, int $post_id ): void {
            //
        }

        /**
         * Registers custom post type
         *
         * @return void
         */
        abstract public function register(): void;

    }

}
