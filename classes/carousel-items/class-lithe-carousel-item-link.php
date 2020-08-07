<?php

if ( ! class_exists( 'Lithe_Carousel_Item_Link' ) ) {

    class Lithe_Carousel_Item_Link extends Lithe_Carousel_Item {

        /**
         * Contains list of default attributes
         *
         * @var array
         */
        protected $defaults = array(
            'src'  => '',
            'href' => '#',
            'text' => '',
            'alt'  => '',
        );

        /**
         * Constructs new lithe carousel link instance
         *
         * @param  array $atts
         *
         * @return void
         */
        public function __construct( array $atts ) {
            parent::__construct( $atts );

            $this->add_class( 'item-link' );
            $this->src = esc_url( lithe()->carousel->do_tags( $this->src ) );

            $this->add_attr( 'aria-label', lithe()->carousel->do_tags( $this->text ) );

            if ( '#' !== $this->href ) {
                $this->href = lithe()->carousel->do_tags( $this->href );
            }
        }

        /**
         * Gets item content
         *
         * @return string
         */
        public function get_content(): string {
            return '<a href="' . $this->href . '" title="' . $this->text . '">
                <img src="' . $this->src . '" alt="' . $this->alt . '" /></a>';
        }

    }

}
