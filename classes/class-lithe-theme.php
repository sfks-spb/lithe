<?php

if ( ! class_exists('Lithe_Theme') ) {

    class Lithe_Theme {

        /**
         * Version of user roles
         *
         * @var int
         */
        const ROLES_VERSIONS = 3;

        /**
         * Singleton instance
         *
         * @var Lithe_Theme
         */
        public static $instance = null;

        /**
         * Lithe asset loader instance
         *
         * @var Lithe_Asset_Loader
         */
        public $assets;

        /**
         * Lithe shortcode manager instance
         *
         * @var Lithe_Shortcodes
         */
        public $shortcodes;

        /**
         * Lithe carousel manager instance
         *
         * @var Lithe_Carousel
         */
        public $carousel;

        /**
         * Theme version
         *
         * @var string
         */
        public $version;

        /**
         * Gets singleton instance
         *
         * @return Lithe_Theme
         */
        public static function instance(): self {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructs new lithe theme instance
         *
         * @return void
         */
        public function __construct() {
            $this->includes();

            $this->version = wp_get_theme()->get( 'Version' );

            remove_action( 'wp_head', 'wp_generator' );

            add_action( 'after_setup_theme',  array( $this, 'setup_theme' ) );
            add_action( 'after_switch_theme', 'FortAwesome\FontAwesome_Loader::initialize' );
            add_action( 'switch_theme', array( $this, 'remove_roles' ) );
            add_action( 'switch_theme', array( $this, 'maybe_deactivate_fontawesome' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
            add_action( 'widgets_init', array( $this, 'register_widget_areas' ) );
            add_action( 'init', array( $this, 'add_roles' ) );
            add_action( 'font_awesome_preferences', array( $this, 'register_fontawesome_preferences' ) );
            add_filter( 'the_content_more_link', array( $this, 'modify_read_more_link' ) );
        }

        /**
         * Registers bundled FontAwesome preferences
         *
         * @return void
         */
        public function register_fontawesome_preferences(): void {
            FortAwesome\fa()->register( array(
                'name'           => 'lithe',
                'technology'     => 'svg',
                'v4Compat'       => false,
                'pseudoElements' => false,
                'version'        => array(
                    array( '5.10.0', '>=' ),
                    array( '6.0.0', '<' ),
                ),
            ) );
        }

        /**
         * Maybe deactivates FontAwesome
         *
         * @return void
         */
        public function maybe_deactivate_fontawesome(): void {
            FortAwesome\FontAwesome_Loader::maybe_deactivate();
            FortAwesome\FontAwesome_Loader::maybe_uninstall();
        }

        /**
         * Adds user roles
         *
         * @return void
         */
        public function add_roles(): void {
            if ( get_option( 'lithe_roles_version', 0 ) < Lithe_Theme::ROLES_VERSIONS ) {

                remove_role( 'athlete' );

                add_role(
                    'athlete',
                    __( 'Athlete', 'lithe' ),
                    array(
                        'read'         => true,
                        'edit_dog'     => true,
                        'delete_dog'   => true,
                        'publish_dogs' => true,
                    )
                );

                update_option( 'lithe_roles_version', Lithe_Theme::ROLES_VERSIONS );

            }
        }

        /**
         * Removes user roles
         *
         * @return void
         */
        public function remove_roles(): void {
            delete_option( 'lithe_roles_version' );
            remove_role( 'athlete' );
        }

        /**
         * Modifies read more link
         *
         * @return string
         */
        public function modify_read_more_link(): string {
            return '<p><a class="more-link" href="' . get_permalink() . '">' . __( 'Read more', 'lithe' ) . ' <i class="fas fa-arrow-right"></i></a></p>';
        }

        /**
         * Registers widget areas
         *
         * @return void
         */
        public function register_widget_areas(): void {
            register_sidebar( array(
                'name'          => __( 'Primary Widget Area', 'lithe' ),
                'id'            => 'sidebar-1',
                'description'   => __( 'The primary widget area', 'lithe' ),
                'before_widget' => '<div class="sidebar-widget has-light-gray-box-shadow has-very-light-gray-background-color has-light-gray-border-color">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="sidebar-widget-title has-dark-gray-border-color has-light-gray-text-shadow">',
                'after_title'   => '</h3>',
            ) );
        }

        /**
         *
         */
        public function register_menus( array $menus ) {
            foreach ( $menus as $location => $description ) {
                register_nav_menu( $location, $description);
            }
        }

        /**
         * Setups theme
         *
         * @return void
         */
        public function setup_theme(): void {
            add_theme_support( 'automatic-feed-links' );
            add_theme_support( 'title-tag' );
            add_theme_support( 'responsive-embeds' );
            add_theme_support(
                'html5',
                array(
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'script',
                    'style',
                )
            );

            // Colors
            add_theme_support( 'editor-color-palette', array(
                array(
                    'name' => __( 'Dark Blue', 'lithe' ),
                    'slug' => 'dark-blue',
                    'color' => '#037dc7',
                ),
                array(
                    'name' => __( 'Dark Red', 'lithe' ),
                    'slug' => 'dark-red',
                    'color' => '#e74040',
                ),
                array(
                    'name' => __( 'White', 'lithe' ),
                    'slug' => 'white',
                    'color' => '#ffffff',
                ),
                array(
                    'name' => __( 'Very Light Gray', 'lithe' ),
                    'slug' => 'very-light-gray',
                    'color' => '#fefefe',
                ),
                array(
                    'name' => __( 'Light Gray', 'lithe' ),
                    'slug' => 'light-gray',
                    'color' => '#b4b4b4',
                ),
                array(
                    'name' => __( 'Dark Gray', 'lithe' ),
                    'slug' => 'dark-gray',
                    'color' => '#3f3f3f',
                ),
            ) );

            // Thumbnails
            add_theme_support( 'post-thumbnails' );
            set_post_thumbnail_size( 250, 200, true );

            // Yoast SEO breadcrumbs
            add_theme_support( 'yoast-seo-breadcrumbs' );

            // Jetpack's infinite scroll
            add_theme_support( 'infinite-scroll', array(
                'type'      => 'scroll',
                'container' => 'site-content',
                'render'    => array( $this, 'infinite_scroll_render' ),
                'footer'    => false,
            ) );

            // load l10n
            load_theme_textdomain( 'lithe', get_template_directory() . '/languages' );

            // add support for async and defer scripts
            add_filter( 'script_loader_tag', $this->assets->get_filter(), 10, 2 );
        }

        /**
         * Gets classes directory
         *
         * @param  string $path - relative path
         * @return string
         */
        public function get_classes_directory_path( ?string $path ): string {
            $classes_directory = trailingslashit( get_template_directory() ) . 'classes';

            if ( is_null( $path ) ) {
                return $classes_directory;
            }

            return trailingslashit( $classes_directory ) . $path;
        }

        /**
         * Renders content for infinite scroll
         *
         * @return void
         */
        public function infinite_scroll_render(): void {
            while ( have_posts() ) {
                the_post();
                get_template_part( 'template-parts/content', get_post_format() );
            }
        }

        /**
         * Registers scripts
         *
         * @return void
         */
        public function register_scripts(): void {
            if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }

            // load packages
            $this->assets->load_packages( array(
                'GoogleTag',
                'YandexMetrika',
                'YandexMaps',
                'DataTables-Responsive@2.2.5',
                'OwlCarousel2@2.3.4',
                'cookieconsent@3.1.1',
                'OpenAPI@1.6.8',
                'Lithe@1.0.1',
            ) );
        }

        /**
         * Registers styles
         *
         * @return void
         */
        public function register_styles(): void {
            $this
                ->assets
                ->register_style( 'lithe-style', get_stylesheet_uri(), array(), $this->version )
                ->register_style( 'lithe-print-style', get_template_directory_uri() . '/print.css', null, $this->version, 'print' )
                ->register_style( 'lithe-fonts' )
                ->add_inline_style( 'lithe-fonts', '@import url("https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=PT+Serif&display=swap");' );
        }

        /**
         * Includes dependencies
         *
         * @return void
         */
        protected function includes(): void {
            $template_directory = get_template_directory();

            include_once $template_directory . '/vendor/fortawesome/wordpress-fontawesome/index.php';

            include_once $template_directory . '/includes/template-functions.php';
            include_once $template_directory . '/classes/class-lithe-social-menu.php';
            include_once $template_directory . '/classes/class-lithe-aside-menu.php';
            include_once $template_directory . '/classes/class-lithe-customizer.php';
            include_once $template_directory . '/classes/class-lithe-breadcrumbs.php';
            include_once $template_directory . '/classes/class-lithe-widgets.php';
            include_once $template_directory . '/classes/class-lithe-taxonomies.php';
            include_once $template_directory . '/classes/class-lithe-post-types.php';
            include_once $template_directory . '/classes/class-lithe-meta-boxes.php';
            include_once $template_directory . '/classes/class-lithe-wpcf7.php';

            include_once $template_directory . '/classes/class-lithe-svg.php';
            include_once $template_directory . '/includes/svg-functions.php';

            include_once $template_directory . '/classes/class-lithe-rest.php';
            $this->rest = new Lithe_Rest();

            include_once $template_directory . '/classes/class-lithe-shortcodes.php';
            $this->shortcodes = new Lithe_Shortcodes();

            include_once $template_directory . '/classes/class-lithe-carousel.php';
            include_once $template_directory . '/includes/carousel-functions.php';
            $this->carousel = new Lithe_Carousel();

            include_once $template_directory . '/classes/class-lithe-asset-manifest.php';
            include_once $template_directory . '/classes/class-lithe-asset-loader.php';
            $this->assets = new Lithe_Asset_Loader();
        }

    }

}
