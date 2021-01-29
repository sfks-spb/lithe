<?php

return array(

    'scripts' => array(
        'default' => array(
            'src'       => 'rellax.js',
            'after'     => '
window.lithe = window.lithe || {};
window.lithe.rellax = new Rellax(".rellax");
            ',
            'in_footer' => true,
            'condition' => is_singular() && lithe_has_parallax_thumbnail(),
        ),
    ),

);