<?php

if ( ! class_exists( 'Lithe_Widget_VK' ) ) {

    class Lithe_Widget_VK extends WP_Widget {

        /**
         * Constructs new lithe vk widget instance.
         *
         * @return void
         */
        public function __construct() {

            parent::__construct(
                'lithe_vk_widget',
                esc_html__( 'Lithe VK Widget', 'lithe' ),
                array('description' => esc_html__( 'VK group widget for Lithe theme', 'lithe' ) )
            );

        }

        /**
         * Outputs widget html.
         *
         * @param  array $args Widget arguments.
         * @param  array $instance Widget instance data.
         *
         * @return void
         */
        public function widget( $args, $instance ): void {

            echo $args['before_widget'];

            lithe_render( 'widgets/views/view-vk-group', array( 'vk' => $instance ) );

            echo $args['after_widget'];
        }

        /**
         * Outputs widget settings form.
         *
         * @param  array $instance widget instance data.
         *
         * @return void
         */
        public function form( $instance ): void {
            lithe_render( 'widgets/views/view-vk-form', array(
                'widget' => $this,
                'gid'    => ( ! empty( $instance['gid'] ) ) ? $instance['gid'] : '',
            ) );
        }

        /**
         * Handles widget settings update.
         *
         * @param  array $new_instance New widget data.
         * @param  array $old_instance Old widget data.
         *
         * @return array
         */
        public function update( $new_instance, $old_instance ): array {
            return array(
                'gid'   => ( ! empty( $new_instance['gid'] ) ) ? sanitize_text_field( $new_instance['gid'] ) : 0,
            );
        }

    }

}
