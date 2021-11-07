<?php

/**
 * Post Views
 */
$route->get('posts/(?P<id>[\d]+)/views', 'Lithe_Post_Views_Controller@get_views');
$route->post('posts/(?P<id>[\d]+)/views', 'Lithe_Post_Views_Controller@set_views');

/**
 * Sports
 */
$route->get('sports', 'Lithe_Sports_Controller@get_sports');

/**
 * Trainers
 */
$route->get('trainers', 'Lithe_Trainers_Controller@get_trainers');

/**
 * Venues
 */
$route->get('venues', 'Lithe_Venues_Controller@get_venues');
$route->get('venues/(?P<id>[\d]+)', 'Lithe_Venues_Controller@get_venue');