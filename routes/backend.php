<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'install'], static function (Router $router) {
    // Route to installation.
    $router->get('/', 'CheckRequirementController');
    $router->get('prepare', 'PreparationController');

    $router->get('create', 'InstallerController@create');
    $router->post('create', 'InstallerController@store');

    $router->get('done', 'CompletedController');
});
