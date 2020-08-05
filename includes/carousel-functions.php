<?php

if ( ! function_exists( 'lithe_register_carousel' ) ) {

    /**
     *
     */
    function lithe_register_carousel( string $handle, array $items = array() ): void {
        lithe()->carousel->register( $handle, $items );
    }
}

if ( ! function_exists( 'lithe_carousel' ) ) {

    /**
     *
     */
    function lithe_carousel( string $handle, string $order = 'normal', array $classes = array() ): void {
        echo lithe()->carousel->get( $handle, $order, $classes );
    }

}

if ( ! function_exists( 'lithe_has_carousel' ) ) {

    /**
     *
     */
    function lithe_has_carousel( string $handle ): bool {
        return lithe()->carousel->has( $handle  );
    }

}

if ( ! function_exists( 'lithe_carousel_photo' ) ) {

    /**
     *
     */
    function lithe_carousel_photo( array $atts ): Lithe_Carousel_Item_Photo {
        return new Lithe_Carousel_Item_Photo( $atts );
    }

}

if ( ! function_exists( 'lithe_carousel_link' ) ) {

    /**
     *
     */
    function lithe_carousel_link( array $atts ): Lithe_Carousel_Item_Link {
        return new Lithe_Carousel_Item_Link( $atts );
    }
}

if ( ! function_exists( 'lithe_carousel_slide' ) ) {

    /**
     *
     */
    function lithe_carousel_slide( array $atts ): Lithe_Carousel_Item_Slide {
        return new Lithe_Carousel_Item_Slide( $atts );
    }
}
