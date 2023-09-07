<?php

namespace App\Routes;

$routes = \Config\Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(true);

// $routes->add('member/registration/(:any)', 'Member\Registration::$1');