<?php

if ( ! class_exists( 'Lithe_Carousel_Item_Photo' ) ) {

    class Lithe_Carousel_Item_Photo extends Lithe_Carousel_Item {

        /**
         * Contains list of default attributes.
         *
         * @var array
         */
        protected $defaults = array(
            'author'     => '',
            'title'      => '',
            'src'        => '',
            'src-retina' => '',
            'lazy'       => true,
        );

        /**
         * Constructs new lithe carousel photo instance.
         *
         * @param  array $atts Carousel photo instance attributes.
         *
         * @return void
         */
        public function __construct( array $atts ) {
            parent::__construct( $atts );

            $this->add_class( 'item-photo' );

            $this->src = esc_url(
                lithe_photon_url(
                    lithe()->carousel->do_tags( $this->src ) ) );

            if ( '' !== $this->src_retina ) {
                $this->src_retina = esc_url(
                    lithe_photon_url(
                        lithe()->carousel->do_tags( $this->src_retina ) ) );
            }
        }

        /**
         * Gets item content.
         *
         * @return string
         */
        public function get_content(): string {
            $html = '<img src="' . $this->src . '" />';

            if ( $this->lazy ) {
                $this->add_class( 'uses-lazy-load' );
                $this->use_lazy_load( $html );
            }

            if ( ! ( '' === $this->title && '' === $this->author )  ) {
                $this->add_class( 'has-title-card' );
                $this->append_title_card( $html );
            }

            return $html;
        }

        /**
         * Appends title card, that cointains author name and description.
         *
         * @param  string &$html The content to append title card to.
         */
        protected function append_title_card( string &$html ): void {
            $html = preg_replace( '/\/>$/', 'alt="' . esc_attr( $this->title ) . '" />', $html );
            $html .= '<span class="title-card">';

            if ( '' !== $this->title ) {
                $html .= $this->title;
                $this->add_attr( 'aria-label', $this->title );
            }

            if ( '' !== $this->author ) {
                $html .= '<span class="author"><i class="fas fa-copyright"></i> ' . $this->author  . '</span>';
            }

            $html .= '</span>';
        }

        /**
         * Adds retina attribute.
         *
         * @param  string &$html The content to add restina attribute to.
         *
         * @return void
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
