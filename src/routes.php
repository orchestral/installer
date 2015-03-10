<?php

use Illuminate\Routing\Router;
use Orchestra\Support\Facades\Foundation;

Foundation::namespaced('Orchestra\Installation\Http\Controllers', function (Router $router) {
    $router->group(['prefix' => 'install'], function (Router $router) {
        // Route to installation.
        $router->get('/', 'InstallerController@index');
        $router->get('create', 'InstallerController@create');
        $router->post('create', 'InstallerController@store');
        $router->get('done', 'InstallerController@done');
        $router->get('prepare', 'InstallerController@prepare');
    });
});
