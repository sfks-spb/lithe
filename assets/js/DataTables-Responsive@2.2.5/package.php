<?php

return array(

    'scripts' => array(

        'default' => array(

            'src'  => 'js/dataTables.responsive.min.js',

            'atts' => array( 'defer' => true ),

            'deps' => array( 'jquery', 'tablepress-datatables' ),

        ),

    ),

    'styles' => array(

        'default' => array(

            'src'    => 'css/responsive.dataTables.min.css',

            'deps' => array( 'tablepress-default' ),

        ),

    ),

);