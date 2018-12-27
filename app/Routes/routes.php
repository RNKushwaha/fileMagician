<?php

$routes = new App\Models\Router();

$routes->get('/', 'App\Controllers\PagesController@index');
$routes->get('/contact-us', 'App\Controllers\PagesController@contact');
$routes->get('/blog', 'App\Controllers\PostsController@index');
$routes->get('/blog/{alias}', 'App\Controllers\PostsController@view');
$routes->get('/{alias}', 'App\Controllers\PagesController@view');
$routes->add('GET|POST','/{alias}', 'App\Controllers\PagesController@view');

return $routes;
