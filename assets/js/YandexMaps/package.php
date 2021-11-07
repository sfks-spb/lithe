<?php

return array(

    'scripts' => array(

        'default' => array(

            'src' => 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=18e3cb03-ec89-4f4e-a9e8-5cc4c86f177b',

            'l10n' => array(

                'youarehere' => __( 'You are here', 'lithe' ),

            ),

            'condition' => is_page_template( 'templates/template-venues.php' ),

        ),

    ),

);
