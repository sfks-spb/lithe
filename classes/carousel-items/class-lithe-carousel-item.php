<?php

if ( ! class_exists( 'Lithe_Carousel_Item' ) ) {

    class Lithe_Carousel_Item {

        /**
         *
         */
        public $classes = array();

        /**
         *
         */
        protected $defaults = array();

        /**
         *
         */
        protected $attributes = array();

        /**
         *
         */
        public function __construct( array $atts ) {
            $atts = array_merge( $this->defaults, $atts );

            $this->add_attr( 'role', 'group' );
            $this->add_attr( 'aria-roledescription', 'slide' );

            foreach ( $atts as $key => $value ) {
                $key = str_replace( '-', '_', $key );

                $this->$key = $value;
            }
        }

        /**
         *
         */
        protected function add_class( string $class ): void {
            $this->classes[] = $class;
        }

        /**
         *
         */
        protected function add_attr( string $key, string $value = '' ): void {
            $this->attributes[ $key ] = esc_attr( $value );
        }

        /**
         *
         */
        public function __toString() {
            return $this->get_item();
        }

        /**
         *
         */
        public function get_item(): string {
            $content = $this->get_content();

            $attributes = '';

            foreach ( $this->attributes as $key => $value ) {
                $attributes .= ' ' . $key . '="' . $value . '"';
            }


            return '<div class="item ' . implode( ' ', $this->classes ) . '"' . $attributes . '>' . $content . '</div>';
        }

        /**
         *
         */
        public function get_content(): string {
            return '';
        }

    }

}