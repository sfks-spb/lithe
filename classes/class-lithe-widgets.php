<?php

if ( ! class_exists( 'Lithe_Widgets' ) ) {

    class Lithe_Widgets {

        /**
         * Contains list of widget classnames.
         *
         * @var array
         */
        protected $widgets = array(
            Lithe_Widget_Weather::class,
        );

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
                register_widget( $widget );
            }
        }

        /**
         * Includes dependencies.
         *
         * @return void
         */
        protected function includes(): void {
            $template_directory = get_template_directory();

            foreach ( $this->widgets as $widget ) {
                $widget_file = 'class-' . strtolower( str_replace( '_', '-', $widget ) ) . '.php';
                include_once $template_directory . '/classes/widgets/' . $widget_file;
            }
        }

    }

    return new Lithe_Widgets();

}
