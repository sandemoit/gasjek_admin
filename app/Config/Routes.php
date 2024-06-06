<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// user
$routes->get('/user', 'Home::user');
$routes->delete('/user/delete/(:any)', 'Home::user_delete/$1');

// order
$routes->get('/order', 'Home::order');
$routes->post('/laporan', 'LaporanPesanan::index');

// driver
$routes->get('/driver', 'Home::driver');
$routes->post('/driver_block/(:num)', 'Home::driver_block/$1');
$routes->post('/driver_cancel/(:num)', 'Home::driver_cancel/$1');
$routes->get('/driver_accept/(:num)', 'Home::driver_accept/$1');

// api
$routes->get('/api', 'Home::api');

// wallet
$routes->get('/wallet', 'Home::wallet');
$routes->post('/wallet/check_email', 'Home::check_email');
$routes->post('/wallet/add_wallet', 'Home::add_wallet');
$routes->post('/update_balance', 'Home::update_balance');

// map
$routes->get('/map', 'Home::map');
$routes->post('/update_map', 'Home::update_map');

// login
// $routes->get('/login', 'Home::login');

// banner
$routes->get('/banner', 'Home::banner');
$routes->post('/banner/save', 'Home::banner_save');
$routes->delete('/banner/delete/(:any)', 'Home::banner_delete/$1');
$routes->get('/banner/delete/(:any)', 'Home::banner_delete/$1');

// restaurant
$routes->get('/restaurant', 'Home::restaurant');
$routes->get('/view_restaurant/(:num)', 'Home::view_restaurant/$1');
$routes->post('/restaurant/save', 'Home::restaurant_save');
$routes->post('/restaurant/edit', 'Home::save_edit_restaurant');
$routes->post('/restaurant/edit_food', 'Home::save_edit_food');
$routes->get('/restaurant/edit_restaurant/(:num)', 'Home::edit_restaurant/$1');
$routes->post('/restaurant/is_open/(:num)', 'Home::is_open/$1');
$routes->get('/restaurant/edit_food/(:num)', 'Home::edit_food/$1');
$routes->post('/food/save', 'Home::food_save');
$routes->delete('/restaurant/delete/(:any)', 'Home::restaurant_delete/$1');
$routes->delete('/view_restaurant/delete/(:any)', 'Home::food_delete/$1');
$routes->get('/view_restaurant/delete/(:any)', 'Home::food_delete/$1');
$routes->get('/comment_restaurant/(:num)', 'Home::comment_restaurant/$1');

// mitra
$routes->get('/mitra', 'Home::mitra');
$routes->put('/mitra/delete/(:any)', 'Home::mitra_delete/$1');
$routes->put('/mitra/cancel/(:any)', 'Home::mitra_cancel/$1');
$routes->get('/accept_mitra/(:num)', 'Home::accept_mitra/$1');

// broadcast
$routes->get('/broadcast', 'Home::broadcast');
$routes->post('/broadcast', 'Home::send_broadcast');

// setting
$routes->get('/setting', 'Home::setting');
$routes->post('/setting/update_account/(:num)', 'Home::update_account/$1');
$routes->post('/setting/update_password/(:num)', 'Home::update_password/$1');
$routes->post('/update_fitur/(:num)', 'Home::update_fitur/$1');

// banner 
$routes->get('api/banner_api', 'BannerApi::index');

// user 
$routes->get('api/user_api', 'UserApi::index');
$routes->post('api/user_api', 'UserApi::create');
$routes->post('api/user_verify', 'UserApi::verify');
$routes->post('api/otp_request', 'UserApi::otp_request');
$routes->post('api/login_user_api', 'UserApi::login');
$routes->post('api/update_user_api', 'UserApi::update');
$routes->post('api/update_password', 'UserApi::update_password');
$routes->post('api/update_fcm_user', 'UserApi::update_fcm_user');

// map
$routes->get('api/map', 'MapApi::index');

// feature
$routes->get('api/feature', 'FeatureApi::index');

// driver
$routes->get('api/driver_api', 'DriverApi::index');
$routes->post('api/login_driver_api', 'DriverApi::login');
$routes->post('api/create_driver_api', 'DriverApi::create');
$routes->post('api/update_status', 'DriverApi::update_status');
$routes->post('api/update_rating_driver', 'DriverApi::update_rating');
$routes->post('api/update_location_driver', 'DriverApi::update_location_driver');
$routes->post('api/update_fcm_token', 'DriverApi::update_fcm_token');
$routes->post('api/update_balance_driver', 'DriverApi::update_balance_driver');
$routes->post('api/update_city_name', 'DriverApi::update_city_name');
$routes->post('api/update_driver', 'DriverApi::update');

// restaurant
$routes->get('api/restaurant', 'RestaurantApi::index');
$routes->post('api/edit_restaurant', 'RestaurantApi::edit_restaurant');

// mitra
$routes->get('api/mitra', 'RestaurantApi::mitra');
$routes->post('api/mitra', 'RestaurantApi::login');
$routes->post('api/create_mitra', 'RestaurantApi::create_mitra');
$routes->post('api/edit_mitra', 'RestaurantApi::edit_mitra');

// food
$routes->get('api/food', 'FoodApi::index');
$routes->post('api/food', 'FoodApi::create');
$routes->post('api/delete_food', 'FoodApi::delete_food');
$routes->post('api/edit_food', 'FoodApi::edit_food');

// application
$routes->get('api/application_api', 'ApplicationApi::index');

// review
$routes->get('api/review', 'ReviewApi::index');
$routes->post('api/send_review_api', 'ReviewApi::send_review_api');

// cek review
$routes->get('api/check_review', 'CheckReview::index');

// wallet
$routes->post('api/top_up', 'WalletApi::top_up');
$routes->get('api/wallet_transaction', 'WalletApi::history');
$routes->get('api/transaction', 'WalletApi::transaction');

// midtrans
$routes->post('api/midtrans-callback', 'MidtransApi::callback');

/*
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
