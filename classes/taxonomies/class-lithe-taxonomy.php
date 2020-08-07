<?php

if ( ! class_exists( 'Lithe_Taxonomy' ) ) {

    abstract class Lithe_Taxonomy {

        /**
         * Contains taxonomy handle.
         *
         * @var string
         */
        protected $handle;

        /**
         * Contains taxonomy capabilities.
         *
         * @var array
         */
        protected $capabilities = array();

        /**
         * Constructs new lithe taxonomy instance.
         *
         * @return void
         */
        public function __construct() {
            $this->add_capabilities();

            add_action( $this->handle . '_add_form_fields', array( $this, 'add_form_fields' ) );
            add_action( $this->handle . '_edit_form_fields', array( $this, 'edit_form_fields' ) );
            add_action( 'created_' . $this->handle, array( $this, 'save' ), 10, 2 );
            add_action( 'edited_'  . $this->handle, array( $this, 'save' ), 10, 2 );

            if ( ! empty( $this->columns() ) ) {
                add_action( 'manage_edit-' . $this->handle . '_columns', array( $this, 'add_custom_columns' ) );
                add_action( 'manage_' . $this->handle . '_custom_column', array( $this, 'custom_columns' ), 10, 3 );
            }
        }

        /**
         * Adds custom columns.
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
         * Gets list of columns.
         *
         * @return array
         */
        public function columns(): array {
            return array();
        }

        /**
         * Handles custom columns output.
         *
         * @param  string $content
         * @param  string $column_name
         * @param  int    $term_id
         *
         * @return void
         */
        public function custom_columns( string $content, string $column_name, int $term_id ) {
            //
        }

        /**
         * Adds custom term capabilities.
         *
         * @return void
         */
        protected function add_capabilities(): void {

            foreach ( $this->capabilities as $role => $capabilities ) {

                $role = get_role( $role );

                if ( $role ) {
                    foreach ( $capabilities as $capability ) $role->add_cap( $capability );
                }

            }

        }

        /**
         * Registers taxonomy.
         *
         * @return void
         */
        abstract public function register(): void;

        /**
         * Outputs new taxonomy item form HTML.
         *
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        abstract public function add_form_fields( WP_Term $term ): void;

        /**
         * Outputs edit taxonomy item form HTML.
         *
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        abstract public function edit_form_fields( WP_Term $term ): void;

        /**
         * Handles taxonomy item save.
         *
         * @param  int     $term_id Current term id.
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        abstract public function save( int $term_id, WP_Term $term ): void;

    }

}
