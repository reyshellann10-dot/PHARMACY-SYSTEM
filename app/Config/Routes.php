<?php

namespace Config;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override(function() {
    echo view('errors/html/error_404');
});

$routes->setAutoRoute(false);  // Keep auto route OFF for security

/*
 * --------------------------------------------------------------------
 * PUBLIC ROUTES (No login required)
 * --------------------------------------------------------------------
 */
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth', 'Auth::auth');
$routes->get('/logout', 'Auth::logout');

/*
 * --------------------------------------------------------------------
 * PROTECTED ROUTES (Login required)
 * --------------------------------------------------------------------
 */

// Dashboard
$routes->get('/dashboard', 'Dashboard::index');

// ---------- PRODUCTS ----------
$routes->group('products', function($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('create', 'Products::create');
    $routes->post('store', 'Products::store');
    $routes->get('edit/(:num)', 'Products::edit/$1');
    $routes->post('update/(:num)', 'Products::update/$1');
    $routes->get('delete/(:num)', 'Products::delete/$1');
});


// ---------- CATEGORIES ----------
$routes->group('categories', function($routes) {
    $routes->get('/', 'Categories::index');
    $routes->get('create', 'Categories::create');
    $routes->post('store', 'Categories::store');
    $routes->get('edit/(:num)', 'Categories::edit/$1');
    $routes->post('update/(:num)', 'Categories::update/$1');
    $routes->get('delete/(:num)', 'Categories::delete/$1');
});

// ---------- CUSTOMERS ----------
$routes->group('customers', function($routes) {
    $routes->get('/', 'Customers::index');
    $routes->get('create', 'Customers::create');
    $routes->post('store', 'Customers::store');
    $routes->get('edit/(:num)', 'Customers::edit/$1');
    $routes->post('update/(:num)', 'Customers::update/$1');
    $routes->get('delete/(:num)', 'Customers::delete/$1');
    $routes->get('view/(:num)', 'Customers::view/$1');
});

// ---------- SALES ----------
$routes->group('sales', function($routes) {
    $routes->get('/', 'Sales::index');
    $routes->get('/sales/view/(:num)', 'Sales::view/$1');
});

// ---------- REPORTS ----------
$routes->group('reports', function($routes) {
    $routes->get('/', 'Reports::index');
    $routes->get('sales', 'Reports::sales');
});

// ---------- USERS (Admin only) ----------
$routes->group('users', function($routes) {
    $routes->get('/', 'Users::index');
    $routes->post('save', 'Users::save');
    $routes->post('update', 'Users::update');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->get('delete/(:num)', 'Users::delete/$1');
    $routes->post('fetchRecords', 'Users::fetchRecords');
});

// ---------- LOGS (Admin only) ----------
$routes->group('log', function($routes) {
    $routes->get('/', 'Logs::log');
    $routes->get('clear', 'Logs::clear');
    $routes->get('export', 'Logs::export');
});