<?php

if ( ! function_exists( 'lithe_register_carousel' ) ) {

    /**
     * Registers lithe carousel.
     *
     * @param  string $handle Carousel handle.
     * @param  array  $items List of carousel items.
     *
     * @return void
     */
    function lithe_register_carousel( string $handle, array $items = array() ): void {
        lithe()->carousel->register( $handle, $items );
    }
}

if ( ! function_exists( 'lithe_carousel' ) ) {

    /**
     * Outputs lithe carousel.
     *
     * @param  string $handle Carousel handle.
     * @param  string $order Order of items. (normal or shuffled)
     * @param  array  $classes Carousel CSS classes.
     *
     * @return void
     */
    function lithe_carousel( string $handle, string $order = 'normal', array $classes = array() ): void {
        echo lithe()->carousel->get( $handle, $order, $classes );
    }

}

if ( ! function_exists( 'lithe_has_carousel' ) ) {

    /**
     * Check if carousel exists.
     *
     * @param  string $handle Carousel handle.
     *
     * @return bool
     */
    function lithe_has_carousel( string $handle ): bool {
        return lithe()->carousel->has( $handle  );
    }

}

if ( ! function_exists( 'lithe_carousel_photo' ) ) {

    /**
     * Creates new carousel photo.
     *
     * @param  array $atts Carousel photo attributes.
     *
     * @return Lithe_Carousel_Item_Photo
     */
    function lithe_carousel_photo( array $atts ): Lithe_Carousel_Item_Photo {
        return new Lithe_Carousel_Item_Photo( $atts );
    }

}

if ( ! function_exists( 'lithe_carousel_link' ) ) {

    /**
     * Creates new carousel link.
     *
     * @param  array $atts Carousel link attributes.
     *
     * @return Lithe_Carousel_Item_Link
     */
    function lithe_carousel_link( array $atts ): Lithe_Carousel_Item_Link {
        return new Lithe_Carousel_Item_Link( $atts );
    }
}

if ( ! function_exists( 'lithe_carousel_slide' ) ) {

    /**
     * Creates new carousel slide.
     *
     * @param  array $atts Carousel slide attributes.
     *
     * @return Lithe_Carousel_Item_Slide
     */
    function lithe_carousel_slide( array $atts ): Lithe_Carousel_Item_Slide {
        return new Lithe_Carousel_Item_Slide( $atts );
    }
}
