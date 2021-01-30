<?php

if ( ! class_exists( 'Lithe_Asset_Loader' ) ) {

    class Lithe_Asset_Loader {

        /**
         * Contains list of loaded packages.
         *
         * @var array
         */
        protected $loaded = array();

        /**
         * Contains relative path to assets directory.
         *
         * @var string
         */
        protected $assets_directory = 'assets/js/';

        /**
         * Gets script loader filter.
         *
         * @return callable
         */
        public function get_filter() : callable {
            return array( $this, 'filter_script_loader_tag' );
        }

        /**
         * Processes async and defer attributes.
         *
         * @param string  $tag Script tag code.
         * @param string  $handle Script handle.
         *
         * @return string
         */
        public function filter_script_loader_tag( string $tag, string $handle ): string {

            foreach ( array( 'async', 'defer' ) as $attr ) {

                if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
                    continue;
                }

                if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
                    $tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
                }

                break;
            }

            return $tag;
        }

        /**
         * Gets asset uri.
         *
         * @param  string $file Relative path to the file.
         *
         * @return string
         */
        public function get_assets_file_uri( string $file ): string {
            return get_theme_file_uri( $this->assets_directory ) . $file;
        }

        /**
         * Loads all packages.
         *
         * @return Lithe_Asset_Loader
         */
        public function load_packages_all(): self {

            $packages = array();

            foreach ( new DirectoryIterator( get_theme_file_path( $this->assets_directory ) ) as $file_info ) {

                if ( $file_info->isFile() || $file_info->isDot() || ! is_file( $file_info->getPathname() . '/package.php' ) ) {
                    continue;
                }

                $packages[] = $file_info->getFilename();

            }

            return $this->load_packages( $packages );

        }

        /**
         * Loads packages.
         *
         * @param  array $packages List of packages to load.
         *
         * @return Lithe_Asset_Loader
         */
        public function load_packages( array $packages ): self {

            foreach ( $packages as $package ) {

                if ( $this->is_package_loaded( $package ) ) {
                    // skip already loaded packages
                    continue;
                }

                $manifest_file = get_theme_file_path( $this->assets_directory . $package ) . '/package.php';

                if ( ! is_file( $manifest_file ) ) {
                    continue;
                }

                $manifest = new Lithe_Asset_Manifest( $manifest_file );

                if ( false !== strpos( $package, '@' ) ) {
                    list( $package, $version ) = explode( '@', $package, 2 );
                    $manifest->set_version( $version );
                }

                $manifest->set_name( $package );
                $manifest->set_loader( $this );

                $this->loaded[ $package ] = $manifest->load();

            }

            return $this;

        }

        /**
         * Checks if package already loaded.
         *
         * @param  string $package Package name to check.
         *
         * @return bool
         */
        public function is_package_loaded( string $package ): bool {
            return array_key_exists( $package, $this->loaded );
        }

        /**
         * Registers script.
         *
         * @param  string           $handle Script handle.
         * @param  string           $src Script source file.
         * @param  array|null       $deps Script dependencies.
         * @param  string|bool|null $ver Script version.
         * @param  bool             $in_footer Load in footer.
         * @param  array            $atts Script attributes.
         *
         * @return Lithe_Asset_Loader
         */
        public function register_script( string $handle, string $src = '', ?array $deps = array(), $ver = false, bool $in_footer = false, array $atts = array() ): self {
            wp_register_script( $handle, $src, $deps, $ver, $in_footer );

            foreach ( $atts as $key => $value ) {
                wp_script_add_data( $handle, $key, $value );
            }

            wp_enqueue_script( $handle );

            return $this;
        }

        /**
         * Adds inline script before handle.
         *
         * @param  string       $handle Script handle.
         * @param  string|array $data Inline script data.
         *
         * @return Lithe_Asset_Loader
         */
        public function add_before_script( string $handle, $data ): self {

            if ( is_array( $data ) ) {
                $data = implode("\n", $data);
            }

            wp_add_inline_script( $handle, $data, 'before' );

            return $this;

        }

        /**
         * Adds inline script after handle.
         *
         * @param  string       $handle Script handle.
         * @param  string|array $data Inline script data.
         *
         * @return Lithe_Asset_Loader
         */
        public function add_after_script( string $handle, $data ): self {

            if ( is_array( $data ) ) {
                $data = implode("\n", $data);
            }

            wp_add_inline_script( $handle, $data, 'after' );

            return $this;

        }

        /**
         * Localizes script.
         *
         * @param  string $handle Script handle.
         * @param  string $object_name JS object name.
         * @param  array  $l10n Localization data.
         *
         * @return Lithe_Asset_Loader
         */
        public function localize_script( string $handle, string $object_name, array $l10n ): self {
            wp_localize_script( $handle, $object_name, $l10n );

            return $this;
        }

        /**
         * Registers stylesheet.
         *
         * @param  string           $handle Stylesheet handle.
         * @param  string           $src Stylesheet source.
         * @param  array|null       $deps Stylesheet dependencies.
         * @param  string|bool|null $ver Stylesheet version.
         * @param  string           $media Stylesheet media.
         * @param  array            $atts Stylesheet attributes.
         *
         * @return Lithe_Asset_Loader
         */
        public function register_style( string $handle, string $src = '', ?array $deps = array(), $ver = false, string $media = 'all', array $atts = array() ): self {
            wp_register_style( $handle, $src, $deps, $ver, $media );

            foreach ( $atts as $key => $value ) {
                wp_style_add_data( $handle, $key, $value );
            }

            wp_enqueue_style( $handle );

            return $this;
        }

        /**
         * Adds inline style.
         *
         * @param  string       $handle Stylesheet handle.
         * @param  string|array $data Inline style data.
         *
         * @return Lithe_Asset_Loader
         */
        public function add_inline_style( string $handle, $data ): self {
            if ( is_array( $data ) ) {
                $data = implode("\n", $data);
            }

            wp_add_inline_style( $handle, $data );

            return $this;
        }
    }

}
