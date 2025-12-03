<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get( '/login', 'AuthController::index');
$routes->post( '/auth', 'AuthController::auth');
$routes->match(['get', 'post'], '/register', 'AuthController::register', ['filter' => 'guest']);
$routes->post('/logout', 'AuthController::logout');
$routes->get('scheduler/run', 'CronController::runPush');

$routes->group('dashboard', ['filter' => 'auth'], function (RouteCollection $routes) {
    $routes->post('saveToken', 'NotificationController::saveToken');
    $routes->group('profile', function (RouteCollection $routes) {
        $routes->get('/', 'DashboardController::profile');
        $routes->get('edit/(:segment)', 'AuthController::editProfile/$1');
        $routes->put('update/(:segment)', 'AuthController::updateProfile/$1');
    });
    $routes->get('/', 'DashboardController::index');
    $routes->group('task', ['filter' => 'auth'], function (RouteCollection $routes) {
        $routes->get('get-subtask/(:segment)', 'TaskController::getSubtask/$1');
        $routes->get('create/(:segment)', 'TaskController::create/$1');
        $routes->post('store', 'TaskController::store');
        $routes->get('edit/(:segment)', 'TaskController::edit/$1');
        $routes->put('update/(:segment)', 'TaskController::update/$1');
        $routes->put('update-checklist/(:segment)', 'TaskController::updateChecklist/$1');
        $routes->delete('delete/(:segment)', 'TaskController::delete/$1');
        $routes->put('complete/(:segment)', 'TaskController::completedTask/$1');
    });
    $routes->group('activity', ['filter' => 'auth'], function (RouteCollection $routes) {
        $routes->get('/', 'ActivityController::index');
        $routes->get('detail(:segment)', 'ActivityController::detail/$1');
        $routes->get('create', 'ActivityController::create');
        $routes->post('save', 'ActivityController::save');
        $routes->get('edit/(:segment)', 'ActivityController::edit/$1');
        $routes->put('update/(:segment)', 'ActivityController::update/$1');
        $routes->post('delete/(:segment)', 'ActivityController::delete/$1');
        $routes->post('bulk-delete', 'ActivityController::bulkDelete');
    });
     $routes->group('notes', ['filter' => 'auth'], function (RouteCollection $routes) {
        $routes->get('/', 'NoteController::index');
        $routes->get('detail(:segment)', 'NoteController::detail/$1');
        $routes->get('create', 'NoteController::create');
        $routes->post('save', 'NoteController::save');
        $routes->get('edit/(:segment)', 'NoteController::edit/$1');
        $routes->put('update/(:segment)', 'NoteController::update/$1');
        $routes->post('delete/(:segment)', 'NoteController::delete/$1');
        $routes->post('bulk-delete', 'NoteController::bulkDelete');
    });

    $routes->get('summary', 'SummaryController::index');
    $routes->group('calendar', ['filter' => 'auth'], function (RouteCollection $routes) {
        $routes->get('/', 'CalendarController::index');
        $routes->get('fetch', 'CalendarController::fetchTasks');
        $routes->get('fetch-activities', 'CalendarController::fetchActivities'); // fetch activities
    });
    $routes->get('profile', 'DashboardController::profile');
});