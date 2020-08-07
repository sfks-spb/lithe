<?php

if ( ! class_exists( 'Lithe_Carousel_Item_Slide' ) ) {

    class Lithe_Carousel_Item_Slide extends Lithe_Carousel_Item {

        /**
         * Contains list of default attributes
         *
         * @var array
         */
        protected $defaults = array(
            'src'    => '',
            'title'  => '',
            'text'   => '',
            'button' => '',
            'href'   => '#',
        );

        /**
         * Constructs new lithe carousel slide instance
         *
         * @param  array $atts
         *
         * @return void
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
         * Gets item content
         *
         * @return string
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
         * Adds slide button html
         *
         * @param  string &$html
         *
         * @return void
         */
        protected function add_slide_button( string &$html ): void {
            $html .= '<button class="slide-button" href="' . $this->href . '">' . $this->text . '</button>';
        }

        /**
         * Adds slide text html
         *
         * @param  string &$html
         *
         * @return void
         */
        protected function add_slide_text( string &$html ): void {
            $html .= '<div class="slide-text">' . $this->text . '</div>';
        }

        /**
         * Adds slide title html
         *
         * @param  string &$html
         *
         * @return void
         */
        protected function add_slide_title( string &$html ): void {
            $html .= '<h2 class="slide-title">' . $this->title . '</h2>';
        }

    }

}
