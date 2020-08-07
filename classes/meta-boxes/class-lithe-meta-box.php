<?php

if ( ! class_exists( 'Lithe_Meta_Box' ) ) {

    abstract class Lithe_Meta_Box {

        /**
         * Contains custom post type handle
         *
         * @var string
         */
        protected $post_type;

        /**
         * Constructs new lithe meta box instance
         *
         * @return void
         */
        public function __construct() {
            add_action( 'save_' . $this->post_type . '_meta', array( $this, 'save' ), 10, 2 );
        }

        /**
         * Outputs metabox html
         *
         * @param  WP_Post $post
         *
         * @return void
         */
        abstract public static function render( WP_Post $post ): void;

        /**
         * Handles meta box save
         *
         * @param  int     $post_id
         * @param  WP_Post $post
         *
         * @return void
         */
        abstract public function save( int $post_id, WP_Post $post ): void;

    }

}
