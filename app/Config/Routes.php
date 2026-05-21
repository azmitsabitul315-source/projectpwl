<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Login::index');



$routes->get('login', 'Login::index');
$routes->post('login-auth', 'Login::auth'); 
$routes->get('logout', 'Login::logout');



$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('admin/dashboard', 'Dashboard::admin');
    
    // Rute baca data kuliner untuk semua user yang sudah login
    $routes->get('kuliner', 'Kuliner::index');
});

$routes->group('', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('kuliner/create', 'Kuliner::create');
    $routes->post('kuliner/store', 'Kuliner::store');
    $routes->get('kuliner/edit/(:num)', 'Kuliner::edit/$1');
    $routes->post('kuliner/update/(:num)', 'Kuliner::update/$1');
    $routes->get('kuliner/delete/(:num)', 'Kuliner::delete/$1');
});