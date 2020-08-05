<ul class="weather-forecast">

<?php foreach ( $forecast as $location => $weather ): ?>

    <li class="forecast" data-location-key="<?php echo esc_attr( $location ); ?>">

        <span class="forecast-icon">
            <i class="fas fa-<?php echo esc_attr( $icons[ $weather['WeatherIcon'] ] ); ?> fa-lg fa-pull-left"></i>
        </span>

        <a title="<?php echo esc_attr( $locations[ $location ] ); ?>" class="forecast-location" href="<?php echo esc_attr( $weather['Link'] ); ?>" target="_blank">
            <?php echo $locations[ $location ]; ?></a>

        <time datetime="<?php echo esc_attr( $weather['DateTime'] ); ?>">
            <?php echo wp_date( 'H:i', $weather['EpochDateTime'] ); ?>
        </time>

        <span class="forecast-data">

            <?php echo esc_html( $weather['IconPhrase'] ); ?>

            <span class="forecast-extra">

                <i class="fas fa-thermometer-quarter fa-fw"></i><?php echo round( $weather['Temperature']['Value'], 1 ); ?>&deg;C
                <i class="fas fa-wind fa-fw"></i><?php echo round( $weather['Wind']['Speed']['Value'], 1 ) . ' ' . __( 'km/h', 'lithe' ); ?>

            </span>

        </span>

    </li>

<?php endforeach; ?>

</ul>

<span class="weather-updated"><?php echo __( 'Updated:', 'lithe' ) . ' ' . wp_date( lithe_date_format() ) ?></span>