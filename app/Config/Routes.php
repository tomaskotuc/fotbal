<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Main::index');
$routes->get('sezona/(:num)', 'Main::sezona/$1');
$routes->get('novinky', 'Main::novinky');
$routes->get('article/(:num)-(:any)', 'Main::article/$1');
$routes->get('administrace', 'Main::administrace');

