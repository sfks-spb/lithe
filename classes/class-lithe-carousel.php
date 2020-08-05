<?php

if ( ! class_exists( 'Lithe_Carousel' ) ) {

    class Lithe_Carousel {

        /**
         *
         */
        protected $tags = array();

        /**
         *
         */
        public function __construct() {
            $this->includes();

            $this->tags = array(
                '%{template_uri}' => get_template_directory_uri(),
            );
        }

        /**
         *
         */
        protected function includes() {
            $template_directory = get_template_directory();

            include_once $template_directory . '/classes/carousel-items/class-lithe-carousel-item.php';
            include_once $template_directory . '/classes/carousel-items/class-lithe-carousel-item-link.php';
            include_once $template_directory . '/classes/carousel-items/class-lithe-carousel-item-photo.php';
            include_once $template_directory . '/classes/carousel-items/class-lithe-carousel-item-slide.php';
        }

        /**
         *
         */
        public function get( string $handle, string $order = 'normal', array $classes = array() ): ?string {
            if ( $this->has( $handle ) ) {

                $items = $this->carousels[ $handle ];

                if ( 'shuffled' == $order ) {
                    shuffle( $items );
                    $classes[] = 'shuffled';
                }

                ob_start();

                set_query_var( 'carousel', array(
                    'id'      => 'owl-' . $handle,
                    'items'   => $items,
                    'classes' => implode( ' ', $classes ),
                ) );
                get_template_part( 'template-parts/carousel' );
                set_query_var( 'carousel', false );

                $html = ob_get_contents();
                ob_end_clean();

                return $html;
            }
        }

        /**
         *
         */
        public function has( string $handle ): bool {
            return array_key_exists( $handle, $this->carousels );
        }


        /**
         *
         */
        public function register( $handle, $items ): self {
            $this->carousels[ $handle ] = $items;

            return $this;
        }

        /**
         *
         */
        public function prepare_link( string $link ): string {
            foreach ( $this->tags as $tag => $content ) {
                $link = str_replace( $tag, $content, $link );
            }

            return esc_url( $link );
        }

    }

}
