<?php

// post views
$this->get('posts/(?P<id>[\d]+)/views', 'Lithe_Post_Views_Controller@get_views');
$this->post('posts/(?P<id>[\d]+)/views', 'Lithe_Post_Views_Controller@set_views');

// sports
$this->get('sports', 'Lithe_Sports_Controller@get_sports');
//$this->get('sports/(?P<id>[\d]+)', 'Lithe_Sports_Controller@get_sport');

// venues
$this->get('venues', 'Lithe_Venues_Controller@get_venues');
$this->get('venues/(?P<id>[\d]+)', 'Lithe_Venues_Controller@get_venue');

// trainers
// $this->get('trainers', 'Lithe_Venue_Controller@get_venues');
// $this->get('trainers/(?P<id>[\d]+)', 'Lithe_Venue_Controller@get_venue');