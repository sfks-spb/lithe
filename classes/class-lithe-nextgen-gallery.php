<?php

if ( ! class_exists( 'Lithe_NextGEN_Gallery' ) ) {

    class Lithe_NextGEN_Gallery {

        /**
         * Contains list of image options.
         *
         * @var array
         */
        public $options = array();

        /**
         * Contains list of sizes.
         *
         * @var array
         */
        protected $sizes = array(
            'full' => array(
                'width'  => array( 'imgWidth', 0 ),
                'height' => array( 'imgHeight', 0 ),
                'fix'    => array( 'imgFix', true ),
            ),

            'thumnail' => array(
                'width'  => array( 'thumbwidth', 0 ),
                'height' => array( 'thumbheight', 0 ),
                'fix'    => array( 'thumbfix', true ),
            ),
        );

        /**
         * Constructs new lithe ngg instance.
         *
         * @return void
         */
        public function __construct() {
            if ( ! function_exists( 'jetpack_photon_url' ) ) {
                return;
            }

            add_action( 'init', array( $this, 'store_ngg_options' ) );
            add_filter( 'ngg_get_image_url', array( $this, 'get_photon_url_for_image' ), 10, 3);
        }

        /**
         * Stores NextGEN Gallery options in variable.
         *
         * @return void
         */
        public function store_ngg_options(): void {

            $ngg_options = get_option( 'ngg_options' );

            if ( ! empty( $ngg_options ) ) {
                $this->set_options( $ngg_options );
            }
        }

        /**
         * Sets the options.
         *
         * @param  array $ngg_options NextGEN Gallery options.
         *
         * @return self
         */
        public function set_options( array $ngg_options ): self {

            foreach ( $this->sizes as $size => $properties ) {

                $opts = array();

                foreach( $properties as $property => $value ) {
                    list( $key, $default ) = $value;
                    $opts[ $property ] = array_key_exists( $key, $ngg_options ) ? $ngg_options[ $key ] : $default;
                }

                $this->options[ $size ] = $opts;
            }

            return $this;
        }

        /**
         * Gets Photon URL for NextGEN Gallery image.
         *
         * @param  string      $retval Image URL.
         * @param  stdClass    $image Image object.
         * @param  string|null $size Image size.
         *
         * @return string
         */
        public function get_photon_url_for_image( $retval, $image, $size ): string {

            return lithe_photon_url( $retval, $this->get_args( $size ) );

        }

        /**
         * Gets Photon arguments for image size.
         *
         * @param  string $size Image size.
         *
         * @return array
         */
        protected function get_args( string $size = 'full' ): array {
            $args = array();

            $width = $this->options[ $size ]['width'];
            $height = $this->options[ $size ]['height'];
            $transform = ( $this->options[ $size ]['fix'] ) ? 'fit' : 'resize';

            if ( $width > 0 && $height > 0 ) {
                $args[ $transform ] = $width . ',' . $height;
            }

            return $args;
        }

    }

}

return new Lithe_NextGEN_Gallery();
