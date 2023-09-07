<?php

namespace App\Routes;

$routes = \Config\Services::routes();

$routes->setDefaultNamespace('App');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->cli('/cron/serial/(:any)', 'Cron\Serial::$1');
$routes->cli('/cron/network/(:any)', 'Cron\Network::$1');
$routes->cli('/cron/netgrow/(:any)', 'Cron\Netgrow::$1');
$routes->cli('/cron/profitsharing/(:any)', 'Cron\Profitsharing::$1');
$routes->cli('/cron/bonus/(:any)', 'Cron\Bonus::$1');
$routes->cli('/cron/report/(:any)', 'Cron\Report::$1');
$routes->cli('/cron/rank/(:any)', 'Cron\Rank::$1');
$routes->cli('/cron/common/(:any)', 'Cron\Common::$1');
$routes->cli('/cron/ewallet/(:any)', 'Cron\Ewallet::$1');
$routes->cli('/cron/recap/(:any)', 'Cron\Recap::$1');
$routes->cli('/cron/test/(:any)', 'Cron\Test::$1');
$routes->cli('/cron/tax/(:any)', 'Cron\Tax::$1');
