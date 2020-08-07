<?php

if ( ! class_exists( 'Lithe_Aside_Menu' ) ) {

    class Lithe_Aside_Menu {

        /**
         * Maps url keywords to icons.
         *
         * @var array
         */
        protected $icons = array(
            'agility'   => 'jump',
            'obedience' => 'apport',
            'frisbee'   => 'frisbee',
            'flyball'   => 'tennis-ball',
        );

        /**
         * Constructs new lithe aside menu instance.
         *
         * @return void
         */
        public function __construct() {
            add_filter( 'walker_nav_menu_start_el', array( $this, 'append_icon_to_aside_menu' ) , 10, 4 );
        }

        /**
         * Appends icons to sidebar menu based on menu url.
         *
         * @param  string   $item_output The menu item's starting HTML output.
         * @param  WP_Post  $item Menu item data object.
         * @param  int      $depth Depth of menu item. Used for padding.
         * @param  stdClass $args An object of wp_nav_menu() arguments.
         *
         * @return string
         */
        public function append_icon_to_aside_menu( string $item_output, WP_Post $item, int $depth, $args ): string {
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

    }

    return new Lithe_Aside_Menu();

}
