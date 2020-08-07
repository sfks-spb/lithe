<?php

if ( ! class_exists( 'Lithe_Carousel_Item' ) ) {

    class Lithe_Carousel_Item {

        /**
         * Contains list of item classes.
         *
         * @var array
         */
        public $classes = array();

        /**
         * Contains list of default attributes.
         *
         * @var array
         */
        protected $defaults = array();

        /**
         * Contains list of attributes.
         *
         * @var array
         */
        protected $attributes = array();

        /**
         * Constructs new lithe carousel item instance.
         *
         * @param  array $atts Carousel item attributes.
         *
         * @return void
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
         * Adds item class.
         *
         * @param  string $class CSS class name.
         *
         * @return Lithe_Carousel_Item
         */
        protected function add_class( string $class ): self {
            $this->classes[] = $class;

            return $this;
        }

        /**
         * Adds item attribute.
         *
         * @param  string $name HTML attribute name.
         * @param  string $value HTML attribute value.
         *
         * @return Lithe_Carousel_Item
         */
        protected function add_attr( string $name, string $value = '' ): self {
            $this->attributes[ $name ] = esc_attr( $value );

            return $this;
        }

        /**
         * Outputs item as html.
         *
         * @return string
         */
        public function __toString() {
            return $this->get_item();
        }

        /**
         * Gets item html.
         *
         * @return string
         */
        public function get_item(): string {
            $content = $this->get_content();

            $attributes_string = '';

            foreach ( $this->attributes as $key => $value ) {
                $attributes_string .= ' ' . $key . '="' . $value . '"';
            }

            return '<div class="item ' . implode( ' ', $this->classes ) . '"' . $attributes_string . '>' . $content . '</div>';
        }

        /**
         * Gets item content.
         *
         * @return string
         */
        public function get_content(): string {
            return '';
        }

    }

}
