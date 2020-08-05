<?php

if ( ! function_exists('lithe_get_svg') ) {

    /**
     *
     */
    function lithe_get_svg( string $icon, array $attributes = array() ): ?string {
        return Lithe_SVG::get_svg( $icon, $attributes );
    }

}

if ( ! function_exists('lithe_svg') ) {

    /**
     *
     */
    function lithe_svg( string $icon, array $attributes = array() ): void {
        echo lithe_get_svg( $icon, $attributes );
    }

}
