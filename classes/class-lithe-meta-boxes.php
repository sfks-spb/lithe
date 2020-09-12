<?php

if ( ! class_exists('Lithe_Meta_Boxes') ) {

    class Lithe_Meta_Boxes {

        /**
         * Constructs new lithe meta boxes instance.
         *
         * @return void
         */
        public function __construct() {
            $this->includes();

            add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ) );
            add_action( 'admin_menu', array( $this, 'add_meta_boxes' ) );
            add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );
        }

        /**
         * Adds meta boxes to WordPress.
         *
         * @return void
         */
        public function add_meta_boxes(): void {

            // Posts
            add_meta_box(
                'post-style',
                __( 'Post Style', 'lithe' ),
                'Lithe_Meta_Box_Post_Style::render',
                'post',
                'side',
                'high'
            );

            // Trainers.
            add_meta_box(
                'trainer-profile',
                __( "Trainer's Profile", 'lithe' ),
                'Lithe_Meta_Box_Trainer_Profile::render',
                'trainer',
                'normal',
                'high'
            );

            add_meta_box(
                'trainer-timetable',
                __( "Trainer's Timetable", 'lithe' ),
                'Lithe_Meta_Box_Trainer_Timetable::render',
                'trainer',
                'normal',
                'high'
            );

            // Dogs
            add_meta_box(
                'dog-profile',
                __( "Dogs's Profile", 'lithe' ),
                'Lithe_Meta_Box_Dog_Profile::render',
                'dog',
                'normal',
                'high'
            );

        }

        /**
         * Removes meta boxes
         *
         * @return void
         */
        public function remove_meta_boxes(): void {
            remove_meta_box('wpseo_meta', 'trainer', 'normal');
            remove_meta_box('wpseo_meta', 'dog', 'normal');
        }

        /**
         * Handles meta box save.
         *
         * @param  int     $post_id Current post id.
         * @param  WP_Post $post Current post instance.
         *
         * @return void
         */
        public function save_meta_boxes( int $post_id, WP_Post $post ): void {

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
                return;
            }

            if ( ! isset( $_POST['lithe_meta_nonce'] ) || ! wp_verify_nonce( $_POST['lithe_meta_nonce'], 'lithe_save_data' ) ) {
                return;
            }

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            do_action( 'save_' . $post->post_type . '_meta', $post_id, $post );

        }

        /**
         * Includes dependencies.
         *
         * @return void
         */
        protected function includes(): void {
            $meta_boxes_directory = get_template_directory() . '/classes/meta-boxes/';

            include_once $meta_boxes_directory . 'class-lithe-meta-box.php';
            include_once $meta_boxes_directory . 'class-lithe-meta-box-post-style.php';
            include_once $meta_boxes_directory . 'class-lithe-meta-box-trainer-profile.php';
            include_once $meta_boxes_directory . 'class-lithe-meta-box-trainer-timetable.php';
            include_once $meta_boxes_directory . 'class-lithe-meta-box-dog-profile.php';
        }

    }

    return new Lithe_Meta_Boxes();

}
