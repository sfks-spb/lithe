<?php

if ( ! class_exists( 'Lithe_Asset_Manifest' ) ) {

    class Lithe_Asset_Manifest {

        /**
         * Contains loader instance.
         *
         * @var Lithe_Asset_Loader|null
         */
        protected $loader = null;

        /**
         * Contains package name.
         *
         * @var string|null
         */
        public $name = null;

        /**
         * Contains package version.
         *
         * @var string
         */
        public $version;

        /**
         * Contains list of scripts to load.
         *
         * @var array
         */
        public $scripts = array();

        /**
         * Contains list of stylesheets to load.
         *
         * @var array
         */
        public $styles = array();

        /**
         * Constructs new lithe manifest instance.
         *
         * @param  string $manifest_file Manifest file path.
         *
         * @return void
         */
        public function __construct( string $manifest_file ) {
            $manifest = include $manifest_file;

            if ( is_array( $manifest ) && array_key_exists( 'scripts', $manifest ) ) {
                $this->scripts = $manifest['scripts'];
            }

            if ( is_array( $manifest ) && array_key_exists( 'styles', $manifest ) ) {
                $this->styles = $manifest['styles'];
            }

            $this->version = lithe()->version;
        }

        /**
         * Sets asset name
         *
         * @param  string $name Asset name.
         *
         * @return Lithe_Asset_Manifest
         */
        public function set_name( string $name ): self {
            $this->name = $name;
            $this->prefix = strtolower( $this->name );

            return $this;
        }

        /**
         * Sets asset version
         *
         * @param  string|null $version
         *
         * @return Lithe_Asset_Manifest
         */
        public function set_version( ?string $version ): self {
            $this->version = $version;

            return $this;
        }

        /**
         * Sets asset loader instance.
         *
         * @param  Lithe_Asset_Loader $loader Asset loader instance.
         *
         * @return Lithe_Asset_Manifest
         */
        public function set_loader( Lithe_Asset_Loader $loader ): self {
            $this->loader = $loader;

            return $this;
        }

        /**
         * Loads package with loader.
         *
         * @return Lithe_Asset_Manifest
         */
        public function load(): self {

            if ( ! is_null( $this->loader ) ) {
                $this->register_scripts();
                $this->register_styles();
            }

            return $this;
        }

        /**
         * Gets src uri
         *
         * @param  string $file Relative path to file.
         *
         * @return string
         */
        protected function get_src_file_uri( string $file ): string {
            if ( ! $this->version ) {
                return $file;
            }

            return $this->loader->get_assets_file_uri( $this->name . '@' . $this->version . '/' . $file );
        }

        /**
         * Adds handle prefix.
         *
         * @param  string &$handle Handle to prepend prefix to.
         *
         * @return void
         */
        protected function prefix_handle( string &$handle ): void {
            if ( 'default' === $handle ) {
                $handle = $this->prefix;
                return;
            }

            $handle = $this->prefix . '-' . $handle;
        }

        /**
         * Initializes script data with default values.
         *
         * @param  array &$script Script data.
         *
         * @return void
         */
        protected function init_with_default_script_args( array &$script ): void {
            $script = array_merge( array(
                'src'       => '',
                'atts'      => array(),
                'deps'      => null,
                'ver'       => $this->version,
                'before'    => false,
                'after'     => false,
                'l10n'      => false,
                'in_footer' => false,
                'condition' => true,
            ), $script );
        }

        /**
         * Initializes style data with default values.
         *
         * @param  array &$style Style data.
         *
         * @return void
         */
        protected function init_with_default_style_args( array &$style ): void {
            $style = array_merge( array(
                'src'       => '',
                'atts'      => array(),
                'deps'      => null,
                'ver'       => $this->version,
                'inline'    => false,
                'media'     => 'all',
                'condition' => true,
            ), $style );
        }

        /**
         * Registers package scripts.
         *
         * @return void
         */
        protected function register_scripts(): void {
            foreach ($this->scripts as $handle => $script) {
                $this->init_with_default_script_args( $script );

                if ( ! $script['condition'] ) {
                    continue;
                }

                $this->prefix_handle( $handle );

                $this->loader->register_script(
                    $handle,
                    $this->get_src_file_uri( $script['src'] ),
                    $script['deps'],
                    $script['ver'],
                    $script['in_footer'],
                    $script['atts']
                );

                foreach ( array( 'before', 'after' ) as $position ) {
                    if ( $script[ $position ] ) {
                        $fn = 'add_' . $position . '_script';
                        $this->loader->$fn( $handle, $script[ $position ], $position );
                    }
                }

                if ( $script[ 'l10n' ] ) {
                    $this->loader->localize_script( $handle, $handle . '_l10n', $script['l10n'] );
                }
            }
        }

        /**
         * Registers package styles.
         *
         * @return void
         */
        protected function register_styles(): void {
            foreach ($this->styles as $handle => $style) {
                $this->init_with_default_style_args( $style );

                if ( ! $style['condition'] ) {
                    continue;
                }

                $this->prefix_handle( $handle );

                $this->loader->register_style(
                    $handle,
                    $this->get_src_file_uri( $style['src'] ),
                    $style['deps'],
                    $style['ver'],
                    $style['media'],
                    $style['atts']
                );

                if ( $style['inline'] ) {
                    $this->loader->add_inline_style( $handle, $style['inline'] );
                }
            }
        }

    }

}
