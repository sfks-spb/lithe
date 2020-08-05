<?php

if ( ! class_exists( 'Lithe_Taxonomy' ) ) {

    abstract class Lithe_Taxonomy {

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
        public function custom_columns( $content, $column_name, $term_id ) {
            //
        }

        /**
         *
         */
        protected function add_capabilities() {

            foreach ( $this->capabilities as $role => $capabilities ) {

                $role = get_role( $role );

                if ( $role ) {
                    foreach ( $capabilities as $capability ) $role->add_cap( $capability );
                }

            }

        }

        /**
         *
         */
        abstract public function register();

        /**
         *
         */
        abstract public function add_form_fields( $term );

        /**
         *
         */
        abstract public function edit_form_fields( $term );

        /**
         *
         */
        abstract public function save( $term_id, $term );

    }

}