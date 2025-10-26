<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');
$routes->post( '/login', 'AuthController::auth');
$routes->match(['get', 'post'], '/register', 'AuthController::register', ['filter' => 'guest']);
$routes->get('/logout', 'Home::logout');



$routes->group('dashboard', ['filter' => 'auth'], function (RouteCollection $routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->group('task', ['filter' => 'auth'], function (RouteCollection $routes) {
        $routes->get('/', 'TaskController::index');
        $routes->get('detail(:segment)', 'TaskController::detail/$1');
        $routes->get('create', 'TaskController::create');
        $routes->put('edit/(:segment)', 'TaskController::update/$1');
        $routes->delete('delete/(:segment)', 'TaskController::delete/$1');
    });
    $routes->group('activity', ['filter' => 'auth'], function (RouteCollection $routes) {
        $routes->get('/', 'ActivityController::index');
        $routes->get('detail(:segment)', 'ActivityController::detail/$1');
        $routes->get('create', 'ActivityController::create');
        $routes->post('save', 'ActivityController::save');
        $routes->put('edit/(:segment)', 'ActivityController::update/$1');
        $routes->delete('delete/(:segment)', 'ActivityController::delete/$1');
    });
    $routes->get('notes', 'NoteController::index');
    $routes->get('notes/(:segment)', 'NoteController::detail/$1');
    $routes->get('calendar', 'CalendarController::index');
    $routes->get('profile', 'DashboardController::profile');
});