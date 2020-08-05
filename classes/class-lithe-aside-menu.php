<?php

if ( ! class_exists( 'Lithe_Aside_Menu' ) ) {

    class Lithe_Aside_Menu {
        /**
         *
         */
        public function __construct() {
            add_filter( 'walker_nav_menu_start_el', array( $this, 'append_icon_to_aside_menu' ) , 10, 4 );
        }

        /**
         *
         */
        public function append_icon_to_aside_menu( $item_output, $item, $depth, $args ) {
            if ( 'aside' === $args->theme_location ) {

                foreach ( $this->icons as $uri => $icon ) {
                    if ( 1 === preg_match('/ href="https?:\/\/.*' . $uri . '/i', $item_output) ) {
                        $found = $icon;
                    }
                }

                if ( isset( $found ) ) {
                    $item_output = preg_replace('/(<a.*>)(.*)(<\/a>)/is', '$1' . lithe_get_svg( 'icon-' . $found ) . '$2$3', $item_output);
                }
            }

            return $item_output;
        }

        /**
         *
         */
        protected $icons = array(
            'agility'   => 'jump',
            'obedience' => 'apport',
            'frisbee'   => 'frisbee',
            'flyball'   => 'tennis-ball',
        );
    }

    return new Lithe_Aside_Menu();

}
