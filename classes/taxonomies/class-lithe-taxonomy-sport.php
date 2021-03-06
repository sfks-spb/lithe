<?php

if ( ! class_exists( 'Lithe_Taxonomy_Sport' ) ) {

    class Lithe_Taxonomy_Sport extends Lithe_Taxonomy {

        /**
         * Contains taxonomy handle.
         *
         * @var string
         */
        protected $handle = 'sport';

        /**
         * Registers taxonomy.
         *
         * @return void
         */
        public function register(): void {
            register_taxonomy( $this->handle, array( 'trainer', 'post', 'page' ), array(
                'labels' => array(
                    'name'          => __( 'Sports', 'lithe' ),
                    'singular_name' => __( 'Sport', 'lithe' ),
                    'search_items'  => __( 'Search Sports', 'lithe' ),
                    'all_items'     => __( 'All Sports', 'lithe' ),
                    'edit_item'     => __( 'Edit Sport', 'lithe' ),
                    'update_item'   => __( 'Update Sport', 'lithe' ),
                    'add_new_item'  => __( 'Add New Sport', 'lithe' ),
                    'new_item_name' => __( 'New Sport', 'lithe' ),
                    'menu_name'     => __( 'Sports', 'lithe' ),
                ),
                'public'       => true,
                'show_in_rest' => true,
                'rewrite'      => array( 'slug' => $this->handle . 's' ),
            ) );
        }

        /**
         * Gets list of columns.
         *
         * @return array
         */
        public function columns(): array {
            return array(
                'trainers'     => __( 'Trainers', 'lithe' ),
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

                case 'trainers':
                    $trainers = new WP_Query( array(
                        'post_type' => 'trainer',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'sport',
                                'terms'    => $term_id,
                            ),
                        ),
                    ) );

                    echo $trainers->found_posts;
                    break;

            }

        }

        /**
         * Outputs new taxonomy item form HTML.
         *
         * @param  string $taxonomy Current taxonomy slug.
         *
         * @return void
         */
        public function add_form_fields( string $taxonomy ):void {
            //
        }

        /**
         * Outputs edit taxonomy item form HTML.
         *
         * @param  WP_Term $term Current term instance.
         *
         * @return void
         */
        public function edit_form_fields( WP_Term $term ):void {
            //
        }

        /**
         * Handles taxonomy item save.
         *
         * @param  int $term_id Current term id.
         * @param  int $term_taxonomy_id Current term instance.
         *
         * @return void
         */
        public function save( int $term_id, int $term_taxonomy_id ): void {
            //
        }

    }

}
