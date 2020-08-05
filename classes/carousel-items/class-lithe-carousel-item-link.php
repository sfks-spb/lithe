<?php

if ( ! class_exists( 'Lithe_Carousel_Item_Link' ) ) {

    class Lithe_Carousel_Item_Link extends Lithe_Carousel_Item {

        /**
         *
         */
        protected $defaults = array(
            'src'  => '',
            'href' => '#',
            'text' => '',
            'alt'  => '',
        );

        /**
         *
         */
        public function __construct( array $atts ) {
            parent::__construct( $atts );

            $this->add_class( 'item-link' );
            $this->src = lithe()->carousel->prepare_link( $this->src );

            $this->add_attr( 'aria-label', $this->text );

            if ( '#' !== $this->href ) {
                $this->href = lithe()->carousel->prepare_link( $this->href );
            }
        }

        /**
         *
         */
        public function get_content(): string {
            return '<a href="' . $this->href . '" title="' . $this->text . '">
                <img src="' . $this->src . '" alt="' . $this->alt . '" /></a>';
        }

    }

}
