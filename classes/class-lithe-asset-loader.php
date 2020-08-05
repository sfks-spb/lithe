<?php

if ( ! class_exists( 'Lithe_Asset_Loader' ) ) {

    class Lithe_Asset_Loader {

        /**
         *
         */
        protected $loaded = array();

        /**
         *
         */
        protected $assets_directory = 'assets/js/';

        /**
         *
         */
        public function get_filter() : callable {
            return array( $this, 'filter_script_loader_tag' );
        }

        /**
         *
         */
        public function filter_script_loader_tag( $tag, $handle ): string {
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
         *
         */
        public function get_assets_file_uri( string $file ): string {
            return get_theme_file_uri( $this->assets_directory ) . $file;
        }

        /**
         *
         */
        public function load_packages( array $packages ): self {

            foreach ( $packages as $package ) {

                if ( $this->is_package_loaded( $package ) ) {
                    // skip already loaded packages
                    continue;
                }

                list( $name, $version ) = explode( '@', $package, 2 );

                $manifest_directory = ( $version ) ? $name . '-' . $version : $name;
                $manifest_file = get_theme_file_path( $this->assets_directory . $manifest_directory ) . '/package.php';

                if ( ! is_file( $manifest_file ) ) {
                    continue;
                }

                $manifest = new Lithe_Asset_Manifest( $manifest_file );
                $manifest->set_name( $name );
                $manifest->set_version( $version );
                $manifest->set_loader( $this );
                $this->loaded[ $package ] = $manifest->load();
            }

            return $this;
        }

        /**
         *
         */
        public function is_package_loaded( $package ): bool {
            return array_key_exists( $package, $this->loaded );
        }

        /**
         *
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
         *
         */
        public function add_before_script( string $handle, $data ): self {
            if ( is_array( $data ) ) {
                $data = implode("\n", $data);
            }

            wp_add_inline_script( $handle, $data, 'before' );

            return $this;
        }

        /**
         *
         */
        public function add_after_script( string $handle, $data ): self {
            if ( is_array( $data ) ) {
                $data = implode("\n", $data);
            }

            wp_add_inline_script( $handle, $data, 'after' );

            return $this;
        }

        /**
         *
         */
        public function localize_script( string $handle, string $object_name, array $l10n ): self {
            wp_localize_script( $handle, $object_name, $l10n );

            return $this;
        }

        /**
         *
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
         *
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
