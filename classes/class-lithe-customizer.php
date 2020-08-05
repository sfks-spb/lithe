<?php

if ( ! class_exists( 'Lithe_Customizer' ) ) {

    class Lithe_Customizer {

        /**
         *
         */
        public function __construct() {
            add_action( 'customize_register', array( $this, 'lithe_customize_register' ) );
        }

        /**
         *
         */
        public function lithe_customize_register( $wp_customize ): void {
            $this->add_logo_settings( $wp_customize );
            $this->add_infobar_settings( $wp_customize );
        }

        /**
         * Adds site logo setting
         *
         * @param WP_Customize $wp_customize
         * @return void
         */
        protected function add_logo_settings( $wp_customize ): void {
            $this->add_settings( $wp_customize, array(
                'lithe_logo_svg' => 'plain',
            ) );

            $choices = array_combine( array_keys( Lithe_SVG::$logos ), array(
                _x( 'Plain', 'Plain logo', 'lithe' ),
                __( 'Stay At Home', 'lithe' ),
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lithe_logo_svg', array(
                'label'    => __( 'Site logo', 'lithe' ),
                'section'  => 'title_tagline',
                'settings' => 'lithe_logo_svg',
                'type'     => 'select',
                'choices'  => $choices,
            ) ) );
        }

        /**
         *
         */
        protected function add_infobar_settings( $wp_customize ): void {
            $wp_customize->add_section( 'lithe_infobar', array(
                'title'           => __( 'Infobar', 'lithe' ),
                'description'     => __( 'Infobar below the header on the home page.', 'lithe' ),
                'active_callback' => 'is_front_page',
            ) );

            $this->add_settings( $wp_customize, array(
                'lithe_infobar_icon_collection' => 'fas',
                'lithe_infobar_icon_name'       => '',
                'lithe_infobar_message'         => '',
                'lithe_infobar_link_text'       => __( 'Learn more', 'lithe' ),
                'lithe_infobar_link_title'      => '',
                'lithe_infobar_link_url'        => '',
                'lithe_infobar_bg_color'        => '#105991',
            ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lithe_infobar_icon_name', array(
                'label'    => __( 'FontAwesome icon name (leave empty for no icon)', 'lithe' ),
                'section'  => 'lithe_infobar',
                'settings' => 'lithe_infobar_icon_name',
                'type'     => 'text',
            ) ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lithe_infobar_icon_collection', array(
                'label'    => __( 'FontAwesome icon collection', 'lithe' ),
                'section'  => 'lithe_infobar',
                'settings' => 'lithe_infobar_icon_collection',
                'type'     => 'select',
                'choices'  => array(
                    'fas' => __( 'Solid', 'lithe' ),
                    'far' => __( 'Regular', 'lithe' ),
                    'fab' => __( 'Brands', 'lithe' ),
                ),
            ) ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lithe_infobar_message', array(
                'label'    => __( 'Message (leave empty to hide infobar)', 'lithe' ),
                'section'  => 'lithe_infobar',
                'settings' => 'lithe_infobar_message',
                'type'     => 'textarea',
            ) ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lithe_infobar_link_text', array(
                'label'    => __( 'Link text', 'lithe' ),
                'section'  => 'lithe_infobar',
                'settings' => 'lithe_infobar_link_text',
                'type'     => 'text',
            ) ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lithe_infobar_link_url', array(
                'label'    => __( 'Link URL (leave empty for no button)', 'lithe' ),
                'section'  => 'lithe_infobar',
                'settings' => 'lithe_infobar_link_url',
                'type'     => 'url',
            ) ) );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lithe_infobar_link_title', array(
                'label'    => __( 'Link Title', 'lithe' ),
                'section'  => 'lithe_infobar',
                'settings' => 'lithe_infobar_link_title',
                'type'     => 'text',
            ) ) );

            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'lithe_infobar_bg_color', array(
                'label'    => __( 'Background color', 'lithe' ),
                'section'  => 'lithe_infobar',
                'settings' => 'lithe_infobar_bg_color',
            ) ) );
        }

        /**
         *
         */
        protected function add_settings( $wp_customize, array $settings ): void {
            foreach ( $settings as $id => $default ) {
                $wp_customize->add_setting( $id, array( 'default' => $default ) );
            }
        }
    }

    return new Lithe_Customizer();

}