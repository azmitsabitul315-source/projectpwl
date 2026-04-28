<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('produk', 'Produk::produk');
$routes->get('produk/edit/(:num)', 'Produk::edit/$1');
$routes->get('produk/delete/(:num)', 'Produk::delete/$1');
$routes->get('front/(:any)','Main::front/$1');

$routes->get('login','Login::index');
$routes->post('login-auth','Login::auth');
$routes->get('logout', 'Login::logout');




