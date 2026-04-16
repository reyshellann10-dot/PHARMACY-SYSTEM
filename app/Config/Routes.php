<?php

namespace Config;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// ========== PUBLIC ROUTES ==========
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth', 'Auth::auth');
$routes->get('/logout', 'Auth::logout');

// ========== DASHBOARD ==========
$routes->get('/dashboard', 'Dashboard::index');

// ========== PRODUCTS ==========
$routes->group('products', function($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('create', 'Products::create');
    $routes->post('store', 'Products::store');
    $routes->get('edit/(:num)', 'Products::edit/$1');
    $routes->post('update/(:num)', 'Products::update/$1');
    $routes->get('delete/(:num)', 'Products::delete/$1');
    $routes->get('expiring', 'Products::expiring');
    $routes->get('low-stock', 'Products::lowStock');
});

// ========== CATEGORIES ==========
$routes->group('categories', function($routes) {
    $routes->get('/', 'Categories::index');
    $routes->get('create', 'Categories::create');
    $routes->post('store', 'Categories::store');
    $routes->get('edit/(:num)', 'Categories::edit/$1');
    $routes->post('update/(:num)', 'Categories::update/$1');
    $routes->get('delete/(:num)', 'Categories::delete/$1');
});

// ========== CUSTOMERS ==========
$routes->group('customers', function($routes) {
    $routes->get('/', 'Customers::index');
    $routes->get('create', 'Customers::create');
    $routes->post('store', 'Customers::store');
    $routes->get('edit/(:num)', 'Customers::edit/$1');
    $routes->post('update/(:num)', 'Customers::update/$1');
    $routes->get('delete/(:num)', 'Customers::delete/$1');
    $routes->get('view/(:num)', 'Customers::view/$1');
});

// ========== SALES + POS (Point of Sale) ==========
$routes->group('sales', function($routes) {
    // Sales list
    $routes->get('/', 'Sales::index');
    $routes->get('view/(:num)', 'Sales::view/$1');
    
    // POS routes
    $routes->get('pos', 'Sales::pos');
    $routes->post('add-to-cart', 'Sales::addToCart');
    $routes->post('remove-from-cart', 'Sales::removeFromCart');
    $routes->post('update-cart', 'Sales::updateCart');
    $routes->post('clear-cart', 'Sales::clearCart');
    $routes->post('checkout', 'Sales::checkout');
    $routes->get('receipt/(:num)', 'Sales::receipt/$1');
    $routes->get('search-products', 'Sales::searchProducts');
    
    // Delete sale (void)
    $routes->get('delete/(:num)', 'Sales::delete/$1');
});

// ========== REPORTS ==========
$routes->group('reports', function($routes) {
    $routes->get('/', 'Reports::index');
    $routes->get('sales', 'Reports::sales');
    $routes->get('inventory', 'Reports::inventory');
});

// ========== USERS (Admin only) ==========
$routes->group('users', function($routes) {
    $routes->get('/', 'Users::index');
    $routes->post('save', 'Users::save');
    $routes->post('update', 'Users::update');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->get('delete/(:num)', 'Users::delete/$1');
});

// ========== LOGS (Admin only) ==========
$routes->group('log', function($routes) {
    $routes->get('/', 'Logs::log');
    $routes->get('clear', 'Logs::clear');
    $routes->get('export', 'Logs::export');
});

// ========== 404 HANDLER ==========
$routes->set404Override(function() {
    echo view('errors/html/error_404');
});