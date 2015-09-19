<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'install'], function (Router $router) {
    // Route to installation.
    $router->get('/', 'InstallerController@index');
    $router->get('create', 'InstallerController@create');
    $router->post('create', 'InstallerController@store');
    $router->get('done', 'InstallerController@done');
    $router->get('prepare', 'InstallerController@prepare');
});
