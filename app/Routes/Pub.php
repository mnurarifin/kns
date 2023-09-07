<?php

namespace App\Routes;

$routes = \Config\Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Pub\Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->add('/', 'Pub\Home::index');
// $routes->add('/', 'Pub\Login::index');
$routes->add('/login/', 'Pub\Login::index');
$routes->add('/login/(:any)', 'Pub\Login::$1');

$routes->add('/admin/', 'Pub\Login_admin::index');
$routes->add('/login-admin/', 'Pub\Login_admin::index');
$routes->add('/login-admin/(:any)', 'Pub\Login_admin::$1');

$routes->add('/home/(:any)', 'Pub\Home::$1');
$routes->add('/home', 'Pub\Home::index');

$routes->add('/contact', 'Pub\Contact::index');

$routes->add('/registration/(:any)', 'Pub\Registration::$1');
$routes->add('/registration', 'Pub\Registration::index');

$routes->add('/404', 'Pub\Error_404::index');
$routes->add('/ref/(:any)', 'Pub\Ref::index/$1');

$routes->add('/testimony/(:any)', 'Pub\Testimony::$1');
$routes->add('/testimony', 'Pub\Testimony::index');

$routes->add('/product/(:any)', 'Pub\Product::$1');
$routes->add('/product', 'Pub\Product::index');

$routes->add('/business/(:any)', 'Pub\Business::$1');

$routes->add('/about', 'Pub\About::index');

$routes->add('/contact', 'Pub\Contact::index');

$routes->add('/content', 'Pub\Content::index');
$routes->add('/content/(:any)', 'Pub\Content::$1');
