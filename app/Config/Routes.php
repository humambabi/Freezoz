<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Pages');
$routes->setDefaultMethod('home');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // Use only the defined routes

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/',													'Pages::home');
$routes->get('/index',											'Pages::home');
$routes->get('/home',											'Pages::home');
$routes->get('/register',										'Pages::register');
$routes->get('/terms',											'Pages::terms');
$routes->get('/privacy',										'Pages::privacy');
$routes->get('/activation/(:segment)/(:segment)',		'Pages::activation/$1/$2');
$routes->get('/forgot_pw',										'Pages::forgot_pw');
$routes->get('/reset_pw/(:segment)/(:segment)',			'Pages::reset_pw/$1/$2');

$routes->get('/assets/signin_form', 						'Assets::signin_form');
$routes->get('/assets/categories_form', 					'Assets::categories_form');

$routes->post('/requests/user_register',					'Requests::user_register');
$routes->post('/requests/sign_in',							'Requests::sign_in');
$routes->post('/requests/sign_out',							'Requests::sign_out');
$routes->post('/requests/forgot_pw',						'Requests::forgot_pw');
$routes->post('/requests/reset_pw',							'Requests::reset_pw');


/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
