<?php

if ( ! class_exists( 'Lithe_Widget_Weather' ) ) {

    class Lithe_Widget_Weather extends WP_Widget {

        /**
         * Contains prefix for cache key
         *
         * @var string
         */
        protected $transient_key_prefix = 'lithe_weather';

        /**
         * Contains AccuWeather API endpoint uri
         *
         * @var string
         */
        protected $endpoint = 'http://dataservice.accuweather.com/forecasts/v1/hourly/12hour/%s';

        /**
         * Contains list of venue locations
         *
         * @var array
         */
        protected $locations = array();

        /**
         * Maps icon codes to FontAwesome icon names
         *
         * @var array
         */
        protected $icons = array(
            1  => 'sun',                  // Sunny
            2  => 'cloud-sun',            // Mostly Sunny
            3  => 'cloud-sun',            // Partly Sunny
            4  => 'cloud-sun',            // Intermittent Clouds
            5  => 'cloud-sun',            // Hazy Sunshine
            6  => 'cloud-sun',            // Mostly Cloudy
            7  => 'cloud',                // Cloudy
            8  => 'cloud',                // Dreary (Overcast)
            11 => 'smog',                 // Fog
            12 => 'cloud-showers-heavy',  // Showers
            13 => 'cloud-sun-rain',       // Mostly Cloudy w/ Showers
            14 => 'cloud-sun-rain',       // Partly Sunny w/ Showers
            15 => 'bolt',                 // T-Storms
            16 => 'bolt',                 // Mostly Cloudy w/ T-Storms
            17 => 'bolt',                 // Partly Sunny w/ T-Storms
            18 => 'cloud-rain',           // Rain
            19 => 'wind',                 // Flurries
            20 => 'wind',                 // Mostly Cloudy w/ Flurries
            21 => 'wind',                 // Partly Sunny w/ Flurries
            22 => 'snowflake',            // Snow
            23 => 'snowflake',            // Mostly Cloudy w/ Snow
            24 => 'icicles',              // Ice
            25 => 'snowflake',            // Sleet
            26 => 'snowflake',            // Freezing Rain
            29 => 'cloud-rain',           // Rain and Snow
            30 => 'fa-temperature-high',  // Hot
            31 => 'fa-temperature-low',   // Cold
            32 => 'wind',                 // Windy
            33 => 'moon',                 // Clear
            34 => 'cloud-moon',           // Mostly Clear
            35 => 'cloud-moon',           // Partly Cloudy
            36 => 'cloud-moon',           // Intermittent Clouds
            37 => 'cloud-moon',           // Hazy Moonlight
            38 => 'cloud-moon',           // Mostly Cloudy
            39 => 'cloud-moon-rain',      // Partly Cloudy w/ Showers
            40 => 'cloud-moon-rain',      // Mostly Cloudy w/ Showers
            41 => 'bolt',                 // Partly Cloudy w/ T-Storms
            42 => 'bolt',                 // Mostly Cloudy w/ T-Storms
            43 => 'wind',                 // Mostly Cloudy w/ Flurries
            44 => 'snowflake',            // Mostly Cloudy w/ Snow
        );

        /**
         * Constructs new lithe weather widget instance
         *
         * @return void
         */
        public function __construct() {
            parent::__construct(
                'lithe_weather_widget',
                esc_html__( 'Lithe Weather Widget', 'lithe' ),
                array('description' => esc_html__( 'Weather widget for Lithe theme', 'lithe' ) )
            );

            $this->locations = $this->get_locations();

            if ( wp_doing_ajax() ) {
                add_action( 'wp_ajax_lithe_refresh_weather', array( $this, 'ajax' ) );
                add_action( 'wp_ajax_nopriv_lithe_refresh_weather' . $action, array( $this, 'ajax' ) );
            }
        }

        /**
         * Outputs widget html
         *
         * @return void
         */
        public function widget( $args, $instance ) {
            echo $args['before_widget'];

            ?>
                <?php echo $args['before_title'] . esc_html( $instance[ 'title' ] ) . $args['after_title']; ?></h3>

                <div class="weather-content" data-widget-action="refresh_weather" data-widget-id="<?php echo esc_attr($this->number); ?>" data-widget-update-interval="120">

                    <?php
                        lithe_render( 'widgets/views/view-weather-forecast', array(
                            'forecast'  => $this->get_next_hour_forecast( $instance ),
                            'icons'     => $this->icons,
                            'locations' => $this->locations,
                        ) );
                    ?>

                </div>

                <div class="weather-footer"><?php lithe_render( 'widgets/views/view-weather-footer' ); ?></div>

            <?php

            echo $args['after_widget'];
        }

        /**
         * Outputs json for ajax calls
         *
         * @return void
         */
        public function ajax(): void {
            check_ajax_referer( 'lithe-ajax-nonce' );

            $widget_id = ( array_key_exists( 'widget_id', $_REQUEST ) ) ? $_REQUEST['widget_id'] : null;

            if ( ! $widget_id ) {
                wp_send_json_error( __( 'Missing widget id', 'lithe' ) );
                wp_die();
            }

            $widget_options = get_option($this->option_name);
            $options = $widget_options[ $widget_id ];

            ob_start();

            lithe_render( 'widgets/views/view-weather-forecast', array(
                'forecast'  => $this->get_next_hour_forecast( $instance ),
                'icons'     => $this->icons,
                'locations' => $this->locations,
            ) );

            $data = array();
            $data['html'] = $this->sanitize_html( ob_get_contents() );
            $data['updated'] = time();

            ob_end_clean();

            wp_send_json_success( $data );
            wp_die();
        }

        /**
         * Outputs widget settings form
         *
         * @param  array $instance widget instance data
         *
         * @return void
         */
        public function form( $instance ) {

            lithe_render( 'widgets/views/view-weather-form', array(
                'widget'          => $this,
                'title'           => ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'New Weather Widget', 'lithe' ),
                'apikey'          => ( ! empty( $instance['apikey'] ) ) ? $instance['apikey'] : '',
                'update_interval' => ( ! empty( $instance['update_interval'] ) ) ? $instance['update_interval'] : 6,
            ) );

        }

        /**
         * Handles widget settings update
         *
         * @param  array $new_instance new widget data
         * @param  array $old_instance old widget data
         *
         * @return array
         */
        public function update( $new_instance, $old_instance ) {
            return array(
                'title'           => ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : __( 'New Weather Widget', 'lithe' ),
                'apikey'          => ( ! empty( $new_instance['apikey'] ) ) ? sanitize_text_field( $new_instance['apikey'] ) : '',
                'update_interval' => ( ! empty( $new_instance['update_interval'] ) ) ? sanitize_text_field( $new_instance['update_interval'] ) : 6,
            );
        }

        /**
         * Sanitizes html
         *
         * @param  string $html html to sanitize
         *
         * @return string
         */
        protected function sanitize_html( string $html ): string {
            $html = str_replace( "\n", '', $html );
            return preg_replace( array( '/>\s+/', '/\s+</' ), array( '> ', ' <' ), $html );
        }

        /**
         * Gets forecast for upcoming hour
         *
         * @param  array $instance widget instance data
         *
         * @return array
         */
        protected function get_next_hour_forecast( array $instance ): array {
            $now = time();
            $weather = $this->get_forecast( $instance );

            foreach ( $weather as &$forecast ) {

                foreach ( $forecast as $hourly ) {

                    if ( ! is_array( $hourly ) ) break;

                    if ( $hourly['EpochDateTime'] > $now ) {

                        $forecast = $hourly;
                        break;

                    }

                }

            }

            return $weather;
        }

        /**
         * Gets list of locations
         *
         * @return array
         */
        protected function get_locations(): array {
            $venues = get_terms( array(
                'taxonomy'   => 'venue',
                'orderby'    => 'position',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'position',
                        'type'    => 'NUMERIC',
                    ),
                    array(
                        'key'     => 'location_id',
                        'value'   => '',
                        'compare' => '!=',
                    ),
                ),
            ) );

            $locations = array();

            foreach ( $venues as $venue ) {
                $locations[ get_term_meta( $venue->term_id, 'location_id', true ) ] = $venue->name;
            }

            return $locations;
        }

        /**
         * Gets forecast data
         *
         * @param  array $instance widget instance data
         *
         * @return array
         */
        protected function get_forecast( array $instance ): array {

            $weather = array();

            foreach ( $this->locations as $location => $name ) {

                $transient_key = $this->transient_key_prefix . '_' . $location;
                $forecast = get_transient( $transient_key );

                if ( ! $forecast ) {

                    $query = http_build_query( array(
                        'apikey'   => $instance['apikey'],
                        'language' => 'ru-ru',
                        'metric'   => 'true',
                        'details'  => 'true',
                    ) );

                    $response = wp_remote_get( sprintf( $this->endpoint, $location ) . '?' . $query );

                    if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
                        $response_body = wp_remote_retrieve_body( $response );
                        $forecast = json_decode( $response_body, true );
                    }

                    set_transient( $transient_key, $forecast, $instance['update_interval'] * 60 * 60 );

                }

                if ( empty( $forecast ) ) continue;

                $weather[ $location ] = $forecast;

            }

            return $weather;
        }
    }

}
