<?php

return array(

    'scripts' => array(

        'default' => array(

            'src' => 'https://www.googletagmanager.com/gtag/js?id=UA-160845069-1',

            'atts' => array( 'async' => true ),

            'after' => "
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-160845069-1', { 'transport_type': 'beacon'});
            ",

        ),

    ),

);
