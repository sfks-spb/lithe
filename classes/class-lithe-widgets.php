<?php

if ( ! class_exists( 'Lithe_Widgets' ) ) {

    class Lithe_Widgets {

        /**
         * Contains list of widgets.
         *
         * @var array
         */
        protected $widgets = array( 'weather', 'VK' );

        /**
         * Constructs new lithe widget manager instance.
         *
         * @return void
         */
        public function __construct() {
            $this->includes();

            add_action( 'widgets_init', array( $this, 'register_widgets' ) );
        }

        /**
         * Registers widgets.
         *
         * @return void
         */
        public function register_widgets(): void {

            foreach ( $this->widgets as $widget ) {

                $classname = 'Lithe_Widget_' . ucwords( $widget );

                if ( class_exists( $classname ) ) {

                    register_widget( $classname );

                }

            }

        }

        /**
         * Includes dependencies.
         *
         * @return void
         */
        protected function includes(): void {
            $widgets_directory = trailingslashit( lithe_get_classes_directory_path( 'widgets' ) );

            foreach ( $this->widgets as $widget ) {
                include $widgets_directory . 'class-lithe-widget-' . strtolower( $widget ) . '.php';
            }
        }

    }

    return new Lithe_Widgets();

}
