<?php

// post views
$route->get('posts/(?P<id>[\d]+)/views', 'Lithe_Post_Views_Controller@get_views');
$route->post('posts/(?P<id>[\d]+)/views', 'Lithe_Post_Views_Controller@set_views');

// sports
$route->get('sports', 'Lithe_Sports_Controller@get_sports');

// venues
$route->get('venues', 'Lithe_Venues_Controller@get_venues');
$route->get('venues/(?P<id>[\d]+)', 'Lithe_Venues_Controller@get_venue');
