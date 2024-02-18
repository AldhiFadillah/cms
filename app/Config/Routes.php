<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('p16', 'P16::index');
$routes->get('daftarJaksa', 'P16::daftarJaksa');
$routes->get("dataJaksa/(:any)", "P16::detailJaksa/$1");
$routes->get('tambahSPDP', 'P16::tambahSPDP');
