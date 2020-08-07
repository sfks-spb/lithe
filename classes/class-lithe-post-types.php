<?php

if ( ! class_exists( 'Lithe_Post_Types' ) ) {

    class Lithe_Post_Types {

        /**
         * Contains list of custom post type classnames
         *
         * @var array
         */
        protected $post_types = array(
            Lithe_Post_Type_Trainer::class,
            Lithe_Post_Type_Dog::class,
            Lithe_Post_Type_Trial::class,
        );

        /**
         * Constructs new lithe post types instance
         *
         * @return void
         */
        public function __construct() {
            $this->includes();

            add_action( 'init', array( $this, 'register_post_types' ) );
        }

        /**
         * Registers custom post types
         *
         * @return void
         */
        public function register_post_types(): void {
            foreach ( $this->post_types as $post_type ) {
                ( new $post_type )->register();
            }
        }

        /**
         * Includes dependencies
         *
         * @return void
         */
        protected function includes(): void {
            $post_types_directory = get_template_directory() . '/classes/post-types/';

            include_once $post_types_directory . 'class-lithe-post-type.php';
            include_once $post_types_directory . 'class-lithe-post-type-trainer.php';
            include_once $post_types_directory . 'class-lithe-post-type-dog.php';
            include_once $post_types_directory . 'class-lithe-post-type-trial.php';
        }

    }

    return new Lithe_Post_Types();

}
