<?php

if ( ! class_exists( 'Lithe_Carousel' ) ) {

    class Lithe_Carousel {

        /**
         * Contains map of replacible tags
         *
         * @var array
         */
        protected $tags = array();

        /**
         * Constructs new lithe carousel manager instance
         *
         * @return void
         */
        public function __construct() {
            $this->includes();

            $this->tags = array(
                '[template_uri]' => get_template_directory_uri(),
            );
        }

        /**
         * Includes dependencies
         *
         * @return void
         */
        protected function includes(): void {
            $template_directory = get_template_directory();

            include_once $template_directory . '/classes/carousel-items/class-lithe-carousel-item.php';
            include_once $template_directory . '/classes/carousel-items/class-lithe-carousel-item-link.php';
            include_once $template_directory . '/classes/carousel-items/class-lithe-carousel-item-photo.php';
            include_once $template_directory . '/classes/carousel-items/class-lithe-carousel-item-slide.php';
        }

        /**
         * Gets carousel html output
         *
         * @param  string $handle
         * @param  string $order
         * @param  array  $classes
         *
         * @return string|null
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
         * Checks if carousel with certain handle exists
         *
         * @param  string $handle
         *
         * @return bool
         */
        public function has( string $handle ): bool {
            return array_key_exists( $handle, $this->carousels );
        }


        /**
         * Registers carousel
         *
         * @param  string $handle
         * @param  array  $items
         *
         * @return Lithe_Carousel
         */
        public function register( string $handle, array $items ): self {
            $this->carousels[ $handle ] = $items;

            return $this;
        }

        /**
         * Replaces tags in content
         *
         * @param  string $link
         *
         * @return string
         */
        public function do_tags( string $content ): string {
            foreach ( $this->tags as $tag => $replace ) {
                $content = str_replace( $tag, $replace, $content );
            }

            return $content;
        }

    }

}
