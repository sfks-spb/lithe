<?php

if ( ! class_exists( 'Lithe_Taxonomy_Venue' ) ) {

    class Lithe_Taxonomy_Venue extends Lithe_Taxonomy {

        /**
         *
         */
        protected $handle = 'venue';

        /**
         *
         */
        protected $meta_kses = array(
            'link'        => 'strip',
            'address'     => 'strip',
            'coords'      => 'strip',
            'sports'      => 'strip',

            // Weather Widget
            'location_id' => 'strip',
            'position'    => 'strip',
        );

        /**
         *
         */
        public function register():void {
            register_taxonomy( $this->handle, 'trainer', array(
                'labels' => array(
                    'name'          => __( 'Venues', 'lithe' ),
                    'singular_name' => __( 'Venue', 'lithe' ),
                    'search_items'  => __( 'Search Venues', 'lithe' ),
                    'all_items'     => __( 'All Venues', 'lithe' ),
                    'edit_item'     => __( 'Edit Venue', 'lithe' ),
                    'update_item'   => __( 'Update Venue', 'lithe' ),
                    'add_new_item'  => __( 'Add New Venue', 'lithe' ),
                    'new_item_name' => __( 'New Venue', 'lithe' ),
                    'menu_name'     => __( 'Venues', 'lithe' ),
                ),
            ) );
        }

        /**
         *
         */
        public function add_form_fields( $term ):void {
            lithe_render( 'taxonomies/views/view-venue-add-form-fields', array( 'term' => $term ) );
        }

        /**
         *
         */
        public function edit_form_fields( $term ):void {
            lithe_render( 'taxonomies/views/view-venue-edit-form-fields', array( 'term' => $term ) );
        }

        /**
         *
         */
        public function save( $term_id, $term ):void {

            foreach ( $this->meta_kses as $meta_key => $allowed_html ) {

                if ( array_key_exists( $meta_key, $_POST ) ) {
                    update_term_meta( $term_id, $meta_key, wp_kses( $_POST[ $meta_key ], $allowed_html ) );
                }

            }

        }

        /**
         *
         */
        public function columns(): array {
            return array(
                'address'   => __( 'Address', 'lithe' ),
                'website'   => __( 'Website', 'lithe' ),
                'posts'     => __( 'Trainers', 'lithe' ),
            );
        }

        /**
         *
         */
        public function custom_columns( $content, $column_name, $term_id ):void {
            switch ( $column_name ) {

                case 'address':
                    echo get_term_meta( $term_id, 'address', true );
                    break;

                case 'website':
                    $link = get_term_meta( $term_id, 'link', true );

                    if ( ! empty( $link ) ) {
                        ?>
                            <a href="<?php echo esc_attr( $link ); ?>" target="_blank"><?php _e( '(link)', 'lithe' ); ?></a>
                        <?php
                    }

                    break;

            }
        }

    }

}