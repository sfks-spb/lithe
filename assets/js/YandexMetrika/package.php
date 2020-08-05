<?php

return array(

    'scripts' => array(
        'default' => array(
            'src' => 'https://mc.yandex.ru/metrika/tag.js',
            'atts' => array( 'async' => true ),
            'after' => "
window['ym'] = window['ym'] || function(){(window['ym'].a = window['ym'].a || []).push(arguments)};
window['ym'].l = 1*new Date();
ym(61593826, 'init', {
    clickmap:true,
    trackLinks:true,
    accurateTrackBounce:true
});
            ",
        ),
    ),

);
