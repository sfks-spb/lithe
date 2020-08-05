<?php

if ( ! class_exists( 'Lithe_Carousel_Item_Photo' ) ) {

    class Lithe_Carousel_Item_Photo extends Lithe_Carousel_Item {

        /**
         *
         */
        protected $defaults = array(
            'author'     => '',
            'title'      => '',
            'src'        => '',
            'src-retina' => '',
            'lazy'       => true,
        );

        /**
         *
         */
        public function __construct( array $atts ) {
            parent::__construct( $atts );

            $this->add_class( 'item-photo' );
            $this->src = lithe_photon_prepend_uri( lithe()->carousel->prepare_link( $this->src ) );

            if ( '' !== $this->src_retina ) {
                $this->src_retina = lithe_photon_prepend_uri( lithe()->carousel->prepare_link( $this->src_retina ) );
            }
        }

        /**
         *
         */
        public function get_content(): string {
            $html = '<img src="' . $this->src . '" />';

            if ( $this->lazy ) {
                $this->add_class( 'uses-lazy-load' );
                $this->use_lazy_load( $html );
            }

            if ( '' !== $this->title ) {
                $this->add_class( 'has-title-card' );
                $this->append_title_card( $html );
            }

            return $html;
        }

        /**
         *
         */
        protected function append_title_card( string &$html ): void {
            $html = preg_replace( '/\/>$/', 'alt="' . esc_attr( $this->title ) . '" />', $html );
            $html .= '<span class="title-card">' . $this->title;

            $this->add_attr( 'aria-label', $this->title );

            if ( '' !== $this->author ) {
                $html .= '<span class="author"><i class="fas fa-camera"></i> ' . $this->author  . '</span>';
            }

            $html .= '</span>';
        }

        /**
         *
         */
        protected function use_lazy_load( string &$html ): void {
            $html = preg_replace('/src=.+ /i', 'class="owl-lazy" data-src="' . $this->src . '" ', $html);

            if ( '' !== $this->src_retina ) {
                $this->add_class( 'has-retina-image' );
                $html = preg_replace( '/\/>$/', 'data-src-retina="' . $this->src_retina . '" />', $html );
            }
        }

    }

}
