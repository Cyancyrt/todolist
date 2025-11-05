<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');
$routes->post( '/login', 'AuthController::auth');
$routes->match(['get', 'post'], '/register', 'AuthController::register', ['filter' => 'guest']);
$routes->post('/logout', 'AuthController::logout');



$routes->group('dashboard', ['filter' => 'auth'], function (RouteCollection $routes) {
    $routes->post('saveToken', 'NotificationController::saveToken');
    $routes->get('/', 'DashboardController::index');
    $routes->group('task', ['filter' => 'auth'], function (RouteCollection $routes) {
        $routes->get('/', 'TaskController::index');
        $routes->get('detail(:segment)', 'TaskController::detail/$1');
        $routes->get('create/(:segment)', 'TaskController::create/$1');
        $routes->post('store', 'TaskController::store');
        $routes->get('edit/(:segment)', 'TaskController::edit/$1');
        $routes->put('update/(:segment)', 'TaskController::update/$1');
        $routes->delete('delete/(:segment)', 'TaskController::delete/$1');
    });
    $routes->group('activity', ['filter' => 'auth'], function (RouteCollection $routes) {
        $routes->get('/', 'ActivityController::index');
        $routes->get('detail(:segment)', 'ActivityController::detail/$1');
        $routes->get('create', 'ActivityController::create');
        $routes->post('save', 'ActivityController::save');
        $routes->get('edit/(:segment)', 'ActivityController::edit/$1');
        $routes->put('update/(:segment)', 'ActivityController::update/$1');
        $routes->post('delete/(:segment)', 'ActivityController::delete/$1');
    });
    $routes->get('notes', 'NoteController::index');
    $routes->get('notes/(:segment)', 'NoteController::detail/$1');

    $routes->get('summary', 'SummaryController::index');
    $routes->get('calendar', 'CalendarController::index');
    $routes->get('profile', 'DashboardController::profile');
});