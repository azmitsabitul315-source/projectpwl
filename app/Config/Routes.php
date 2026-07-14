<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Kuliner::index');

$routes->get('login', 'Login::index');
$routes->post('login-auth', 'Login::auth'); 
$routes->get('logout', 'Login::logout');

// Public Browse & Detail
$routes->get('kuliner', 'Kuliner::index');
$routes->get('kuliner/detail/(:num)', 'Kuliner::detail/$1');
$routes->get('kuliner/(:num)/reviews', 'Kuliner::reviews/$1');

// Webservice Client: Cari koordinat via Nominatim
$routes->get('kuliner/cariKoordinat', 'Kuliner::cariKoordinat');

// Donasi Developer (Midtrans)
$routes->get('donasi', 'Donasi::index');
$routes->post('donasi/pay', 'Donasi::pay');
$routes->post('donasi/webhook', 'Donasi::webhook');
$routes->post('donasi/finish', 'Donasi::finish');

// Webservice Server: API Kuliner RESTful
$routes->group('api', ['namespace' => 'App\Controllers\Api', 'filter' => 'apikey'], function ($routes) {
    $routes->get('kuliner', 'KulinerController::index');
    $routes->get('kuliner/(:num)', 'KulinerController::show/$1');
    $routes->post('kuliner', 'KulinerController::create');
    $routes->put('kuliner/(:num)', 'KulinerController::update/$1');
    $routes->delete('kuliner/(:num)', 'KulinerController::delete/$1');
});


$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('admin/dashboard', 'Dashboard::admin');
    $routes->get('review', 'Dashboard::review');
    // Kontributor can submit new kuliner
    $routes->get('kuliner/create', 'Kuliner::create');
    $routes->post('kuliner/store', 'Kuliner::store');
    
    // Review feature for logged in users
    $routes->post('review/store', 'ReviewController::store');
    $routes->get('review/delete/(:num)', 'ReviewController::delete/$1');
});

$routes->group('', ['filter' => 'auth:admin'], function ($routes) {
    // Admin Donasi
    $routes->get('admin/donasi', 'Donasi::admin_index');

    // Admin only kuliner management
    $routes->get('kuliner/approve/(:num)', 'Kuliner::approve/$1');
    $routes->get('kuliner/reject/(:num)', 'Kuliner::reject/$1');
    $routes->get('kuliner/edit/(:num)', 'Kuliner::edit/$1');
    $routes->post('kuliner/update/(:num)', 'Kuliner::update/$1');
    $routes->get('kuliner/delete/(:num)', 'Kuliner::delete/$1');

    // Admin CRUD for Kategori and Tag
    $routes->get('kategori', 'KategoriController::index');
    $routes->get('kategori/create', 'KategoriController::create');
    $routes->post('kategori/store', 'KategoriController::store');
    $routes->get('kategori/edit/(:num)', 'KategoriController::edit/$1');
    $routes->post('kategori/update/(:num)', 'KategoriController::update/$1');
    $routes->get('kategori/delete/(:num)', 'KategoriController::delete/$1');

    $routes->get('tag', 'TagController::index');
    $routes->get('tag/create', 'TagController::create');
    $routes->post('tag/store', 'TagController::store');
    $routes->get('tag/edit/(:num)', 'TagController::edit/$1');
    $routes->post('tag/update/(:num)', 'TagController::update/$1');
    $routes->get('tag/delete/(:num)', 'TagController::delete/$1');
});