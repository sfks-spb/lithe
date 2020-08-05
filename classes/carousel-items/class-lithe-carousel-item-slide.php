<?php

if ( ! class_exists( 'Lithe_Carousel_Item_Slide' ) ) {

    class Lithe_Carousel_Item_Slide extends Lithe_Carousel_Item {

        /**
         *
         */
        protected $defaults = array(
            'src'    => '',
            'title'  => '',
            'text'   => '',
            'button' => '',
            'href'   => '#',
        );

        /**
         *
         */
        public function __construct( array $atts ) {
            parent::__construct( $atts );

            $this->add_class( 'item-slide' );
            $this->src = lithe()->carousel->prepare_link( $this->src );

            if ( '#' !== $this->href ) {
                $this->href = lithe()->carousel->prepare_link( $this->href );
            }
        }

        /**
         *
         */
        public function get_content(): string {
            $html = '<img src="' . $this->src . '" />';

            if ( '' !== $this->title ) {
                $this->add_slide_title();
            }

            if ( '' !== $this->text ) {
                $this->add_class( 'has-text-content' );
                $this->add_slide_text();
            }

            if ( '' !== $this->button ) {
                $this->add_class( 'has-button' );
                $this->add_slide_button( $html );
            }

            return $html;
        }

        /**
         *
         */
        protected function add_slide_button( string &$html ) {
            $html .= '<button class="slide-button" href="' . $this->href . '">' . $this->text . '</button>';
        }

        /**
         *
         */
        protected function add_slide_text( string &$html ) {
            $html .= '<div class="slide-text">' . $this->text . '</div>';
        }

        /**
         *
         */
        protected function add_slide_title( string &$html ) {
            $html .= '<h2 class="slide-title">' . $this->title . '</h2>';
        }

    }

}