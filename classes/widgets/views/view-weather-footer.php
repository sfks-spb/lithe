<?php
    printf (
        __( 'Weather data provided by %s', 'lithe' ),
        '<a title="AccuWeather" rel="nofollow" href="https://www.accuweather.com/">' .
        '<img src="' . get_template_directory_uri() . '/assets/images/brands/accuweather.svg" /></a>'
    );
?>