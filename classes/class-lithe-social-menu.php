<?php

if ( ! class_exists( 'Lithe_Social_Menu' ) ) {

    class Lithe_Social_Menu {

        /**
         * Maps urls to icon names
         *
         * @var array
         */
        protected $icons = array(
            'vk.com'        => 'vk',
            'facebook.com'  => 'facebook',
            'fb.me'         => 'facebook',
            'youtube.com'   => 'youtube',
            'youtu.be'      => 'youtube',
            'instagram.com' => 'instagram',
            'twitter.com'   => 'twitter',
        );

        /**
         * Constructs new lithe social menu
         *
         * @return void
         */
        public function __construct() {
            add_filter( 'walker_nav_menu_start_el', array( $this, 'replace_social_links_with_icons' ) , 10, 4 );
        }

        /**
         * Replaces social menu link text with icons
         *
         * @param  string   $item_output
         * @param  WP_Post  $item
         * @param  int      $depth
         * @param  stdClass $args
         *
         * @return string
         */
        public function replace_social_links_with_icons( string $item_output, WP_Post $item, int $depth, $args ): string {
            if ( 'social' === $args->theme_location ) {

                foreach ( $this->icons as $uri => $icon ) {
                    if ( 1 === preg_match('/ href="https?:\/\/' . $uri . '/i', $item_output) ) {
                        $found = $icon;
                    }
                }

                $class = isset( $found ) ? "fab fa-fw fa-$found" : "fas fa-fw fa-external-link-square-alt";

                $item_output = preg_replace('/(<a.*>)(.*)(<\/a>)/is', '$1<i title="$2" class="' . $class . '"></i><span class="screen-reader-text">$2</span>$3', $item_output);
            }

            return $item_output;
        }

    }

    return new Lithe_Social_Menu();

}
