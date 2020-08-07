<?php

if ( ! function_exists('lithe_get_svg') ) {

    /**
     * Gets svg content
     *
     * @param  string $icon
     * @param  array  $attributes
     *
     * @return string|null
     */
    function lithe_get_svg( string $icon, array $attributes = array() ): ?string {
        return Lithe_SVG::get_svg( $icon, $attributes );
    }

}

if ( ! function_exists('lithe_svg') ) {

    /**
     * Outputs sbg content
     *
     * @param  string $icon
     * @param  array  $attributes
     *
     * @return void
     */
    function lithe_svg( string $icon, array $attributes = array() ): void {
        echo lithe_get_svg( $icon, $attributes );
    }

}
