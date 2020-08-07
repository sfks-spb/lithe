<?php

if ( ! class_exists( 'Lithe_Taxonomy_Venue' ) ) {

    class Lithe_Taxonomy_Venue extends Lithe_Taxonomy {

        /**
         * Contains taxonomy handle.
         *
         * @var string
         */
        protected $handle = 'venue';

        /**
         * Contains list of custom fields and filters.
         *
         * @var array
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
         * Registers taxonomy.
         *
         * @return void
         */
        public function register(): void {
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
         * Outputs new taxonomy item form HTML.
         *
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        public function add_form_fields( WP_Term $term ): void {
            lithe_render( 'taxonomies/views/view-venue-add-form-fields', array( 'term' => $term ) );
        }

        /**
         * Outputs edit taxonomy item form HTML.
         *
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        public function edit_form_fields( WP_Term $term ): void {
            lithe_render( 'taxonomies/views/view-venue-edit-form-fields', array( 'term' => $term ) );
        }

        /**
         * Handles taxonomy item save.
         *
         * @param  int     $term_id Current term id.
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        public function save( int $term_id, WP_Term $term ): void {

            foreach ( $this->meta_kses as $meta_key => $allowed_html ) {

                if ( array_key_exists( $meta_key, $_POST ) ) {
                    update_term_meta( $term_id, $meta_key, wp_kses( $_POST[ $meta_key ], $allowed_html ) );
                }

            }

        }

        /**
         * Gets list of columns.
         *
         * @return array
         */
        public function columns(): array {
            return array(
                'address'   => __( 'Address', 'lithe' ),
                'website'   => __( 'Website', 'lithe' ),
                'posts'     => __( 'Trainers', 'lithe' ),
            );
        }

        /**
         * Handles custom columns output.
         *
         * @param  string $content Current column content.
         * @param  string $column_name Current column name.
         * @param  int    $term_id Current term id.
         *
         * @return void
         */
        public function custom_columns( string $content, string $column_name, int $term_id ) {
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
