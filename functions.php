<?php

/**
 * Registers menus
 *
 * @return void
 */
function lithe_menus(): void {

    lithe_register_menus( array(
        'primary'      => __( 'Header Horizontal Menu', 'lithe' ),
        'social'       => __( 'Header Social Menu', 'lithe' ),
        'aside'        => __( 'Sidebar Menu', 'lithe' ),
        'aside-extra'  => __( 'Sidebar Extra Menu', 'lithe' ),
        'footer'       => __( 'Footer Menu', 'lithe' ),
    ) );

}
add_action( 'init', 'lithe_menus' );

/**
 * Registers carousels
 *
 * @return void
 */
function lithe_carousels(): void {

    lithe_register_carousel( 'home', array(

        lithe_carousel_photo( array(
            'src'        => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-frisbee.jpg?resize=812,400',
            'src-retina' => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-frisbee.jpg',
            'title'      => __( 'Russian Spaniel', 'lithe' ) . ' ' . __( 'Vilui', 'lithe' ),
            'author'     => __( 'Els Keurlinckx', 'lithe' ),
        ) ),

        lithe_carousel_photo( array(
            'src'        => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-flyball.jpg?resize=812,400',
            'src-retina' => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-flyball.jpg',
            'title'      => __( 'Jack Russell Terrier', 'lithe') . ' ' . __( 'Tussa', 'lithe' ),
            'author'     => __( 'Viktor Bykov', 'lithe' ),
        ) ),

        lithe_carousel_photo( array(
            'src'        => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-agility2.jpg?resize=812,400',
            'src-retina' => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-agility2.jpg',
            'title'      => __( 'Pembroke Welsh Corgi', 'lithe' ) . ' ' . __( 'Hina', 'lithe' ),
            'author'     => __( 'Irina Evstrahina', 'lithe' ),
        ) ),

        lithe_carousel_photo( array(
            'src'        => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-agility3.jpg?resize=812,400',
            'src-retina' => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-agility3.jpg',
            'title'      => __( 'Petit Brabancon', 'lithe' ) . ' ' . __( 'Arni', 'lithe' ),
            'author'     => __( 'Sergey Korsakov', 'lithe' ),
        ) ),

        lithe_carousel_photo( array(
            'src'        => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-agility4.jpg?resize=812,400',
            'src-retina' => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-agility4.jpg',
            'title'      => __( 'Pembroke Welsh Corgi', 'lithe' ) . ' ' . __( 'Forky Strong', 'lithe' ),
            'author'     => __( 'Sergey Korsakov', 'lithe' ),
        ) ),

        lithe_carousel_photo( array(
            'src'        => 'https://sfks.ru/wp-content/uploads/2021/04/carousel-frisbee2.jpg?resize=812,400',
            'src-retina' => 'https://sfks.ru/wp-content/uploads/2021/04/carousel-frisbee2.jpg',
            'title'      => __( 'Frisbee Trials', 'lithe' ) . ' "' . __( 'Kluchi ot Goroda', 'lithe' ) . '"',
            'author'     => __( 'Yana Fedorova', 'lithe' ),
        ) ),

        lithe_carousel_photo( array(
            'src'        => 'https://sfks.ru/wp-content/uploads/2021/03/carousel-agility8.jpg?resize=812,400',
            'src-retina' => 'https://sfks.ru/wp-content/uploads/2021/03/carousel-agility8.jpg',
            'title'      => __( 'Border Collie', 'lithe' ) . ' ' . __( 'Jackie', 'lithe' ),
            'author'     => __( 'Anastasi Belskai', 'lithe' ),
        ) ),

        lithe_carousel_photo( array(
            'src'        => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-agility7.jpg?resize=812,400',
            'src-retina' => 'https://sfks.ru/wp-content/uploads/2020/07/carousel-agility7.jpg',
            'title'      => __( 'Poodle', 'lithe' ) . ' ' . __( 'Justa', 'lithe' ),
            'author'     => __( 'Anna Averiyanova', 'lithe' ),
        ) ),

    ) );

    lithe_register_carousel( 'sponsors', array(

        lithe_carousel_link( array(
            'src'  => '[template_uri]/assets/images/brands/royal-canin.svg',
            'href' => 'https://royal-canin.ru',
            'text' => __( 'Royal Canin', 'lithe' ),
            'alt'  => __( 'Royal Canin logo', 'lithe' ),
        ) ),

        lithe_carousel_link( array(
            'src'  => '[template_uri]/assets/images/brands/pronature.svg',
            'href' => 'http://pronature.ru',
            'text' => __( 'Pronature', 'lithe' ),
            'alt'  => __( 'Pronature logo', 'lithe' ),
        ) ),

        lithe_carousel_link( array(
            'src'  => '[template_uri]/assets/images/brands/avz.svg',
            'href' => 'https://www.vetmag.ru/',
            'text' => __( 'AVZ', 'lithe' ),
            'alt'  => __( 'AVZ logo', 'lithe' ),
        ) ),

    ) );

}
add_action( 'init', 'lithe_carousels' );

require_once get_template_directory() . '/classes/class-lithe-theme.php';

/**
 * Returns lithe theme instance
 *
 * @return Lithe_Theme
 */
function lithe(): Lithe_Theme {
    return Lithe_Theme::instance();
}

/**
 * @global Lithe_Theme $_GLOBALS['lithe'] Lithe theme instance.
 * @name $lithe
 */
$_GLOBALS['lithe'] = lithe();
