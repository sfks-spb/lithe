<?php

if ( ! class_exists( 'Lithe_Meta_Box' ) ) {

    abstract class Lithe_Meta_Box {

        /**
         *
         */
        protected $post_type;

        /**
         *
         */
        public function __construct() {
            add_action( 'save_' . $this->post_type . '_meta', array( $this, 'save' ), 10, 2 );
        }

        /**
         *
         */
        abstract public static function render( $post ): void;

        /**
         *
         */
        abstract public function save( $post_id, $post ): void;

    }

}